<?php

require_once 'Repository.php';

class DashboardRepository extends Repository {
    public function metrics(int $userId): array
    {
        $totalServices = $this->countScalar("SELECT COUNT(*) FROM user_services WHERE user_id = :user_id", $userId);
        $dataPoints = $this->countScalar(
            "
            SELECT COUNT(*)
            FROM user_services us
            JOIN service_data_types sdt ON sdt.user_service_id = us.id
            WHERE us.user_id = :user_id
            ",
            $userId
        );

        $email = $this->countByDataType($userId, 'Email Address');
        $phone = $this->countByDataType($userId, 'Phone Number');
        $address = $this->countByDataType($userId, 'Home Address');
        $highRisk = $this->countScalar(
            "SELECT COUNT(*) FROM user_services WHERE user_id = :user_id AND risk_level = 'high'",
            $userId
        );
        $privacyHealth = max(0, 100 - ($highRisk * 12) - max(0, $dataPoints - $totalServices) * 2);

        return compact('totalServices', 'dataPoints', 'email', 'phone', 'address', 'highRisk', 'privacyHealth');
    }

    public function latestServices(int $userId): array
    {
        $query = $this->connection->prepare(
            "
            SELECT us.*, COALESCE(us.custom_name, s.name) AS display_name, c.name AS category_name
            FROM user_services us
            LEFT JOIN services s ON s.id = us.service_id
            LEFT JOIN categories c ON c.id = us.category_id
            WHERE us.user_id = :user_id
            ORDER BY us.created_at DESC
            LIMIT 5
            "
        );
        $query->execute([':user_id' => $userId]);

        return $query->fetchAll();
    }

    public function topRiskyServices(int $userId): array
    {
        $query = $this->connection->prepare(
            "
            SELECT us.*, COALESCE(us.custom_name, s.name) AS display_name
            FROM user_services us
            LEFT JOIN services s ON s.id = us.service_id
            WHERE us.user_id = :user_id
            ORDER BY us.risk_score DESC, us.updated_at DESC
            LIMIT 5
            "
        );
        $query->execute([':user_id' => $userId]);

        return $query->fetchAll();
    }

    public function riskBreakdown(int $userId): array
    {
        $query = $this->connection->prepare(
            "
            SELECT risk_level, COUNT(*) AS count
            FROM user_services
            WHERE user_id = :user_id
            GROUP BY risk_level
            ORDER BY risk_level
            "
        );
        $query->execute([':user_id' => $userId]);

        return $query->fetchAll();
    }

    public function dataTypeDistribution(int $userId): array
    {
        $query = $this->connection->prepare(
            "
            SELECT dt.name, COUNT(*) AS count
            FROM user_services us
            JOIN service_data_types sdt ON sdt.user_service_id = us.id
            JOIN data_types dt ON dt.id = sdt.data_type_id
            WHERE us.user_id = :user_id
            GROUP BY dt.name
            ORDER BY count DESC, dt.name
            "
        );
        $query->execute([':user_id' => $userId]);

        return $query->fetchAll();
    }

    private function countByDataType(int $userId, string $dataType): int
    {
        $query = $this->connection->prepare(
            "
            SELECT COUNT(DISTINCT us.id)
            FROM user_services us
            JOIN service_data_types sdt ON sdt.user_service_id = us.id
            JOIN data_types dt ON dt.id = sdt.data_type_id
            WHERE us.user_id = :user_id AND dt.name = :data_type
            "
        );
        $query->execute([':user_id' => $userId, ':data_type' => $dataType]);

        return (int) $query->fetchColumn();
    }

    private function countScalar(string $sql, int $userId): int
    {
        $query = $this->connection->prepare($sql);
        $query->execute([':user_id' => $userId]);

        return (int) $query->fetchColumn();
    }
}
