<?php

namespace App\Http\Controllers;

use App\DataTables\VehiclesDataTable;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    protected $directory = 'vehicle';

    public function index()
    {
        $datatable = new VehiclesDataTable();
        $this->data['title'] = 'Kendaraan';
        return $this->renderDatatable('index', $datatable);
    }
}
