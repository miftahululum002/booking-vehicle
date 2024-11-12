<?php

namespace App\Http\Controllers;

use App\DataTables\VehiclesDataTable;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    protected $directory = 'driver';

    public function index()
    {
        $datatable = new VehiclesDataTable();
        $this->data['title'] = 'Sopir';
        return $this->renderDatatable('index', $datatable);
    }
}
