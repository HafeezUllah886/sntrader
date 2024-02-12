<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\catergory;
use App\Models\company;
use App\Models\products;
use App\Models\sale;
use App\Models\sale_details;
use App\Models\salesman;
use App\Models\stock;
use Illuminate\Http\Request;

class POSController extends Controller
{
    public function index()
    {
        $products = products::all();
        /* $categories = catergory::with('products')->get();
        $brands = company::with('products')->get(); */
        $accounts = account::where('type', 'Business')->get();
        $customers = account::where('type', 'Customer')->get();
        return view('pos.index', compact('products', 'accounts', 'customers'));
    }

    public function allProducts()
    {
        $products = products::all();
        $data = [];
        foreach($products as $product)
        {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'pic' => $product->pic,
                'category' => $product->category,
                'brand' => $product->brand,
            ];
        }
        return $data;
    }
    public function byCategory($id)
    {
        $products = products::with('company', 'category')
        ->where('cat', $id)
        ->get();
        $data = [];
        foreach($products as $product)
        {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'size' => $product->size,
                'pic' => $product->pic,
                'color' => $product->color,
                'category' => $product->category->cat,
                'brand' => $product->company->name
            ];
        }
        return $data;
    }
    public function byBrand($id)
    {
        $products = products::with('company', 'category')
        ->where('coy', $id)
        ->get();
        $data = [];
        foreach($products as $product)
        {
            $data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'size' => $product->size,
                'pic' => $product->pic,
                'color' => $product->color,
                'category' => $product->category->cat,
                'brand' => $product->company->name
            ];
        }

        return $data;
    }

    public function getSingleProduct($id)
    {
        $product = products::find($id);
        $cr = stock::where('product_id', $id)->where('warehouseID', auth()->user()->warehouseID)->sum('cr');
        $db = stock::where('product_id', $id)->where('warehouseID', auth()->user()->warehouseID)->sum('db');
        $stock = $cr - $db;

        return response()->json(
            [
                'product' => $product,
                'stock' => $stock,
            ]
        );
    }

    public function store(request $req)
    {
        $refID = getRef();

        $sale = sale::create(
            [
                'customer' => $req->customer,
                'paidIn' => $req->account,
                'date' => $req->date,
                'desc' => $req->notes,
                'isPaid' => "Yes",
                'warehouseID' => auth()->user()->warehouseID,
                'discount' => $req->discount,
                'ref' => $refID,
            ]
        );

        $ids = $req->id;
        foreach ($ids as $key => $id)
        {
            sale_details::create(
                [
                    'bill_id' => $sale->id,
                    'product_id' => $id,
                    'price' => $req->price[$key],
                    'discount' => $req->discount[$key],
                    'qty' => $req->qty[$key],
                    'warehouseID' => auth()->user()->warehouseID,
                    'ref' => $refID,
                    'date' => $req->date
                ]
            );

            stock::create([
                'product_id' => $id,
                'date' => $req->date,
                'desc' => "Sold in Bill No. $sale->id",
                'db' => $req->qty[$key],
                'warehouseID' => auth()->user()->warehouseID,
                'ref' => $refID,
            ]);
        }

        $totalBill = $req->total - $req->discount;

        createTransaction($req->account, $req->date, $totalBill, 0, "Payment of Sale Bill No. $sale->id", "Sale", $refID);
        if($req->customer != 0)
        {
            createTransaction($req->customer, $req->date, $totalBill, $totalBill, "Payment of Sale Bill No. $sale->id", "Sale", $refID);
        }
        $account = account::find($req->account);
            $customer = account::find($req->customer);
            $customer = $customer->title;

        addLedger($req->date, $customer, $account->title . "/Paid", "Products Sold", $totalBill, $refID);

        return redirect('/sale/print/'.$refID.'/POS');
}
}
