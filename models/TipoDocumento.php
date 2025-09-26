<?php
// mizzastore/models/TipoDocumento.php
require_once __DIR__ . '/../config/database.php';

class TipoDocumento
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    public function all(): array
    {
        $sql = "SELECT id_tipo_documento, nombre_tipo_documento
                FROM tipo_documento
                ORDER BY id_tipo_documento ASC";
        return $this->pdo->query($sql)->fetchAll();
    }

    public function create(string $nombre): bool
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO tipo_documento (nombre_tipo_documento) VALUES (:n)"
        );
        return $stmt->execute([':n' => $nombre]);
    }

    public function update(int $id, string $nombre): bool
    {
        $stmt = $this->pdo->prepare(
            "UPDATE tipo_documento SET nombre_tipo_documento = :n WHERE id_tipo_documento = :id"
        );
        return $stmt->execute([':n' => $nombre, ':id' => $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM tipo_documento WHERE id_tipo_documento = :id"
        );
        return $stmt->execute([':id' => $id]);
    }
}
