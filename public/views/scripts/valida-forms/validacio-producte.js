document.querySelector('#productForm').addEventListener('submit', function(event) {
    var nomProducte = document.querySelector('#nomProducte').value;
    var descripcioCurtaProducte = document.querySelector('#descripcioCurtaProducte').value;
    var descripcioLlargaProducte = document.querySelector('#descripcioLlargaProducte').value;
    var preuProducte = document.querySelector('#preuProducte').value;
    var imatgeProducte = document.querySelector('#imatgeProducte').value;
    var imatgeProducteFile = document.querySelector('#imatgeProducte').files[0];

    
    document.querySelector('#nomProducteError').textContent = '';
    document.querySelector('#descripcioCurtaProducteError').textContent = '';
    document.querySelector('#descripcioLlargaProducteError').textContent = '';
    document.querySelector('#preuProducteError').textContent = '';
    document.querySelector('#imatgeProducteError').textContent = '';

    var isValid = true;

    if (!nomProducte || !descripcioCurtaProducte || !descripcioLlargaProducte || !preuProducte || !imatgeProducte) {
        if (!nomProducte) document.querySelector('#nomProducteError').textContent = 'Aquest camp és obligatori.';
        if (!descripcioCurtaProducte) document.querySelector('#descripcioCurtaProducteError').textContent = 'Aquest camp és obligatori.';
        if (!descripcioLlargaProducte) document.querySelector('#descripcioLlargaProducteError').textContent = 'Aquest camp és obligatori.';
        if (!preuProducte) document.querySelector('#preuProducteError').textContent = 'Aquest camp és obligatori.';
        if (!imatgeProducte) document.querySelector('#imatgeProducteError').textContent = 'Aquest camp és obligatori.';
        isValid = false;
    }


    if (nomProducte.length > 100 || descripcioCurtaProducte.length > 100 || descripcioLlargaProducte.length > 250) {
        if (nomProducte.length > 100) document.querySelector('#nomProducteError').textContent = 'No pot superar els 100 caràcters.';
        if (descripcioCurtaProducte.length > 100) document.querySelector('#descripcioCurtaProducteError').textContent = 'No pot superar els 100 caràcters.';
        if (descripcioLlargaProducte.length > 250) document.querySelector('#descripcioLlargaProducteError').textContent = 'No pot superar els 250 caràcters.';
        isValid = false;
    }


    if (preuProducte < 0) {
        document.querySelector('#preuProducteError').textContent = 'El preu no pot ser negatiu.';
        isValid = false;
    }

    if (imatgeProducteFile) {
        var validImageTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        if (!validImageTypes.includes(imatgeProducteFile.type)) {
            document.querySelector('#imatgeProducteError').textContent = 'Només es permeten imatges en format PNG o JPG.';
            isValid = false;
        }
    }

    if (!isValid) {
        event.preventDefault();
    }
});