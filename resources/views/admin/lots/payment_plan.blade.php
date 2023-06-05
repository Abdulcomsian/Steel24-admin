@extends('admin.layouts.main', ['activePage' => 'Payment Plan', 'titlePage' => 'Payment Plan'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Payment Plan</h4>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="table-responsive">
                                        <!--Footer-->
                                    {{-- <div class="card-footer ml-auto mr-auto">
                                        <a href="{{ url('admin/lots') }}" class="btn btn-primary">Back</a> --}}
                                        <a href="{{ url('admin/addpayment_plan') }}" class="btn btn-sm btn-facebook">Add</a>
                                    </div>
                                    <!--End footer-->
                                    <div class="table-responsive">
                                        <table class="table data-table">
                                            <thead class="text-primary text-center">                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Payment Terms</th>
                                                    <th>Price Bases</th>
                                                    <th>Taxes and Duties</th>
                                                    <th>Commercial Terms</th>
                                                    <th>Test Certificate</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $count =1;
                                                @endphp
                                                @foreach($paymentTerms as $paymentTerm)
                                                    <tr>
                                                        <td>{{ $count }}</td>
                                                        <td>{{ $paymentTerm->Payment_Terms }}</td>
                                                        <td>{{ $paymentTerm->Price_Bases }}</td>
                                                        <td>{{ $paymentTerm->Texes_and_Duties }}</td>
                                                        <td>{{ $paymentTerm->Commercial_Terms }}</td>
                                                        <td>{{ $paymentTerm->Test_Certificate }}</td>
                                                    </tr>
                                                    @php
                                                        $count++;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer mr-auto">
                                    {{-- {{ $lots->links() }} --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .sorting:before,
    .sorting_asc:before,
    .sorting_desc:before,
    .sorting:after,
    .sorting_asc:after,
    .sorting_desc:after,
    .sorting::after {
        display: none !important;
    }
</style>
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<style>
    .sorting:before,
    .sorting_asc:before,
    .sorting_desc:before,
    .sorting:after,
    .sorting_asc:after,
    .sorting_desc:after,
    .sorting::after {
        display: none !important;
    }
</style>

