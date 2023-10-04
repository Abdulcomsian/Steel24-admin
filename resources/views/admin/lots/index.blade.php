@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'Lots'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- <div class="card-header card-header-primary">
                                    <h4 class="card-title">Lots</h4>
                                </div> -->
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-12 text-right header_customer">
                                        <div>
                                            <div >
                                                    <h4 >Lots</h4>
                                            </div>
                                        </div>
                                            <div>
                                            <a href="{{ url('admin/users-send-email') }}"
                                                class="btn btn-info btn-sm send-email">Send Email</a>
                                            <a href="{{ url('admin/lots/create') }}" class="btn btn-sm btn-facebook">Add</a>

                                            <a href="{{ url('admin/lots/import-csv') }}" class="btn btn-sm btn-facebook">Import Lots</a>
                                  </div>
                                        </div>
                                        {{-- <select id='status' class="form-control" style="width: 200px">
                                            <option value="">--Select Status--</option>
                                            <option value="1">Active</option>
                                            <option value="0">Deactive</option>
                                        </select> --}}
                                    </div>
                                    <div class="table-responsive"><!-- remove class="table-responsive" -->
                                        <table class="table data-table table-striped w-100">
                                            <thead class="text-primary text-center">
                                                <th>ID</th>
                                                <th>Title</th>
                                                <th>Start Date</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody class="text-center">
                                                {{-- @foreach ($lots as $key=>$lot) --}}
                                                  
                                                {{-- @endforeach --}}
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
{{-- <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
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
    //     var counter = 1;
        
    //     var table = $('.data-table').DataTable({
    //         serverSide: true,
    //     processing: true,
    //     paging: true,
    //         ajax: {
    //             url: "{{ route('admin.lots') }}",
    //             data: function(d) {
    //                 d.status = $('#status').val(),
    //                     d.search = $('input[type="search"]').val()
    //             }
    //         },
    //         lengthChange: false,
    //         searching: true,
    //         columns: [
    //             {
    //                 data: null,
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
    //                 data: 'Price',
    //                 name: 'Price'
    //             },
    //             {
    //                 data: 'lot_status',
    //                 name: 'lot_status'
    //             },
    //             {
    //                 data: null,
    //                 sorting: false,
    //                 render: function(data, type, row) 
    //                 {
    //                     return (`<div><a href="{{ url('admin/lots/${data.id}') }}" class="btn btn-info btn-sm">Details</a></div>`);
    //                 },
    //             }
    //         ],

    //         rowCallback: function(row, data, index) 
    //         {
    //             // console.log(counter);
    //             $('td:eq(0)', row).html(counter++);
    //         }
    //     });
    // });



//     $(function () {
    
//     var table = $('.data-table').DataTable({
//         processing: true,
//         serverSide: true,
//         ajax: "{{ route('admin.lots') }}",
//         columns: [
//             {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
//             {data: 'title', name: 'title'},
//             {data: 'StartDate', name: 'StartDate'},
//             {data: 'Price', name: 'Price'},
//             {data: 'lot_status', name: 'lot_status'},
//             {data: 'action', name: 'action', orderable: false, searchable: false},
//         ]
//     });
    
//   });

$(function () {
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.lots') }}",
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'title', name: 'title'},
                // {data: 'StartDate', name: 'StartDate'},
                {
                    data: 'StartDate',
                    name: 'StartDate',
                    render: function (data) 
                    {
                        // Assuming 'StartDate' is a date string in ISO format
                        var date = new Date(data);
                        var day = date.getDate().toString().padStart(2, '0');
                        var month = (date.getMonth() + 1).toString().padStart(2, '0');
                        var year = date.getFullYear();
                        var hours = date.getHours().toString().padStart(2, '0');
                        var minutes = date.getMinutes().toString().padStart(2, '0');
                        var seconds = date.getSeconds().toString().padStart(2, '0');

                        return day + '-' + month + '-' + year + ' ' + hours + ':' + minutes + ':' + seconds;
                    }
                },
                {data: 'Price', name: 'Price'},
                {data: 'lot_status', name: 'lot_status'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });

</script>


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

<script type="text/javascript">
    $(".send-email").click(function() {
        alert("hello");
        $.ajax({
            type: 'POST',
            url: "{{ route('admin.send.email') }}",
            data: null,
            success: function(data) {
                alert(data.success);
            }
        });

    });
</script>
