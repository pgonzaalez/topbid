document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('#editProductForm');
    const nomField = form.querySelector('input[name="nom"]');
    const descripcioCurtaField = form.querySelector('input[name="descripcio_curta"]');
    const descripcioLlargaField = form.querySelector('input[name="descripcio_llarga"]');
    const preuField = form.querySelector('input[name="preu"]');

    const nomError = form.querySelector('#nom-error');
    const descripcioCurtaError = form.querySelector('#descripcio-curta-error');
    const descripcioLlargaError = form.querySelector('#descripcio-llarga-error');
    const preuError = form.querySelector('#preu-error');

    form.addEventListener('submit', function(event) {
        let isValid = true;

        
        nomError.textContent = '';
        descripcioCurtaError.textContent = '';
        descripcioLlargaError.textContent = '';
        preuError.textContent = '';

        
        if (nomField.value.length > 45) {
            nomError.textContent = 'El nom no pot tenir més de 45 caràcters.';
            isValid = false;
        }

        
        if (descripcioCurtaField.value.length > 100) {
            descripcioCurtaError.textContent = 'La descripció curta no pot tenir més de 100 caràcters.';
            isValid = false;
        }

        
        if (descripcioLlargaField.value.length > 255) {
            descripcioLlargaError.textContent = 'La descripció llarga no pot tenir més de 255 caràcters.';
            isValid = false;
        }

        
        if (parseFloat(preuField.value) > 99999999.99) {
            preuError.textContent = 'El preu no pot ser més de 99999999.99.';
            isValid = false;
        }

       
        if (!isValid) {
            event.preventDefault();
        }
    });
});