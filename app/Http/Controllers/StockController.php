<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\stock;
use App\Models\warehouses;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function stock($ware = 0)
    {
        $products = products::all();
        $data = [];
        $balance = 0;
        $value = 0;

        foreach ($products as $product) {
            if($ware == 0){
                $stock_cr = stock::where('product_id', $product->id)->sum('cr');
                $stock_db = stock::where('product_id', $product->id)->sum('db');
            }
            else
            {
                $stock_cr = stock::where('product_id', $product->id)->where('warehouseID', $ware)->sum('cr');
                $stock_db = stock::where('product_id', $product->id)->where('warehouseID', $ware)->sum('db');
            }

            $balance = $stock_cr - $stock_db;
            $value = $balance * $product->pprice;

            $data[] = ['code' => $product->code, 'product' => $product->name, 'balance' => $balance, 'category' => $product->category, 'brand' => $product->brand,'value' => $value, 'price' => $product->pprice, 'retail' => $product->price, 'wholesale' => $product->wholesale];
        }

        $warehouses = warehouses::all();
        return view('purchase.stock')->with(compact('data', 'warehouses', 'ware'));
    }
}
