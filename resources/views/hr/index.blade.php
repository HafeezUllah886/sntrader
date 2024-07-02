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
                <h4>Employees</h4>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-success mr-2" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body table-responsive new-user">
                <div class="">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">Name</th>
                                <th class="border-top-0">Designation</th>
                                <th class="border-top-0">Phone</th>
                                <th class="border-top-0">Address</th>
                                <th class="border-top-0">Salary</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                           
                            @foreach ($hrs as $key => $hr)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td class="text-left">{{ $hr->name }}</td>
                                <td class="text-left">{{ $hr->designation }}</td>
                                <td>{{ $hr->phone }}</td>
                                <td>{{ $hr->address }}</td>
                                <td>{{ $hr->salary }}</td>
                                <td>
                                    <button class="btn btn-success mr-2" data-toggle="modal" data-target="#edit_{{$hr->id}}">Edit</button>
                                    <a href="{{route('salaries.index')}}/{{$hr->id}}" class="btn btn-secondary mr-2">Salary</a>
                                </td>
                            </tr>
                            {{-- Model Starts Here --}}
<div class="modal" id="edit_{{$hr->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('employees.update', $hr->id)}}">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{$hr->id}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" required name="name" value="{{$hr->name}}" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation">Designtion</label>
                                <input type="text" required name="designation" value="{{$hr->designation}}" id="designation" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary">Salary</label>
                                <input type="number" name="salary" class="form-control" value="{{$hr->salary}}" id="salary">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" value="{{$hr->phone}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" value="{{$hr->address}}" id="address">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('lang.Update') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    </div>
            </form>
        </div>
    </div>
</div>
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
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Employee</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{route('employees.store')}}">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" required name="name" id="name" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="designation">Designtion</label>
                                <input type="text" required name="designation" id="designation" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="salary">Salary</label>
                                <input type="number" name="salary" class="form-control" id="salary">
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input type="text" name="phone" id="phone" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address</label>
                                <input type="text" name="address" class="form-control" id="address">
                            </div>
                        </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ __('lang.Add') }}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    </div>
            </form>
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
