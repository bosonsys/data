@extends('layout.index')
@section('title')
	{{$nse}}
@stop
@section('content')
<script type="text/javascript" src="{{ asset('js/chart/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chart/Chart.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chart/Chart.Financial.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/chart/utils.js') }}"></script>
<link rel="stylesheet" type="text/css" href="{{ URL::to('css/plugins/chart/style.css') }}">

<div style="width:1000px">
			<canvas id="chart1"></canvas>
</div>

<script>
			// var data = getRandomData('April 01 2017', 60);

var data = {{json_encode($d)}};

		// Candlestick
		var ctx = document.getElementById("chart1").getContext("2d");
		ctx.canvas.width = 1000;
		ctx.canvas.height = 500;
		new Chart(ctx, {
			type: 'candlestick',
			data: {
				datasets: [{
					label: {{json_encode($nse)}},
					data: data,
					fractionalDigitsCount: 2,
					borderColor: '#000',
					borderWidth: 2,
				}]
			},
			options: {
				tooltips: {
					position: 'nearest',
					mode: 'index',
				},
			},
		});
</script>
@stop
