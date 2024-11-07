document.addEventListener('DOMContentLoaded', function() {
    var registerForm = document.querySelector('#myModal form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(event) {
            var username = document.querySelector('#username').value;
            var dni = document.querySelector('#dni').value;
            var usernameError = document.querySelector('#username-error');
            var dniError = document.querySelector('#dni-error');
            var isValid = true;

            
            usernameError.textContent = '';
            dniError.textContent = '';

            
            if (username.length > 20) {
                usernameError.textContent = 'El nombre no puede tener más de 20 caracteres.';
                isValid = false;
            }

            
            if (dni.length > 30) {
                dniError.textContent = 'El DNI no puede tener más de 30 caracteres.';
                isValid = false;
            }

            
            if (!isValid) {
                event.preventDefault();
            }
        });
    }
});