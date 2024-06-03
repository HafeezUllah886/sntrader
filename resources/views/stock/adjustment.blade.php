@extends('layout.dashboard')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Stock Adjustment</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ref') }}</th>
                                <th class="border-top-0">Product</th>
                                <th class="border-top-0">{{ __('lang.Date') }}</th>
                                <th class="border-top-0">Type</th>
                                <th class="border-top-0">Quantity</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($items as $item)
                            <tr>
                                <td> {{ $item->refID }} </td>
                                <td>{{ $item->product->name}}</td>
                                <td>{{ date('d M Y', strtotime($item->date))}}</td>
                                <td>{{ $item->type}}</td>
                                <td>{{ $item->qty}}</td>
                                <td>
                                    <a href="{{ route('adjustment.delete', $item->refID) }}" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Stock Adjustment</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('adjustment.store')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="product">{{__('lang.SelectProduct')}}</label>
                        <select name="product" id="product"  class="selectized">
                            <option value=""></option>
                            @foreach ($products as $pro)
                                <option value="{{ $pro->id }}">
                                    {{$pro->code}} | {{ $pro->name }} | {{$pro->bike}} | {{$pro->brand}} | {{$pro->model}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="type">Type</label>
                       <select name="type" id="type" class="form-control">
                            <option value="in">Stock In</option>
                            <option value="out">Stock Out</option>
                       </select>
                    </div>
                    <div class="form-group">
                        <label for="qty">Quantity</label>
                       <input type="number" step="any" name="qty" id="qty" value="1" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="date">{{ __('lang.Date') }}</label>
                       <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="{{ asset('assets/plugins/selectize/selectize.min.js') }}"></script>
<style>
    .dataTables_paginate {
        display: block
    }

</style>
<script>
    $('#datatable1').DataTable({
        "bSort": true
        , "bLengthChange": true
        , "bPaginate": true
        , "bFilter": true
        , "bInfo": true,

    });
    $('.selectized').selectize();
</script>
@endsection
