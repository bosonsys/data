@extends('layout.index')
{{ HTML::script('js/d3.min.js') }}
{{ HTML::script('js/nv.d3.js') }}
@section('title')
    Watch List
@stop
@section('content')

<!-- <div id="chart_gainer"></div> -->
<div id="chart"></div>

<!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
    
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!

    var yyyy = today.getFullYear();
    if(dd<10){
        dd='0'+dd;
    } 
    if(mm<10){
        mm='0'+mm;
    } 
    var today = dd+'-'+mm+'-'+yyyy;

    // Load the Visualization API and the piechart package.
    google.charts.load('current', {'packages':['corechart', 'line']});
      
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChart);
    setInterval(drawChart, (20 * 1000));
    
    // google.charts.setOnLoadCallback(drawGainerChart);
    // setInterval(drawGainerChart, (20 * 1000));

    function drawChart() {
      var jsonData = $.ajax({
          url: "http://localhost/market/public/json/results_"+today+".json",
          // url: "http://localhost/market/public/call/json/gainers",
          dataType: "json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data = new google.visualization.DataTable(jsonData);
var options = {
        explorer: {
          axis: 'horizontal',
            keepInBounds: true
          },
        colors: ['#a52714', '#097138', '#FF7138', '#0F7F38', '#F071FF', '#007138', '#FFFF38', '#000038']
      };
      // Instantiate and draw our chart, passing in some options.
      var chart = new google.visualization.LineChart(document.getElementById('chart'));
      //chart.draw(data, {width: 400, height: 240});
	  chart.draw(data, options);
    }

    function drawGainerChart() {
      var jsonData = $.ajax({
          // url: "http://localhost/market/public/json/results.json",
          url: "http://localhost/market/public/call/json/gainers",
          dataType: "json",
          async: false
          }).responseText;
          
      // Create our data table out of JSON data loaded from server.
      var data1 = new google.visualization.DataTable(jsonData);
var options = {
        hAxis: {
          title: 'Time',
          textStyle: {
            color: '#01579b',
            fontSize: 12,
            fontName: 'Arial',
            bold: true,
            italic: true
          },
          titleTextStyle: {
            color: '#01579b',
            fontSize: 12,
            fontName: 'Arial',
            bold: false,
            italic: true
          }
        },
        vAxis: {
          title: '%',
          textStyle: {
            color: '#1a237e',
            fontSize: 24,
            bold: true
          },
          titleTextStyle: {
            color: '#1a237e',
            fontSize: 12,
            bold: true
          }
        },
        colors: ['#a52714', '#097138', '#FF7138', '#0F7F38', '#F071FF', '#007138', '#FFFF38', '#000038']
      };
      // Instantiate and draw our chart, passing in some options.
      // var chart1 = new google.visualization.LineChart(document.getElementById('chart_gainer'));
      //chart.draw(data, {width: 400, height: 240});
	  // chart1.draw(data1, options);
    }
    </script>
@stop