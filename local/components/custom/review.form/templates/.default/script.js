BX.ready(function() {
	const form = document.querySelector('.review-form');
	if (!form) return;

	const fields = {
		NAME: form.querySelector('input[name="NAME"]'),
		PLUS: form.querySelector('textarea[name="PLUS"]'),
		MINUS: form.querySelector('textarea[name="MINUS"]'),
		REVIEW: form.querySelector('textarea[name="REVIEW"]'),
	};

	form.addEventListener('submit', function(e) {
		clearAllErrors();
		let valid = true;

		if (!fields.NAME.value.trim()) {
			showError(fields.NAME, 'Введите имя');
			valid = false;
		}

		const reviewText = fields.REVIEW.value.trim();
		if (!reviewText) {
			showError(fields.REVIEW, 'Введите текст отзыва');
			valid = false;
		} else if (reviewText.length < 10) {
			showError(fields.REVIEW, 'Отзыв должен содержать не менее 10 символов');
			valid = false;
		}

		if (!valid) {
			e.preventDefault();
			return false;
		}

		return true;
	});


	// Функция для вывода ошибки под конкретным полем
	function showError(field, message) {
		clearError(field);
		field.classList.add('error');

		const error = document.createElement('div');
		error.className = 'field-error';
		error.textContent = message;
		field.insertAdjacentElement('afterend', error);
	}

	// Очистка ошибки
	function clearError(field) {
		field.classList.remove('error');
		const next = field.nextElementSibling;
		if (next && next.classList.contains('field-error')) {
			next.remove();
		}
	}

	// Очистка всех ошибок
	function clearAllErrors() {
		Object.values(fields).forEach(clearError);
	}
});