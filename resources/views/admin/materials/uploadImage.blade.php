@extends('admin.layouts.main', ['activePage' => 'materialFiles', 'titlePage' => 'New Material Files'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    {{-- <form method="POST" action="{{ url('admin/newmaterials') }}" class="form-horizontal"> --}}
                    @csrf
                    <div class="card ">
                        <!--Header-->
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Material Images</h4>
                        </div>
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">

                            <form method="post" action="{{ route('admin.materialFiles.store') }}"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label for="material" class="col-sm-2 col-form-label">Select Material</label>
                                    <div class="col-sm-7">
                                        <select class="custom-select" id="materialId" data-live-search="true"
                                            name="materialId">
                                            @foreach ($materials as $matr)
                                                <option value="{{ $matr->id }}"
                                                    @if ($lots && $lots->material->contains($mtr->id)) selected @endif>{{ $matr->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="input-group hdtuto control-group lst increment">
                                    <input type="file" name="filenames[]" class="myfrm form-control">
                                    <div class="input-group-btn">
                                        <button class="btn btn-info btn-sm" type="button"><i
                                                class="fldemo glyphicon glyphicon-plus"></i>Add</button>
                                    </div>
                                </div>
                                <div class="clone hide">
                                    <div class="hdtuto control-group lst input-group" style="margin-top:10px">
                                        <input type="file" name="filenames[]" class="myfrm form-control">
                                        <div class="input-group-btn">
                                            <button class="btn btn-danger btn-sm" type="button"><i
                                                    class="fldemo glyphicon glyphicon-remove"></i> Remove</button>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm"
                                    style="margin-top:10px">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endsection
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".btn-success").click(function() {
                    var lsthmtl = $(".clone").html();
                    $(".increment").after(lsthmtl);
                });
                $("body").on("click", ".btn-danger", function() {
                    $(this).parents(".hdtuto").remove();
                });
            });
        </script>
