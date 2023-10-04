@extends('admin.layouts.main', ['activePage' => 'Live Lots', 'titlePage' => 'Lots'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <!-- <div class="card-header card-header-primary">
                                    <h4 class="card-title">Live Lots</h4>
                                </div> -->
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="header_customer">
                                    
                                         <div >
                                            <h4 >Live Lots</h4>
                                        </div>
                                        {{-- <div class="d-flex justify-content-end">
                                        <a href="pushonfirbase" class="btn btn-primary add_New_Button">Start Lots</a>
                                        </div> --}}
                                    </div>
                                    
                                    {{-- <h4 class="card-title">Lot Sequence</h4> --}}
                                    {{-- <form method="post" action="livelotsequencechange">
                                        @csrf
                                        <div id="sequenceDiv" class="row">
                                        </div>
                                        <input type="submit" class="btn btn-primary btn-sm" value="Change" />
                                    </form> --}}

                                    @foreach ($categories as $categorie)
                                    
                                        <a href="{{ url("admin/live_lots/categorie/{$categorie->id}") }}"
                                            class="btn btn btn-info btn-sm">{{ $categorie->title }}</a>
                                    @endforeach
                                    
                                    <a href="{{ url("admin/sta_lots") }}"
                                    class="btn btn btn-info btn-sm">STA Lots</a>
                                    <a href="{{ url("admin/live_lots") }}"
                                        class="btn btn btn-info btn-sm">All Lots</a>
                                    <div class="table-responsive">
                                        <table class="table data-table">
                                            <thead class="text-primary text-center">
                                                <th>#</th>
                                                <th>Lot ID</th>
                                                <th>Title</th>
                                                <th>Start Date</th>
                                                <th>End Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </thead>
                                            <tbody class="text-center">
                                                @foreach ($livelots as $lot)
                                                    <tr>
                                                        <td>{{ $loop->index + 1 }}</td>
                                                        <td>{{ $lot->id }}</td>
                                                        <td>{{ $lot->title }}</td>

                                                        {{-- <td>
                                                            @if ($lot->ReStartDate > $lot->StartDate)
                                                                {{ $lot->ReStartDate }}
                                                            @else
                                                                {{ $lot->StartDate }}
                                                            @endif
                                                        </td> --}}
                                                        <td>
                                                            @php
                                                                $startDate = \Carbon\Carbon::parse($lot->StartDate)->format('d-m-Y H:i:s');
                                                                $reStartDate = \Carbon\Carbon::parse($lot->ReStartDate)->format('d-m-Y H:i:s');
                                                                echo $lot->ReStartDate > $lot->StartDate ? $reStartDate : $startDate;
                                                            @endphp
                                                        </td>
                                                    
                                                        {{-- <td>
                                                            @if ($lot->ReEndDate > $lot->EndDate)
                                                                {{ $lot->ReEndDate }}
                                                            @else
                                                                {{ $lot->EndDate }}
                                                            @endif

                                                        </td> --}}
                                                        <td>
                                                            @php
                                                                $endDate = \Carbon\Carbon::parse($lot->EndDate)->format('d-m-Y H:i:s');
                                                                $reEndDate = \Carbon\Carbon::parse($lot->ReEndDate)->format('d-m-Y H:i:s');
                                                                echo $lot->ReEndDate > $lot->EndDate ? $reEndDate : $endDate;
                                                            @endphp
                                                        </td>
                                                        

                                                        <td>{{ $lot->lot_status }}</td>
                                                        <td>
                                                            <a href="{{ url("admin/live_lots_bids/{$lot->id}") }}"
                                                                class="btn btn btn-info btn-sm">Details</a>
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
        <script>
            var lotsList = <?php echo json_encode($livelots); ?>;
            var divHtml = '';
            lotsList.forEach((lot, index) => {

                divHtml += "<div  class='col-2 row my-2'>";
                divHtml += "<lable class='col-4 font-weight-bold'>" + lot['id'] + "</lable>";
                divHtml += "<input type='number' name='" + lot['id'] + "' value='" + Number(index + 1) +
                    "' class='col-8 p-0' >";
                divHtml += "</div>";
            })
            document.getElementById('sequenceDiv').innerHTML = divHtml;
        </script>
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
      $(document).ready(function() {
    $('.data-table').DataTable({
      paging: true, // Enable pagination
      searching: true, // Enable search box
      lengthChange: false, // This disables the "Show [X] entries" dropdown
        searching: true, 
      // Add more options as needed
    });
  });
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
