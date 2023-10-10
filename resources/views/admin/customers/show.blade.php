@extends('admin.layouts.main', ['activePage' => 'customers', 'titlePage' => 'Details Of customers'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <!-- <div class="card-header card-header-primary m-0">
                            <h4 class="card-title">Customers</h4>
                        </div> -->
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                            <div class="row">
                                        <div class="header_customer px-4">
                                         <div >
                                <h4 >Customers</h4>
                            </div>
                        </div>
                                <!-- first -->
                                <div class="col-md-12">
                                    <div class="card card-user">
                                        <div class="card-body">
                                            <p class="card-text">
                                            <div class="author">
                                                <div class="block block-one"></div>
                                                <div class="block block-two"></div>
                                                <div class="block block-three"></div>
                                                <div class="block block-four"></div>
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex" style="margin-left:3%">
                                                        <img class="avatar" src="{{ asset('/img/default-avatar.png') }}"
                                                        alt="">
                                                        <div >
                                                        <p class="text-xs text-secondary mb-0 ml-4 font-weight-Bold" style="color:black !important">{{ $customer->name }}</p>
                                                        <p class="text-xs text-secondary mb-0 ml-4 ">
                                                                    {{ $customer->email }}</p>
                                                      </div>
                                                    </div>

                                                    <h5 class="title mt-3">Registered at:
                                                        <small>{{ $customer->created_at->format('d-m-Y H:i:s') }}</small>
                                                    </h5>                                                    
                                                    
                                                    <div>
                                                        <a href="{{ url("admin/customers/balancehistory/{$customer->id}") }}">
                                                            <button class="btn btn-primary btn-sm">
                                                                Balance History
                                                            </button>
                                                        </a>
                                                        <a href="{{ url("admin/activecustomers/{$customer->id}") }}">
                                                            <button class="btn btn-primary btn-sm">
                                                                @if ($customer->isApproved)
                                                                    Inactive
                                                                @else
                                                                    Active
                                                                @endif
                                                            </button>
                                                        </a>
                                                    </div>
                                                </div>
                                                <div class="form-row justify-content-around mt-4">
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6 >Contact no.</h6>
                                                            <h6>
                                                                    {{ $customer->contactNo }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>Aadhaar no.</h6>
                                                            <h6>
                                                            {{ $customer->adharNo }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>GST no.</h6>
                                                            <h6>
                                                            {{ $customer->GSTNo }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>PAN no.</h6>
                                                            <h6>
                                                            {{ $customer->PanNo }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>Address</h6>
                                                            <h6>
                                                            {{ $customer->address }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>City</h6>
                                                            <h6>
                                                            {{ $customer->city }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>STATE</h6>
                                                            <h6>
                                                            {{ $customer->state }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>PINCODE</h6>
                                                            <h6>
                                                            {{ $customer->pincode }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>BALANCE</h6>
                                                            <h6>
                                                            @if ($customerBalance)
                                                                        {{ $customerBalance->finalAmount }}
                                                                    @endif</h6>
                                                        </div>
                                                            <div >
                                                            <div class="d-flex justify-content-between mt-3">
                                                            <h6>GST Bill</h6>
                                                            <img src="{{ url('files/' . $customer->gst_img) }}"
                                                                        style="width:120px; height:120px; margin-top:10px;" />
                                                            </div>
                                                            <div>
                                                       
                                                    </div>
                                                    </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between mt-3">
                                                        <h6>Adhar Card</h6>
                                                        <img src="{{ url('files/' . $customer->aadhar_img) }}"
                                                                    style="width:120px; height:120px; margin-top:10px;" />
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3">
                                                        <h6>PAN Card</h6>
                                                        <img src="{{ url('files/' . $customer->pan_img) }}"
                                                                    style="width:120px; height:120px; margin-top:10px;" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <table class="table align-items-center mb-0">
                                                    <!-- <thead>
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                Fields</th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                Details</th>
                                                        </tr>
                                                    </thead> -->
                                                    <tbody>
                                                        <!-- <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Customer name</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">{{ $customer->name }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Email</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->email }}</p>
                                                            </td>
                                                        </tr> -->
                                                        <!-- <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Contact no.</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->contactNo }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Aadhaar no.</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->adharNo }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">GST no.</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->GSTNo }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">PAN no.</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->PanNo }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Address</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->address }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">City</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->city }}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">State</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->state }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Pincode</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $customer->pincode }}</p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Balance</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    @if ($customerBalance)
                                                                        {{ $customerBalance->finalAmount }}
                                                                    @endif
                                                                </p>
                                                            </td>
                                                        </tr> -->
                                                        <!-- <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">GST Bill</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <img src="{{ url('files/' . $customer->gst_img) }}"
                                                                    style="width:150px; height:150px;" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">PAN Card</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <img src="{{ url('files/' . $customer->pan_img) }}"
                                                                    style="width:150px; height:150px;" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Adhar Card</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <img src="{{ url('files/' . $customer->aadhar_img) }}"
                                                                    style="width:150px; height:150px;" />
                                                            </td>
                                                        </tr> -->
                                                    </tbody>
                                                </table>
                                            </div>
                                            </p>

                                        </div>
                                        <div class="card-footer">
                                            <div class="button-container">
                                                <a href="{{ route('admin.customers.index') }}"
                                                    class="btn btn-primary btn-sm Back_btn_customer">Back</a>
                                                <a href="{{ url("admin/customers/edit/{$customer->id}") }}"
                                                    class="btn btn-success btn-sm">Update</a>
                                                <a href="{{ url("admin/customers/destroy/{$customer->id}") }}"
                                                    onclick="return confirm('Once deleted, you will not be able to recover this User!?')"
                                                    class="btn btn-danger  btn-sm remove">Remove</a>
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
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
{{-- <script>
    $(document).on('click', '.remove', function(e) {
        e.preventDefault();
        var id = $(this).attr('id');
        var token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this Lot!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Remove!",
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve, reject) {
                    setTimeout(function() {
                        $.ajax({
                            url: "{{ url('/admin/customers/destroy') }}" +
                                "/" + id,
                            type: 'get',
                            data: {
                                "id": id,
                                "_token": token,
                            },
                            success: function(data) {
                                Swal.fire(
                                    "Success! Lot has been deleted!", {
                                        icon: "success",
                                    });
                                // window.location.href = "{{ url('admin/lots') }}";
                            }
                        });
                    }, 0);
                });
            },
        });
    });
</script> --}}
