@extends('admin.layouts.main', ['activePage' => 'payments', 'titlePage' => 'New Material'])

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ url('admin/newmaterials') }}" class="form-horizontal">
                        @csrf
                        <div class="card ">
                            <!--Header-->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Materials</h4>
                                <p class="card-category">Create Materials</p>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">

                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Title</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="title" name="title"
                                            placeholder="Title" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="description" class="col-sm-2 col-form-label">Description</label>
                                    <div class="col-sm-7">
                                        <textarea type="text" class="form-control" id="description" name="description" placeholder="Description" autofocus></textarea>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="" class="col-sm-2 col-form-label">Category</label>
                                    
                                </div>
                                <div class="row">
                                    <label for="height" class="col-sm-2 col-form-label">Height</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="height" name="height" min="0"
                                            placeholder="Height" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="width" class="col-sm-2 col-form-label">Width</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="width" name="width" min="0"
                                            placeholder="Width" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="thick" class="col-sm-2 col-form-label">Thickness</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="thick" name="thick" min="0"
                                            placeholder="Thickness" autocomplete="off" utofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="length" class="col-sm-2 col-form-label">Length</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="length" name="length" min="0"
                                            placeholder="Length" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="weight" class="col-sm-2 col-form-label">Weight</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="weight" name="weight" min="0"
                                            placeholder="Weight" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="inStock" class="col-sm-2 col-form-label">Stock</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="inStock" name="inStock" min="0"
                                            placeholder="Stock" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="price" class="col-sm-2 col-form-label">Price</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="price" name="price" min="0"
                                            placeholder="Price" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="coilLength" class="col-sm-2 col-form-label">Coil Length</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="coilLength" name="coilLength" min="0"
                                            placeholder="Coil Length" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="JSWgrade" class="col-sm-2 col-form-label">JSW Grade</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="JSWgrade" name="JSWgrade"
                                            placeholder="JSW Grade" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="grade" class="col-sm-2 col-form-label">Grade</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="grade" name="grade"
                                            placeholder="Grade" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="quantity" class="col-sm-2 col-form-label">Quantity</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="quantity" name="qty" min="0"
                                            placeholder="Quantity" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="majorDefect" class="col-sm-2 col-form-label">Major Defect</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="majorDefect" name="majorDefect"
                                            placeholder="Major Defect" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="coatinc" class="col-sm-2 col-form-label">Coating</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="coating" name="coating"
                                            placeholder="Coating" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="testedCoating" class="col-sm-2 col-form-label">Tested Coating</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="testedCoating"
                                            name="testedCoating" placeholder="Tested Coating" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="tinTemper" class="col-sm-2 col-form-label">Tin Temper</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="tinTemper" name="tinTemper"
                                            placeholder="Tin Temper" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="eqSpeci" class="col-sm-2 col-form-label">WQ Speci</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="eqSpeci" name="eqSpeci"
                                            placeholder="WQ Speci" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="heatNo" class="col-sm-2 col-form-label">Heat No</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="heatNo" name="heatNo"
                                            placeholder="Heat No" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="passivation" class="col-sm-2 col-form-label">Passivation</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="passivation" name="passivation"
                                            placeholder="Passivation" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="coldTreatment" class="col-sm-2 col-form-label">Cold Treatment</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="coldTreatment"
                                            name="coldTreatment" placeholder="Cold Treatment" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="plantNo" class="col-sm-2 col-form-label">Plant No</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="plantNo" name="plantNo"
                                            placeholder="Plant No" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="qualityRemark" class="col-sm-2 col-form-label">Quality Remark</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="qualityRemark"
                                            name="qualityRemark" placeholder="Quality Remark" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="storageLocation" class="col-sm-2 col-form-label">Storage Location</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="storageLocation"
                                            name="storageLocation" placeholder="Storage Location" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="edgeCondition" class="col-sm-2 col-form-label">Edge Condition</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="edgeCondition"
                                            name="edgeCondition" placeholder="Edge Condition" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="plantLotNo" class="col-sm-2 col-form-label">Plant Lot No</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="plantLotNo" name="plantLotNo" min="0"
                                            placeholder="plant lot no" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="inStock" class="col-sm-2 col-form-label">In Stock</label>
                                    <div class="col-sm-7">
                                        <input type="number" class="form-control" id="inStock" name="inStock" min="0"
                                            placeholder="In Stock" autocomplete="off" autofocus>
                                    </div>
                                </div>
                            </div>
                            <!--End body-->

                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ route('admin.materials') }}" class="btn btn-primary">Back</a>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </div>
                            <!--End footer-->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
