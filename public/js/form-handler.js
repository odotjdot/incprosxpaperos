document.addEventListener('DOMContentLoaded', function () {
	const form = document.getElementById('incpros-intake-form');
	if (form) {
		form.addEventListener('submit', function (event) {
			event.preventDefault();

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
		});
	}
});
