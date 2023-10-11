<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Hi Man How are you</h1>
    {{-- <script src="{{asset('js/app.js')}}"></script>
    <script>
         Echo.channel('bid-placed')
            .listen('BidPlaced' , function(e){
                alert('hrere');
                console.log(e);
            });
    </script> --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>

        // Enable pusher logging - don't include this in production
        Pusher.logToConsole = true;

        var pusher = new Pusher('ad6209c0096cf25b141f', {
            cluster: 'ap2'
        });

        var channel = pusher.subscribe('bid-placed');
        channel.bind('bid.placed', function(data) {
            alert("here");
            alert(JSON.stringify(data));
        });
  </script>
</body>
</html>