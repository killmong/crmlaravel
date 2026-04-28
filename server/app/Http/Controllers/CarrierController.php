<?php

namespace App\Http\Controllers;

use App\Models\Carrier;

class CarrierController extends Controller
{
    public function index()
    {
        $carriers = Carrier::orderBy('company_name')->paginate(20);
        return view('carriers.index', compact('carriers'));
    }

    public function show(Carrier $carrier)
    {
        return view('carriers.show', compact('carrier'));
    }
}           
