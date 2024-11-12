<?php

namespace App\Http\Controllers;

use App\DataTables\DriversDataTable;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    protected $directory = 'driver';

    public function index()
    {
        $datatable = new DriversDataTable();
        $this->data['title'] = 'Sopir';
        return $this->renderDatatable('index', $datatable);
    }
}
