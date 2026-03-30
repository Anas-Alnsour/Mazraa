<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class FinancialController extends Controller
{
    public function index()
    {
        return view('admin.financials');
    }
}
