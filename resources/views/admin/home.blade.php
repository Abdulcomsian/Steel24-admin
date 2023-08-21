
@extends('admin.layouts.main',['activePage' => 'dashboard', 'titlePage' => __('Dashboard')])
<script src="{{ asset('js/app.js') }}"></script>

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <h1>WelCome To Steel24</h1>
             <div id="msg"></div>
        </div>
    </div>
</div>
@endsection
<script>
    var user_id = 2;
    console.log('user',user_id);
    window.Echo.channel('live_lots').listen('MessageEvent', function(e) 
    {
                console.log("listan",e);
                $('#msg').html(e.message);
    });

</script>
