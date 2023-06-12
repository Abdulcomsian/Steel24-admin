@extends('admin.layouts.main', ['activePage' => 'customers', 'titlePage' => 'Edit customers'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Customer Balance History</h4>
                        </div>
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">

                            <table  class="table data-table table-striped">
                            <thead class="text-primary text-center">
                                    <tr>
                                        <th>Id</th>
                                        <th>Action</th>
                                        <th>Action Amount</th>
                                        <th>Final Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($balanceHistory as $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->action }}</td>
                                            <td>{{ $record->actionAmount }}</td>
                                            <td>{{ $record->finalAmount }}</td>
                                            <td>{{ $record->date }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>


                        </div>
                        <!--End body-->
                        <!--Footer-->
                        <div class="card-footer ml-auto mr-auto">
                            <a href="{{ route('admin.customers.index') }}" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                        </div>

                    </div>
                    <!--End footer-->
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
  $(document).ready(function() {
    $(".data-table").DataTable({
      paging: true, // Enable pagination
      searching: true, // Enable search box
      // Add more options as needed
    });
  });
</script>