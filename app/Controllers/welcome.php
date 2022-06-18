<?php

namespace App\Controllers;

class welcome extends BaseController
{
    public function index()
    {
        echo view('front/landing');
    }
}
