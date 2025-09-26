<?php
// mizzastore/models/Pais.php
require_once __DIR__ . '/../config/database.php';

class Pais
{
    private PDO $pdo;

    private const TABLE  = 'pais';
    private const ID_COL = 'id_pais';
    private const NAME   = 'nombre_pais';

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function all(): array
    {
        $sql = "SELECT " . self::ID_COL . " AS id_pais,
                       " . self::NAME . "   AS nombre_pais
                FROM " . self::TABLE . "
                ORDER BY " . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (" . self::NAME . ") VALUES (:n)"
        );
        return $stmt->execute([':n' => $nombre]);
    }

    public function update(int $id, string $nombre): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET " . self::NAME . " = :n WHERE " . self::ID_COL . " = :id"
        );
        return $stmt->execute([':n' => $nombre, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM " . self::TABLE . " WHERE " . self::ID_COL . " = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
