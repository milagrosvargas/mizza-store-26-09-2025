// assets/js/alerts.js

// Mensaje de éxito (por ejemplo, login exitoso, guardado, etc.)
function mostrarExito(titulo = "¡Éxito!", mensaje = "") {
    Swal.fire({
        icon: "success",
        title: titulo,
        text: mensaje
    });
}

// Mensaje de error (con descripción personalizada)
function mostrarError(titulo = "Oops...", mensaje = "Algo salió mal.") {
    Swal.fire({
        icon: "error",
        title: titulo,
        text: mensaje
    });
}

// Mostrar alerta de carga con tiempo
function mostrarCarga(titulo = "Cargando...", tiempo = 2000) {
    let timerInterval;
    Swal.fire({
        title: titulo,
        html: "Cerrando en <b></b> milisegundos...",
        timer: tiempo,
        timerProgressBar: true,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
            const timer = Swal.getPopup().querySelector("b");
            timerInterval = setInterval(() => {
                timer.textContent = `${Swal.getTimerLeft()}`;
            }, 100);
        },
        willClose: () => {
            clearInterval(timerInterval);
        }
    });
}
