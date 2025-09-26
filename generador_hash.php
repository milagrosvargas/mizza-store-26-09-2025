<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Generador de Hash - MizzaStore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .box {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0px 5px 15px rgba(0,0,0,0.2);
            width: 400px;
            text-align: center;
        }
        input[type="text"] {
            width: 90%;
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .btn {
            padding: 10px 20px;
            background: #3498db;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .btn:hover {
            background: #2980b9;
        }
        .output {
            margin-top: 20px;
            background: #eee;
            padding: 10px;
            word-break: break-all;
            border-radius: 5px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="box">
        <h2>🔐 Generador de Hash (password_hash)</h2>
        <form method="POST">
            <input type="text" name="palabra" placeholder="Escribe una contraseña..." required>
            <br>
            <button type="submit" class="btn">Generar Hash</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST["palabra"])) {
            $textoPlano = $_POST["palabra"];
            $hash = password_hash($textoPlano, PASSWORD_DEFAULT);
            echo "<div class='output'><strong>Hash generado:</strong><br>$hash</div>";
        }
        ?>
    </div>
</body>
</html>
