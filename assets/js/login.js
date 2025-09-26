// assets/js/login.js

// Alternar entre tabs de login y registro
document.addEventListener("DOMContentLoaded", function () {
    const btnLogin = document.getElementById('btn-login');
    const btnRegister = document.getElementById('btn-register');
    const formLogin = document.getElementById('form-login');
    const formRegister = document.getElementById('form-register');

    if (btnLogin && btnRegister && formLogin && formRegister) {
        btnLogin.addEventListener('click', () => {
            btnLogin.classList.add('active');
            btnRegister.classList.remove('active');
            formLogin.classList.add('active');
            formRegister.classList.remove('active');
        });

        btnRegister.addEventListener('click', () => {
            btnRegister.classList.add('active');
            btnLogin.classList.remove('active');
            formRegister.classList.add('active');
            formLogin.classList.remove('active');
        });
    }

    // Mostrar u ocultar contraseña
    window.togglePassword = function (id) {
        const input = document.getElementById(id);
        if (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        }
    };

    // Procesar alertas enviadas desde PHP
    if (typeof alertasDesdePHP !== "undefined" && Array.isArray(alertasDesdePHP)) {
        alertasDesdePHP.forEach(alerta => {
            switch (alerta.tipo) {
                case 'error':
                    mostrarError(alerta.titulo, alerta.mensaje);
                    break;
                case 'success':
                    mostrarExito(alerta.titulo, alerta.mensaje);
                    break;
                case 'redirect':
                    mostrarCargando(alerta.titulo);
                    setTimeout(() => {
                        window.location.href = alerta.url;
                    }, 2000);
                    break;
            }
        });
    }
    // Acción para "¿Olvidaste tu contraseña?"
    const enlaceOlvideClave = document.getElementById('link-olvide-clave');

    if (enlaceOlvideClave) {
        enlaceOlvideClave.addEventListener('click', function (e) {
            e.preventDefault();

            mostrarPregunta(
                '¿Restablecer contraseña?',
                '¿Deseas solicitar el restablecimiento de tu contraseña?',
                'Ir a restablecimiento de contraseña',
                'Cancelar',
                function () {
                    window.location.href = 'index.php?controller=login&action=recuperar';
                }
            );
        });
    }

});
