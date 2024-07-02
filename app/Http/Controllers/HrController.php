<?php

namespace App\Http\Controllers;

use App\Models\hr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HrController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hrs = hr::all();

        return view('hr.index', compact('hrs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $check = hr::where('name', $request->name)->count();
        if($check > 0 )
        {
            return back()->with('error', "Employee Already Exists");
        }
        else
        {
            hr::create($request->all());
            return back()->with('success', "Employee Created");
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(hr $hr)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(hr $hr)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, hr $hr)
    {
        $check = hr::where('name', $request->name)->where('id', '!=', $request->id)->count();
        if($check > 0)
        {
            return back()->with('error', "Employee Already Exists");
        }
        else
        {
            $hr = hr::find($request->id);
            $hr->update($request->all());
            return back()->with('success', "Employee Updated");
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(hr $hr)
    {
        //
    }
}
