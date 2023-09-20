@extends('admin.layouts.main', ['activePage' => 'bids', 'titlePage' => 'Live Lots'])
@section('content')
<script>
var  lotid = <?php echo $lots->id?> 
</script>
    <script  type="module"   src="{{ asset('js/app.js') }}"></script>
    <script  type="module"  src="{{ asset('js/customeJs.js') }}"></script>
    
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header card-header-primary">
                                    <h4 class="card-title">Live Bids</h4>
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
                                                <div>
                                                    <p class="h5 ">Lot Id : <span
                                                            id='lotid'>{{ $lots->id }}</span>
                                                    </p>
                                                    <p class="h5 ">Title : {{ $lots->title }}</p>
                                                </div>
                                                <div>
                                                    <p class="btn btn-info btn-sm ">Lot Status :<span
                                                            id='lotStatus'>{{ $lots->lot_status }}</span>
                                                    </p>
                                                </div>

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
                                                            <select id="time" name="time" class="selectpicker w-25"
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

                                                            </select>
                                                            <button class="btn btn-success btn-sm ">Add Time</button>
                                                        </form>
                                                    </div>
                                                    {{-- <a href="{{ url("/admin/poselot/{$lots->id}") }}"
                                                        onclick="return confirm('Are you sure to Pause this Lot?')"
                                                        class="btn btn-primary btn-sm">Pause</a> --}}

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
                                                    <span id="remainingTime"></span>
                                                </span>
                                                <span> End at : <span id="lotendtime">
                                                        {{ $lots->EndDate }}</span></span>

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

                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead class="text-primary">
                                                <th>ID</th>
                                                <th>Last bid</th>
                                                <th>Bid amount </th>
                                                <th>Time</th>
                                                <th class="text-right">Actions</th>
                                            </thead>
                                            <tbody id="msg">
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

    // <script type="text/javascript">
    //     var lastbidid = $('#msg').children('tr:first').children('th').text() || 0;
    //     setInterval(function() {
    //         if ($('#lotStatus').text() == 'live' || $('#lotStatus').text() == 'Restart') {
    //             // debugger
    //             var lotidval = $('#lotid').text();
    //             $.ajax({
    //                 type: 'GET',
    //                 url: '/api/getlivebid',
    //                 data: {
    //                     lotid: lotidval,
    //                     lastBid: lastbidid,
    //                 },
    //                 success: function(result) {


    //                     result = JSON.parse(result);
    //                     // console.log('result', result.sucess)
    //                     if (result.sucess) {

    //                         if (lastbidid < result.newbid[0].id) {

    //                             var trhtml = "<tr>";
    //                             trhtml += "<th scope='row'>" + result.newbid[0].id + "</th>";
    //                             trhtml += "<td>" + result.newbid[0].customerName + "</td>";
    //                             trhtml += "<td>" + result.newbid[0].amount + "</td>";
    //                             trhtml += "<td>" + result.newbid[0].created_at + "</td>";
    //                             trhtml += "<td>";
    //                             trhtml +=
    //                                 "<a href='/admin/customers/{{ '+result.newbid[0].customerId+' }}'";
    //                             trhtml += "class='btn btn-primary btn-sm'>";
    //                             trhtml += "Customer Details";
    //                             trhtml += "</a>";
    //                             trhtml += "</td>";
    //                             trhtml += "</tr>";
    //                             $('table > tbody > tr:first').before(trhtml);
    //                             lastbidid = result.newbid[0].id;

    //                             // document.getElementById("lotendtime").innerHTML = (result.newbid[0]
    //                             //     .lotEnd)
    //                         }
    //                     } else {

    //                     }
    //                     document.getElementById("lotendtime").innerHTML = (result.EndDate)

    //                 }
    //             });
    //         }
    //     }, 1000);
    // </script>

    <script type="module">
       
  </script>
   <script>
    var today = new Date();
    var formattedToday = today.toISOString().slice(0, 16);
    document.getElementById('ReStartDate').setAttribute('min', formattedToday);
    document.getElementById('ReEndDate').setAttribute('min', formattedToday);
</script>
@endsection
