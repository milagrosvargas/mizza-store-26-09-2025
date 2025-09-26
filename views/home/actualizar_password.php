<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<title>MizzaStore - Argentina</title>
<style>
    :root {
        --blue: #3498db;
        --blue-dark: #2980b9;
        --border: #ccc;
        --text: #333;
    }

    * {
        box-sizing: border-box
    }

    body {
        background: linear-gradient(to right, #74ebd5, #ACB6E5);
        min-height: 100vh;
        margin: 0;
        font-family: 'Segoe UI', sans-serif;
        display: flex;
        flex-direction: column;
    }

    .main-content {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px;
    }

    .container {
        background: #fff;
        width: 100%;
        max-width: 400px;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, .2);
        text-align: center;
    }

    h2 {
        margin: 0 0 20px;
        color: var(--text);
        font-size: 24px
    }

    label {
        display: block;
        text-align: left;
        font-size: 14px;
        margin: 12px 0 6px;
        color: #555;
    }

    /* --- INPUT con icono estable --- */
    .input-wrapper {
        position: relative;
        display: block;
        width: 100%;
    }

    .input-wrapper input {
        width: 100%;
        padding: 12px 44px 12px 12px;
        /* deja espacio fijo para el ojito */
        border: 1px solid var(--border);
        border-radius: 6px;
        font-size: 16px;
        background: #fff;
        transition: border-color .2s, box-shadow .2s;
        /* sin cambio de tamaño */
    }

    .input-wrapper input:focus {
        outline: none;
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(52, 152, 219, .15);
        /* halo suave sin mover */
    }

    .toggle-pass {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #888;
        user-select: none;
        transition: background-color .2s, color .2s;
    }

    .toggle-pass:hover {
        background: #f2f2f2;
        color: #333;
    }

    .toggle-pass i {
        pointer-events: none;
    }

    /* evita clics sobre el ícono mover foco */

    .btn {
        width: 100%;
        padding: 12px;
        border: none;
        background: var(--blue);
        color: #fff;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color .2s;
        margin-top: 6px;
    }

    .btn:hover {
        background: var(--blue-dark);
    }

    @media (max-width:480px) {
        .container {
            padding: 20px 15px
        }

        h2 {
            font-size: 20px
        }

        .input-wrapper input,
        .btn {
            font-size: 14px;
            padding: 10px 40px 10px 10px
        }
    }

    .footer {
        text-align: center;
        padding: 20px 10px;
        font-size: 13px;
        color: #666;
        background-color: rgba(255, 255, 255, 0.2);
    }

    .footer span {
        display: block;
        margin-top: 8px;
        font-size: 12px;
        color: #7f8c8d;
        letter-spacing: 0.3px;
    }
</style>

<div class="main-content">
    <div class="container">
        <h2>Actualizar contraseña</h2>

        <form action="index.php?controller=login&action=guardarNuevaPassword" method="POST" id="formCambioPassword" novalidate>
            <label for="nueva_password">Nueva contraseña:</label>
            <div class="input-wrapper">
                <input type="password" id="nueva_password" name="nueva_password" required minlength="6" autocomplete="new-password">
                <span class="toggle-pass" onclick="togglePassword('nueva_password', this)" aria-label="Mostrar u ocultar contraseña" title="Mostrar/Ocultar">
                    <i class="fa fa-eye"></i>
                </span>
            </div>

            <label for="confirmar_password">Confirmar contraseña:</label>
            <div class="input-wrapper">
                <input type="password" id="confirmar_password" name="confirmar_password" required minlength="6" autocomplete="new-password">
                <span class="toggle-pass" onclick="togglePassword('confirmar_password', this)" aria-label="Mostrar u ocultar contraseña" title="Mostrar/Ocultar">
                    <i class="fa fa-eye"></i>
                </span>
            </div>

            <button type="submit" class="btn">Actualizar</button>
        </form>
    </div>
</div>

<script>
    function togglePassword(fieldId, el) {
        const input = document.getElementById(fieldId);
        const icon = el.querySelector('i');
        const showing = input.type === 'text';
        input.type = showing ? 'password' : 'text';
        icon.classList.toggle('fa-eye', showing);
        icon.classList.toggle('fa-eye-slash', !showing);
    }

    // Validación front (usa tus mostrarError de global.js)
    document.getElementById('formCambioPassword').addEventListener('submit', function(e) {
        const nueva = document.getElementById('nueva_password').value.trim();
        const confirmar = document.getElementById('confirmar_password').value.trim();

        if (!nueva || !confirmar) {
            e.preventDefault();
            mostrarError('Campos vacíos', 'Debes completar ambos campos.');
            return;
        }
        if (nueva.length < 6) {
            e.preventDefault();
            mostrarError('Contraseña demasiado corta', 'Debe tener al menos 6 caracteres.');
            return;
        }
        if (!/\d/.test(nueva)) {
            e.preventDefault();
            mostrarError('Falta número', 'La contraseña debe contener al menos un número.');
            return;
        }
        if (nueva !== confirmar) {
            e.preventDefault();
            mostrarError('No coinciden', 'Las contraseñas no coinciden.');
            return;
        }
    });
</script>