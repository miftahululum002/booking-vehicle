<?php

namespace App\Http\Controllers;

use App\DataTables\EmployeesDataTable;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    protected $directory = 'employee';

    public function index()
    {
        $datatable = new EmployeesDataTable();
        $this->data['title'] = 'Pegawai';
        return $this->renderDatatable('index', $datatable);
    }
}
