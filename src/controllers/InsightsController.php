<?php

require_once 'AppController.php';
require_once __DIR__.'/../repositories/DashboardRepository.php';

class InsightsController extends AppController {
    private DashboardRepository $dashboardRepository;

    public function __construct()
    {
        parent::__construct();
        $this->dashboardRepository = new DashboardRepository();
    }

    public function index(): void
    {
        $this->requireAuth();
        $userId = (int) $this->currentUser()['id'];

        $this->render('insights', [
            'metrics' => $this->dashboardRepository->metrics($userId),
            'distribution' => $this->dashboardRepository->dataTypeDistribution($userId),
            'riskBreakdown' => $this->dashboardRepository->riskBreakdown($userId),
            'topRiskyServices' => $this->dashboardRepository->topRiskyServices($userId),
        ]);
    }
}
