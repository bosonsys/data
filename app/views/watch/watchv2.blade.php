@extends('layout.index')
{{ HTML::script('js/chartist.min.js') }}
<link href="{{ asset('css/chartist.min.css') }}" rel="stylesheet"> 

@section('title')
    Watch List
@stop
@section('content')

<div id="container" class="ct-chart" style="height: 600px; min-width: 310px"></div>
<script type="text/javascript">
datas = {{json_encode($datas)}};
names = {{json_encode($names)}};
var seriesOptions = [],
    seriesCounter = 0
var chart = new Chartist.Line('.ct-chart', {
  labels: datas,
  series: [
    [4, 3, 6, 5, 3],
    [1, 4, 2, 1, 2],
    [3, 2, 1, 2, 4],
    [5, 5, 5, 4, 1]
  ]
}, {
  fullWidth: true,
  chartPadding: {
    right: 10
  },
  lineSmooth: Chartist.Interpolation.cardinal({
    fillHoles: true,
  }),
  low: 0
});
</script>
@stop