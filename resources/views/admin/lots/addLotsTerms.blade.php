@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'New Lots'])
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <!--Header-->
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">Add Lot Terms </h4>
                        </div>
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                            <form method="POST" action="{{ url('admin/addlotsterms/' . $lots->id) }}" class="form-horizontal">
                                @csrf
                                <div class="row">
                                    <label for="Payment_Terms" class="col-sm-2 col-form-label">Payment Terms</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Payment_Terms" name="Payment_Terms"
                                            required>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="Price_Bases" class="col-sm-2 col-form-label">Price Basis</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Price_Bases" name="Price_Bases"
                                            required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="Texes_and_Duties" class="col-sm-2 col-form-label">Taxes and Duties</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Texes_and_Duties"
                                            name="Texes_and_Duties" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="Commercial_Terms" class="col-sm-2 col-form-label">Commercial Terms</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Commercial_Terms"
                                            name="Commercial_Terms" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="Test_Certificate" class="col-sm-2 col-form-label">Test Certificate</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Test_Certificate"
                                            name="Test_Certificate" required>
                                    </div>
                                </div>
                        </div>

                        <!--End body-->

                        <!--Footer-->

                        <div class="card-footer ml-auto mr-auto">
                            <a href="{{ url('admin/lots') }}" class="btn btn-primary">Back</a>
                            <button type="submit" class="btn btn-primary">Save Lot Terms</button>
                        </div>
                        <!--End footer-->
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
