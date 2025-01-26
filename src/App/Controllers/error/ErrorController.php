<?php

namespace App\Controllers\error;

use App\Controllers\Controller;

class ErrorController extends Controller
{
    public function error($code){
        $this->view('error/'.$code);
    }
}