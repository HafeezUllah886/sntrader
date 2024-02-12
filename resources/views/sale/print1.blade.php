<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS</title>
    <style>


        body {
            -webkit-print-color-adjust: exact;
            background-color: #F6F6F6;
            margin: 0;
            padding: 0;
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            margin: 0;
            padding: 0;
        }

        p {
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin-right: auto;
            margin-left: auto;
        }

        .brand-section {
            background-color: #898811;
            padding: 10px 40px;
        }

        .logo {
            width: 50%;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
        }

        .col-6 {
            width: 50%;
            flex: 0 0 auto;
        }

        .text-white {
            color: #fff;
        }

        .company-details {
            float: right;
            text-align: right;
        }

        .body-section {
            padding: 5px;
          /*   border-left: 2px solid #898811;
            border-right: 2px solid #898811; */

        }

        .body-section1 {
            background-color: #898811;
            color: white;
            border-radius: 4px;
        }

        .heading {
            font-size: 20px;
            margin-bottom: 08px;
        }

        .sub-heading {
            color: #262626;
            margin-bottom: 05px;
        }

        table {
            background-color: #fff;
            width: 100%;
            border-collapse: collapse;
        }

        table thead tr {
            border: 1px solid #111;
            background-color: #f2f2f2;
        }

        table td {
            vertical-align: middle !important;
            text-align: center;
        }

        table th,
        table td {
            padding-top: 08px;
           /*  padding-bottom: 08px; */
        }

        /* .table-bordered {
            box-shadow: 0px 0px 5px 0.5px gray;
        } */

        .table-bordered td,
        .table-bordered th {
           /*  border: 1px solid #dee2e6; */
        }

        .text-right {
            text-align: end;
            padding-right: 3px;
            ;
        }
        .text-left {
            text-align: start;
            padding-left: 3px;
            ;
        }

        .w-20 {
            width: 10%;
        }

        .w-15 {
            width: 22%;
        }

        .w-5 {
            width: 5%;
        }

        .w-10 {
            width: 18%;
        }

        .float-right {
            float: right;
        }

        .container1 {
          /*   border: 2px solid #898811; */
            color: #ffffff;
            height: 90px;
            border-radius: 6px;
        }

        .sub-container {
            background-color: #898811;
            ;
            margin: 5px;
            padding-bottom: 2px;
            display: flex;
            height: 78px;
            border-radius: 6px;


        }

        .m-query1 {
            font-size: 22px;
        }

        .m-query2 {
            font-size: 11px;
        }

        img {
            margin-top: -36px;
            padding: 2px;
            width: 92%;
            height: 148px;
            margin-left: 2px;

        }

        .text1 {
            text-align: center;
            width: 70%;
            padding-top: 11px;
        }

        .qoute {
            width: 100px;
            margin: auto;
            text-align: center;
            background-color: #898811;
            color: white;
            border-radius: 5px;
            font-size: 12px;
        }

        @media screen and (max-width: 1014px) {
            .m-query1 {
                margin-top: 6PX;
                font-size: 28px;
            }

            .m-query2 {
                font-size: 11px;
            }
        }

        @media screen and (max-width: 900px) {
            .m-query1 {
                font-size: 24px;
            }

            .m-query2 {
                font-size: 14px;
            }

            img {
                width: 99%;
                height: 171%;
                margin-top: -50px;
                margin-left: 8px;
            }


        }

        .div3 {}

        #myDiv {
            width: 128px;
            font-size: 18px;
            margin-top: 19px;
        }

        .dot {
            height: 60px;
            width: 65px;
            background-color: #898811;
            color: white;
            /* color: #b80000; */
            border-radius: 50%;
            display: inline-block;
            border: 5px solid white;
            margin: -14px;
            margin-left: 7px;
            text-align: center;
        }
        .sub-heading{
            display: flex;
            align-items: center
            height:100px;
        }
    </style>
</head>

<body>

    <div class="container">
        <div style="text-align: center;">
           <img style="width:100%;margin:0 auto;height:100px;" src="{{ asset('assets/images/header.jpg') }}" alt="">
        </div>

        <div class="body-section">
            <div><h5 style="text-align: center;">Customer Invoice</h5></div>
            <div class="row">
                <div class="qoute">
                    <h5 style="text-align: center;">INVOICE# &nbsp; {{ $invoice->id }}</h5>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <!-- <h2 class="heading">Invoice No.: 001</h2> -->
                    <h5 class="sub-heading">Invoice to:
                            {{ $invoice->customer_account->title }}
                    </h5>
                </div>
                <div class="col-6">
                    <div class="company-details">
                        <h5 class="text-dark">Date: {{ date('d M Y', strtotime($invoice->date)) }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="body-section">
            <!-- <h3 class="heading">Ordered Items</h3>
            <br> -->
            <table class="table-bordered">
                <thead>
                    <tr style="background-color: #111;color:#fff;">
                        <th class="w-5">#</th>
                        <th class="w-15">Product</th>
                        <th class="w-10">Qty</th>
                        <th class="w-10">Price</th>
                        <th class="w-15">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $total = 0;
                        $ser = 0;
                    @endphp

                    @foreach ($details as $item)
                        @php
                            $ser += 1;
                        @endphp
                        <tr>
                            <td>{{ $ser }}</td>
                            <td colspan="4" class="text-left">{{ $item->product->name }}</td></tr>
                        <tr>
                            <td></td>
                            <td></td>
                            <td>{{ $item->qty }}</td>
                            <td>{{ round($item->price,0) }}</td>
                            <td>{{ round($item->price * $item->qty,0) }}</td>
                        </tr>
                        @php
                            $total += $item->price * $item->qty;
                        @endphp
                    @endforeach

                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Total</strong>
                        </td>
                        <td>
                            <strong>{{ round($total,0)}}</strong>
                        </td>
                    </tr>
                    @if($invoice->discount > 0)
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Discount</strong>
                        </td>
                        <td class="text-right">
                            <strong>{{ $invoice->discount == 0 ? 0 : $invoice->discount}}</strong>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Net Total</strong>
                        </td>
                        <td>
                            <strong>{{ ($total - $invoice->discount) }}</strong>
                        </td>
                    </tr>
                    @endif
                    @if (@$invoice->customer_account->title)
                    <tr>
                        @php
                        $paidAmount = $invoice->amount;
                        if(!$invoice->paidIn){
                             $paidAmount = 0;
                        }
                        else{

                            if($invoice->amount == 0){
                                $paidAmount = $total - ($invoice->amount + $invoice->discount);
                            }
                        }

                        @endphp
                        @if(@$invoice->isPaid != "Yes")
                        <td colspan="4" class="text-right">
                            <strong>Paid Amount</strong>
                        </td>
                        <td>
                            <strong>{{ $paidAmount }}</strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="4" class="text-right">
                            <strong>Remaining</strong>
                        </td>
                        <td>
                            <h3> {{ $total - $paidAmount - $invoice->discount}}</h3>
                        </td>
                    </tr>
                        @endif

                    @endif
                </tbody>
            </table>
            <br>
            <br>
        </div>
    </div>
    <div style="text-align: right; margin-right:10px;">
        <div class="mt-2" style="font-size: 10px; ">Powered by Diamond Software 03202565919</p>
        </div>
</body>

</html>
<script>

    window.print();
        setTimeout(function() {
        window.location.href = "{{ url('/sale')}}";
    }, 5000);


</script>
