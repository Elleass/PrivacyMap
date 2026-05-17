<?php

require_once 'Repository.php';

class UserServicesRepository extends Repository {
    public function listForUser(int $userId, array $filters = []): array
    {
        $sql = "
            SELECT us.*, COALESCE(us.custom_name, s.name) AS display_name, c.name AS category_name,
                   COUNT(sdt.id) AS data_points
            FROM user_services us
            LEFT JOIN services s ON s.id = us.service_id
            LEFT JOIN categories c ON c.id = us.category_id
            LEFT JOIN service_data_types sdt ON sdt.user_service_id = us.id
            WHERE us.user_id = :user_id
        ";
        $params = [':user_id' => $userId];

        if (!empty($filters['q'])) {
            $sql .= " AND (LOWER(COALESCE(us.custom_name, s.name)) LIKE :q OR LOWER(us.notes) LIKE :q)";
            $params[':q'] = '%' . strtolower($filters['q']) . '%';
        }
        if (!empty($filters['category_id'])) {
            $sql .= " AND us.category_id = :category_id";
            $params[':category_id'] = (int) $filters['category_id'];
        }
        if (!empty($filters['risk_level'])) {
            $sql .= " AND us.risk_level = :risk_level";
            $params[':risk_level'] = $filters['risk_level'];
        }
        if (!empty($filters['data_type_id'])) {
            $sql .= " AND EXISTS (
                SELECT 1 FROM service_data_types filter_sdt
                WHERE filter_sdt.user_service_id = us.id AND filter_sdt.data_type_id = :data_type_id
            )";
            $params[':data_type_id'] = (int) $filters['data_type_id'];
        }

        $sql .= " GROUP BY us.id, s.name, c.name";
        $sql .= (($filters['sort'] ?? '') === 'risk') ? " ORDER BY us.risk_score DESC, display_name" : " ORDER BY us.created_at DESC";

        $query = $this->connection->prepare($sql);
        $query->execute($params);

        return $query->fetchAll();
    }

    public function findOwned(int $id, int $userId): ?array
    {
        $query = $this->connection->prepare(
            "
            SELECT us.*, COALESCE(us.custom_name, s.name) AS display_name, c.name AS category_name
            FROM user_services us
            LEFT JOIN services s ON s.id = us.service_id
            LEFT JOIN categories c ON c.id = us.category_id
            WHERE us.id = :id AND us.user_id = :user_id
            "
        );
        $query->execute([':id' => $id, ':user_id' => $userId]);
        $service = $query->fetch();

        return $service ?: null;
    }

    public function dataTypesForService(int $id): array
    {
        $query = $this->connection->prepare(
            "
            SELECT dt.*
            FROM data_types dt
            JOIN service_data_types sdt ON sdt.data_type_id = dt.id
            WHERE sdt.user_service_id = :id
            ORDER BY dt.name
            "
        );
        $query->execute([':id' => $id]);

        return $query->fetchAll();
    }

    public function recommendationsForService(int $id): array
    {
        $query = $this->connection->prepare(
            "SELECT * FROM recommendations WHERE user_service_id = :id ORDER BY is_completed, priority DESC, created_at DESC"
        );
        $query->execute([':id' => $id]);

        return $query->fetchAll();
    }

    public function create(int $userId, array $data): int
    {
        [$score, $level] = $this->calculateRisk($data['data_type_ids']);
        $query = $this->connection->prepare(
            "
            INSERT INTO user_services (user_id, service_id, category_id, custom_name, website_url, notes, risk_score, risk_level)
            VALUES (:user_id, :service_id, :category_id, :custom_name, :website_url, :notes, :risk_score, :risk_level)
            RETURNING id
            "
        );
        $query->execute([
            ':user_id' => $userId,
            ':service_id' => $data['service_id'] ?: null,
            ':category_id' => $data['category_id'] ?: null,
            ':custom_name' => $data['custom_name'],
            ':website_url' => $data['website_url'],
            ':notes' => $data['notes'],
            ':risk_score' => $score,
            ':risk_level' => $level,
        ]);
        $id = (int) $query->fetchColumn();
        $this->syncDataTypes($id, $data['data_type_ids']);

        return $id;
    }

    public function update(int $id, int $userId, array $data): void
    {
        [$score, $level] = $this->calculateRisk($data['data_type_ids']);
        $query = $this->connection->prepare(
            "
            UPDATE user_services
            SET service_id = :service_id,
                category_id = :category_id,
                custom_name = :custom_name,
                website_url = :website_url,
                notes = :notes,
                risk_score = :risk_score,
                risk_level = :risk_level,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = :id AND user_id = :user_id
            "
        );
        $query->execute([
            ':id' => $id,
            ':user_id' => $userId,
            ':service_id' => $data['service_id'] ?: null,
            ':category_id' => $data['category_id'] ?: null,
            ':custom_name' => $data['custom_name'],
            ':website_url' => $data['website_url'],
            ':notes' => $data['notes'],
            ':risk_score' => $score,
            ':risk_level' => $level,
        ]);
        $this->syncDataTypes($id, $data['data_type_ids']);
    }

    public function delete(int $id, int $userId): void
    {
        $query = $this->connection->prepare("DELETE FROM user_services WHERE id = :id AND user_id = :user_id");
        $query->execute([':id' => $id, ':user_id' => $userId]);
    }

    public function calculateRisk(array $dataTypeIds): array
    {
        if (!$dataTypeIds) {
            return [0, 'low'];
        }

        $placeholders = implode(',', array_fill(0, count($dataTypeIds), '?'));
        $query = $this->connection->prepare("SELECT COALESCE(SUM(sensitivity_level), 0) FROM data_types WHERE id IN ($placeholders)");
        $query->execute(array_values($dataTypeIds));
        $score = (int) $query->fetchColumn();

        if ($score >= 9) {
            return [$score, 'high'];
        }
        if ($score >= 4) {
            return [$score, 'medium'];
        }

        return [$score, 'low'];
    }

    public function dataTypeNames(array $dataTypeIds): array
    {
        if (!$dataTypeIds) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($dataTypeIds), '?'));
        $query = $this->connection->prepare("SELECT name FROM data_types WHERE id IN ($placeholders)");
        $query->execute(array_values($dataTypeIds));

        return array_column($query->fetchAll(), 'name');
    }

    private function syncDataTypes(int $serviceId, array $dataTypeIds): void
    {
        $delete = $this->connection->prepare("DELETE FROM service_data_types WHERE user_service_id = :id");
        $delete->execute([':id' => $serviceId]);

        $insert = $this->connection->prepare(
            "INSERT INTO service_data_types (user_service_id, data_type_id) VALUES (:service_id, :data_type_id)"
        );

        foreach (array_unique(array_map('intval', $dataTypeIds)) as $dataTypeId) {
            if ($dataTypeId > 0) {
                $insert->execute([':service_id' => $serviceId, ':data_type_id' => $dataTypeId]);
            }
        }
    }
}
