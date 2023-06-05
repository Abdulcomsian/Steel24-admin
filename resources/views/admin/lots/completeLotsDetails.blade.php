@extends('admin.layouts.main', ['activePage' => 'bids', 'titlePage' => 'Live Lots'])
@section('content')
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://www.gstatic.com/firebasejs/3.2.0/firebase.js"></script>

    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Live bids</h4>
                                </div>
                                <div class="card-body">
                                    @if (session('success'))
                                        <div class="alert alert-success" role="success">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <div class="row">

                                        <div class="col-12 ">
                                            <div class="d-flex justify-content-between">
                                                <p class="h5 ">Title : {{ $lots->title }}</p>
                                                <p class="btn btn-info btn-sm ">Lot status :<span
                                                        id='lotStatus'>{{ $lots->lot_status }}</span>
                                                </p>
                                                <div>
                                                    <a href="/admin/lots/edit/{{ $lots->id }}"
                                                        class="btn btn-success btn-sm"> Lot details
                                                    </a>
                                                    <a href="/admin/expireLot/{{ $lots->id }}"
                                                        class="btn btn-danger btn-sm"> Expire Lot
                                                    </a>

                                                </div>

                                            </div>
                                            <p class="h6 ">Description : {{ $lots->description }}</p>
                                        </div>
                                        <hr>
                                        <br>
                                        <div class="col-12 ">
                                            <h4 class="d-flex justify-content-between">
                                                <span> Started at : <span
                                                        id="lotstarttime">{{ $lots->StartDate }}</span></span>
                                                <span class="font-weight-bold ">
                                                    {{-- <span id="remainingTime"></span> --}}
                                                </span>
                                                <span> End at : <span id="lotendtime">
                                                        {{ $lots->EndDate }}</span></span>
                                            </h4>
                                        </div>

                                        @if ($lots->lot_status == 'sold' || $lots->lot_status == 'STA' || $lots->lot_status == 'expired')
                                            <div class="align-items-center d-flex justify-content-around w-100">
                                                @if (($lotbids && $lots->lot_status == 'STA') || $lots->lot_status == 'sold')
                                                    @if (count($lotbids) && count($paymentRequest))
                                                        <h4 class="font-weight-bold"> Payment request sent to
                                                            {{ $lotbids[0]->customerName }} for
                                                            {{ $lotbids[0]->amount }} </h4>
                                                    @else
                                                        @if (count($lotbids))
                                                            <form action="/admin/createPayment" method="POST">
                                                                @csrf
                                                                <h5>Get payment of {{ $lotbids[0]->amount }} from
                                                                    {{ $lotbids[0]->customerName }}</h5>

                                                                <input type="hidden" id="lotid" name="lotid"
                                                                    value="{{ $lots->id }}">
                                                                <input type="checkbox" id="customerVisible"
                                                                    name="customerVisible" checked>
                                                                <label for="customerVisible"> Show Customer
                                                                    Name.</label><br>
                                                                <button type="submit" class="btn btn-primary btn-sm">Get
                                                                    Payment.</button>
                                                            </form>
                                                        @endif
                                                    @endif
                                                @endif
                                                @if ($lots->lot_status == 'sold' || $lots->lot_status == 'STA' || $lots->lot_status == 'expired')
                                                    <div class="row w-50">
                                                        <form action="/admin/reStartExpirelot" class="col-12"
                                                            method="POST">
                                                            @csrf
                                                            <h3>Restart lot</h3>
                                                            <div class="row ">
                                                                <label for="ReStartDate"
                                                                    class="col-sm-2 col-form-label">Start
                                                                    time</label>
                                                                <div class="col-sm-7">
                                                                    <input type="datetime-local" class="form-control"
                                                                        id="ReStartDate" name="ReStartDate" required>
                                                                </div>
                                                            </div>
                                                            <div class="row ">
                                                                <label for="ReEndDate" class="col-sm-2 col-form-label">End
                                                                    time</label>
                                                                <div class="col-sm-7">
                                                                    <input type="datetime-local" class="form-control"
                                                                        id="ReEndDate" name="ReEndDate" required>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" id="lotid" name="lotid"
                                                                value="{{ $lots->id }}">
                                                            <br />
                                                            <button type="submit" class="btn btn-primary btn-sm">Restart
                                                                lot</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="text-primary">
                                            <th>ID</th>
                                            <th>Last bid</th>
                                            <th>Bid amount </th>
                                            <th>Time</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody id="msg">
                                            @foreach ($lotbids as $key => $bid)
                                                <tr>
                                                    <th scope="row">{{ $bid->id }}</th>
                                                    <td>{{ $bid->customerName }}</td>
                                                    <td>{{ $bid->amount }}</td>
                                                    <td>{{ $bid->bidTime }}</td>
                                                    <td>
                                                        <a href="/admin/customers/{{ $bid->customerId }}"
                                                            class="btn btn-primary btn-sm">
                                                            Customer Details
                                                        </a>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="card-footer ml-auto mr-auto">
                                    <a href="{{ url('admin/complete_lots') }}" class="btn btn-primary">Back</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
