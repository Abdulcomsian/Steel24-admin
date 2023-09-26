@extends('admin.layouts.main', ['activePage' => 'Payment Plan', 'titlePage' => 'Payment Plan'])

@section('content')
<div class="container">
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <!--Header-->
                        <!-- <div class="card-header card-header-primary m-0">
                            <h4 class="card-title">Add Lot Terms</h4>
                        </div> -->
                        <!--End header-->
                        <!--Body-->
                        <div class="card-body">
                        <div class="header_customer ">
                                         <div >
                                <h4 >Add Lot terms</h4>
                            </div>
                        </div>
                            <form method="POST" action="{{ url('admin/addpayment_plan') }}" class="form-horizontal">
                                @csrf
                                <div class="form-row">
                                    <!-- <label for="Payment_Terms" class="col-sm-2 col-form-label">Payment Terms</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="Payment_Terms" placeholder="Payment Terms" name="Payment_Terms" required>
                                    </div>
                                    <!-- <label for="Price_Bases" class="col-sm-2 col-form-label">Price Basis</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="Price_Bases" name="Price_Bases" placeholder="Price Basis" required>
                                    </div>
                                    <!-- <label for="Texes_and_Duties" class="col-sm-2 col-form-label">Taxes and Duties</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="Texes_and_Duties" name="Texes_and_Duties" placeholder="Taxes and Duties" required>
                                    </div>
                                    <!-- <label for="Commercial_Terms" class="col-sm-2 col-form-label">Commercial Terms</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="Commercial_Terms" name="Commercial_Terms" placeholder="Commercial Terms" required>
                                    </div>
                                    <!-- <label for="Test_Certificate" class="col-sm-2 col-form-label">Test Certificate</label> -->
                                    <div class="col-sm-4">
                                        <input type="text" class="form_customer" id="Test_Certificate" name="Test_Certificate" placeholder="Test Certificate" required>
                                    </div>
                                    <!--Footer-->
                            <div class="card-footer ml-auto mr-auto col-sm-12">
                                <a href="{{ url('admin/lots') }}" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
                                <button type="submit" class="btn btn-primary">Save Lot Terms</button>
                            </div>
                            <!--End footer-->
                            </div>
                        </div>
                            <!--End body-->

                            
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
