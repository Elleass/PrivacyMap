<?php

require_once 'AppController.php';
require_once __DIR__.'/../repositories/CatalogRepository.php';
require_once __DIR__.'/../repositories/UserServicesRepository.php';
require_once __DIR__.'/../repositories/RecommendationsRepository.php';
require_once __DIR__.'/../repositories/AuditLogRepository.php';

class ServicesController extends AppController {
    private CatalogRepository $catalogRepository;
    private UserServicesRepository $servicesRepository;
    private RecommendationsRepository $recommendationsRepository;
    private AuditLogRepository $auditLogRepository;

    public function __construct()
    {
        parent::__construct();
        $this->catalogRepository = new CatalogRepository();
        $this->servicesRepository = new UserServicesRepository();
        $this->recommendationsRepository = new RecommendationsRepository();
        $this->auditLogRepository = new AuditLogRepository();
    }

    public function index(): void
    {
        $this->requireAuth();
        $filters = [
            'q' => trim($_GET['q'] ?? ''),
            'category_id' => $_GET['category_id'] ?? '',
            'data_type_id' => $_GET['data_type_id'] ?? '',
            'risk_level' => $_GET['risk_level'] ?? '',
            'sort' => $_GET['sort'] ?? '',
        ];

        $this->render('services/index', [
            'services' => $this->servicesRepository->listForUser((int) $this->currentUser()['id'], $filters),
            'categories' => $this->catalogRepository->categories(),
            'dataTypes' => $this->catalogRepository->dataTypes(),
            'filters' => $filters,
        ]);
    }

    public function add(): void
    {
        $this->requireAuth();
        $errors = [];
        $form = $this->serviceFormData();

        if ($this->isPost()) {
            $this->verifyCsrf();
            [$errors, $form] = $this->validateServiceForm();

            if (!$errors) {
                $serviceId = $this->servicesRepository->create((int) $this->currentUser()['id'], $form);
                [$score, $level] = $this->servicesRepository->calculateRisk($form['data_type_ids']);
                $this->recommendationsRepository->regenerate($serviceId, $this->servicesRepository->dataTypeNames($form['data_type_ids']), $level);
                $this->auditLogRepository->log((int) $this->currentUser()['id'], 'created_service', 'user_service', $serviceId);
                $this->flash('Service added with risk score ' . $score . '.');
                $this->redirect('/services/' . $serviceId);
            }
        }

        $this->render('services/form', $this->formVariables('Add Service', '/services/add', $form, $errors));
    }

    public function edit(int $id): void
    {
        $this->requireAuth();
        $service = $this->servicesRepository->findOwned($id, (int) $this->currentUser()['id']);
        if (!$service) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        $selectedDataTypes = array_column($this->servicesRepository->dataTypesForService($id), 'id');
        $form = [
            'service_id' => $service['service_id'],
            'category_id' => $service['category_id'],
            'custom_name' => $service['custom_name'],
            'website_url' => $service['website_url'],
            'notes' => $service['notes'],
            'data_type_ids' => $selectedDataTypes,
        ];
        $errors = [];

        if ($this->isPost()) {
            $this->verifyCsrf();
            [$errors, $form] = $this->validateServiceForm();
            if (!$errors) {
                $this->servicesRepository->update($id, (int) $this->currentUser()['id'], $form);
                [, $level] = $this->servicesRepository->calculateRisk($form['data_type_ids']);
                $this->recommendationsRepository->regenerate($id, $this->servicesRepository->dataTypeNames($form['data_type_ids']), $level);
                $this->auditLogRepository->log((int) $this->currentUser()['id'], 'updated_service', 'user_service', $id);
                $this->flash('Service updated.');
                $this->redirect('/services/' . $id);
            }
        }

        $this->render('services/form', $this->formVariables('Edit Service', '/services/' . $id . '/edit', $form, $errors));
    }

    public function show(int $id): void
    {
        $this->requireAuth();
        $service = $this->servicesRepository->findOwned($id, (int) $this->currentUser()['id']);
        if (!$service) {
            http_response_code(404);
            $this->render('404');
            return;
        }

        $this->render('services/show', [
            'service' => $service,
            'dataTypes' => $this->servicesRepository->dataTypesForService($id),
            'recommendations' => $this->servicesRepository->recommendationsForService($id),
        ]);
    }

    public function delete(int $id): void
    {
        $this->requireAuth();
        if (!$this->isPost()) {
            $this->redirect('/services');
        }
        $this->verifyCsrf();
        $this->servicesRepository->delete($id, (int) $this->currentUser()['id']);
        $this->auditLogRepository->log((int) $this->currentUser()['id'], 'deleted_service', 'user_service', $id);
        $this->flash('Service deleted.');
        $this->redirect('/services');
    }

    public function completeRecommendation(int $serviceId, int $recommendationId): void
    {
        $this->requireAuth();
        if (!$this->isPost()) {
            $this->redirect('/services/' . $serviceId);
        }
        $this->verifyCsrf();
        $service = $this->servicesRepository->findOwned($serviceId, (int) $this->currentUser()['id']);
        if ($service) {
            $this->recommendationsRepository->complete($recommendationId, $serviceId);
            $this->auditLogRepository->log((int) $this->currentUser()['id'], 'completed_recommendation', 'recommendation', $recommendationId);
        }
        $this->redirect('/services/' . $serviceId);
    }

    private function validateServiceForm(): array
    {
        $form = $this->serviceFormData();
        $errors = [];

        if ($form['custom_name'] === '') {
            $errors[] = 'Service name is required.';
        }
        if ($form['website_url'] !== '' && !filter_var($form['website_url'], FILTER_VALIDATE_URL)) {
            $errors[] = 'Service URL must be valid or empty.';
        }
        if (strlen($form['notes']) > 1000) {
            $errors[] = 'Notes can have at most 1000 characters.';
        }

        return [$errors, $form];
    }

    private function serviceFormData(): array
    {
        return [
            'service_id' => (int) ($_POST['service_id'] ?? 0),
            'category_id' => (int) ($_POST['category_id'] ?? 0),
            'custom_name' => trim($_POST['custom_name'] ?? ''),
            'website_url' => trim($_POST['website_url'] ?? ''),
            'notes' => trim($_POST['notes'] ?? ''),
            'data_type_ids' => array_map('intval', $_POST['data_type_ids'] ?? []),
        ];
    }

    private function formVariables(string $title, string $action, array $form, array $errors): array
    {
        return [
            'title' => $title,
            'action' => $action,
            'form' => $form,
            'errors' => $errors,
            'categories' => $this->catalogRepository->categories(),
            'catalogServices' => $this->catalogRepository->services(),
            'dataTypes' => $this->catalogRepository->dataTypes(),
        ];
    }
}
