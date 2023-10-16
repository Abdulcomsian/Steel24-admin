@extends('admin.layouts.main', ['activePage' => 'customers', 'titlePage' => 'Edit customers'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <!-- <div class="card-header card-header-primary">
                            <h4 class="card-title">Customer Balance History</h4>
                        </div> -->
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                        <div class="header_customer">
                                         <div >
                                <h4  >Customer Balance History</h4>
                            </div>
                        </div>
                            <table  class="table data-table table-striped">
                            <thead class="text-primary text-center">
                                    <tr>
                                        <th>Id</th>
                                        <th>Action</th>
                                        {{-- <th>Action Amount</th> --}}
                                        <th>Debit</th>
                                        <th>Credit</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    @foreach ($balanceHistory as $record)
                                        <tr>
                                            <td>{{ $record->id }}</td>
                                            <td>{{ $record->action }}</td>

                                            <td>
                                                {{ $record->action==="Participate Fees" ? $record->actionAmount:"-"}}
                                            </td>
                                            <td>
                                                {{ $record->action === "Participate Fees Back" || $record->action === "Return Participation Fee" || $record->action === "credit" ? $record->actionAmount:"-" }}
                                            </td>
                                            <td>{{ $record->finalAmount }}</td>
                                            <td>{{ \Carbon\Carbon::parse($record->date)->format('d-m-Y h:i:s A') }}</td>
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