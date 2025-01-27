<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ErrorsController extends BaseController
{
    public function show404() {
        return view('errors/show404');
    }

    public function show403(){

        if (!$this->session->get('role')) {

        }

        return view('errors/show403');
        
    }

    public function show500(){
        
    }
}
