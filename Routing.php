<?php

require_once 'src/controllers/SecurityController.php';
require_once 'src/controllers/DashboardController.php';
require_once 'src/controllers/ServicesController.php';
require_once 'src/controllers/InsightsController.php';
require_once 'src/controllers/AdminController.php';

class Routing {
    public static $routes = [
        "login" => ["controller" => "SecurityController", "action" => "login"],
        "register" => ["controller" => "SecurityController", "action" => "register"],
        "logout" => ["controller" => "SecurityController", "action" => "logout"],
        "dashboard" => ["controller" => "DashboardController", "action" => "index"],
        "services" => ["controller" => "ServicesController", "action" => "index"],
        "services/add" => ["controller" => "ServicesController", "action" => "add"],
        "insights" => ["controller" => "InsightsController", "action" => "index"],
        "admin" => ["controller" => "AdminController", "action" => "index"],
        "" => ["controller" => "SecurityController", "action" => "login"],
    ];

    public static function run(string $path) {
        $path = trim($path, '/');

        if (preg_match('#^services/(\d+)/edit$#', $path, $matches)) {
            (new ServicesController())->edit((int) $matches[1]);
            return;
        }

        if (preg_match('#^services/(\d+)/delete$#', $path, $matches)) {
            (new ServicesController())->delete((int) $matches[1]);
            return;
        }

        if (preg_match('#^services/(\d+)/recommendations/(\d+)/complete$#', $path, $matches)) {
            (new ServicesController())->completeRecommendation((int) $matches[1], (int) $matches[2]);
            return;
        }

        if (preg_match('#^services/(\d+)$#', $path, $matches)) {
            (new ServicesController())->show((int) $matches[1]);
            return;
        }

        if (preg_match('#^admin/(categories|data-types|catalog-services)/(\d+)/delete$#', $path, $matches)) {
            (new AdminController())->delete($matches[1], (int) $matches[2]);
            return;
        }

        if (array_key_exists($path, self::$routes)) {
            $controller = self::$routes[$path]["controller"];
            $action = self::$routes[$path]["action"];
            (new $controller())->$action();
            return;
        }

        http_response_code(404);
        include 'public/views/404.html';
    }
}
