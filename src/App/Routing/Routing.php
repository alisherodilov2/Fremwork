<?php

namespace App\Routing;

use App\Http\Request;
use ReflectionMethod;

class Routing {
    protected $routes = [];

    /**
     * Register a GET route.
     */
    public function get(string $path, $callback, array $middlewares = []): void {
        $this->registerRoute('GET', $path, $callback, $middlewares);
    }

    /**
     * Register a POST route.
     */
    public function post(string $path, $callback, array $middlewares = []): void {
        $this->registerRoute('POST', $path, $callback, $middlewares);
    }

    /**
     * Dispatch the request to the appropriate route.
     */
    public function dispatch(string $requestUri, string $requestMethod): void {
        if (!isset($this->routes[$requestMethod])) {
            $this->sendNotFoundResponse();
            return;
        }

        foreach ($this->routes[$requestMethod] as $route => $data) {
            if ($this->matchRoute($route, $requestUri, $params)) {
                if ($this->applyMiddlewares($data['middlewares'])) {
                    $this->executeCallback($data['callback'], $params);
                }
                return;
            }
        }

        $this->sendNotFoundResponse();
    }

    /**
     * Register a route.
     */
    private function registerRoute(string $method, string $path, $callback, array $middlewares = []): void {
        $this->routes[$method][$path] = [
            'callback' => $callback,
            'middlewares' => $middlewares,
        ];
    }

    /**
     * Match the request URI with a route pattern.
     */
    private function matchRoute(string $route, string $requestUri, &$params): bool {
        $pattern = "#^" . preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route) . "$#";
        return preg_match($pattern, $requestUri, $params) && array_shift($params);
    }

    /**
     * Apply middlewares to the current request.
     */
    private function applyMiddlewares(array $middlewares): bool {
        foreach ($middlewares as $middleware) {
            if (!class_exists($middleware)) {
                die("Middleware $middleware not found");
            }

            $middlewareInstance = new $middleware();
            $result = $middlewareInstance->handle(new Request(), function () {
                // Middleware passed
            });

            if ($result === false) {
                return false;
            }
        }

        return true;
    }

    /**
     * Execute the callback for the matched route.
     */
    private function executeCallback($callback, array $params): void {
        if (is_callable($callback)) {
            call_user_func_array($callback, $params);
        } elseif (is_string($callback)) {
            $this->resolveControllerWithParams($callback, $params);
        }
    }

    /**
     * Resolve the controller and method, and invoke it with parameters.
     */
    private function resolveControllerWithParams(string $controllerAction, array $params = []): void {
        [$controller, $method] = explode('@', $controllerAction);
        $controller = "App\\Controllers\\" . $controller;

        if (!class_exists($controller)) {
            die("Controller $controller not found");
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            die("Method $method not found in controller $controller");
        }

        $this->invokeControllerMethod($instance, $method, $params);
    }

    /**
     * Invoke a controller method with the provided parameters.
     * @throws \ReflectionException
     */
    private function invokeControllerMethod($instance, string $method, array $params): void {
        $reflectionMethod = new ReflectionMethod($instance, $method);
        $parameters = $reflectionMethod->getParameters();

        if (!empty($parameters) && $this->isRequestParameter($parameters[0])) {
            array_unshift($params, new Request());
        }

        call_user_func_array([$instance, $method], $params);
    }

    /**
     * Check if the parameter is a Request object.
     */
    private function isRequestParameter($parameter): bool {
        return $parameter->getClass() && $parameter->getClass()->getName() === Request::class;
    }

    /**
     * Send a 404 response.
     */
    private function sendNotFoundResponse(): void {
        http_response_code(404);
        echo "404 Not Found";
    }
}
