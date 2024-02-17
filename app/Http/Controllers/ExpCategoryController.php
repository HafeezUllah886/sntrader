<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\expCategory;
use Illuminate\Http\Request;

class ExpCategoryController extends Controller
{
    public function index()
    {
        $categories = expCategory::all();

        return view('finance.expense_category', compact('categories'));
    }

    public function store(request $req)
    {
        expCategory::create($req->all());

        return back()->with('success', "Expense Category Created");
    }

    public function update(request $req)
    {
        expCategory::find($req->id)->update($req->except(['id']));

        return back()->with('success', 'Expense Category Updated');
    }
}
