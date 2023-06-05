@extends('admin.layouts.main', ['activePage' => 'customers', 'titlePage' => 'New Customers'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.customers.store') }}" class="form-horizontal">
                        @csrf
                        <div class="card ">
                            <!--Header-->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Customers</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                                <div class="form-row justify-content-center" >
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Name</label> -->
                                    <input type="text" class="form-control" name="name" autocomplete="off" placeholder="Name"
                                                autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Email</label> -->
                                    <input type="text" class="form-control" name="name" autocomplete="off" placeholder="Email"
                                                autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Password</label> -->
                                    <input type="password" class="form-control" name="password" autocomplete="off" placeholder="Password"
                                            autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Contact No</label> -->
                                    <input type="number" class="form-control" name="contactNo" min="0" placeholder="Contact No"
                                            autocomplete="off" autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Adhar no.</label> -->
                                    <input type="number" class="form-control" name="adharNo" min="0" placeholder="Adhar no."
                                            autocomplete="off" autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">GST no.</label> -->
                                    <input type="number" class="form-control" name="GSTNo" min="0" placeholder="GST no."
                                            autocomplete="off" autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">PAN no.</label> -->
                                    <input type="text" class="form-control" name="PanNo" autocomplete="off" placeholder="PAN no."
                                            autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Address</label> -->
                                    <input type="text" class="form-control" name="address" autocomplete="off" placeholder="Address"
                                            autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">City</label> -->
                                    <input type="text" class="form-control" name="city" autocomplete="off" placeholder="City"
                                            autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">State</label> -->
                                    <input type="text" class="form-control" name="state" autocomplete="off" placeholder="State"
                                            autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Pincode</label> -->
                                    <input type="number" class="form-control" name="pincode" min="0" placeholder="Pincode"
                                            autocomplete="off" autofocus>
                                    </div>
                                    <div class="col-md-4 mx-3 mb-3">
                                    <!-- <label for="validationTooltip01">Compny Name</label> -->
                                    <input type="text" class="form-control" name="compnyName" autocomplete="off" placeholder="Compny Name"
                                            autofocus>
                                    </div>
                            </div>
                                <!-- <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="name" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-7">
                                        <input type="email" class="form-control" name="email" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Password</label>
                                    <div class="col-sm-7">
                                        <input type="password" class="form-control" name="password" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Contact no</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="contactNo" min="0"
                                            autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Adhar no.</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="adharNo" min="0"
                                            autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">GST no.</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="GSTNo" min="0"
                                            autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">PAN no.</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="PanNo" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="address" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="city" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">State</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="state" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Pincode</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="pincode" min="0"
                                            autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Compny Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="compnyName" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div> -->
                            </div>

                            <!--End body-->

                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                            <!--End footer-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
