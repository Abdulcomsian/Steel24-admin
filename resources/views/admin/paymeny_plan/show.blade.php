@extends('admin.layouts.main', ['activePage' => 'Payment Plan', 'titlePage' => 'Payment Plan'])
@section('content')
<div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <!-- <div class="card-header card-header-primary">
                            <h4 class="card-title">Categories</h4>
                        </div> -->
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                        <div class="header_customer ">
                                         <div >
                                <h4 >Payment Plan Details</h4>
                            </div>
                        </div>
                            <div class="row">
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
                                                <a href="#">
                                                    <!-- <h3 class="title mt-3 text-center">Categories Details</h3> -->
                                                </a>
                                                <table class="table align-items-center mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th
                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                                Fields</th>
                                                            <th
                                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                                                Details</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Payment Terms</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $lotTerms->Payment_Terms }}</p>
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Price Bases</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $lotTerms->Price_Bases }}</p>
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Texes and Duties</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $lotTerms->Texes_and_Duties	}}</p>
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Commercial Terms</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $lotTerms->Commercial_Terms }}</p>

                                                                    {{-- @dd($lotTerms) --}}
                                                            </td>
                                                        </tr>


                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Test Certificate</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $lotTerms->Test_Certificate }}</p>
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                            </p>
                                            {{-- <div class="card-description">
                        {{ _('Do not be scared of the truth because we need to restart the human foundation in truth And I love you like Kanye loves Kanye I love Rick Owens’ bed design but the back is...') }}
                      </div> --}}
                                        </div>

                                        <div class="card-footer">
                                            <div class="button-container">
                                                <a href="{{ url('admin/payment_plan/') }}"
                                                    class="btn btn-sm btn-primary Back_btn_customer">Back</a>
                                                <a href="{{ url('admin/payment_plan/' . $lotTerms->id . '/edit') }}"
                                                    class="btn btn-sm btn-success">Update</a>
                                                    
                                                <a href="javascript:void" id={{ $lotTerms->id }} class="btn btn-danger btn-sm remove">Remove</a>
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
                                            </div>
@endsection
<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).on('click', '.remove', function(e) 
    {
        e.preventDefault();
        var id = $(this).attr('id');
        var token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: 'Are you sure?',
            text: 'Once deleted, you will not be able to recover this Payment Plan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, Remove!",
            showLoaderOnConfirm: true,
            preConfirm: function() {
                return new Promise(function(resolve, reject) 
                {
                    setTimeout(function() {
                        $.ajax({
                            url: "{{ url('/admin/payment_plan/destroy') }}",
                            type: 'DELETE',
                            data: {
                                "_token": token,
                                id : id
                            },
                            success: function(data) 
                            {
                                Swal.fire(
                                    "Success! Payment Plan has been deleted!",
                                    {
                                        icon: "success",
                                    }
                                );
                                
                            },
                            error: function() 
                            {
                                // Swal.fire(
                                //     "Error! Unable to delete Payment Plan.",
                                //     {
                                //         icon: "error",
                                //     }
                                // );
                                Swal.fire(
                                    "Success! Payment Plan has been deleted!",
                                    {
                                        icon: "success",
                                    }
                                );
                                // window.location.reload();
                                window.location.href='/admin/payment_plan';
                            },
                        });
                    }, 0);
                });
            },
        });
    });
</script>

