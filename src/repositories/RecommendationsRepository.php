<?php

require_once 'Repository.php';

class RecommendationsRepository extends Repository {
    public function regenerate(int $userServiceId, array $dataTypeNames, string $riskLevel): void
    {
        $delete = $this->connection->prepare("DELETE FROM recommendations WHERE user_service_id = :id AND is_completed = FALSE");
        $delete->execute([':id' => $userServiceId]);

        $rules = [
            'Payment Details' => ['Review saved payment methods', 'Remove unused cards from this account'],
            'Location History' => ['Limit location permissions', 'Clear location history if the service allows it'],
            'Behavioral Analytics' => ['Disable personalized ads', 'Clear off-platform activity'],
            'Contact Lists' => ['Review contact synchronization settings'],
            'Biometric Data' => ['Verify biometric data retention policy'],
            'Health Data' => ['Review health-data sharing permissions'],
            'Identity Documents' => ['Check whether uploaded identity documents can be deleted'],
        ];

        $items = ['Enable two-factor authentication'];
        foreach ($dataTypeNames as $name) {
            foreach ($rules[$name] ?? [] as $title) {
                $items[] = $title;
            }
        }
        if ($riskLevel === 'high') {
            $items[] = 'Consider deleting the account if it is no longer used';
        }

        $insert = $this->connection->prepare(
            "
            INSERT INTO recommendations (user_service_id, title, description, priority)
            VALUES (:user_service_id, :title, :description, :priority)
            "
        );

        foreach (array_unique($items) as $title) {
            $insert->execute([
                ':user_service_id' => $userServiceId,
                ':title' => $title,
                ':description' => 'Local checklist item to reduce this service data exposure.',
                ':priority' => $riskLevel === 'high' ? 'high' : 'medium',
            ]);
        }
    }

    public function complete(int $recommendationId, int $userServiceId): void
    {
        $query = $this->connection->prepare(
            "
            UPDATE recommendations
            SET is_completed = TRUE
            WHERE id = :recommendation_id AND user_service_id = :user_service_id
            "
        );
        $query->execute([':recommendation_id' => $recommendationId, ':user_service_id' => $userServiceId]);
    }
}
