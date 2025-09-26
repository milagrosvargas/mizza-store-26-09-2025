<?php
// mizzastore/models/Provincia.php
require_once __DIR__ . '/../config/database.php';

class Provincia
{
    private PDO $pdo;

    private const TABLE   = 'provincia';
    private const ID_COL  = 'id_provincia';
    private const NAME    = 'nombre_provincia';
    private const FK_PAIS = 'id_pais';

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    /** Lista provincias con nombre de país (JOIN) */
    public function all(): array
    {
        $sql = "SELECT p." . self::ID_COL . "   AS id_provincia,
                       p." . self::NAME   . "   AS nombre_provincia,
                       p." . self::FK_PAIS. "   AS id_pais,
                       pa.nombre_pais           AS nombre_pais
                FROM " . self::TABLE . " p
                LEFT JOIN pais pa ON pa.id_pais = p." . self::FK_PAIS . "
                ORDER BY p." . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** Devuelve todos los países (para el <select>) */
    public function allPaises(): array
    {
        $sql = "SELECT id_pais, nombre_pais FROM pais ORDER BY nombre_pais";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre, ?int $id_pais): bool
    {
        $sql = "INSERT INTO " . self::TABLE . " (" . self::NAME . ", " . self::FK_PAIS . ")
                VALUES (:n, :pais)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':pais' => $id_pais ?: null]);
    }

    public function update(int $id, string $nombre, ?int $id_pais): bool
    {
        $sql = "UPDATE " . self::TABLE . "
                   SET " . self::NAME . " = :n,
                       " . self::FK_PAIS . " = :pais
                 WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':pais' => $id_pais ?: null, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
