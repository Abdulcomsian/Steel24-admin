@extends('admin.layouts.main', ['activePage' => 'posts', 'titlePage' => 'New Category'])

@section('content')
<div class="container"
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ url('admin/categories/store') }}" class="form-horizontal">
                        @csrf
                        <div class="card ">
                            <!--Header-->
                            <!-- <div class="card-header card-header-primary m-0">
                                <h4 class="card-title">Categories</h4>
                            </div> -->
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                            <div class="header_customer">
                                         <div >
                                <h4 >Categories</h4>
                            </div>
                        </div>
                                <div class="form-row justify-content-center">
                              
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="title" autocomplete="off" placeholder="Title"
                                            autofocus>
                                    </div>
                               
                                
                                    <!-- <label for="description" class="col-sm-2 col-form-label">Description</label> -->
                                    <div class="col-sm-7">
                                        <textarea type="text" class="form_customer" name="description" autocomplete="off" autofocus placeholder="Description"></textarea>
                                    </div>
                               

                                {{-- <div class="row">
                                    <label for="categoryId" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-7">
                                        <select class="form-select form-control" id="categoryId" name="categoryId">
                                            @foreach ($parentcategories as $categorie)
                                                <option value={{ $categorie->id }}>{{ $categorie->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <!--Footer-->
                                <div class="card-footer col-sm-7" style="margin-bottom:5%; margin-left:0">
                                <a href="{{ route('admin.categories') }}" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                                <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                            <!--End footer-->
                            </div>

                            <!--End body-->

                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                                </div>
@endsection
