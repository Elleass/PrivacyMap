<?php

require_once 'Repository.php';

class CatalogRepository extends Repository {
    public function categories(): array
    {
        return $this->connection->query("SELECT * FROM categories ORDER BY name")->fetchAll();
    }

    public function dataTypes(): array
    {
        return $this->connection->query("SELECT * FROM data_types ORDER BY sensitivity_level DESC, name")->fetchAll();
    }

    public function services(): array
    {
        $query = $this->connection->query(
            "
            SELECT services.*, categories.name AS category_name
            FROM services
            LEFT JOIN categories ON categories.id = services.category_id
            ORDER BY services.name
            "
        );

        return $query->fetchAll();
    }

    public function createCategory(string $name, ?string $description): void
    {
        $query = $this->connection->prepare("INSERT INTO categories (name, description) VALUES (:name, :description)");
        $query->execute([':name' => $name, ':description' => $description]);
    }

    public function createDataType(string $name, ?string $description, int $sensitivity): void
    {
        $query = $this->connection->prepare(
            "INSERT INTO data_types (name, description, sensitivity_level) VALUES (:name, :description, :sensitivity)"
        );
        $query->execute([':name' => $name, ':description' => $description, ':sensitivity' => $sensitivity]);
    }

    public function createService(string $name, ?string $url, ?string $description, ?int $categoryId): void
    {
        $query = $this->connection->prepare(
            "
            INSERT INTO services (name, website_url, description, category_id)
            VALUES (:name, :website_url, :description, :category_id)
            "
        );
        $query->execute([
            ':name' => $name,
            ':website_url' => $url,
            ':description' => $description,
            ':category_id' => $categoryId ?: null,
        ]);
    }

    public function deleteCategory(int $id): void
    {
        $query = $this->connection->prepare("DELETE FROM categories WHERE id = :id");
        $query->execute([':id' => $id]);
    }

    public function deleteDataType(int $id): void
    {
        $query = $this->connection->prepare("DELETE FROM data_types WHERE id = :id");
        $query->execute([':id' => $id]);
    }

    public function deleteService(int $id): void
    {
        $query = $this->connection->prepare("DELETE FROM services WHERE id = :id");
        $query->execute([':id' => $id]);
    }
}
