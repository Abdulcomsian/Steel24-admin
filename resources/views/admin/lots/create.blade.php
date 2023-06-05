@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'New Lots'])
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

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

        ..bootstrap-select>.dropdown-toggle.bs-placeholder:hover {
            color:
        }
    </style>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ url('admin/newlots') }}" class="form-horizontal">
                        @csrf
                        <div class="card ">
                            <!--Header-->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Lots</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="title" name="title" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-7">
                                        <textarea class="form-control" id="description" name="description"> {{ $lots ? $lots->description : '' }} </textarea>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="categoryId" class="col-sm-2 col-form-label">Category</label>
                                    <div class="col-sm-7">
                                        <select class="custom-select" id="categoryId" name="categoryId"
                                            style="margin-top: 10px;">
                                            @foreach ($categorys as $category)
                                                <option value={{ $category->id }}>{{ $category->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="seller" class="col-sm-2 col-form-label">Seller</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="seller" name="Seller" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="plant" class="col-sm-2 col-form-label">Plant</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="plant" name="Plant" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="materialLocation" class="col-sm-2 col-form-label">Material Location</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="materialLocation"
                                            name="materialLocation" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="quantity" name="Quantity" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="startDate" class="col-sm-2 col-form-label">Start Date</label>
                                    <div class="col-sm-7">
                                        <input type="datetime-local" class="form-control" id="startDate" name="StartDate"
                                            required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="endDate" class="col-sm-2 col-form-label">End Date</label>
                                    <div class="col-sm-7">
                                        <input type="datetime-local" class="form-control" id="endDate" name="EndDate"
                                            required>
                                    </div>
                                </div>

                                {{-- <div class="row">
                                    <label for="material" class="col-sm-2 col-form-label">Materials</label>
                                    <div class="col-sm-7">
                                        <select id="material" name="material[]" class="selectpicker" multiple
                                            data-width="100%" title="Materials">
                                            <optgroup label="Meses de lactancia...">

                                                @foreach ($materials as $matr)
                                                    <option data-tokens="{{ $matr->title }}" value="{{ $matr->id }}"
                                                        @if ($lots && $lots->material->contains($mtr->id)) selected @endif>
                                                        {{ $matr->title }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        </select> 
                                    </div>
                                </div> --}}

                                <div class="row">
                                    <label for="startPrice" class="col-sm-2 col-form-label">Start Price</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="startPrice" name="Price"
                                            min="0" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="participate_fee" class="col-sm-2 col-form-label">Participation Fee</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" step="0.01" id="participate_fee"
                                            min="0" name="participate_fee" autocomplete="off" autofocus>
                                    </div>
                                </div>

                            </div>

                            <!--End body-->

                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ url('admin/lots') }}" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-primary">Save And Add Material</button>
                            </div>
                            <!--End footer-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    $(document).ready(function() {
        // Select2 Multiple
        $('.select2-multiple').select2({
            placeholder: "Select",
            allowClear: true
        });

    });
</script>
