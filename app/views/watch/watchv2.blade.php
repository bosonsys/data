@extends('layout.index')
{{ HTML::script('js/highstock.js') }}
@section('title')
    Watch List
@stop
@section('content')

<div id="container" style="height: 600px; min-width: 310px"></div>
<script type="text/javascript">
var seriesOptions = [],
    seriesCounter = 0,
     names = {{json_encode($names)}};
/**
 * Create the chart when all data is loaded
 * @returns {undefined}
 */
function createChart() {
    Highcharts.stockChart('container', {
        rangeSelector: {
            selected: 4
        },

        yAxis: {
            labels: {
                formatter: function () {
                    return (this.value > 0 ? ' + ' : '') + this.value + '%';
                }
            },
            plotLines: [{
                value: 0,
                width: 2,
                color: 'silver'
            }]
        },

        plotOptions: {
            series: {
                compare: 'percent',
                showInNavigator: true
            }
        },

        tooltip: {
            pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
            valueDecimals: 2,
            split: true
        },

        series: seriesOptions
    });
}
// setInterval(function () {
    $.each(names, function (i, name) {
        $.getJSON('http://localhost/market/public/market/stockJSON/' + name.toLowerCase(),
        function (data) {
            seriesOptions[i] = {
                name: name,
                data: data
            };
            // As we're loading the data asynchronously, we don't know what order it will arrive. So
            // we keep a counter and create the chart when all the data is loaded.
            seriesCounter += 1;
            if (seriesCounter === names.length) {
                createChart();
            }
        });
    });
// }, 50000);

		</script>
@stop