<?php

namespace App\Controllers;
use App\Controllers\Controller;
use App\Http\Request;

class AuthController extends Controller {
    public function login(Request $request) {
        $username = $request->getQuery('username');

        // Access POST parameters (if a form submission occurs)
        $password = $request->getPost('password');

        // Prepare data for the view
        $data = [
            'username' => $username ?? 'Guest',
            'password' => $password ?? 'Not Provided'
        ];
        $this->view('login', $data);
    }
}
