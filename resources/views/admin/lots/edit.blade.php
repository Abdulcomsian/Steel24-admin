@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'Edit Lot'])
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    {{-- <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" /> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/css/bootstrap-select.min.css"
        rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.1/js/bootstrap-select.min.js"></script>

    <style>
        .bootstrap-select.btn-group.show-tick .dropdown-menu li.selected a span.check-mark {
            position: relative;
            display: inline-block;
            right: 15px;
            /*margin-top: 5px;*/
            left: 2px;
        }

        .bootstrap-select.btn-group.show-tick .dropdown-menu li a span.text {
            margin-right: 34px;
        }

        .dropdown-menu {
            width: 100%;
        }

        .dropdown-toggle {
            padding: 5px 20px 10px 9px;
        }

        .bootstrap-select>.dropdown-toggle.bs-placeholder,
            {
            color: black !important;
            background: white !important;
        }

        ..bootstrap-select>.dropdown-toggle.bs-placeholder:hover 
        {
            color:
        }
    </style>
    <div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ url('admin/lots', $lots->id) }}" class="form-horizontal" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="card">
                            <!--Header-->
                            <!-- <div class="card-header card-header-primary m-0">
                                <h4 class="card-title">Edit Lot</h4>
                            </div> -->
                            <!--End header-->
                            <!--Body-->

                            <div class="card-body">
                            <div class="header_customer ">
                                         <div >
                                <h4 >Edit Lots</h4>
                            </div>
                        </div>
                                <div class="form-row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="title" name="title" placeholder="Title"
                                            value="{{ $lots ? $lots->title : '' }}" required>
                                    </div>
                                <input type="hidden" name="live" value="{{ $live }}">
                                @isset($expire)
                                    <input type="hidden" name="expire" value="{{ $expire }}">
                                @endisset
                                    <!-- <label for="description" class="col-sm-2 col-form-label">Description</label> -->
                                    <div class="col-sm-4">
                                        <textarea class="form_customer" id="description" name="description" placeholder="Description"> {{ $lots ? $lots->description : '' }} </textarea>
                                    </div>
                                    <!-- <label for="categoryId" class="col-sm-2 col-form-label">Category</label> -->
                                    <div class="col-sm-4">
                                        <select class="form-select custom-select" id="categoryId" name="categoryId" placeholder="Category">
                                            @foreach ($categorys as $category)
                                                <option value={{ $category->id }}
                                                    @if ($lots->categoryId == $category->id) selected @endif>
                                                    {{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- <label for="seller" class="col-sm-2 col-form-label">Seller</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="seller" name="Seller" placeholder="Seller"
                                            value="{{ $lots->Seller }}" required>
                                    </div>

                                    <!-- <label for="plant" class="col-sm-2 col-form-label">Plant</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="plant" name="Plant" placeholder="Plant"
                                            value="{{ $lots ? $lots->Plant : '' }}" required>
                                    </div>
                                    <!-- <label for="materialLocation" class="col-sm-2 col-form-label">Material Location</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="materialLocation"
                                            name="materialLocation" value="{{ $lots ? $lots->materialLocation : '' }}" placeholder="Material Location"
                                            required>
                                    </div>
                                    <!-- <label for="quantity" class="col-sm-2 col-form-label">Quantity</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="quantity" name="Quantity" placeholder="Quantity"
                                            value="{{ $lots ? $lots->Quantity : '' }}" required>
                                    </div>
                                    <!-- <label for="startDate" class="col-sm-2 col-form-label">Start Date</label> -->
                                    <div class="col-sm-4">
                                        <input type="datetime-local" class="form_customer" id="startDate" name="StartDate" placeholder="Start Date"
                                            value="{{ $lots ? $lots->StartDate : '' }}" required>
                                    </div>
                                    <!-- <label for="endDate" class="col-sm-2 col-form-label">End Date</label> -->
                                    <div class="col-sm-4">
                                        <input type="datetime-local" class="form_customer" id="endDate" name="EndDate" placeholder="End Date"
                                            value="{{ $lots ? $lots->EndDate : '' }}" required>
                                    </div>

                                {{--
                                    <!-- <label for="material" class="col-sm-2 col-form-label">Materials</label> -->
                                    <div class="col-sm-4">
                                        <select id="material" name="material[]" class="selectpicker" multiple placeholder="Materials"
                                            data-width="100%" title="Materials">
                                            <optgroup label="Meses de lactancia...">

                                                @foreach ($materials as $matr)
                                                    <option data-tokens="{{ $matr->title }}"
                                                        value="{{ $matr->id }}"
                                                        @if ($lots && $lots->materials->contains($matr->id)) selected @endif>
                                                        {{ $matr->title }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div> --}}
                                    <!-- <label for="startPrice" class="col-sm-2 col-form-label">Start Price</label> -->
                                    <div class="col-sm-4">
                                        <input type="number" class="form_customer" id="startPrice" name="Price"
                                            value="{{ $lots ? $lots->Price : '' }}" min="0" autocomplete="off" placeholder="Start Price"
                                            autofocus>
                                    </div>
                                    
                                    <!-- <label for="participate_fee" class="col-sm-2 col-form-label">Participation Fee</label> -->
                                    <div class="col-sm-4">
                                        <input type="number" class="form_customer" step="0.01" id="participate_fee" placeholder="Participation Fee"
                                            min="0" name="participate_fee"
                                            value="{{ $lots ? $lots->participate_fee : '' }}" autocomplete="off"
                                            autofocus>
                                    </div>

                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" step="0.01" id="make_in" placeholder="Made IN"
                                            min="0" name="make_in"
                                            value="{{ $lots ? $lots->make_in : '' }}" autocomplete="off"
                                            autofocus>
                                    </div>

                                    {{-- <div class="col-sm-4">
                                        <select class="custom-select" id="lotStatus" name="lot_status" style="margin-top: 10px;">
                                            <option value="">Select Lot Status</option>
                                            <option <?php #if($lotStatus=="active"){echo "selected";} ?> value="live">Live Lot</option>
                                            <option <?php #if($lotStatus=="expired"){echo "selected";} ?> value="expired">Expired Lot</option>
                                            <option <?php #if($lotStatus=="sold"){echo "selected";} ?> value="sold">Sold Lot</option>
                                        </select>
                                    </div> --}}

                                    <div class="col-sm-4">
                                        <select class="custom-select" id="lotStatus" name="lot_status" style="margin-top: 10px;">
                                            <option value="">Select Lot Status</option>
                                            <option {{ $lots->lot_status === "live" ? 'selected' : '' }} value="live">Live Lot</option>
                                            <option {{ $lots->lot_status === "Expired" ? 'selected' : '' }} value="Expired">Expired Lot</option>
                                            <option {{ $lots->lot_status === "Sold" ? 'selected' : '' }} value="Sold">Sold Lot</option>
                                        </select>
                                    </div>


                                    <div class="col-sm-4">
                                        <input type="file" class="form_customer" name="uploadlotpicture" placeholder="Upload Lot Image"
                                            autocomplete="off" autofocus  accept="image/*">
                                    </div>

                                    {{-- <div class="col-sm-4">
                                        <label for="new_uploadlotpicture">Upload New Lot Image</label>
                                        <input type="file" name="uploadlotpicture" class="form_customer" accept="image/*">
                                    </div> --}}


                                    {{-- <div class="col-sm-4">
                                        <label for="uploadlotpicture">Upload New Lot Image</label>
                                        <input type="file" class="form-control" id="uploadlotpicture" name="uploadlotpicture" accept="image/*">
                                    </div> --}}
                                    
                                    
                
                                     <!--Footer-->
                            <div class="card-footer ml-auto mr-auto col-sm-12">
                                <a href="javascript:history.back()" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                            <!--End footer-->
                                </div>
                            </div>


                            <!--End body-->

                           
                        </div>


                </div>
                <!--End footer-->
                </form>
            </div>
        </div>
    </div>
                                </div>
    </div>
@endsection
