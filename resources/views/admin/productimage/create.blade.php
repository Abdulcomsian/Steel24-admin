@extends('admin.layouts.main', ['activePage' => 'posts', 'titlePage' => 'New Product Images'])

@section('content')
<div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ url('admin/productimages/store') }}" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <!-- Header -->
                            <!-- <div class="card-header card-header-primary m-0">
                                <h4 class="card-title">Product Images</h4>
                            </div> -->
                            <!-- End header -->
                            <!-- Body -->
                            <div class="card-body">
                            <div class="header_customer ">
                                         <div >
                                <h4 >Product Images</h4>
                            </div>
                        </div>
                                <div class="form-row justify-content-center">
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="title" autocomplete="off" placeholder="Title" autofocus>
                                    </div>
                                    <div class="col-sm-7">
                                        <textarea type="text" class="form_customer" name="description" autocomplete="off" placeholder="Description" autofocus></textarea>
                                    </div>
                                    <div class="col-sm-7">
                                        <label for="image" class="form-label">Image</label>
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                </div>
                                <!-- Footer -->
                                <div class="card-footer col-sm-7" style="margin-bottom: 5%; margin-left: 0">
                                    <a href="{{ route('admin.productimages') }}" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                            <!-- End footer -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
