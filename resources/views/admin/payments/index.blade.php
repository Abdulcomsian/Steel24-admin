@extends('admin.layouts.main', ['activePage' => 'payments', 'titlePage' => 'Payments'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- <div class="card-header card-header-primary">
                                    <h4 class="card-title">Payments</h4>
                                </div> -->
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="row">
                                        {{-- <div class="col-12 text-right">
                                            <a href="{{ url('admin/payments/create') }}"
                                                class="btn btn-sm btn-facebook">Add</a>
                                        </div> --}}
                                        <div class="header_customer">
                                         <div >
                                <h4 >Payments</h4>
                            </div>
                        </div>
                                    </div>
                                    <div class="table-responsive"><!-- remove class="table-responsive"-->
                                        <table class="table data-table table-striped w-100">
                                            <thead class="text-primary text-center">
                                                <th>Lot</th>
                                                <th>Customer</th>
                                                <th>Total Amount</th>
                                                <th>Remaining Amount</th>
                                                <th>Date</th>
                                                <th>Action</th>
                                            </thead>
                                            <tbody class="text-center">

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="card-footer mr-auto">
                                    {{-- {{ $materialss->links() }} --}}
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
    // $(document).ready(function() 
    // {
    //     var table = $('.data-table').DataTable({

    //         processing: true,
    //         serverSide: true,
    //         ajax: "{!! route('admin.payments.index') !!}",

    //         columns: [{
    //                 data: 'lotTitle',
    //                 name: 'lotTitle'
    //             },
    //             {
    //                 data: 'customerName',
    //                 name: 'customerName'
    //             },
    //             {
    //                 data: 'total_amount',
    //                 name: 'total_amount'
    //             },
    //             {
    //                 data: 'remaining_amount',
    //                 name: 'remaining_amount'
    //             },
    //             {
    //                 data: 'Date',
    //                 name: 'Date'
    //             },
    //             {
    //                 data: null,
    //                 sorting:false,
    //                 render: function(data, type, row) {
    //                     return (`<div><a href="{{ url('admin/payments/${data.lotId}') }}"class="btn btn-info btn-sm">Details</a>
    //                 `);
    //                 },
    //             }
    //         ]
    //         // <a href="{{ url('admin/payments/${data.id}/edit') }}"class="btn btn-success"><i class="material-icons">edit</i></a>
    //         //         <a href='javascript:void(0);' id="${data.id}" class='remove btn btn-danger'><i class='fa fa-trash'></i></a></div>

    //     });
    // });


        $(document).ready(function() 
        {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{!! route('admin.payments.index') !!}",
            lengthChange: false, // This disables the "Show [X] entries" dropdown
        searching: false, 
            columns: [
                { data: 'lotTitle', name: 'lotTitle' },
                { data: 'customerName', name: 'customerName' },
                { data: 'total_amount', name: 'total_amount' },
                { data: 'remaining_amount', name: 'remaining_amount' },
                { data: 'paymentDate', name: 'paymentDate' },
                {
                    data: null,
                    sorting: false,
                    render: function(data, type, row) 
                    {
                        return `<div><a href="{{ url('admin/payments/${data.lotId}') }}" class="btn btn-info btn-sm">Details</a></div>`;
                    },
                },
            ],
        });
    });

</script>
<script>
    $(document).on('click', '.remove', function(e) 
    {
        e.preventDefault();
        var id = $(this).attr('id');
        var token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this Material!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Remove!",
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve, reject) {
                    setTimeout(function() {
                        $.ajax({
                            url: "{{ url('/admin/payments/destroy') }}" +
                                "/" + id,
                            type: 'delete',
                            data: {
                                "id": id,
                                "_token": token,
                            },
                            success: function(data) {
                                Swal.fire(
                                    "Success! Material has been deleted!", {
                                        icon: "success",
                                    });
                                $('.data-table').DataTable().ajax
                                    .reload(null, true);
                            }
                        });
                    }, 0);
                });
            },
        });
    });
</script>
