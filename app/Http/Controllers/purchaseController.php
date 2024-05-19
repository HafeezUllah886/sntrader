<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\ledger;
use App\Models\products;
use App\Models\purchase;
use App\Models\purchase_details;
use App\Models\purchase_draft;
use App\Models\purchase_receives;
use App\Models\stock;
use App\Models\transactions;
use App\Models\units;
use App\Models\warehouses;
use Illuminate\Http\Request;

class purchaseController extends Controller
{
    public function purchase()
    {
        $vendors = account::where('type', '!=', 'Business')->get();
        $paidFroms = account::where('type', 'Business')->get();
        $products = products::all();
        $warehouses = warehouses::all();
        $units = units::all();
        return view('purchase.purchase')->with(compact('vendors', 'products', 'paidFroms', 'warehouses', 'units'));
    }

    public function StoreDraft(request $req)
    {
        $check = purchase_draft::where('product_id', $req->product)->count();
        if ($check > 0) {
            return "Existing";
        }

        $rate = $req->rate / $req->unit;

        purchase_draft::create(
            [
                'product_id' => $req->product,
                'qty' => $req->qty * $req->unit,
                'rate' => $rate,
                'warehouseID' => auth()->user()->warehouseID,
            ]
        );

        products::where('id', $req->product)->update(
            [
                'pprice' => $rate,
                'price' => $req->price,
                'wholesale' => $req->wholesale,
            ]
        );

        return "Done";
    }

    public function draftItems()
    {
        $items = purchase_draft::with('product')->where('warehouseID', auth()->user()->warehouseID,)->get();

        return view('purchase.draft')->with(compact('items'));
    }

    public function updateDraftQty($id, $qty)
    {
        $item = purchase_draft::find($id);
        $item->qty = $qty;
        $item->save();

        return "Qty Updated";
    }

    public function updateDraftRate($id, $rate)
    {
        $item = purchase_draft::find($id);
        $item->rate = $rate;
        $item->save();

        return "Rate Updated";
    }

    public function deleteDraft($id)
    {
        purchase_draft::find($id)->delete();
        return "Draft deleted";
    }

    public function storePurchase(request $req)
    {
        $req->validate([
            'date' => 'required',
            'vendor' => 'required',
            'amount' => 'required_if:isPaid,Partial',
            'paidFrom' => 'required_unless:isPaid,No',
        ], [
            'date.required' => 'Select Date',
            'vendor.required' => 'Select Vendor',
            'amount' => 'Enter Paid Amount',
            'paidFrom' => 'Select Account',
        ]);
        $items = purchase_draft::all();
        if($items->count() < 1)
        {
            return back()->with('error', "Please add some products");
        }
        $ref = getRef();
        $vendor = null;
        $walkIn = null;
        $amount = null;
        $paidFrom = null;
        if ($req->isPaid == 'Yes') {
            if ($req->vendor == 0) {
                $walkIn = $req->walkIn;
            } else {
                $vendor = $req->vendor;
            }
            $paidFrom = $req->paidFrom;
        } elseif ($req->isPaid == 'No') {
            $vendor = $req->vendor;
        } else {
            $vendor = $req->vendor;
            $paidFrom = $req->paidFrom;
            $amount = $req->amount;
        }
        $purchase = purchase::create([
            'vendor' => $vendor,
            'paidFrom' => $paidFrom,
            'date' => $req->date,
            'desc' => $req->desc,
            'amount' => $amount,
            'warehouseID' => $req->warehouse,
            'isPaid' => $req->isPaid,
            'ref' => $ref,
        ]);
        $desc = "<strong>Purchased</strong><br/> Bill No. " . $purchase->id;
        $items = purchase_draft::all();
        $total = 0;
        $amount1 = 0;
        foreach ($items as $item) {
            $amount1 = $item->rate * $item->qty;
            $total += $amount1;
            purchase_details::create([
                'bill_id' => $purchase->id,
                'product_id' => $item->product_id,
                'rate' => $item->rate,
                'qty' => $item->qty,
                'warehouseID' => $req->warehouse,
                'date' => $req->date,
                'ref' => $ref,
            ]);

           if($req->status == 'Received')
           {
            purchase_receives::create([
                    'purchaseID' => $purchase->id,
                    'productID' => $item->product_id,
                    'date' => $req->date,
                    'qty' => $item->qty,
                    'ref' => $ref,
                ]);
            stock::create([
                'product_id' => $item->product_id,
                'date' => $req->date,
                'desc' => $desc,
                'warehouseID' => $req->warehouse,
                'cr' => $item->qty,
                'ref' => $ref
            ]);
           }
        }
        $desc1 = "<strong>Products Purchased</strong><br/>Bill No. " . $purchase->id;
        $desc2 = "<strong>Products Purchased</strong><br/>Partial payment of Bill No. " . $purchase->id;
        if ($req->vendor != 0) {
            $check_vendor = account::find($req->vendor);
            if ($req->isPaid == 'Yes') {
                createTransaction($req->paidFrom, $req->date, 0, $total, $desc1, "Purchase", $ref);
                createTransaction($req->vendor, $req->date, $total, $total, $desc1, "Purchase", $ref);
            } elseif ($req->isPaid == 'No') {
                if ($check_vendor->type == "Vendor") {
                    createTransaction($req->vendor, $req->date, $total, 0, $desc1, "Purchase", $ref);
                } else {
                    createTransaction($req->vendor, $req->date, 0, $total, $desc1, "Purchase", $ref);
                }
            } else {
                if ($check_vendor->type == "Vendor") {
                    createTransaction($req->vendor, $req->date, $total, $req->amount, $desc2, "Purchase", $ref);
                } else {
                    createTransaction($req->vendor, $req->date, $req->amount, $total, $desc2, "Purchase", $ref);
                }
                createTransaction($req->paidFrom, $req->date, 0, $req->amount, $desc1, "Purchase", $ref);
            }
        } else {
            createTransaction($req->paidFrom, $req->date, 0, $total, $desc1, "Purchase", $ref);
        }
        $ledger_head = null;
        $ledger_type = null;
        $ledger_details = "Stock Purchased";
        $ledger_amount = null;
        $v_acct = account::find($req->vendor);
        $p_acct = account::find($req->paidFrom);
        if ($req->isPaid == "Yes") {
            if ($req->vendor == 0) {
                $ledger_head = $req->walkIn . "(Walk-In)";
            } else {
                $ledger_head = $v_acct->title;
            }
            $ledger_type = $p_acct->title . "/Paid";
            $ledger_amount = $total;
        } elseif ($req->isPaid == "No") {
            $ledger_head = $v_acct->title;
            $ledger_type = "/Unpaid";
            $ledger_amount = $total;
        } else {
            $ledger_head = $v_acct->title;
            $ledger_type = $p_acct->title . "/Partial";
            $ledger_amount = $req->amount;
        }
        addLedger($req->date, $ledger_head, $ledger_type, $ledger_details, $ledger_amount, $ref);
        purchase_draft::truncate();
        return redirect('/purchase/history');
    }
    public function history()
    {
        $history = purchase::with('vendor_account', 'account', 'receives', 'details')->orderBy('id', 'desc')->get();

        foreach($history as $bill)
        {
            $pendings = [];
            foreach($bill->details as $detail)
            {
                $received = purchase_receives::where('purchaseID', $detail->bill_id)->where('productID', $detail->product_id)->sum('qty');

                $qty = $detail->qty - $received;
                if($qty > 0)
                {
                    $pendings[] = ['qty' => $qty, 'productID' => $detail->product_id, 'productName' => $detail->product->name];
                }

            }
            $bill->pendings = $pendings;
        }
        return view('purchase.history')->with(compact('history'));
    }

    public function edit($id)
    {
        $bill = purchase::where('id', $id)->first();
        $vendors = account::where('type', '!=', 'Business')->get();
        $paidFroms = account::where('type', 'Business')->get();
        $products = products::all();

        return view('purchase.edit')->with(compact('bill', 'products', 'vendors', 'paidFroms'));
    }

    public function editItems($id)
    {
        $items = purchase_details::with('product')->where('bill_id', $id)->get();

        return view('purchase.edit_details')->with(compact('items'));
    }

    public function editAddItems(request $req, $id)
    {
        $check = purchase_details::where('product_id', $req->product)->where('bill_id', $id)->count();
        if ($check > 0) {
            return "Existing";
        }
        $bill = purchase::where('id', $id)->first();
        $purchase = purchase_details::create(
            [
                'bill_id' => $bill->id,
                'product_id' => $req->product,
                'qty' => $req->qty,
                'rate' => $req->rate,
                'date' =>  $bill->date,
                'warehouseID' => auth()->user()->warehouseID,
                'ref' => $bill->ref,
            ]
        );
        $desc = "<strong>Purchased</strong><br/> Bill No. " . $purchase->id;
        stock::create([
            'product_id' => $purchase->product_id,
            'date' => $bill->date,
            'desc' => $desc,
            'warehouseID' => auth()->user()->warehouseID,
            'cr' => $req->qty,
            'ref' => $bill->ref
        ]);
        updatePurchaseAmount($bill->id);
        return "Done";
    }

    public function deleteEdit($id)
    {

        $item = purchase_details::find($id);
        $ref = $item->ref;
        $id = $item->bill_id;
        $item->delete();
        stock::where('ref', $ref)->delete();
        updatePurchaseAmount($id);
        return "Deleted";
    }

    public function updateEditQty($id, $qty)
    {
        $item = purchase_details::find($id);
        $item->qty = $qty;
        $item->save();

        $stock = stock::where('product_id', $item->product_id)->where('ref', $item->ref)->first();
        $stock->cr = $qty;
        $stock->save();

        updatePurchaseAmount($item->bill->id);
        return "Qty Updated";
    }
    public function updateEditRate($id, $rate)
    {
        $item = purchase_details::find($id);
        $item->rate = $rate;
        $item->save();
        updatePurchaseAmount($item->bill->id);
        return "Rate Updated";
    }

    public function deletePurchase($ref)
    {
        $purchase = purchase::where('ref', $ref)->first();
        foreach($purchase->receives as $receives)
        {
            stock::where('ref', $receives->ref)->delete();
            $receives->delete();
        }
    
        purchase_details::where('ref', $ref)->delete();
        transactions::where('ref', $ref)->delete();
        
        purchase::where('ref', $ref)->delete();

        ledger::where('ref', $ref)->delete();
        session()->forget('confirmed_password');
        return redirect('/purchase/history')->with('error', "Purchase Deleted");
    }


}
