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

    public function getChart(Request $request)
    {
        $data = getDataChart($request->start_date, $request->end_date);

        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => $data,
        ], 200);
    }
}
