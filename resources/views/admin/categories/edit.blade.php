@extends('admin.layouts.main', ['activePage' => 'auction', 'titlePage' => 'Editar Post'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="post" action="{{ url('admin/categories', $categories->id) }}" class="form-horizontal">
                        @method('PATCH')
                        @csrf
                        <div class="card">
                            <!--Header-->
                            <!-- <div class="card-header card-header-primary">
                                <h4 class="card-title">Edit categories</h4>
                            </div> -->
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                                <div class="header_customer ">
                                         <div >
                                <h4 >Edit categories</h4>
                            </div>
                        </div>
                                <div>
                                <div class="row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="title" placeholder="Title" autocomplete="off"
                                            value="{{ $categories->title }}" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <!-- <label for="description" class="col-sm-2 col-form-label">Description</label> -->
                                    <div class="col-sm-7">
                                        <textarea type="text" class="form_customer" placeholder="Description" name="description" autocomplete="off" autofocus>{{ $categories->description }}</textarea>
                                    </div>
                                </div>
</div>
                                {{-- <div class="row">
                                    <label for="categoryId" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-7">
                                        <select class="form-select form-control" id="categoryId" name="categoryId">
                                            @foreach ($parentcategories as $categorie)
                                                @if ($categorie->id != $categories->id)
                                                    <option value={{ $categorie->id }}
                                                        @if ($categories->parentcategory == $categorie->id) selected @endif>
                                                        {{ $categorie->title }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                            </div>
                            <!--End body-->
                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ url('admin/categories') }}" class="btn btn-primary">Back</a>
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
