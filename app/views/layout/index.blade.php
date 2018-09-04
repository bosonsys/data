<!DOCTYPE html>
<html lang="en">
<head>
    <title>Data Analyser</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{url('css/nv.d3.css')}}">
    <link rel="stylesheet" href="{{url('css/style.css')}}">
	<script src="{{url('js/jquery-1.10.2.js')}}"></script>
	<script src="{{url('js/bootstrap.min.js')}}"></script>

</head>
<body>

<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand" href="{{url('/')}}">Data Analyser</a>
        </div>
        <div>
            <ul class="nav navbar-nav">
                <li class="{{ Request::is('/') ? 'active' : '' }}"><a href="{{url('/')}}">Home</a></li>
                <li class="{{ Request::is('*market/top') ? 'active' : '' }}"><a href="{{url('/market/top')}}">Top</a></li>
                <li class="{{ Request::is('*market/top1') ? 'active' : '' }}"><a href="{{url('/market/top1')}}">Always Top</a></li>
                <li class="{{ Request::is('*market/lasttop') ? 'active' : '' }}"><a href="{{url('/market/lasttop')}}">Last Top</a></li>
                <li class="{{ Request::is('*market/lose') ? 'active' : '' }}"><a href="{{url('/market/lose')}}">Top Losers</a></li>
                <li class="{{ Request::is('*market/lose1') ? 'active' : '' }}"><a href="{{url('/market/lose1')}}">Always Losers</a></li>
                <li class="{{ Request::is('*market/lastlose') ? 'active' : '' }}"><a href="{{url('/market/lastlose')}}">Last Losers</a></li>
                <li class="{{ Request::is('*news') ? 'active' : '' }}"><a href="{{url('/news')}}">News</a></li>
                <li class="{{ Request::is('*marketwatch') ? 'active' : '' }}"><a href="{{url('/marketwatch')}}">Market Watch</a></li>
                <li class="{{ Request::is('*strategy/first15') ? 'active' : '' }}"><a href="{{url('/strategy/first15')}}">First 15M</a></li>
                <li class="{{ Request::is('*strategy/uptrend') ? 'active' : '' }}"><a href="{{url('/strategy/uptrend')}}">Uptrend</a></li>
                <li class="{{ Request::is('*strategy/upDown') ? 'active' : '' }}"><a href="{{url('/strategy/upDown')}}">upDown</a></li>
                <li class="{{ Request::is('*strategy/open') ? 'active' : '' }}"><a href="{{url('/strategy/open')}}">Open High Low</a></li>
                <!-- <li><a href="{{url('/buysell')}}">Buy/Sell Calls</a></li> -->
                <!-- <li><a href="{{url('/call')}}">Trade Script</a></li> -->
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    @yield('content')
</div>

</body>
</html>
