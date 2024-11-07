function mostrarFormulari() {
    var form = document.querySelector('#formSubhasta');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

function mostrarTaula() {
    var taula = document.querySelector('#taulaSubhasta');
    if (taula.style.display === 'none' || taula.style.display === '') {
        taula.style.display = 'block';
    } else {
        taula.style.display = 'none';
    }
}

function mostrarFormulariProductes() {
    var form = document.querySelector('#formProductes');
    if (form.style.display === 'none' || form.style.display === '') {
        form.style.display = 'block';
    } else {
        form.style.display = 'none';
    }
}

function cambiaTaules() {
    var table1 = document.querySelector('#table1');
    var table2 = document.querySelector('#table2');
    var button = document.querySelector('#toggleButton');
    var editButton = document.querySelector('#edit');

    if (table1.style.display === 'none') {
        table1.style.display = 'table';
        table2.style.display = 'none';
        editButton.style.display = 'none';
        button.textContent = 'Veure tots els productes';
    } else {
        table1.style.display = 'none';
        table2.style.display = 'table';
        editButton.style.display = 'block';
        button.textContent = 'Veure productes pendents';
    }
}

function enviaNotificacio() {
    var notificacio = document.querySelector('#notificacio');
    if (notificacio.classList.contains('hidden')) {
        notificacio.classList.remove('hidden');
    } else {
        notificacio.classList.add('hidden');
    }
}

var modal = document.querySelector("#myModal");
var span = document.getElementsByClassName("close")[0];

document.querySelectorAll('.openModal').forEach(button => {
    button.addEventListener('click', function () {
        modal.style.display = "block";
        var productId = this.getAttribute('id').split('-')[1];
        var form = document.querySelector('#mostraResposta' + productId).innerHTML;
        document.querySelector('#modal-content').innerHTML = form;
    });
});

span.onclick = function () {
    modal.style.display = "none";
}

window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}