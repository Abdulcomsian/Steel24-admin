@extends('admin.layouts.main', ['activePage' => 'auction', 'titlePage' => 'Editar Post'])
@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form method="POST" action="{{ route('admin.auctions.update', $auction->id) }}" class="form-horizontal">
                        @csrf
                        @method('PUT')
                        <div class="card">
                            <!--Header-->
                            <div class="card-header card-header-primary">
                                <h4 class="card-title">Editar post</h4>
                            </div>
                            <!--End header-->
                            <!--Body-->
                            <div class="card-body">
                                <div class="row">
                                    <label for="" class="col-sm-2 col-form-label">Lot Name</label>

                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Starting Price</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="starting_price"
                                            value="{{ old('starting_price', $auction->starting_price) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Last Bid</label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" name="last_bid"
                                            value="{{ old('last_bid', $auction->last_bid) }}" autocomplete="off" autofocus>
                                    </div>
                                </div>
                                <div class="row">
                                    <label for="title" class="col-sm-2 col-form-label">Auction Date</label>
                                    <div class="col-sm-7">
                                        <input type="date" class="form-control" name="auction_date"
                                            value="{{ old('auction_date', $auction->auction_date) }}" autocomplete="off"
                                            autofocus>
                                    </div>
                                </div>
                            </div>
                            <!--End body-->
                            <!--Footer-->
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ route('admin.auctions.index') }}" class="btn btn-primary">Back</a>
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
