<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\units;
use Illuminate\Http\Request;

class UnitsController extends Controller
{
    public function index()
    {
        $units = units::all();

        return view('products.unit', compact('units'));
    }

    public function store(request $req)
    {
        $check = units::where('title', $req->title)->count();
        if($check > 0)
        {
            return back()->with('error', "Unit already Exists");
        }
        units::create($req->all());

        return back()->with('success', 'Unit Created');
    }

    public function update(request $req)
    {
        $check = units::where('title', $req->title)->whereNot('id', $req->id)->count();
        if($check > 0)
        {
            return back()->with('error', "Unit already Exists");
        }
        units::find($req->id)->update(
            $req->except('id')
        );

        return back()->with('success', 'Unit Updated');
    }
}
