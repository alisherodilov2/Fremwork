<?php

namespace App\Controllers;
use App\Controllers\Controller;
use App\Http\Request;
use App\Models\Users;
use App\Security\Csrf;

class AuthController extends Controller {
    public function login(Request $request) {

        $this->view('login');
    }
    public function authenticate(Request $request) {
        $csrfToken = $request->getPost('csrf_token');
        if (!Csrf::validateToken($csrfToken)) {
            http_response_code(403);
            die('Invalid CSRF token');
        }
        $user = new Users();
        // Handle login logic here
        $name = $request->getPost('name');
        $password = $request->getPost('password');
       print_r( $user->where('username', $name));

    }
}
