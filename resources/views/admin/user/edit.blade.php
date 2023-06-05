@extends('admin.layouts.main', ['activePage' => 'user', 'titlePage' => 'Edit User'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <!--Header-->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Edit user</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="name"
                                            value="{{ old('name', $user->name) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Email</label>
                                    <div class="col-sm-7">
                                        <input type="email" class="form-control" name="email"
                                            value="{{ old('email', $user->email) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Contact No</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="contactNo" min="0"
                                            value="{{ old('contactNo', $user->contactNo) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Adhar No.</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="adharNo" min="0"
                                            value="{{ old('adharNo', $user->adharNo) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">GST No.</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="GSTNo" min="0"
                                            value="{{ old('GSTNo', $user->GSTNo) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">PAN No.</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="PanNo"
                                            value="{{ old('PanNo', $user->PanNo) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Address</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="address"
                                            value="{{ old('address', $user->address) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">City</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="city"
                                            value="{{ old('city', $user->city) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">State</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="state"
                                            value="{{ old('state', $user->state) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Pincode</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" name="pincode" min="0"
                                            value="{{ old('pincode', $user->pincode) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Compny Name</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="compnyName"
                                            value="{{ old('compnyName', $user->compnyName) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>

                            </div>
                            <!--End body-->
                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">Back</a>
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
