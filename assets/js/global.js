// Mostrar mensaje de error con SweetAlert
function mostrarError(titulo, mensaje) {
    Swal.fire({
        icon: 'error',
        title: titulo,
        text: mensaje,
        confirmButtonColor: '#d33'
    });
}

// Mostrar mensaje de éxito con SweetAlert
function mostrarExito(titulo, mensaje) {
    Swal.fire({
        icon: 'success',
        title: titulo,
        text: mensaje,
        confirmButtonColor: '#3085d6'
    });
}

// Mostrar modal de carga
function mostrarCargando(mensaje = 'Cargando...') {
    Swal.fire({
        title: mensaje,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

// Mostrar mensaje de confirmación con opciones
function mostrarPregunta(titulo, texto, textoConfirmar = 'Sí', textoCancelar = 'Cancelar', callback) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: textoConfirmar,
        cancelButtonText: textoCancelar,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
}

// ----------------------------------------
// Verifica automáticamente mensajes GET
// ----------------------------------------
document.addEventListener("DOMContentLoaded", function () {
    const params = new URLSearchParams(window.location.search);

    if (params.has('error_titulo') && params.has('error_mensaje')) {
        const titulo = decodeURIComponent(params.get('error_titulo'));
        const mensaje = decodeURIComponent(params.get('error_mensaje'));
        mostrarError(titulo, mensaje);
    }

    if (params.has('success_titulo') && params.has('success_mensaje')) {
        const titulo = decodeURIComponent(params.get('success_titulo'));
        const mensaje = decodeURIComponent(params.get('success_mensaje'));
        mostrarExito(titulo, mensaje);
    }
    
});
document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.alertasDesdePHP !== 'undefined' && Array.isArray(window.alertasDesdePHP)) {
        window.alertasDesdePHP.forEach(alerta => {
            if (alerta.tipo === 'error') {
                mostrarError(alerta.titulo, alerta.mensaje);
            } else if (alerta.tipo === 'exito') {
                mostrarExito(alerta.titulo, alerta.mensaje);
            } else if (alerta.tipo === 'redirect') {
                mostrarCargando(alerta.titulo);
                setTimeout(() => {
                    window.location.href = alerta.url;
                }, 1500);
            }
        });
    }
});

