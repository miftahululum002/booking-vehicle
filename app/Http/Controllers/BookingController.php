<?php

namespace App\Http\Controllers;

use App\DataTables\VehiclesDataTable;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    protected $directory = 'booking';

    public function index()
    {
        $datatable = new VehiclesDataTable();
        $this->data['title'] = 'Booking';
        return $this->renderDatatable('index', $datatable);
    }

    public function create()
    {
        $this->data['title'] = 'Tambah Booking';
        return $this->render('create');
    }
}
