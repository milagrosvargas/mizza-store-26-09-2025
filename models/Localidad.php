<?php
// mizzastore/models/Localidad.php
require_once __DIR__ . '/../config/database.php';

class Localidad
{
    private PDO $pdo;

    private const TABLE   = 'localidad';
    private const ID_COL  = 'id_localidad';
    private const NAME    = 'nombre_localidad';
    private const FK_PROV = 'id_provincia';

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    /** Lista localidades con nombre de provincia */
    public function all(): array
    {
        $sql = "SELECT l." . self::ID_COL . "   AS id_localidad,
                       l." . self::NAME   . "   AS nombre_localidad,
                       l." . self::FK_PROV. "   AS id_provincia,
                       p.nombre_provincia        AS nombre_provincia
                FROM " . self::TABLE . " l
                LEFT JOIN provincia p ON p.id_provincia = l." . self::FK_PROV . "
                ORDER BY l." . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** Para el <select> */
    public function allProvincias(): array
    {
        $sql = "SELECT id_provincia, nombre_provincia FROM provincia ORDER BY nombre_provincia";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre, ?int $id_provincia): bool
    {
        $sql = "INSERT INTO " . self::TABLE . " (" . self::NAME . ", " . self::FK_PROV . ")
                VALUES (:n, :prov)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':prov' => $id_provincia ?: null]);
    }

    public function update(int $id, string $nombre, ?int $id_provincia): bool
    {
        $sql = "UPDATE " . self::TABLE . "
                   SET " . self::NAME . " = :n,
                       " . self::FK_PROV . " = :prov
                 WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':prov' => $id_provincia ?: null, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
