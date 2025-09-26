<?php

/**
 * ============================================================
 * ARCHIVO: config/database.php
 * DESCRIPCIÓN: Clase para gestionar la conexión a la base de datos
 * utilizando PDO y el patrón orientado a objetos. Se usa en los modelos.
 * ============================================================
 */

class Database
{
    // Propiedad privada con el nombre del host del servidor MySQL
    private $host = 'localhost';

    // Nombre de la base de datos a la que se conectará
    private $db_name = 'mizzastore';

    // Usuario de la base de datos
    private $username = 'root';

    // Contraseña del usuario de la base de datos
    private $password = '';

    // Variable que almacenará la conexión PDO
    private $conn;

    /**
     * Método para establecer la conexión a la base de datos
     * @return PDO|null Retorna la conexión o null si falla
     */
    public function connect()
    {
        // Inicializa la variable de conexión en null
        $this->conn = null;

        try {
            // Define el DSN (Data Source Name) con codificación utf8mb4
            $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8mb4";

            // Crea una nueva instancia PDO
            $this->conn = new PDO($dsn, $this->username, $this->password);

            // Activa el modo de errores mediante excepciones
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Desactiva la emulación de prepares (más seguro y eficiente)
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            // Si hay error, muestra el mensaje y detiene ejecución
            die("❌ Error en la conexión a la base de datos: " . $e->getMessage());
        }

        // Retorna la instancia PDO lista para ser usada
        return $this->conn;
    }
}

function getPDO() : PDO {
    static $pdo = null;
    if ($pdo instanceof PDO) return $pdo;

    $host = 'localhost';
    $db_name   = 'mizzastore';
    $user = 'root';
    $pass = '';
    $dsn  = "mysql:host=$host;dbname=$db_name;charset=utf8mb4";

    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}