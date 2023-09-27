@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'Details'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Lots</h4>
                        </div>
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                            <div class="row">
                                <!-- first -->
                                <div class="col-md-6">
                                    <div class="card card-user">
                                        <div class="card-body">
                                            <p class="card-text">
                                            <div class="author">
                                                <div class="block block-one"></div>
                                                <div class="block block-two"></div>
                                                <div class="block block-three"></div>
                                                <div class="block block-four"></div>
                                                @foreach ($lotbids as $bid)
                                                    <tr>
                                                        <th scope="row">{{ $bid->id }}</th>
                                                        <td>{{ $bid->customerName }}</td>
                                                        <td>{{ $bid->amount }}</td>
                                                        <td> @customDateFormat($bid->bidTime)</td>
                                                        <td>
                                                            <a
                                                                href="/bid/{{ $bid->id }}"class="btn btn-primary btn-sm">
                                                                Bid Details
                                                            </a>
                                                            <a
                                                                href="/customers/{{ $bid->customerId }}"class="btn btn-primary btn-sm">
                                                                Customer Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </div>
                                            </p>
                                            {{-- <div class="card-description">
                      {{ _('Do not be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...') }}
                    </div> --}}
                                        </div>
                                        <div class="card-footer">
                                            <div class="button-container">
                                                <a href="{{ url('admin/lots') }}" class="btn btn-primary">Back</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end first-->
                            </div>
                            <!--end row-->
                        </div>
                        <!--End card body-->
                    </div>
                    <!--End card-->
                </div>
            </div>
        </div>
    </div>
@endsection
