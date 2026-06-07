<?php

class AppController {
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '',
                'secure' => $this->isHttps(),
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
            session_start();
        }
    }

    protected function isGet(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'GET';
    }

    protected function isPost(): bool
    {
        return $_SERVER["REQUEST_METHOD"] === 'POST';
    }

    protected function render(string $template = null, array $variables = [])
    {
        $templatePath = 'public/views/'. $template.'.html';
        $templatePath404 = 'public/views/404.html';
        $variables = array_merge([
            'currentUser' => $this->currentUser(),
            'csrfToken' => $this->csrfToken(),
            'messages' => $_SESSION['flash'] ?? null,
        ], $variables);
        unset($_SESSION['flash']);

        ob_start();
        if(file_exists($templatePath)){
            extract($variables);
            include $templatePath;
        } else {
            include $templatePath404;
        }
        echo ob_get_clean();
    }

    protected function redirect(string $path): void
    {
        header('Location: ' . $path);
        exit;
    }

    protected function json(array $payload, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($payload);
        exit;
    }

    protected function wantsJson(): bool
    {
        return str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json');
    }

    protected function flash(string $message): void
    {
        $_SESSION['flash'] = $message;
    }

    protected function requireAuth(): void
    {
        if (!$this->currentUser()) {
            $this->flash('Please log in to continue.');
            $this->redirect('/login');
        }
    }

    protected function requireAdmin(): void
    {
        $this->requireAuth();

        if (($this->currentUser()['role'] ?? '') !== 'admin') {
            http_response_code(403);
            $this->render('403');
            exit;
        }
    }

    protected function currentUser(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    protected function verifyCsrf(): void
    {
        $token = $_POST['csrf_token'] ?? '';
        if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(419);
            die('Invalid CSRF token.');
        }
    }

    protected function e(?string $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }

    private function isHttps(): bool
    {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    }
}
