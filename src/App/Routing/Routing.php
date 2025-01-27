<?php
namespace App\Routing;
namespace App\Routing;

class Routing {
    protected $routes = [];

    public function get($path, $callback, $middlewares = []) {
        $this->routes['GET'][$path] = [
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function post($path, $callback, $middlewares = []) {
        $this->routes['POST'][$path] = [
            'callback' => $callback,
            'middlewares' => $middlewares
        ];
    }

    public function dispatch($requestUri, $requestMethod) {
        foreach ($this->routes[$requestMethod] as $route => $data) {
            // Convert route placeholders (e.g., /error/{code}) into a regex pattern
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route);
            $pattern = "#^" . $pattern . "$#";

            // Match the URI against the pattern
            if (preg_match($pattern, $requestUri, $matches)) {
                // Remove the full match from the matches array
                array_shift($matches);

                // Check for middlewares and apply them
                if (!empty($data['middlewares'])) {
                    foreach ($data['middlewares'] as $middleware) {
                        if (class_exists($middleware)) {
                            $middlewareInstance = new $middleware();
                            $middlewareResult = $middlewareInstance->handle(new \App\Http\Request(), function () {
                                // Middleware passed
                            });

                            if ($middlewareResult === false) {
                                return; // If any middleware fails, stop processing the request
                            }
                        } else {
                            die("Middleware $middleware not found");
                        }
                    }
                }

                // Resolve the controller action with parameters
                $callback = $data['callback'];

                if (is_callable($callback)) {
                    call_user_func_array($callback, $matches);
                } elseif (is_string($callback)) {
                    $this->resolveControllerWithParams($callback, $matches);
                }

                return;
            }
        }

        // No matching route found
        http_response_code(404);
        echo "404 Not Found";
    }

    protected function resolveControllerWithParams($controllerAction, $params = []) {
        list($controller, $method) = explode('@', $controllerAction);

        $controller = "App\\Controllers\\" . $controller;

        if (!class_exists($controller)) {
            die("Controller $controller not found");
        }

        $instance = new $controller();

        if (!method_exists($instance, $method)) {
            die("Method $method not found in controller $controller");
        }

        // Create a Request object and add it as the first parameter if the method expects it
        $reflectionMethod = new \ReflectionMethod($instance, $method);
        $parameters = $reflectionMethod->getParameters();

        if (count($parameters) > 0 && $parameters[0]->getClass() && $parameters[0]->getClass()->getName() === \App\Http\Request::class) {
            array_unshift($params, new \App\Http\Request());
        }

        // Call the method with the provided parameters
        call_user_func_array([$instance, $method], $params);
    }
}
