<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $directory = 'dashboard';
    public function index()
    {
        $this->data['title'] = 'Dashboard';
        return $this->render('index');
    }
}
