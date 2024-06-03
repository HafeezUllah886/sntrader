<?php

namespace App\Http\Controllers;

use App\Models\stock_adjustment;
use App\Http\Controllers\Controller;
use App\Models\products;
use App\Models\stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = stock_adjustment::orderBy('id', 'desc')->get();
        $products = products::all();
        return view('stock.adjustment', compact('items', 'products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      try{
            DB::beginTransaction(); 
            $ref = getRef();
            stock_adjustment::create(
                [
                    'date' => $request->date,
                    'productID' => $request->product,
                    'qty' => $request->qty,
                    'type' => $request->type,
                    'refID' => $ref,
                ]
            );

            if($request->type == 'in')
            {
                stock::create([
                    'product_id' => $request->product,
                    'date' => $request->date,
                    'desc' => "Stock Adjusted",
                    'cr' => $request->qty,
                    'ref' => $ref,
                    'warehouseID' => auth()->user()->warehouseID,
                ]);
            }
            else
            {
                stock::create([
                    'product_id' => $request->product,
                    'date' => $request->date,
                    'desc' => "Stock Adjusted",
                    'db' => $request->qty,
                    'ref' => $ref,
                    'warehouseID' => auth()->user()->warehouseID,
                ]);
            }
            DB::commit();

            return back()->with('success', "Stock Adjusted");
       }  catch(\Exception $e)
        {
            DB::rollBack();
            return back()->with('error', 'Something Went Wrong');
        } 

       
    }

    /**
     * Display the specified resource.
     */
    public function show(stock_adjustment $stock_adjustment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(stock_adjustment $stock_adjustment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, stock_adjustment $stock_adjustment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($ref)
    {
        stock::where('ref', $ref)->delete();
        stock_adjustment::where('ref', $ref)->delete();
        return back()->with('success', 'Adjustment Deleted');
    }

    public function delete($ref)
    {
        stock::where('ref', $ref)->delete();
        stock_adjustment::where('refID', $ref)->delete();
        return back()->with('success', 'Adjustment Deleted');
    }
}
