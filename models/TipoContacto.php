<?php
// mizzastore/models/TipoContacto.php
require_once __DIR__ . '/../config/database.php';

class TipoContacto
{
    private PDO $pdo;

    private const TABLE  = 'tipo_contacto';
    private const ID_COL = 'id_tipo_contacto';
    private const NAME   = 'nombre_tipo_contacto';

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function all(): array
    {
        $sql = "SELECT " . self::ID_COL . " AS id_tipo_contacto,
                       " . self::NAME   . " AS nombre_tipo_contacto
                FROM " . self::TABLE . "
                ORDER BY " . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre): bool
    {
        $sql = "INSERT INTO " . self::TABLE . " (" . self::NAME . ") VALUES (:n)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre]);
    }

    public function update(int $id, string $nombre): bool
    {
        $sql = "UPDATE " . self::TABLE . "
                   SET " . self::NAME . " = :n
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
