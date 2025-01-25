<?php

namespace App\Controllers;
use App\Controllers\Controller;

class AuthController extends Controller {
    public function login() {
        $data = ['username' => 'example'];
        $this->view('login', $data);
    }
}
