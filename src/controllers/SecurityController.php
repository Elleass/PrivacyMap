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
            $rateKey = $this->loginRateKey($email);

            if ($this->isLoginRateLimited($rateKey)) {
                http_response_code(429);
                return $this->render("login", ["messages" => "Too many login attempts. Please try again in a few minutes."]);
            }

            if (strlen($email) > 255 || strlen($password) > 128) {
                $this->recordFailedLogin($rateKey);
                return $this->render("login", ["messages" => "Invalid email or password."]);
            }

            $user = $this->usersRepository->getUserByEmail($email);

            if (!$user || !password_verify($password, $user['password_hash'])) {
                $this->recordFailedLogin($rateKey);
                return $this->render("login", ["messages" => "Invalid email or password."]);
            }

            $this->clearLoginAttempts($rateKey);
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
            if (strlen($name) > 100) {
                $errors[] = 'Name can have at most 100 characters.';
            }
            if (strlen($email) > 255) {
                $errors[] = 'Email can have at most 255 characters.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = 'Valid email is required.';
            }
            if (strlen($password) < 8) {
                $errors[] = 'Password must have at least 8 characters.';
            }
            if (strlen($password) > 128) {
                $errors[] = 'Password can have at most 128 characters.';
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
        if (!$this->isPost()) {
            $this->redirect($this->currentUser() ? '/dashboard' : '/login');
        }

        $this->verifyCsrf();

        if ($this->currentUser()) {
            $this->auditLogRepository->log((int) $this->currentUser()['id'], 'logout', 'user', (int) $this->currentUser()['id']);
        }

        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', [
                'expires' => time() - 42000,
                'path' => $params['path'],
                'domain' => $params['domain'],
                'secure' => $params['secure'],
                'httponly' => $params['httponly'],
                'samesite' => $params['samesite'] ?? 'Lax',
            ]);
        }
        session_destroy();
        $this->redirect('/login');
    }

    private function isLoginRateLimited(string $key): bool
    {
        $attempt = $_SESSION['login_attempts'][$key] ?? null;

        return $attempt && ($attempt['blocked_until'] ?? 0) > time();
    }

    private function recordFailedLogin(string $key): void
    {
        $attempt = $_SESSION['login_attempts'][$key] ?? [
            'count' => 0,
            'first_attempt_at' => time(),
            'blocked_until' => 0,
        ];

        if (($attempt['first_attempt_at'] ?? 0) < time() - 900) {
            $attempt = [
                'count' => 0,
                'first_attempt_at' => time(),
                'blocked_until' => 0,
            ];
        }

        $attempt['count']++;
        if ($attempt['count'] >= 5) {
            $attempt['blocked_until'] = time() + 300;
        }

        $_SESSION['login_attempts'][$key] = $attempt;
    }

    private function clearLoginAttempts(string $key): void
    {
        unset($_SESSION['login_attempts'][$key]);
    }

    private function loginRateKey(string $email): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        return hash('sha256', strtolower(trim($email)) . '|' . $ip);
    }
}
