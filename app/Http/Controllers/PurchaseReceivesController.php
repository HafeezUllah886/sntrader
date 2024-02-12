<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\purchase;
use Illuminate\Http\Request;
use App\Models\purchase_receives;
use App\Models\stock;

class PurchaseReceivesController extends Controller
{
    public function store(request $req)
    {
        $purchase = purchase::find($req->purchaseID);
       $products = $req->product;
       foreach($products as $key => $product)
       {

        $ref = getRef();
            purchase_receives::create(
                [
                    'purchaseID' => $req->purchaseID,
                    'productID' => $product,
                    'date' => now(),
                    'qty' => $req->qty[$key],
                    'ref' => $ref,
                ]
            );

           stock::create(
            [
                'product_id' => $product,
                'date' => now(),
                'desc' => "Products Received Bill No. $req->purchaseID",
                'cr' => $req->qty[$key],
                'ref' => $ref,
                'warehouseID' => $purchase->warehouseID
            ]
           );

       }
       return back()->with('success', "Products Received");
    }
}
