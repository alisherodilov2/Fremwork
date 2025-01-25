<?php
namespace App\Controllers;

class Controller {
    public function view($view, $data = []) {
        extract($data);
        include "../resources/views/{$view}.php";
    }
}
