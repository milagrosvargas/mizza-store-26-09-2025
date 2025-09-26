<?php
// mizzastore/models/EstadoLogico.php
require_once __DIR__ . '/../config/database.php';

class EstadoLogico
{
    private PDO $pdo;

    private const TABLE       = 'estado_logico';
    private const ID_COL      = 'id_estado_logico';
    private const NAME_COL_DB = 'nombre_estado'; // <- así está en tu SQL

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function all(): array
    {
        // Alias para que la vista siga leyendo 'nombre_estado_logico'
        $sql = "SELECT " . self::ID_COL . "   AS id_estado_logico,
                       " . self::NAME_COL_DB . " AS nombre_estado_logico
                FROM " . self::TABLE . "
                ORDER BY " . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre): bool
    {
        $sql = "INSERT INTO " . self::TABLE . " (" . self::NAME_COL_DB . ") VALUES (:n)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre]);
    }

    public function update(int $id, string $nombre): bool
    {
        $sql = "UPDATE " . self::TABLE . " 
                   SET " . self::NAME_COL_DB . " = :n
                 WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
