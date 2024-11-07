var modal = document.querySelector("#myModal");
var span = document.getElementsByClassName("close")[0];

document.querySelectorAll('.openModal').forEach(button => {
    button.addEventListener('click', function () {
        modal.style.display = "block";
        var productId = this.getAttribute('id').split('-')[1];
        document.querySelector('#modal-content').innerHTML = 'Contenido del popup para el producto ' + productId;
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