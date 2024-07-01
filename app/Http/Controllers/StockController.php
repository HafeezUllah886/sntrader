<?php

namespace App\Http\Controllers;

use App\Models\products;
use App\Models\sale_details;

use App\Models\stock;
use App\Models\warehouses;


class StockController extends Controller
{
    public function stock($ware = 0)
    {
        if (auth()->user()->role != 1) {
            if ($ware == 0) {
                $ware = auth()->user()->warehouseID;
            }
        }
        $products = products::all();
        $data = [];
        $balance = 0;
        $value = 0;

        foreach ($products as $product) {
            if ($ware == 0) {
                $stock_cr = stock::where('product_id', $product->id)->sum('cr');
                $stock_db = stock::where('product_id', $product->id)->sum('db');
            } else {
                $stock_cr = stock::where('product_id', $product->id)->where('warehouseID', $ware)->sum('cr');
                $stock_db = stock::where('product_id', $product->id)->where('warehouseID', $ware)->sum('db');
            }

            $balance = $stock_cr - $stock_db;
            $value = $balance * $product->pprice;

            $data[] = ['code' => $product->code, 'product' => $product->name, 'balance' => $balance, 'category' => $product->category, 'brand' => $product->brand, 'value' => $value, 'price' => $product->pprice, 'retail' => $product->price, 'wholesale' => $product->wholesale];
        }

        $warehouses = warehouses::all();
        return view('purchase.stock')->with(compact('data', 'warehouses', 'ware'));
    }

    public function sale_history($id, $start = 0, $end = 0)
    {
        if ($start == 0) {
            $start = firstDayOfMonth();
        }
        if ($end == 0) {
            $end = lastDayOfMonth();
        }

        $sales = sale_details::where('product_id', $id)
            ->whereBetween('date', [$start, $end])
            ->with(['bill.customer_account'])
            ->get();
            $reportData = $sales->map(function ($saleDetail) {
                return [
                    'customer_name' => $saleDetail->bill->customer_account->title,
                    'quantity'      => $saleDetail->qty,
                    'amount'        => ($saleDetail->price - $saleDetail->discount) * $saleDetail->qty,
                ];
            });
    
            // Group by customer name
            $groupedReportData = $reportData->groupBy('customer_name')->map(function ($group) {
                return [
                    'customer_name' => $group->first()['customer_name'],
                    'quantity'      => $group->sum('quantity'),
                    'amount'        => $group->sum('amount'),
                ];
            });

        $product = products::find($id);

        return view('products.sale_history', compact('groupedReportData', 'product', 'start', 'end'));
    }
}
