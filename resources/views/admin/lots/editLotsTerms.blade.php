@extends('admin.layouts.main', ['activePage' => 'lots', 'titlePage' => 'New Lots'])
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card ">
                        <!--Header-->
                        <!-- <div class="card-header card-header-primary">
                            <h4 class="card-title">Edit Lot Terms </h4>
                        </div> -->
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                        <div class="header_customer ">
                                         <div >
                                <h4 >Edit Lot Terms</h4>
                            </div>
                        </div>
                            <form method="POST" action="{{ url('admin/lotsterms/' . $lots->id) }}" class="form-horizontal">
                                @csrf
                                @method('PATCH')
                                <div class="row">
                                    <label for="Payment_Terms" class="col-sm-2 col-form-label">Payment Terms</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Payment_Terms" name="Payment_Terms"
                                            @isset($lotTerms)    
                                            value="{{ $lotTerms->Payment_Terms }}" 
                                            @endisset
                                            required>
                                    </div>
                                </div>

                                <div class="row">
                                    <label for="Price_Bases" class="col-sm-2 col-form-label">Price Basis</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Price_Bases" name="Price_Bases"
                                            @isset($lotTerms)
                                        value="{{ $lotTerms->Price_Bases }}" 
                                        @endisset
                                            required />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="Texes_and_Duties" class="col-sm-2 col-form-label">Taxes and
                                        Duties</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Texes_and_Duties"
                                            name="Texes_and_Duties"
                                            @isset($lotTerms)
                                            value="{{ $lotTerms->Texes_and_Duties }}" 
                                            @endisset
                                            required />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="Commercial_Terms" class="col-sm-2 col-form-label">Commercial
                                        Terms</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Commercial_Terms"
                                            name="Commercial_Terms"
                                            @isset($lotTerms)
                                                    value="{{ $lotTerms->Commercial_Terms }}" 
                                                    @endisset
                                            required />
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="Test_Certificate" class="col-sm-2 col-form-label">Test
                                        Certificate</label>
                                    <div class="col-sm-9">
                                        <input type="text" class="form-control" id="Test_Certificate"
                                            name="Test_Certificate"
                                            @isset($lotTerms)
                                            value="{{ $lotTerms->Test_Certificate }}" 
                                            @endisset
                                            required />
                                    </div>
                                </div>
                        </div>

                        <!--End body-->

                        <!--Footer-->

                        <div class="card-footer ml-auto mr-auto">
                            <a href="{{ url('admin/lots/' . $lots->id) }}" class="btn btn-primary">Back</a>
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
