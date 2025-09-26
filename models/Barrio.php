<?php
// mizzastore/models/Barrio.php
require_once __DIR__ . '/../config/database.php';

class Barrio
{
    private PDO $pdo;

    private const TABLE     = 'barrio';
    private const ID_COL    = 'id_barrio';
    private const NAME_COL  = 'nombre_barrio';
    private const FK_LOCALI = 'id_localidad';

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    /** Lista barrios con nombre de localidad */
    public function all(): array
    {
        $sql = "SELECT b." . self::ID_COL   . " AS id_barrio,
                       b." . self::NAME_COL . " AS nombre_barrio,
                       b." . self::FK_LOCALI. " AS id_localidad,
                       l.nombre_localidad     AS nombre_localidad
                FROM " . self::TABLE . " b
                LEFT JOIN localidad l ON l.id_localidad = b." . self::FK_LOCALI . "
                ORDER BY b." . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** Para el <select> */
    public function allLocalidades(): array
    {
        $sql = "SELECT id_localidad, nombre_localidad
                FROM localidad
                ORDER BY nombre_localidad";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre, ?int $id_localidad): bool
    {
        $sql = "INSERT INTO " . self::TABLE . " (" . self::NAME_COL . ", " . self::FK_LOCALI . ")
                VALUES (:n, :loc)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':loc' => $id_localidad ?: null]);
    }

    public function update(int $id, string $nombre, ?int $id_localidad): bool
    {
        $sql = "UPDATE " . self::TABLE . "
                   SET " . self::NAME_COL . " = :n,
                       " . self::FK_LOCALI . " = :loc
                 WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':loc' => $id_localidad ?: null, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
