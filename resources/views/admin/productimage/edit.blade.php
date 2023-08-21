@extends('admin.layouts.main', ['activePage' => 'auction', 'titlePage' => 'Editar Post'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ url('admin/productimage', $productimage->id) }}" class="form-horizontal" enctype="multipart/form-data">
                        @method('PATCH')
                        @csrf
                        <div class="card">
                            <!-- Header -->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Edit Product Images</h4>
                            </div>
                            <!-- End header -->
                            <!-- Body -->
                            <div class="card-body">
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="title" autocomplete="off"
                                            value="{{ $productimage->title }}" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-7">
                                        <textarea type="text" class="form-control" name="description" autocomplete="off" autofocus>{{ $productimage->description }}</textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="image" class="col-sm-2 col-form-label">Image</label>
                                    <div class="col-sm-7">
                                        <input type="file" class="form-control" name="image">
                                    </div>
                                </div>
                            </div>
                            <!-- End body -->
                            <!-- Footer -->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ url('admin/productimage') }}" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                            <!-- End footer -->
                        </div>
                        <!-- End card -->
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
