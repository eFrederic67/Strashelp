<?php
/**
 * This file dispatch routes.
 *
 * PHP version 7
 *
 * @author   WCS <contact@wildcodeschool.fr>
 *
 * @link     https://github.com/WildCodeSchool/simple-mvc
 */

$routeParts = explode('/', ltrim($_SERVER['REQUEST_URI'], '/') ?: HOME_PAGE);
if (isset($_SESSION['Auth']) && isset($_SESSION['Auth']['login']) && isset($_SESSION['Auth']['pass'])) {
    // Si l'utilisateur est loggué
        $controller = 'App\Controller\\' . ucfirst($routeParts[0] ?? '') . 'Controller';
        $method = $routeParts[1] ?? '';
} else {
    switch ($routeParts[0]) {
        case "home":
            $controller = 'App\Controller\HomeController';
            $method = 'index';
            break;
        case "Session":
            $controller = 'App\Controller\SessionController';
            $method = $routeParts[1];
            break;
        default:
            $controller = 'App\Controller\SessionController';
            $method = 'login';
            break;
    }
}

        $vars = array_slice($routeParts, 2);


if (class_exists($controller) && method_exists(new $controller(), $method)) {
        echo call_user_func_array([new $controller(), $method], $vars);
} else {
    header("HTTP/1.0 404 Not Found");
    echo '<html>
    <body>
        <div style="text-align:center;">
            <h1>404 - Page not found</h1>
            <img src="/assets/images/404.gif">
        </div>
    </body>
</html>';
    exit();
}
