@extends('admin.layouts.main', ['activePage' => 'customers', 'titlePage' => 'Edit customers'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.customers.update', $customer->id) }}" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <!--Header-->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Edit customers</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $customer->name) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-7">
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $customer->email) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Contact no</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="contactNo" min="0"
                                            value="{{ old('contactNo', $customer->contactNo) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Adhar no.</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="adharNo" min="0"
                                            value="{{ old('adharNo', $customer->adharNo) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">GST no.</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="GSTNo" min="0"
                                            value="{{ old('GSTNo', $customer->GSTNo) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">PAN no.</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="PanNo" min="0"
                                            value="{{ old('PanNo', $customer->PanNo) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="address"
                                            value="{{ old('address', $customer->address) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="city"
                                            value="{{ old('city', $customer->city) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">State</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="state"
                                            value="{{ old('state', $customer->state) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Pincode</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="pincode" min="0"
                                            value="{{ old('pincode', $customer->pincode) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Compny name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="compnyName"
                                            value="{{ old('compnyName', $customer->compnyName) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>

                            </div>
                            <!--End body-->
                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ route('admin.customers.index') }}" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>

                        </div>
                        <!--End footer-->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
