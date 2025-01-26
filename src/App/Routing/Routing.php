<?php
namespace App\Routing;

class Routing {
    protected $routes = [];

    public function get($path, $callback, $middleware = null) {
        $this->routes['GET'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function post($path, $callback, $middleware = null) {
        $this->routes['POST'][$path] = [
            'callback' => $callback,
            'middleware' => $middleware
        ];
    }

    public function dispatch($requestUri, $requestMethod) {
        $routes = $this->routes[$requestMethod] ?? [];

        foreach ($routes as $path => $route) {
            // Check for dynamic parameters in the route
            $regex = preg_replace('/{(\w+)}/', '(?P<\1>[^/]+)', $path);
            $regex = '@^' . $regex . '$@';

            if (preg_match($regex, $requestUri, $matches)) {
                // Filter numeric keys from the matches
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Check if middleware exists and handle it
                if (isset($route['middleware'])) {
                    $middleware = $route['middleware'];
                    $this->applyMiddleware($middleware);
                }

                // Resolve the callback (controller action)
                $callback = $route['callback'];

                if (is_callable($callback)) {
                    call_user_func_array($callback, $params);
                } elseif (is_string($callback)) {
                    $this->resolveControllerWithParams($callback, $params);
                }
                return;
            }
        }

        // If no route matches, return 404
        http_response_code(404);
        echo "404 Not Found";
    }

    protected function resolveControllerWithParams($controllerAction, $params) {
        list($controller, $method) = explode('@', $controllerAction);

        // The controller's full class name
        $controller = "App\\Controllers\\$controller";

        // Check if the class exists
        if (!class_exists($controller)) {
            die("Controller $controller not found");
        }

        // Instantiate the controller
        $instance = new $controller();

        // Check if the method exists on the controller
        if (!method_exists($instance, $method)) {
            die("Method $method not found in controller $controller");
        }

        // Call the method with parameters
        call_user_func_array([$instance, $method], $params);
    }

    protected function applyMiddleware($middleware) {
        if (class_exists($middleware)) {
            $middlewareInstance = new $middleware();
            $middlewareInstance->handle($_REQUEST, function ($request) {
                // Continue with the request
            });
        } else {
            die("Middleware $middleware not found");
        }
    }
}
