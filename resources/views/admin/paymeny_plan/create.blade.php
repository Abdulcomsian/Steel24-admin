@extends('admin.layouts.main', ['activePage' => 'posts', 'titlePage' => 'New Category'])

@section('content')
<div class="container"
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ url('admin/payment_plan/store') }}" class="form-horizontal">
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
                                <h4 >Payment Plan</h4>
                            </div>
                        </div>
                                <div class="form-row justify-content-center">
                              
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Payment_Terms" autocomplete="off" placeholder="Enter Payment Terms"
                                            autofocus>
                                    </div>

                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Price_Bases" autocomplete="off" placeholder="Enter Price Bases"
                                            autofocus>
                                    </div>

                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Texes_and_Duties" autocomplete="off" placeholder="Enter Texes and Duties"
                                            autofocus>
                                    </div>

                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Commercial_Terms" autocomplete="off" placeholder="Enter Commercial Terms"
                                            autofocus>
                                    </div>

                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Test_Certificate" autocomplete="off" placeholder="Enter Test Certificate"
                                            autofocus>
                                    </div>
                               
                                <!--Footer-->
                                <div class="card-footer col-sm-7" style="margin-bottom:5%; margin-left:0">
                                <a href="{{ route('admin.payment_plan') }}" class="btn btn-primary mr-3 Back_btn_customer">Back</a>
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
