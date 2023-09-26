@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'Details'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <!-- <div class="card-header card-header-primary m-0">
                            <h4 class="card-title">Lots</h4>
                        </div> -->
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                        <div class="header_customer ">
                                         <div >
                                <h4 >Lots</h4>
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
                                                    <h5 class="title mt-3 " style="text-align:center ; color:black !important">Lot Name : <small>{{ $lots->title }}</small>
                                                    </h5>
                                                </a>

                                                <div class="form-row justify-content-around mt-4">
                                                    <div class="col-md-5">
                                                        {{-- <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6 >Title</h6>
                                                            <h6>
                                                            {{ $lots->title }}</h6>
                                                        </div> --}}

                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6 >Description</h6>
                                                            <h6>
                                                            {{ $lots->description }}</h6>
                                                        </div>
                                                        {{-- <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>Category</h6>
                                                            <h6>
                                                            {{ $lots->categories->title }}</h6>
                                                        </div> --}}
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6>Category</h6>
                                                            <h6>
                                                                @if ($lots->categories)
                                                                    {{ $lots->categories->title }}
                                                                @else
                                                                    No category available
                                                                @endif
                                                            </h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>Material Location</h6>
                                                            <h6>
                                                            {{ $lots->materialLocation }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6 >LOT QUANTITY</h6>
                                                            <h6>
                                                            {{ $lots->Quantity }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>START DATE</h6>
                                                            <h6>
                                                            {{ $lots->StartDate }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>END DATE</h6>
                                                            <h6>
                                                            {{ $lots->EndDate }}</h6>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6 >LOT PRICE</h6>
                                                            <h6>
                                                            {{ $lots->Price }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>LOT STATUS</h6>
                                                            <h6>
                                                            {{ $lots->lot_status }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>SELLER</h6>
                                                            <h6>
                                                            {{ $lots->Seller }}</h6>
                                                        </div>


                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6>Lot Image</h6>
                                                            <img src="{{ asset('LotImages/'.$lots->uploadlotpicture) }}" alt="Lot Image" width="150" height="100">
                                                        </div>  

                                                       
                                                        

                                                    </div>
                                                    <div class="col-md-5">
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6 >PLANT</h6>
                                                            <h6>
                                                            {{ $lots->Plant }}</h6>
                                                        </div>
                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                        <h6>PARTICIPATION FEES</h6>
                                                            <h6>
                                                            {{ $lots->participate_fee }}</h6>
                                                        </div>

                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6>Lot Status</h6>
                                                                <h6>
                                                                {{ $lots->lot_status }}</h6>
                                                        </div>

                                                        <div class="d-flex justify-content-between mt-3" style="border-bottom:1px solid #c5b8b8;">
                                                            <h6>Made IN</h6>
                                                                <h6>
                                                                {{ $lots->make_in }}</h6>
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
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Description</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->description }}</p> --}}
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Category</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->categories->title }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Material Location</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->materialLocation }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Lot Quantity</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->Quantity }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Start Date</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->StartDate }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">End Date</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->EndDate }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Lot Price</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->Price }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Lot Status</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->lot_status }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Seller</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->Seller }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Plant</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->Plant }} --}}
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        {{-- <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Materials</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    @foreach ($lots->materials as $material)
                                                                        {{ $material->title }},
                                                                    @endforeach
                                                                </p>
                                                            </td>
                                                        </tr> --}}
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Participation Fees</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{-- {{ $lots->participate_fee }} --}}
                                                                </p>
                                                            </td>
                                                        </tr> -->

                                                        {{-- <tr>
                                                            <td>
                                                                <div class="d-flex px-2 py-1">
                                                                    <div class="d-flex flex-column justify-content-center">
                                                                        <h6 class="mb-0 text-sm">Paymant Plans</h6>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                <p class="text-xs text-secondary mb-0">
                                                                    {{ $lots->Payment_terms }}
                                                                </p>
                                                            </td>
                                                        </tr> --}}
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            {{-- @if ($payment_term)
                                                <h4 class="Payment_terms">Payment Terms</h4>
                                                <div class="col-12">
                                                    <table class="table" >
                                                        <thead>
                                                            <tr>
                                                                <th> Id</th>
                                                                <th> Payment Terms</th>
                                                                <th> Price Basis</th>
                                                                <th> Taxes and Duties</th>
                                                                <th> Commercial Terms</th>
                                                                <th> Test Ceriticates</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id='tablebody'>
                                                            @foreach ($payment_term as $payment)
                                                                <tr>
                                                                    <td>
                                                                        {{ $payment->id }}
                                                                    </td>
                                                                    <td>

                                                                        {{ $payment->Payment_Terms }}
                                                                    </td>

                                                                    <td>

                                                                        {{ $payment->Price_Bases }}
                                                                    </td>

                                                                    <td>
                                                                        {{ $payment->Texes_and_Duties }}

                                                                    </td>

                                                                    <td>

                                                                        {{ $payment->Commercial_Terms }}
                                                                    </td>

                                                                    <td>

                                                                        {{ $payment->Test_Certificate }}
                                                                    </td>

                                                                   
                                                                    
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif --}}

                                            @if ($payment_terms)
                                                <h4 class="Payment_terms">Payment Terms</h4>
                                                <div class="col-12">
                                                    <table class="table">
                                                        <thead>
                                                            <tr>
                                                                <th>Id</th>
                                                                <th>Payment Terms</th>
                                                                <th>Price Basis</th>
                                                                <th>Taxes and Duties</th>
                                                                <th>Commercial Terms</th>
                                                                <th>Test Certificates</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tablebody">
                                                            @foreach ($payment_terms as $payment)
                                                                <tr>
                                                                    <td>{{ $payment->id }}</td>
                                                                    <td>{{ $payment->Payment_Terms }}</td>
                                                                    <td>{{ $payment->Price_Bases }}</td>
                                                                    <td>{{ $payment->Texes_and_Duties }}</td>
                                                                    <td>{{ $payment->Commercial_Terms }}</td>
                                                                    <td>{{ $payment->Test_Certificate }}</td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif


                                            @if ($materialilist)
                                                <h4 class="Payment_terms">Materials</h4>
                                                <div class="col-12">
                                                    <table class="table" >
                                                        <thead>
                                                            <tr>
                                                                <th> Id</th>
                                                                <th> Product</th>
                                                                <th> Thickness</th>
                                                                <th> Width</th>
                                                                <th> Length</th>
                                                                <th> Weight</th>
                                                                <th> Grade</th>
                                                                <th> Remark</th>
                                                                <th> images</th>

                                                            </tr>
                                                        </thead>
                                                        <tbody id='tablebody'>
                                                            @foreach ($materialilist as $material)
                                                                <tr>
                                                                    <td>
                                                                        {{ $material->id }}
                                                                    </td>
                                                                    <td>

                                                                        {{ $material->Product }}
                                                                    </td>

                                                                    <td>

                                                                        {{ $material->Thickness }}
                                                                    </td>

                                                                    <td>
                                                                        {{ $material->Width }}

                                                                    </td>

                                                                    <td>

                                                                        {{ $material->Length }}
                                                                    </td>

                                                                    <td>

                                                                        {{ $material->Weight }}
                                                                    </td>

                                                                    <td>

                                                                        {{ $material->Grade }}
                                                                    </td>

                                                                    <td>

                                                                        {{ $material->Remark }}
                                                                    </td>

                                                                    <td>
                                                                        <img src="{{ url('files/' . $material->images) }}"
                                                                            style="width: 150px;height: 75px;;">
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                            @endif
                                            </p>
                                        </div>

                                        <div class="card-footer">
                                            <div class="button-container">
                                                <a href="{{ url('admin/lots') }}" class="btn btn-primary  Back_btn_customer">Back</a>
                                                <a href="{{ url("admin/lots/edit/{$lots->id}") }}"
                                                    class="btn btn-success">Edit</a>

                                                <a href="{{ url("admin/materialslots/{$lots->id}") }}"
                                                    class="btn btn-success">Edit Materials</a>

                                                {{-- <a href="{{ url("admin/lotsterms/{$lots->id}") }}"
                                                    class="btn btn-success">Edit Terms
                                                </a> --}}

                                                <a href="{{ url("admin/lots/remove/{$lots->id}") }}"
                                                    onclick="return confirm('Once deleted, you will not be able to recover this Lot!?')"
                                                    class="btn btn-danger remove">Remove</a>

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
                            url: "{{ url('/admin/lots/remove') }}" + "/" +
                                id,
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
