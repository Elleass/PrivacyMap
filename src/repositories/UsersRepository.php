<?php

require_once 'Repository.php';

class UsersRepository extends Repository {
    public function getUsers(): array
    {
        $query = $this->connection->query(
            "
            SELECT users.*, roles.name AS role
            FROM users
            JOIN roles ON roles.id = users.role_id
            ORDER BY users.created_at DESC
            "
        );

        return $query->fetchAll();
    }

    public function getUserByEmail(string $email): ?array
    {
        $query = $this->connection->prepare(
            "
            SELECT users.*, roles.name AS role
            FROM users
            JOIN roles ON roles.id = users.role_id
            WHERE users.email = :email
            "
        );
        $query->execute([':email' => $email]);
        $user = $query->fetch();

        return $user ?: null;
    }

    public function createUser(string $name, string $email, string $hashedPassword): void
    {
        $roleQuery = $this->connection->prepare("SELECT id FROM roles WHERE name = 'user'");
        $roleQuery->execute();
        $roleId = (int) $roleQuery->fetchColumn();

        $query = $this->connection->prepare(
            "
            INSERT INTO users (role_id, name, email, password_hash)
            VALUES (:role_id, :name, :email, :password_hash)
            "
        );
        $query->execute([
            ':role_id' => $roleId,
            ':name' => $name,
            ':email' => $email,
            ':password_hash' => $hashedPassword,
        ]);
    }

    public function getUsersWithServiceCounts(): array
    {
        $query = $this->connection->query(
            "
            SELECT users.id, users.name, users.email, roles.name AS role, users.created_at,
                   COUNT(user_services.id) AS services_count
            FROM users
            JOIN roles ON roles.id = users.role_id
            LEFT JOIN user_services ON user_services.user_id = users.id
            GROUP BY users.id, roles.name
            ORDER BY users.created_at DESC
            "
        );

        return $query->fetchAll();
    }
}
