<?php

require_once 'Repository.php';

class AuditLogRepository extends Repository {
    public function log(?int $userId, string $action, ?string $entityType = null, ?int $entityId = null): void
    {
        $query = $this->connection->prepare(
            "
            INSERT INTO audit_logs (user_id, action, entity_type, entity_id)
            VALUES (:user_id, :action, :entity_type, :entity_id)
            "
        );

        $query->execute([
            ':user_id' => $userId,
            ':action' => $action,
            ':entity_type' => $entityType,
            ':entity_id' => $entityId,
        ]);
    }

    public function latestForUser(int $userId, int $limit = 8): array
    {
        $query = $this->connection->prepare(
            "
            SELECT *
            FROM audit_logs
            WHERE user_id = :user_id
            ORDER BY created_at DESC
            LIMIT :limit
            "
        );
        $query->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $query->bindValue(':limit', $limit, PDO::PARAM_INT);
        $query->execute();

        return $query->fetchAll();
    }
}
