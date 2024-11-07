document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form.actions');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            const messageInput = form.querySelector('input[name="enviaMissatge"]');
            const messageError = form.querySelector('#enviaMissatge-error');
            let isValid = true;

            messageError.textContent = '';

            if (messageInput.value.length > 50) {
                messageError.textContent = 'El missatge no pot tenir més de 250 caràcters.';
                isValid = false;
            }

            if (!isValid) {
                event.preventDefault();
            }
        });
    });
});