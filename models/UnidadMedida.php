<?php
// mizzastore/models/UnidadMedida.php
require_once __DIR__ . '/../config/database.php';

class UnidadMedida
{
    private PDO $pdo;

    private const TABLE  = 'unidad_medida';
    private const ID_COL = 'id_unidad_medida';
    private const NAME   = 'nombre_unidad_medida';

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function all(): array
    {
        $sql = "SELECT " . self::ID_COL . " AS id_unidad_medida,
                       " . self::NAME   . " AS nombre_unidad_medida
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
