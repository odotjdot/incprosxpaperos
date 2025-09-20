document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('incpros-intake-form');
	if (form) {
		let currentStep = 1;
		let steps = Array.from(document.querySelectorAll('.incpros-wizard-step'));
		const prevBtn = document.getElementById('prevBtn');
		const nextBtn = document.getElementById('nextBtn');
		const formDataStore = {
			product: {},
			customer: {},
			entity: {
				name: [],
				members: [],
			},
			paperos: {
				docs: {
					governance: {},
					contracts: {},
				},
				assets: [],
			},
			commerce: {
				capital_plan: {},
				compliance: {},
			},
		};

		function populateReviewStep() {
			document.getElementById('review_product_code').textContent =
				formDataStore.product.code || '';
			document.getElementById('review_customer_email').textContent =
				formDataStore.customer.email || '';
			document.getElementById('review_entity_name_0').textContent =
				formDataStore.entity.name[0] || '';
			document.getElementById('review_entity_name_1').textContent =
				formDataStore.entity.name[1] || '';
			document.getElementById('review_entity_name_2').textContent =
				formDataStore.entity.name[2] || '';
			document.getElementById('review_entity_type').textContent =
				formDataStore.entity.type || '';
			document.getElementById('review_entity_purpose').textContent =
				formDataStore.entity.purpose || '';
			document.getElementById('review_entity_location_formation').textContent =
				formDataStore.entity.location.formation || '';
			document.getElementById(
				'review_entity_location_qualification',
			).textContent = (formDataStore.entity.location.qualification || []).join(
				', ',
			);

			const membersReview = document.getElementById('review_entity_members');
			membersReview.innerHTML = '';
			(formDataStore.entity.members || []).forEach((member) => {
				const p = document.createElement('p');
				p.innerHTML = `<strong>Name:</strong> ${member.name}, <strong>Email:</strong> ${member.email}, <strong>Role:</strong> ${member.role}, <strong>Ownership:</strong> ${member.ownership}%`;
				membersReview.appendChild(p);
			});

			document.getElementById(
				'review_paperos_docs_governance_operating_agreement',
			).textContent =
				formDataStore.paperos.docs.governance.operating_agreement || '';
			document.getElementById(
				'review_paperos_docs_contracts_independent_contractor_agreement',
			).textContent =
				formDataStore.paperos.docs.contracts.independent_contractor_agreement ||
				'';
			document.getElementById('review_paperos_assets').textContent = (
				formDataStore.paperos.assets || []
			).join(', ');

			if (formDataStore.product.code === 'OC') {
				document.getElementById('review_capital_plan_section').style.display =
					'block';
				document.getElementById(
					'review_commerce_capital_plan_amount',
				).textContent = formDataStore.commerce.capital_plan.amount || '';
				document.getElementById(
					'review_commerce_capital_plan_security_type',
				).textContent = formDataStore.commerce.capital_plan.security_type || '';
			} else {
				document.getElementById('review_capital_plan_section').style.display =
					'none';
			}

			document.getElementById('review_commerce_compliance_ein').textContent =
				formDataStore.commerce.compliance.ein ? 'Yes' : 'No';
			document.getElementById(
				'review_commerce_compliance_registered_agent',
			).textContent = formDataStore.commerce.compliance.registered_agent
				? 'Yes'
				: 'No';
		}

		function showStep(n) {
			if (n === steps.length) {
				populateReviewStep();
			}

			steps.forEach((step, index) => {
				step.style.display = index + 1 === n ? 'block' : 'none';
			});

			if (n === 1) {
				prevBtn.style.display = 'none';
			} else {
				prevBtn.style.display = 'inline';
			}

			if (n === steps.length) {
				nextBtn.innerHTML = 'Submit';
			} else {
				nextBtn.innerHTML = 'Next';
			}
		}

		function collectStepData() {
			const activeStep = steps[currentStep - 1];
			if (!activeStep) return;

			const inputs = activeStep.querySelectorAll('input, select, textarea');
			inputs.forEach((input) => {
				const name = input.name;
				if (!name) return;

				let value;
				if (input.type === 'radio') {
					if (input.checked) value = input.value;
					else return;
				} else if (input.type === 'checkbox') {
					value = input.checked ? input.value : '';
				} else {
					value = input.value;
				}

				const keys = name.replace(/\]/g, '').split('[');
				let tempStore = formDataStore;
				keys.forEach((key, index) => {
					if (index === keys.length - 1) {
						if (name.includes('[]') || /\[\d*\]$/.test(name)) {
							const arrayIndexMatch = name.match(/\[(\d+)\]$/);
							if (arrayIndexMatch) {
								// handled by the array of objects logic below
							} else {
								if (!Array.isArray(tempStore[key])) tempStore[key] = [];
								if (input.checked && !tempStore[key].includes(value)) {
									tempStore[key].push(value);
								} else if (!input.checked) {
									tempStore[key] = tempStore[key].filter((v) => v !== value);
								}
							}
						} else {
							tempStore[key] = value;
						}
					} else {
						const nextKey = keys[index + 1];
						if (/^\d+$/.test(nextKey)) {
							// if the next key is a number, we are in an array of objects
							if (!tempStore[key]) tempStore[key] = [];
							const arrayIndex = parseInt(nextKey, 10);
							if (!tempStore[key][arrayIndex]) tempStore[key][arrayIndex] = {};
							tempStore = tempStore[key][arrayIndex];
							keys.splice(index + 1, 1); // consume the numeric key
						} else {
							if (!tempStore[key]) tempStore[key] = {};
							tempStore = tempStore[key];
						}
					}
				});
			});
		}

		function nextPrev(n) {
			collectStepData();

			if (currentStep === 1 && n === 1) {
				if (formDataStore.product.code !== 'OC') {
					const step8 = document.getElementById('step-8');
					if (step8) {
						step8.style.display = 'none'; // Hide instead of remove
					}
				} else {
					const step8 = document.getElementById('step-8');
					if (step8) {
						step8.style.display = 'block'; // Show if it was hidden
					}
				}
				// Re-filter steps array to only include visible steps
				steps = Array.from(
					document.querySelectorAll('.incpros-wizard-step'),
				).filter((s) => s.style.display !== 'none');
			}

			if (n > 0 && currentStep === steps.length) {
				formDataStore.idempotency_key = self.crypto.randomUUID();
				// Submit form
				fetch('/wp-json/incpros/v1/intake', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'X-IncPros-Signature': 'sha256=placeholder',
					},
					body: JSON.stringify(formDataStore),
				})
					.then((response) => {
						if (response.ok) {
							// Save form data to a session cookie
							document.cookie = `incpros_form_data=${encodeURIComponent(
								JSON.stringify(formDataStore),
							)};path=/;samesite=strict`;
							window.location.href = '/checkout';
						}
						return response.json();
					})
					.then((result) => {
						console.log('Success:', result);
					})
					.catch((error) => {
						console.error('Error:', error);
					});
				return;
			}

			currentStep = currentStep + n;
			showStep(currentStep);
		}

		prevBtn.addEventListener('click', () => nextPrev(-1));
		nextBtn.addEventListener('click', () => nextPrev(1));

		document.querySelectorAll('.edit-step').forEach((button) => {
			button.addEventListener('click', function () {
				const step = parseInt(this.dataset.step, 10);
				currentStep = step;
				showStep(currentStep);
			});
		});

		// Event listeners for dynamic elements
		document
			.getElementById('foreign_qualification_checkbox')
			.addEventListener('change', function () {
				document.getElementById('foreign_qualification_states').style.display =
					this.checked ? 'block' : 'none';
			});

		document
			.getElementById('add_person')
			.addEventListener('click', function () {
				const wrapper = document.getElementById('key_people_wrapper');
				const index = wrapper.children.length;
				const newPerson = document.createElement('div');
				newPerson.className = 'key-person';
				newPerson.innerHTML = `
                <label>Full Name: <input type="text" name="entity[members][${index}][name]"></label>
                <label>Email Address: <input type="email" name="entity[members][${index}][email]"></label>
                <label>Role: <input type="text" name="entity[members][${index}][role]"></label>
                <label>Ownership Percentage: <input type="number" name="entity[members][${index}][ownership]"></label>
                <button type="button" class="remove-person">Remove</button>
            `;
				wrapper.appendChild(newPerson);
			});

		document
			.getElementById('key_people_wrapper')
			.addEventListener('click', function (e) {
				if (e.target.classList.contains('remove-person')) {
					e.target.parentElement.remove();
				}
			});

		showStep(currentStep);
	}
});
