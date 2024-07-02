@extends('layout.dashboard')
@php
App::setLocale(auth()->user()->lang);
@endphp
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif
<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>Salary of {{$hr->name}} - {{$hr->designation}}</h4>
               
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <form action="{{route('salaries.store')}}" method="post">
                    @csrf
                    <input type="hidden" name="id" value="{{$hr->id}}">
                    <div class="form-group">
                        <label for="amount">Salary Amount</label>
                        <input type="number" class="form-control" name="amount" required min="0" value="{{$hr->salary}}">
                    </div>
                    <div class="form-group">
                        <label for="month">Salary Month</label>
                        <input type="month" class="form-control" name="month" required>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" value="{{date("Y-m-d")}}" name="date" required>
                    </div>
                    <div class="form-group">
                        <label for="account">Account</label>
                        <select name="account" id="account" class="form-control">
                            @foreach ($accounts as $account)
                                <option value="{{$account->id}}">{{$account->title}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea name="notes" id="notes" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                    <button type="submit" class="btn btn-success mt-2">Save</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">Salary Month</th>
                                <th class="border-top-0">Date</th>
                                <th class="border-top-0">Amount</th>
                                <th class="border-top-0">Notes</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach ($salaries as $key => $salary)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>{{ $salary->month}}-{{$salary->year}}</td>
                                <td>{{ date("d-m-Y", strtotime($salary->date)) }}</td>
                                <td>{{ $salary->amount }}</td>
                                <td>{{ $salary->notes }}</td>
                                
                                <td>
                                    <a href="{{url('/salaries/delete/')}}/{{$hr->id}}/{{$salary->ref}}" class="btn btn-danger mr-2" >Delete</a>
                                </td>
                            </tr>
                            {{-- Model Starts Here --}}
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
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

    function edit_pro(id) {
        $.ajax({
            method: 'get',
            url: "{{url('/products/get_pro')}}",
            data: {
                id: id
            },
             success: function(abc) {
                $('#edit_name').val(abc.pro['name']);
                $('#edit_code').val(abc.pro['code']);
                $('#edit_category').val(abc.pro['category']);
                $('#edit_brand').val(abc.pro['brand']);
                $('#edit_alert').val(abc.pro['alert']);
                $('#edit_id').val(abc.pro['id']);
                $('#edit').modal('show');
            }
        });
    }
</script>
@endsection
