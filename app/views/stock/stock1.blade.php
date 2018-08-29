@extends('layout.index')

@section('content')
<table class="table table-striped table-hover">
    <tr><th>Date#</th>
        <th>Open</th>
        <th>High</th>
        <th>Close</th>
        <th>Open %</th>
        <th>Close %</th>
        <th>Total Qty</th>
        <th>Total Trd</th>
        <th>Total Trades</th>
    </tr>
        <?php
foreach($stock as $row) {
   // print_r($row);
  //  exit;
    echo "<tr>
        <td>$row->TIMESTAMP</td>
        <td>".round($row->OPEN,2)."</td>
        <td>".round($row->HIGH,2)."</td>
        <td>".round($row->CLOSE,2)."</td>
        <td>".round($row->OPENP,2)."</td>
        <td>".round($row->CLOSEP,2)."</td>
        <td>$row->TOTTRDQTY</td>
        <td>$row->TOTTRDVAL</td>
        <td>$row->TOTALTRADES</td>
        </tr>";
}

        ?>
<tr class="info">
    <td colspan="3"><h4>{{$row->ISIN}}</h4></td>
    <td colspan="2"><h4><a href='http://www.moneycontrol.com/stocks/cptmarket/compsearchnew.php?search_data=&cid=&mbsearch_str=&topsearch_type=1&search_str={{$row->ISIN}}' target='_blank'>{{$row->SYMBOL}}</a></h4></td>
    <td colspan="2"><h4><a href={{url('/call/'.$row->SYMBOL)}} target='_blank'>Add to Call</a></h4></td>
    <td colspan="3"><h4>{{$row->SERIES}}</h4></td>
</tr>
</table>
  <script type="text/javascript" src="<a class="vglnk" href="https://www.gstatic.com/charts/loader.js" rel="nofollow"><span>https</span><span>://</span><span>www</span><span>.</span><span>gstatic</span><span>.</span><span>com</span><span>/</span><span>charts</span><span>/</span><span>loader</span><span>.</span><span>js</span></a>"></script>
  <script src="<a class="vglnk" href="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" rel="nofollow"><span>http</span><span>://</span><span>ajax</span><span>.</span><span>googleapis</span><span>.</span><span>com</span><span>/</span><span>ajax</span><span>/</span><span>libs</span><span>/</span><span>jquery</span><span>/</span><span>1</span><span>.</span><span>4</span><span>.</span><span>3</span><span>/</span><span>jquery</span><span>.</span><span>min</span><span>.</span><span>js</span></a>"></script>
    <script type="text/javascript">
      // Load the Visualization API and the line package.
      google.charts.load('current', {'packages':['bar']});
      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawChart);
  
    function drawChart() {
        $.ajax({
        type: 'POST',
        url: '<a class="vglnk" href="http://localhost/charts/charts/getdata" rel="nofollow"><span>http</span><span>://</span><span>localhost</span><span>/</span><span>charts</span><span>/</span><span>charts</span><span>/</span><span>getdata</span></a>',
          
        success: function (data1) {
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable();
  
      data.addColumn('string', 'Year');
      data.addColumn('number', 'Sales');
      data.addColumn('number', 'Expense');
        
      var jsonData = $.parseJSON(data1);
      
      for (var i = 0; i < jsonData.length; i++) {
            data.addRow([jsonData[i].year, parseInt(jsonData[i].sales), parseInt(jsonData[i].expense)]);
      }
      var options = {
        chart: {
          title: 'Company Performance',
          subtitle: 'Show Sales and Expense of Company'
        },
        width: 900,
        height: 500,
        axes: {
          x: {
            0: {side: 'top'}
          }
        }
         
      };
      var chart = new google.charts.Bar(document.getElementById('bar_chart'));
      chart.draw(data, options);
       }
     });
    }
  </script>

  <div id="bar_chart"></div>
</body>
</html>
@stop
