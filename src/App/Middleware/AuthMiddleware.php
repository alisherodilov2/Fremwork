<?php
namespace App\Middleware;

class AuthMiddleware extends Middleware {
    public function handle($request, $next) {
        // Example: Check if the user is authenticated
        if (!isset($_SESSION['user'])) {
            // Set the HTTP response code to 403 (Forbidden)
            http_response_code(403);

            // Redirect to a 403 error page
            header("Location: /403");
            exit; // Stop further execution
        }

        return $next($request); // Proceed to the next middleware or controller
    }
}
