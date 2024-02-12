<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index()
    {
        $areas = area::all();
        return view('finance.area', compact('areas'));
    }

    public function store(request $req)
    {
        area::create(
           [
            'area' => $req->area,
           ]
        );
        return back()->with('success', "Area Created");
    }

    public function update(request $req)
    {
        area::find($req->id)->update(
           [
            'area' => $req->area,
           ]
        );

        return back()->with('success', "Area Updated");
    }
}
