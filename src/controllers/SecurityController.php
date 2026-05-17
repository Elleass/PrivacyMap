<?php

require_once 'AppController.php';
require_once __DIR__.'/../repositories/UsersRepository.php';
require_once __DIR__.'/../repositories/AuditLogRepository.php';

class SecurityController extends AppController {
    private UsersRepository $usersRepository;
    private AuditLogRepository $auditLogRepository;

    public function __construct()
    {
        parent::__construct();
        $this->usersRepository = new UsersRepository();
        $this->auditLogRepository = new AuditLogRepository();
    }

    public function login() {
        if ($this->currentUser()) {
            $this->redirect('/dashboard');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $user = $this->usersRepository->getUserByEmail($email);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                return $this->render("login", ["messages" => "Invalid email or password."]);
            }

            session_regenerate_id(true);
            $_SESSION['user'] = [
                'id' => (int) $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'role' => $user['role'],
            ];
            $this->auditLogRepository->log((int) $user['id'], 'login', 'user', (int) $user['id']);
            $this->redirect('/dashboard');
        }

        return $this->render("login");
    }

    public function register() {
        if ($this->currentUser()) {
            $this->redirect('/dashboard');
        }

        if ($this->isPost()) {
            $this->verifyCsrf();
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $password2 = $_POST['password2'] ?? '';
            $errors = [];

            if ($name === '') {
                $errors[] = 'Name is required.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required.';
            }
            if (strlen($password) < 8) {
                $errors[] = 'Password must have at least 8 characters.';
            }
            if ($password !== $password2) {
                $errors[] = 'Repeated password does not match.';
            }
            if ($email && $this->usersRepository->getUserByEmail($email)) {
                $errors[] = 'This email is already registered.';
            }

            if ($errors) {
                return $this->render("register", ["messages" => implode(' ', $errors)]);
            }

            $this->usersRepository->createUser($name, $email, password_hash($password, PASSWORD_DEFAULT));
            $this->flash('Account created. You can log in now.');
            $this->redirect('/login');
        }

        return $this->render("register");
    }

    public function logout(): void
    {
        if ($this->currentUser()) {
            $this->auditLogRepository->log((int) $this->currentUser()['id'], 'logout', 'user', (int) $this->currentUser()['id']);
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        session_destroy();
        $this->redirect('/login');
    }
}
