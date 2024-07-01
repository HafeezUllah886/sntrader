@extends('layout.dashboard')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')
@php
        App::setLocale(auth()->user()->lang);
    @endphp
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Sales History - {{$product->name}}</h4>
            </div>
        </div>
    </div>
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="hidden" name="id" id="id" value="{{$product->id}}">
                            <label for="from">{{ __('lang.FromDate') }}</label>
                            <input type="date" name="from" id="from" value="{{$start}}" onchange="get_items()" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="to">{{ __('lang.ToDate') }}</label>
                            <input type="date" name="to" id="to" value="{{$end}}" onchange="get_items()" class="form-control">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30" >
            <table class="table" id="datatable1">
                <thead>
                    <th class="text-center">#</th>
                    <th>Customer</th>
                    <th class="text-center">Quantity</th>
                    <th class="text-right">Amount</th>
                </thead>
                <tbody>
                    @php
                        $ser = 0;
                    @endphp
                    @foreach($groupedReportData as $data)
                    @php
                        $ser += 1;
                    @endphp
                    <tr>
                        <td class="text-center">{{$ser}}</td>
                        <td>{{ $data['customer_name'] }}</td>
                        <td class="text-center">{{ number_format($data['quantity']) }}</td>
                        <td class="text-right">{{ number_format($data['amount']) }}</td>
                    </tr>
                @endforeach
                </tbody>
                <tbody>
                    <th colspan="2"></th>
                    <th class="text-center">{{number_format($groupedReportData->sum('quantity'))}}</th>
                    <th class="text-right">{{number_format($groupedReportData->sum('amount'))}}</th>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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

    function get_items(){
        var start = $("#from").val();
        var end = $("#to").val();
        var id = $("#id").val();

        var url = '{{route("productSaleHistory", [":id", ":start", ":end"])}}'.replace(":id", id).replace(":start", start).replace(":end", end);

        window.open(url, "_self");
    }

</script>
@endsection
