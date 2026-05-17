<?php

require_once 'AppController.php';
require_once __DIR__.'/../repositories/CatalogRepository.php';
require_once __DIR__.'/../repositories/UsersRepository.php';
require_once __DIR__.'/../repositories/AuditLogRepository.php';

class AdminController extends AppController {
    private CatalogRepository $catalogRepository;
    private UsersRepository $usersRepository;
    private AuditLogRepository $auditLogRepository;

    public function __construct()
    {
        parent::__construct();
        $this->catalogRepository = new CatalogRepository();
        $this->usersRepository = new UsersRepository();
        $this->auditLogRepository = new AuditLogRepository();
    }

    public function index(): void
    {
        $this->requireAdmin();

        if ($this->isPost()) {
            $this->verifyCsrf();
            $type = $_POST['type'] ?? '';
            try {
                if ($type === 'category') {
                    $this->catalogRepository->createCategory(trim($_POST['name'] ?? ''), trim($_POST['description'] ?? ''));
                } elseif ($type === 'data_type') {
                    $this->catalogRepository->createDataType(trim($_POST['name'] ?? ''), trim($_POST['description'] ?? ''), (int) ($_POST['sensitivity_level'] ?? 1));
                } elseif ($type === 'catalog_service') {
                    $this->catalogRepository->createService(trim($_POST['name'] ?? ''), trim($_POST['website_url'] ?? ''), trim($_POST['description'] ?? ''), (int) ($_POST['category_id'] ?? 0));
                }
                $this->auditLogRepository->log((int) $this->currentUser()['id'], 'admin_updated_catalog', $type ?: null, null);
                $this->flash('Admin catalog updated.');
            } catch (Throwable $e) {
                $this->flash('Admin action failed: ' . $e->getMessage());
            }
            $this->redirect('/admin');
        }

        $this->render('admin/index', [
            'categories' => $this->catalogRepository->categories(),
            'dataTypes' => $this->catalogRepository->dataTypes(),
            'catalogServices' => $this->catalogRepository->services(),
            'users' => $this->usersRepository->getUsersWithServiceCounts(),
        ]);
    }

    public function delete(string $resource, int $id): void
    {
        $this->requireAdmin();
        if (!$this->isPost()) {
            $this->redirect('/admin');
        }
        $this->verifyCsrf();

        try {
            if ($resource === 'categories') {
                $this->catalogRepository->deleteCategory($id);
            } elseif ($resource === 'data-types') {
                $this->catalogRepository->deleteDataType($id);
            } elseif ($resource === 'catalog-services') {
                $this->catalogRepository->deleteService($id);
            }
            $this->auditLogRepository->log((int) $this->currentUser()['id'], 'admin_deleted_catalog_item', $resource, $id);
            $this->flash('Catalog item deleted.');
        } catch (Throwable $e) {
            $this->flash('Delete failed. The item may still be used.');
        }

        $this->redirect('/admin');
    }
}
