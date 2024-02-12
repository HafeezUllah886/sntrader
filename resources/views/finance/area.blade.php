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
                <h4>Area</h4>
                <button class="btn btn-success" data-toggle="modal" data-target="#modal">{{ __('lang.CreateNew') }}</button>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <div class="card-body new-user">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover text-center mb-0" id="datatable1">
                        <thead class="th-color">
                            <tr>
                                <th class="border-top-0">{{ __('lang.Ser') }}</th>
                                <th class="border-top-0">Area</th>
                                <th>{{ __('lang.Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($areas as $key => $area)
                            <tr>
                                <td> {{ $key+1 }} </td>
                                <td>{{ $area->area }}</td>
                                <td><button onclick="edit_area({{ $area->id }}, '{{ $area->area }}')" class="btn btn-primary">{{ __('lang.Edit') }}</button></td>
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
                <h5 class="modal-title">Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" action="{{url('/areas/store')}}">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="text" required name="area" id="area" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('lang.Add') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Model Starts Here --}}
<div class="modal" id="edit" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Area</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

                <div class="modal-body">
                    <form action="{{ url('/areas/update') }}" method="post">
                        @csrf
                    <div class="form-group">
                        <label for="area">Area</label>
                        <input type="text" required id="edit_area"  name="area" class="form-control">
                    </div>

                </div>
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('lang.Close') }}</button>
                    <button type="submit" class="btn btn-info">{{ __('lang.Save') }}</button>
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

    function edit_area(id, area) {
        $('#edit_area').val(area);
        $('#edit_id').val(id);
        $('#edit').modal('show');
    }
</script>
@endsection
