document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('incpros-intake-form');
	if (form) {
		const totalSteps = 3;
		let currentStep = 1;

		const backBtn = document.getElementById('wizard-back-btn');
		const nextBtn = document.getElementById('wizard-next-btn');
		const stepTitle = document.getElementById('wizard-step-title');

		function showStep(stepNumber) {
			const steps = document.querySelectorAll('.wizard-step');
			steps.forEach((step) => {
				step.style.display = 'none';
			});
			document.getElementById(`wizard-step-${stepNumber}`).style.display =
				'block';

			stepTitle.innerText = `Step ${stepNumber}`;

			if (stepNumber === 1) {
				backBtn.style.display = 'none';
			} else {
				backBtn.style.display = 'inline-block';
			}

			if (stepNumber === totalSteps) {
				nextBtn.innerText = 'Submit';
			} else {
				nextBtn.innerText = 'Next';
			}
		}

		nextBtn.addEventListener('click', function () {
			if (currentStep < totalSteps) {
				currentStep++;
				showStep(currentStep);
			} else {
				// Submit form
				const formData = new FormData(form);
				const data = {
					product: {
						name: 'Enterprise Establishment (EE)',
					},
					customer: {
						name: formData.get('customer[name]'),
						email: formData.get('customer[email]'),
					},
					entity: {
						name: formData.get('entity[name]'),
						type: formData.get('entity[type]'),
					},
					paperos: {},
					commerce: {},
					idempotency_key: self.crypto.randomUUID(),
				};

				fetch('/wp-json/incpros/v1/intake', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
					},
					body: JSON.stringify(data),
				})
					.then((response) => response.json())
					.then((result) => {
						console.log('Success:', result);
					})
					.catch((error) => {
						console.error('Error:', error);
					});
			}
		});

		backBtn.addEventListener('click', function () {
			if (currentStep > 1) {
				currentStep--;
				showStep(currentStep);
			}
		});

		showStep(currentStep);
	}
});
