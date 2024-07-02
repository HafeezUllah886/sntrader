<?php

namespace App\Http\Controllers;

use App\Models\salaries;
use App\Http\Controllers\Controller;
use App\Models\account;
use App\Models\hr;
use App\Models\transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalariesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        
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
        // Extract month and year from the input
        $date = \Carbon\Carbon::createFromFormat('Y-m', $request->input('month'));
        $month = $date->month;
        $year = $date->year;

        try
        {
            DB::beginTransaction();
            $ref = getRef();
            salaries::create(
                [
                    'hrID' => $request->id,
                    'date' => $request->date,
                    'month' => $month,
                    'year' => $year,
                    'amount' => $request->amount,
                    'notes' => $request->notes,
                    'ref' => $ref,
                ]
            );

            createTransaction($request->account, $request->date, 0, $request->amount, "Salary Paid", "Salary", $ref);

            DB::commit();
            return back()->with('msg', "Salary Stored");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $salaries = salaries::where('hrID', $id)->orderby('id', 'desc')->get();
        $accounts = account::where('type', 'Business')->get();
        $hr = hr::find($id);

        return view('hr.salary', compact('salaries', 'accounts', 'hr'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(salaries $salaries)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, salaries $salaries)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id, $ref)
    {
        try
        {
            DB::beginTransaction();
            
            salaries::where('ref', $ref)->delete();
            transactions::where('ref', $ref)->delete();

            DB::commit();
            session()->forget('confirmed_password');
            return to_route('salaries.show', $id)->with('msg', "Salary Deleted");
        }
        catch(\Exception $e)
        {
            DB::rollBack();
            session()->forget('confirmed_password');
            return to_route('salaries.show', $id)->with('error', $e->getMessage());
        }
    }
}
