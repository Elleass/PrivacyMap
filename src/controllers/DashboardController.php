<?php

require_once 'AppController.php';
require_once __DIR__.'/../repositories/DashboardRepository.php';
require_once __DIR__.'/../repositories/AuditLogRepository.php';

class DashboardController extends AppController {
    private DashboardRepository $dashboardRepository;
    private AuditLogRepository $auditLogRepository;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardRepository = new DashboardRepository();
        $this->auditLogRepository = new AuditLogRepository();
    }

    public function index() {
        $this->requireAuth();
        $userId = (int) $this->currentUser()['id'];

        return $this->render("dashboard", [
            "metrics" => $this->dashboardRepository->metrics($userId),
            "latestServices" => $this->dashboardRepository->latestServices($userId),
            "topRiskyServices" => $this->dashboardRepository->topRiskyServices($userId),
            "auditLogs" => $this->auditLogRepository->latestForUser($userId),
        ]);
    }
}
