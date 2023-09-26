@extends('admin.layouts.main', ['activePage' => 'Payment Plan', 'titlePage' => 'Payment Plan'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ url('admin/payment_plan', $lotTerms->id) }}" class="form-horizontal">
                        {{-- @method('PATCH') --}}
                        @csrf
                        <input type="hidden" name="lotid" value="{{$lotTerms->id }}" />
                        <div class="card">
                    <div class="card-body">
                                <div class="header_customer">
                                         <div >
                                <h4 >Edit Payment Plan</h4>
                            </div>
                        </div>
                            <div>
                                <div class="row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Payment_Terms" placeholder="Enter Payment Terms" autocomplete="off"
                                            value="{{ $lotTerms->Payment_Terms }}" autofocus>
                                    </div>
                                </div>


                                <div class="row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Price_Bases" placeholder="Enter Price Bases" autocomplete="off"
                                            value="{{ $lotTerms->Price_Bases }}" autofocus>
                                    </div>
                                </div>


                                <div class="row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Texes_and_Duties" placeholder="Enter Texes and Duties" autocomplete="off"
                                            value="{{ $lotTerms->Texes_and_Duties }}" autofocus>
                                    </div>
                                </div>


                                <div class="row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Commercial_Terms" placeholder="Enter Commercial Terms" autocomplete="off"
                                            value="{{ $lotTerms->Commercial_Terms }}" autofocus>
                                    </div>
                                </div>


                                <div class="row">
                                    <!-- <label for="title" class="col-sm-2 col-form-label">Title</label> -->
                                    <div class="col-sm-7">
                                        <input type="text" class="form_customer" name="Test_Certificate" placeholder="Test_Certificate" autocomplete="off"
                                            value="{{ $lotTerms->Test_Certificate }}" autofocus>
                                    </div>
                                </div>
                        </div>
                    </div>
                            <!--End body-->
                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ url('admin/payment_plan') }}" class="btn btn-primary">Back</a>
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
