@php
        App::setLocale(auth()->user()->lang);
    @endphp
@extends('layout.dashboard')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card-header">
            <div class="d-flex justify-content-between">
                <h4>{{ __('lang.CustomerDues') }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card bg-white m-b-30">
            <table class="table" id="datatable1" >
                <thead class="th-color">
                    <th>{{ __('lang.Ser') }}</th>
                    <th>{{ __('lang.Account') }}</th>
                    <th>Mobile #</th>
                    <th class="text-right">{{ __('lang.Balance') }}</th>
                </thead>
                <tbody>
                    @foreach ($accounts as $key => $account)
                    @if ($account->balance != 0)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ $account->title }}</td>
                        <td>{{ $account->phone }}</td>
                        <td class="text-right">{{ number_format($account->balance) }}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <th colspan="3"></th>
                    <th class="text-right">{{number_format($accounts->sum('balance'))}}</th>
                </tfoot>
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
    $(document).ready(function() {

    });
    $('#datatable1').DataTable({
        "bSort": true,
        "bLengthChange": true,
        "bPaginate": true,
        "bFilter": true,
        "bInfo": true,
        "order": [[0, 'desc']],
    });

</script>
@endsection
