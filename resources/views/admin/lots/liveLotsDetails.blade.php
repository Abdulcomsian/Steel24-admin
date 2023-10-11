@extends('admin.layouts.main', ['activePage' => 'bids', 'titlePage' => 'Live Lots'])
@section('content')
<script>
    var lotid = {{ $lots->id }};
</script>
{{-- <script type="module" src="{{ asset('js/app.js') }}"></script>
<script type="module" src="{{ asset('js/customeJs.js') }}"></script> --}}

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                @if (session('success'))
                                    <div class="alert alert-success" role="success">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="header_customer px-4">
                                        <div>
                                            <h4>Live Bids</h4>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <div>
                                                <p class="h5">Lot Id : <span
                                                        id='lotid'>{{ $lots->id }}</span></p>
                                                <p class="h5">Title : {{ $lots->title }}</p>
                                            </div>
                                            <div>
                                                <p class="btn btn-info btn-sm">Lot Status :<span
                                                        id='lotStatus'>{{ $lots->lot_status }}</span>
                                                </p>
                                            </div>
                                            <div>
                                                <a href="/admin/lots/{{ $lots->id }}"
                                                    class="btn btn-success btn-sm"> Lot Details
                                                </a>
                                                <a href="{{ url("/admin/startlot/{$lots->id}") }}"
                                                    class="btn btn-success btn-sm" id='btnStartLot'>Start</a>
                                                <a href="{{ url("/admin/endlot/{$lots->id}") }}"
                                                    class="btn btn-danger btn-sm" id='btnEndtLot'>Stop</a>
                                                <a href="/admin/expireLot/{{ $lots->id }}"
                                                    class="btn btn-danger btn-sm"> Expire Lot
                                                </a>
                                                <div>

                                                    <a href="/admin/lots/{{ $lots->id }}"
                                                        class="btn btn-success btn-sm"> Lot Details
                                                    </a>

                                                    <a href="{{ url("/admin/startlot/{$lots->id}") }}" {{-- onclick="return confirm('Are you sure to make this Lote
                                                        live?')" --}}
                                                        class="btn btn-success btn-sm" id='btnStartLot'>Start</a>
                                                    <a href="{{ url("/admin/endlot/{$lots->id}") }}" {{-- onclick="return
                                                            confirm('Are you sure to stop this Lot?')" --}}
                                                        class="btn btn-danger btn-sm"id='btnEndtLot'>Stop</a>
                                                    <a href="/admin/expireLot/{{ $lots->id }}"
                                                        class="btn btn-danger btn-sm"> Expire Lot
                                                    </a>
                                                    <div>
                                                        <form action="{{ url('admin/addtimeinlive/' . $lots->id) }}">
                                                            <!-- <select id="time" name="time" class="selectpicker w-25"
                                                                title="time">
                                                                <option value="1">1</option>
                                                                <option value="2">2</option>
                                                                <option value="3">3</option>
                                                                <option value="4">4</option>
                                                                <option value="5">5</option>
                                                                <option value="6">6</option>
                                                                <option value="7">7</option>
                                                                <option value="8">8</option>
                                                                <option value="9">9</option>
                                                                <option value="10">10</option>

                                                            </select> -->
                                                            <div class="d-flex justify-content-center align-items-center">
                                                            <input type="number" class=" col-md-6 mr-2" name="time" placeholder="Time" autocomplete="off" autofocus>
                                                            <button class="btn btn-success btn-sm ">Add Time</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                    {{-- <a href="{{ url("/admin/poselot/{$lots->id}") }}"
                                                        onclick="return confirm('Are you sure to Pause this Lot?')"
                                                        class="btn btn-primary btn-sm">Pause</a> --}}

>>>>>>> b23185000f42c93e342b388650bc8de703d17588
                                                </div>
                                            </div>
                                        </div>
                                        <p class="h6">Description : {{ $lots->description }}</p>
                                    </div>
                                    <hr>
                                    <br>
                                    <div class="col-12">
                                        <h4 class="d-flex justify-content-between">
                                            <span> Started at : <span
                                                    id="lotstarttime">{{  \Carbon\Carbon::parse($lots->StartDate)->format('d-m-Y g:i:s A') }}</span>
                                            </span>
                                            {{-- <span> Started at : <span id="lotstarttime">{{ \Carbon\Carbon::parse($lots->StartDate)->format('d-m-Y H:i:s') }}</span></span> --}}
                                            <span class="font-weight-bold ">
                                                <span id="remainingTime"></span>
                                            </span>
                                            <span> End at : <span id="lotendtime">{{  \Carbon\Carbon::parse($lots->EndDate)->format('d-m-Y g:i:s A')  }}</span></span>
                                            {{-- <span> End at : <span id="lotendtime">{{ \Carbon\Carbon::parse($lots->EndDate)->format('d-m-Y H:i:s') }}</span></span> --}}
                                        </h4>
                                    </div>
                                </div>
                                @if ($lots->lot_status == 'Sold' || $lots->lot_status == 'STA' || $lots->lot_status == 'Expired')
                                    <div class="align-items-center d-flex justify-content-around w-100">
                                        @if ($lotbids && $lots->lot_status == 'STA')
                                            @if (count($paymentRequest))
                                                <h4 class="font-weight-bold"> Payment request sent to
                                                    {{ $lotbids[0]->customerName }} for
                                                    {{ $lotbids[0]->amount }} </h4>
                                            @else
                                                <form action="/admin/createPayment" method="POST">
                                                    @csrf
                                                    <h5>Get payment of {{ $lotbids[0]->amount }} from
                                                        {{ $lotbids[0]->customerName }}</h5>
                                                    <input type="hidden" id="lotid" name="lotid"
                                                        value="{{ $lots->id }}">
                                                    <input type="checkbox" id="customerVisible" name="customerVisible"
                                                        checked>
                                                    <label for="customerVisible"> Show Customer Name.</label><br>
                                                    <button type="submit" class="btn btn-primary btn-sm">Get
                                                        Payment.</button>
                                                </form>
                                            @endif
                                        @endif
                                        @if ($lots->lot_status == 'Sold' || $lots->lot_status == 'STA' || $lots->lot_status == 'Expired')
                                            <div class="row w-50">
                                                <form action="/admin/reStartExpirelot" class="col-12" method="POST">
                                                    @csrf

                                                    <h3>Restart lot</h3>
                                                    <div class="row ">
                                                        <label for="ReStartDate" class="col-sm-2 col-form-label">Start
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

                                {{-- <div class="table-responsive">
                                    <table class="table">
                                        <thead class="text-primary">
                                            <th>ID</th>
                                            <th>Last bid</th>
                                            <th>Bid amount </th>
                                            <th>Time</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody id="tableBody">
                                            @if (!$lotbids)
                                                <tr>
                                                    <th scope="row">0</th>
                                                    <td>Initial Price</td>
                                                    <td>{{ $lots->Price }}</td>
                                                    <td>{{ $lots->StartDate }}</td>
                                                </tr>
                                            @endif
                                            @foreach ($lotbids as $bid)
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
                                </div> --}}

                                <div class="table-responsive">
                                    <table class="table" id="bidTable">
                                        <thead class="text-primary">
                                            <th>ID</th>
                                            <th>Last bid</th>
                                            <th>Bid amount</th>
                                            <th>Time</th>
                                            <th>Actions</th>
                                        </thead>
                                        <tbody id="tableBody">
                                            @if (empty($lotbids))
                                                <tr>
                                                    <th scope="row">0</th>
                                                    <td>Initial Price</td>
                                                    <td>{{ $lots->Price }}</td>
                                                    <td>{{ $lots->StartDate }}</td>                                                                                                    --}}
                                                </tr>
                                            @else
                                                @php
                            
                                                    $sortedBids = collect($lotbids)->sortByDesc('id');
                                                @endphp
                                                @foreach ($sortedBids as $bid)
                                                    <tr>
                                                        <th scope="row">{{ $bid->id }}</th>
                                                        <td>{{ $bid->customerName }}</td>
                                                        <td>{{ $bid->amount }}</td>
                                                        {{-- <td>{{ $bid->bidTime }}</td> --}}
                                                        <td>{{ \Carbon\Carbon::parse($bid->bidTime)->format('d-m-Y g:i:s A') }}</td>
                                                        <td>
                                                            <a href="/admin/customers/{{ $bid->customerId }}" class="btn btn-primary btn-sm">
                                                                Customer Details
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            
                            </div>
                            <div class="card-footer ml-auto mr-auto">
                                <a href="{{ url('admin/live_lots') }}" class="btn btn-primary">Back</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function placeBid(data) 
{
        // alert("here")
    var tableBody = document.getElementById('tableBody');
    // var newRow = document.createElement('tr');
    let date = new Date(data.bid.created_at)
    let day = date.getDate() < 10 ? "0"+ date.getDate() : date.getDate();
    let month = (date.getMonth() + 1) < 10 ? "0"+ (date.getMonth() + 1) : (date.getMonth() + 1); 
    //find the am/pm
    let timeIs = date.getHours() < 12 ? "AM" : "PM";
    let dateHour = date.getHours() % 12; 
    let minute = date.getMinutes() < 10 ? "0".date.getMinutes() : date.getMinutes();
    //find am/pm code ends here
    let bidDate =  day + "-" + month + "-" +  date.getFullYear()+ " " + dateHour + ":" + minute + ":" + date.getSeconds()+" "+ timeIs;
    newRow= `<tr>
        <th scope="row">${data.bid.id}</th>
        <td>${data.customer.name}</td>
        <td>${data.bid.amount}</td>
        <td>${bidDate}</td>
        <td>
            <a href="/admin/customers/${data.customer.id}" class="btn btn-primary btn-sm">
                Customer Details
            </a>
        </td></tr>
    `;
    tableBody.insertAdjacentHTML("afterbegin" , newRow);
    // tableBody.appendChild(newRow);
}
</script>

<script>
    var lotStatus = document.getElementById("lotStatus").innerHTML;
    var myfunc = setInterval(function() 
    {
            var startTime = new Date(document.getElementById("lotstarttime").innerHTML).getTime();
            var endTime = new Date(document.getElementById("lotendtime").innerHTML).getTime();
            var now = new Date().getTime();
            var timeleft = null;

            if (lotStatus == 'Upcoming' && startTime <= now) 
            {

                document.getElementById('btnStartLot').click();
                clearInterval(myfunc);
            } else if (lotStatus != 'Expired' && lotStatus != 'pause') 
            {
                if (lotStatus != 'live' && lotStatus != 'Restart') 
                {
                    timeleft = startTime - now;
                } else 
                {
                    timeleft = endTime - now;
                }

                var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
                var hours = (days*24)+Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);

                // hours+=days*24


                document.getElementById("remainingTime").innerHTML = ("Remaining : " + hours + " : " + minutes +
                    " : " + seconds);


                if (minutes < 1 && hours < 1 && timeleft > 0) 
                {
                    document.getElementById("remainingTime").style.color = "red";
                }


                if (minutes < 1 && hours < 1 && timeleft < 0 && (lotStatus == 'live' || lotStatus == 'Restart')) 
                {
                    clearInterval(myfunc);
                    document.getElementById('btnEndtLot').click();
                }

                if ((minutes < 1 && hours < 1 && timeleft < 0) && lotStatus == 'Upcoming') 
                {
                    clearInterval(myfunc);
                    document.getElementById('btnStartLot').click();
                }


                if (timeleft < 0) 
                {
                    clearInterval(myfunc);
                    document.getElementById("remainingTime").innerHTML = "TIME UP!!";
                }
            }
        },
        1000);
</script>

<script>
    var today = new Date();
    var formattedToday = today.toISOString().slice(0, 16);
    document.getElementById('ReStartDate').setAttribute('min', formattedToday);
    document.getElementById('ReEndDate').setAttribute('min', formattedToday);
</script>

<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Enable pusher logging - don't include this in production
        // Pusher.logToConsole = true;
        var pusher = new Pusher('bacf91fa7936ec16edb7', {
            cluster: 'ap2'
        });
        var channel = pusher.subscribe('bid-placed');
        channel.bind('bid.placed', function(data) {
            // alert("here");
            // alert(JSON.stringify(data));
            placeBid(data)
            // console.log(data)
        });
  </script>


@endsection
