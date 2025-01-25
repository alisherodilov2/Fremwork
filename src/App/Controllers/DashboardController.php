<?php

namespace App\Controllers;

class DashboardController extends Controller
{
    public function index(){
        return $this->view("dashboard");
    }
}