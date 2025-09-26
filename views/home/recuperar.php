<style>
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
        background: white;
        width: 100%;
        max-width: 400px;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0px 5px 15px rgba(0, 0, 0, 0.2);
        text-align: center;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
        font-size: 24px;
    }

    input[type="email"] {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 16px;
        box-sizing: border-box;
    }

    .btn {
        width: 100%;
        padding: 12px;
        border: none;
        background-color: #3498db;
        color: white;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .btn:hover {
        background-color: #2980b9;
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


    @media (max-width: 480px) {
        .container {
            padding: 20px 15px;
        }

        h2 {
            font-size: 20px;
        }

        input[type="email"],
        .btn {
            font-size: 14px;
            padding: 10px;
        }

        .footer {
            font-size: 12px;
        }
    }
</style>

<!-- Contenido principal centrado -->
<div class="main-content">
    <div class="container">
        <h2>Recuperar contraseña</h2>
        <form method="POST" action="index.php?controller=login&action=enviarRecuperacion" novalidate>
            <input type="email" name="correo" placeholder="Correo asociado a tu cuenta" required>
            <button type="submit" class="btn">Enviar instrucciones</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form[action*="enviarRecuperacion"]');

        if (form) {
            form.addEventListener('submit', function(e) {
                const correo = form.querySelector('input[name="correo"]').value.trim();

                // Expresión regular básica para validar correos electrónicos
                const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                if (correo === '') {
                    e.preventDefault();
                    mostrarError('Por favor, ingresa tu correo electrónico.');
                    return;
                }

                if (!correoValido.test(correo)) {
                    e.preventDefault();
                    mostrarError('El formato del correo no es válido.');
                    return;
                }
            });
        }
    });
</script>