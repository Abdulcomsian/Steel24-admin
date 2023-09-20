{{-- @extends('admin.layouts.main', ['activePage' => 'Notification', 'titlePage' => 'Lots'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Admin Notification</h4>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <h4 class="card-title">Lot Sequence</h4>
                                    <div class="table-responsive">
                                        <table class="table data-table">
                                            <thead class="text-primary text-center">
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Lot Id</th>
                                                <th>Customer Id</th>
                                                <th>Actions</th>
                                            </thead>                                            
                                            <tbody class="text-center">
                                                @foreach ($notifications as $notification)
                                                        <tr>
                                                            <td>{{ $notification->id }}</td>
                                                            <td>{{ $notification->customername }}</td>
                                                            <td>{{ $notification->lotId }}</td>
                                                            <td>{{ $notification->customerId }}</td>
                                                            <td>
                                                                <a href="{{ url("admin/live_lots_bids/{$notification->id}") }}"
                                                                    class="btn btn btn-info btn-sm">Approved</a>
                                                            </td>
                                                        </tr>
                                                @endforeac
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer mr-auto"> --}}
                                    {{-- {{ $lots->links() }} --}}
                                {{-- </div>
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
<script type="text/javascript"> --}}


    {{-- // $(document).ready(function() {
    //     var table = $('.data-table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: "{!! route('admin.livelots') !!}",

    //         columns: [{
    //                 data: 'id',
    //                 name: 'id'
    //             },
    //             {
    //                 data: 'title',
    //                 name: 'title'
    //             },
    //             {
    //                 data: 'StartDate',
    //                 name: 'StartDate'
    //             },

    //             {
    //                 data: 'EndDate',
    //                 name: 'EndDate'
    //             },
    //             {
    //                 data: 'lot_status',
    //                 name: 'lot_status'
    //             },
    //             {
    //                 data: null,
    //                 render: function(data, type, row) {
    //                     return (`<div><a href="{{ url('admin/live_lots_bids/${data.id}') }}"class="btn btn-info"><i class="material-icons">person</i></a>
    //                 `);
    //                 },
    //             }
    //         ]
    //     });
    // }); --}}

{{-- </script> --}}


@extends('admin.layouts.main', ['activePage' => 'Notification', 'titlePage' => 'Lots'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Admin Notification</h4>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <h4 class="card-title">Lot Sequence</h4>
                                    <div class="table-responsive">
                                        <table class="table data-table">
                                            <thead class="text-primary text-center">
                                                <th>#</th>
                                                <th>User Name</th>
                                                <th>Lot Id</th>
                                                <th>Customer Id</th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody class="text-center">

                                                @foreach ($notifications as $key => $notification)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $notification->customername }}</td>
                                                            <td>{{ $notification->lotId }}</td>
                                                            <td>{{ $notification->customerId }}</td>
                                                            <td>
                                                                <a href="{{ url("admin/live_lots_bids/{$notification->id}") }}"
                                                                    class="btn btn-info btn-sm">Approved</a>
                                                            </td>
                                                        </tr>
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
<script type="text/javascript">
    // $(document).ready(function() {
    //     var table = $('.data-table').DataTable({
    //         processing: true,
    //         serverSide: true,
    //         ajax: "{!! route('admin.livelots') !!}",

    //         columns: [{
    //                 data: 'id',
    //                 name: 'id'
    //             },
    //             {
    //                 data: 'title',
    //                 name: 'title'
    //             },
    //             {
    //                 data: 'StartDate',
    //                 name: 'StartDate'
    //             },

    //             {
    //                 data: 'EndDate',
    //                 name: 'EndDate'
    //             },
    //             {
    //                 data: 'lot_status',
    //                 name: 'lot_status'
    //             },
    //             {
    //                 data: null,
    //                 render: function(data, type, row) {
    //                     return (`<div><a href="{{ url('admin/live_lots_bids/${data.id}') }}"class="btn btn-info"><i class="material-icons">person</i></a>
    //                 `);
    //                 },
    //             }
    //         ]
    //     });
    // });
</script>

