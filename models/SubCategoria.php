<?php
// mizzastore/models/SubCategoria.php
require_once __DIR__ . '/../config/database.php';

class SubCategoria
{
    private PDO $pdo;

    private const TABLE   = 'sub_categoria';
    private const ID_COL  = 'id_sub_categoria';
    private const NAME    = 'nombre_sub_categoria';
    private const FK_CAT  = 'id_categoria';

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function all(): array
    {
        $sql = "SELECT sc." . self::ID_COL . "  AS id_sub_categoria,
                       sc." . self::NAME   . "  AS nombre_sub_categoria,
                       sc." . self::FK_CAT . "  AS id_categoria,
                       c.nombre_categoria      AS nombre_categoria
                FROM " . self::TABLE . " sc
                LEFT JOIN categoria c ON c.id_categoria = sc." . self::FK_CAT . "
                ORDER BY sc." . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    /** Para el <select> de categorías */
    public function allCategorias(): array
    {
        $sql = "SELECT id_categoria, nombre_categoria
                FROM categoria
                ORDER BY nombre_categoria";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre, ?int $id_categoria): bool
    {
        $sql = "INSERT INTO " . self::TABLE . " (" . self::NAME . ", " . self::FK_CAT . ")
                VALUES (:n, :cat)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':cat' => $id_categoria ?: null]);
    }

    public function update(int $id, string $nombre, ?int $id_categoria): bool
    {
        $sql = "UPDATE " . self::TABLE . "
                   SET " . self::NAME . " = :n,
                       " . self::FK_CAT . " = :cat
                 WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':n' => $nombre, ':cat' => $id_categoria ?: null, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $sql = "DELETE FROM " . self::TABLE . " WHERE " . self::ID_COL . " = :id";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
