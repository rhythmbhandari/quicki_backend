<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>WELCOME</title>
</head>

<body>
    Hello, This is the welcome page!
    {{-- {{dd(getTotalPaid(App\Modules\Models\Rider::find(1)->user));}}
    {{dd(getTotalCommissions(App\Modules\Models\Rider::find(1)));}} --}}
    <div class="app">
        <header>
            <h1> BroadCast Notification with Pusher Test </h1>
        </header>
        <h3>SOS: </h3>
        <div id="sos_message">

        </div>

        <h3>SOS EVENTS: </h3>
        <div id="event_message">

        </div>

    </div>
    <script src="{{asset('/js/pusher.js')}}" defer></script>
</body>

</html>