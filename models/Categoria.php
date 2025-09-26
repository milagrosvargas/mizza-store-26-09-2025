<?php
// mizzastore/models/Categoria.php
require_once __DIR__ . '/../config/database.php';

class Categoria
{
    private PDO $pdo;

    private const TABLE   = 'categoria';
    private const ID_COL  = 'id_categoria';
    private const NAME    = 'nombre_categoria';
    private const IMG_COL = 'imagen_categoria'; // columna de tu BD para el archivo (VARCHAR)

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function all(): array
    {
        $sql = "SELECT " . self::ID_COL . "  AS id_categoria,
                       " . self::NAME   . "  AS nombre_categoria,
                       " . self::IMG_COL. "  AS imagen_categoria
                FROM " . self::TABLE . "
                ORDER BY " . self::ID_COL . " ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            "SELECT " . self::ID_COL . "  AS id_categoria,
                    " . self::NAME   . "  AS nombre_categoria,
                    " . self::IMG_COL. "  AS imagen_categoria
             FROM " . self::TABLE . "
             WHERE " . self::ID_COL . " = :id"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        return $row ?: null;
    }

    public function create(string $nombre, ?string $imagen): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (" . self::NAME . ", " . self::IMG_COL . ")
             VALUES (:n, :img)"
        );
        return $stmt->execute([':n' => $nombre, ':img' => $imagen]);
    }

    public function update(int $id, string $nombre, ?string $imagen): bool
    {
        // Si $imagen viene null, no se reemplaza la imagen.
        if ($imagen === null) {
            $stmt = $this->pdo->prepare(
                "UPDATE " . self::TABLE . " SET " . self::NAME . " = :n
                 WHERE " . self::ID_COL . " = :id"
            );
            return $stmt->execute([':n' => $nombre, ':id' => $id]);
        } else {
            $stmt = $this->pdo->prepare(
                "UPDATE " . self::TABLE . "
                   SET " . self::NAME . " = :n,
                       " . self::IMG_COL . " = :img
                 WHERE " . self::ID_COL . " = :id"
            );
            return $stmt->execute([':n' => $nombre, ':img' => $imagen, ':id' => $id]);
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM " . self::TABLE . " WHERE " . self::ID_COL . " = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
