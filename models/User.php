<?php

// mizzastore/models/User.php
require_once __DIR__ . '/../config/database.php';

class User
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = getPDO();
    }

    /* =======================
     *  BÚSQUEDAS / CHEQUEOS
     * ======================= */
    public function usernameExists(string $username): bool
    {
        $st = $this->pdo->prepare("SELECT 1 FROM usuarios WHERE nombre_usuario = :u LIMIT 1");
        $st->execute([':u' => $username]);
        return (bool)$st->fetchColumn();
    }

    public function emailExists(string $email): bool
    {
        // Busco si existe ese email en detalle_contacto
        $st = $this->pdo->prepare("SELECT 1 FROM detalle_contacto WHERE descripcion_contacto = :e LIMIT 1");
        $st->execute([':e' => $email]);
        return (bool)$st->fetchColumn();
    }

    /** Devuelve el id del tipo_contacto "correo" (o 1 como fallback) */
    private function tipoContactoCorreo(): int
    {
        $st = $this->pdo->query("SELECT id_tipo_contacto 
                                   FROM tipo_contacto 
                                  WHERE LOWER(nombre_tipo_contacto) LIKE '%correo%' 
                                  LIMIT 1");
        $id = $st->fetchColumn();
        return $id ? (int)$id : 1;
    }

    /* =======================
     *  REGISTRO (TRANSACCIÓN)
     * ======================= */
    public function registerCustomer(array $data): int
    {
        // $data: nombre_completo, email, username, password_hash
        $this->pdo->beginTransaction();
        try {
            // 1) detalle_contacto (email)
            $tipoCorreo = $this->tipoContactoCorreo();
            $st = $this->pdo->prepare(
                "INSERT INTO detalle_contacto (descripcion_contacto, id_tipo_contacto)
                 VALUES (:email, :tipo)"
            );
            $st->execute([
                ':email' => $data['email'],
                ':tipo'  => $tipoCorreo
            ]);
            $id_detalle_contacto = (int)$this->pdo->lastInsertId();

            // 2) persona (solo nombre + FK email)
            $st = $this->pdo->prepare(
                "INSERT INTO persona (nombre_persona, id_detalle_contacto)
                 VALUES (:nombre, :id_detalle_contacto)"
            );
            $st->execute([
                ':nombre'             => $data['nombre'],
                ':id_detalle_contacto'=> $id_detalle_contacto
            ]);
            $id_persona = (int)$this->pdo->lastInsertId();

            // 3) usuarios (perfil cliente = 2)
            $st = $this->pdo->prepare(
                "INSERT INTO usuarios 
                   (nombre_usuario, password_usuario, estado_usuario, relacion_perfil, relacion_persona)
                 VALUES
                   (:user, :pass, 1, :perfil, :id_persona)"
            );
            $st->execute([
                ':user'      => $data['username'],
                ':pass'      => $data['password_hash'],
                ':perfil'    => (int)($data['perfil'] ?? 2), // 2 = Cliente por defecto
                ':id_persona'=> $id_persona
            ]);

            $id_usuario = (int)$this->pdo->lastInsertId();
            $this->pdo->commit();
            return $id_usuario;
        } catch (\Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }
}
