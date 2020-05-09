
let authForm = document.querySelector('.js-auth-form');

authForm.addEventListener('submit', function (e) {
    e.preventDefault();
    resetValidateErrors();

    let form = e.target;
    let formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
        .then(response => response.json())
        .then(result => {
            if (result.status === 'success') {
                window.location = '/';
            }

            handleValidateErrors(result.errors);
        });
});

let handleValidateErrors = function (errors) {
    for (let key in errors) {
        let selector = `.js-auth-form #${key}`;
        let input = document.querySelector(selector);
        let errorText = document.querySelector(`${selector} + .js-error-text`);
        input.classList.add('is-invalid');
        errorText.innerText = errors[key];
    }
};

let resetValidateErrors = function () {
    let inputs = document.querySelectorAll('.js-input');
    let errors = document.querySelectorAll('.js-error-text');

    inputs.forEach(input => {
        input.classList.remove('is-invalid');
    });

    errors.forEach(error => {
        error.innerText = '';
    });
};
