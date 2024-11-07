document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const nomField = form.querySelector('#nom');
    const llocField = form.querySelector('#lloc');
    const descripcioField = form.querySelector('#descripcio');

    const nomError = form.querySelector('#nom-error');
    const llocError = form.querySelector('#lloc-error');
    const descripcioError = form.querySelector('#descripcio-error');

    form.addEventListener('submit', function(event) {
        let isValid = true;

        // Clear previous error messages
        nomError.textContent = '';
        llocError.textContent = '';
        descripcioError.textContent = '';

        // Validate nom length
        if (nomField.value.length > 100) {
            nomError.textContent = 'El camp "Nom de la Subhasta" no pot tenir més de 100 caràcters.';
            isValid = false;
        }

        // Validate lloc length
        if (llocField.value.length > 100) {
            llocError.textContent = 'El camp "Lloc" no pot tenir més de 100 caràcters.';
            isValid = false;
        }

        // Validate descripcio length
        if (descripcioField.value.length > 250) {
            descripcioError.textContent = 'El camp "Descripció" no pot tenir més de 250 caràcters.';
            isValid = false;
        }

        // Prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
        }
    });
});