@extends('admin.layouts.main', ['activePage' => 'customers', 'titlePage' => 'Edit customers'])
@section('content')
<div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <!--Header-->
                            <div class="card-header card-header-primary m-0">
                                <h4 class="card-title">Edit customers</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                                <div class="form-row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Name</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" name="name" placeholder="Name"
                                            value="{{ old('name', $customer->name) }}" autocomplete="off" autofocus>
                                    </div>
                                
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Email</label> -->
                                    <div class="col-sm-4">
                                        <input type="email" class="form_customer" name="email" placeholder="Email"
                                            value="{{ old('email', $customer->email) }}" autocomplete="off" autofocus>
                                    </div>
                               
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Contact no</label> -->
                                    <div class="col-sm-4">
                                        <input type="number" class="form_customer" name="contactNo" min="0" placeholder="Contact no"
                                            value="{{ old('contactNo', $customer->contactNo) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Adhar no.</label> -->
                                    <div class="col-sm-4">
                                        <input type="number" class="form_customer" name="adharNo" min="0" placeholder="Adhar no."
                                            value="{{ old('adharNo', $customer->adharNo) }}" autocomplete="off" autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">GST no.</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" name="GSTNo" min="0" placeholder="GST no."
                                            value="{{ old('GSTNo', $customer->GSTNo) }}" autocomplete="off" autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">PAN no.</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" name="PanNo" min="0" placeholder="PAN no."
                                            value="{{ old('PanNo', $customer->PanNo) }}" autocomplete="off" autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Address</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" name="address" placeholder="Address"
                                            value="{{ old('address', $customer->address) }}" autocomplete="off" autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">City</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" name="city" placeholder="City"
                                            value="{{ old('city', $customer->city) }}" autocomplete="off" autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">State</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" name="state" placeholder="State"
                                            value="{{ old('state', $customer->state) }}" autocomplete="off" autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Pincode</label> -->
                                    <div class="col-sm-4">
                                        <input type="number" class="form_customer" name="pincode" min="0" placeholder="Pincode"
                                            value="{{ old('pincode', $customer->pincode) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Compny name</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" name="compnyName" placeholder="Compny name"
                                            value="{{ old('compnyName', $customer->compnyName) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                    <!--Footer-->
                            <div class="card-footer ml-auto mr-auto col-sm-12">
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                                </div>

                            </div>
                            <!--End body-->
                            

                        </div>
                        <!--End footer-->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
