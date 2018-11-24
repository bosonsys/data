@extends('layout.index')
@section('title')
    Screener
@stop
@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://code.jquery.com/jquery-1.x-git.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

<div class="container-fluid">
	<div class="row">
		<!-- <div class="col-md-3"> -->
        <!-- <table border="1" width="100%" align="center"> -->
        <table id="sort" class="table table-bordered">
        <thead>
        <tr style="background-color:#c1c5c5">
        <th style="text-align:center">S.No.</th>
        <th style="text-align:center">Symbol</th>
        <th style="text-align:center">LTP</th>
        <th style="text-align:center">SMA (14)</th>
        <th style="text-align:center">SMA (21)</th>
        <th style="text-align:center">SMA (80)</th>
        <th style="text-align:center">RSI (14)</th>
        </tr>
         </thead>
          <tbody>
        <?php
            foreach ($arr as $key => $p)
            {  //print_r($p['symbol']); 
            ?>
            <tr style="text-align:center">
                <td><?php echo $key+1 ?></td>
                <td><?php echo "<a href='".url('stock/'.$p['symbol'])."' target='_blank'>$p[symbol]</a>" ?></td>
                <td><?php echo $p['close'] ?></td>
                <td><?php echo $p['sma2'] ?></td>
                <td><?php echo $p['sma1'] ?></td>
                <td><?php echo $p['sma3'] ?></td>
                <td><?php echo $p['rsi'] ?></td>
            </tr>
             <?php
            }
            ?>
         </tbody>
        </table>
        <script>
            $(document).ready(function() {
                $('#sort').DataTable({
                "order": [[ 0, "desc" ]]
            });
            } );
        </script>
		<!-- </div> -->
    </div>
</div>
@stop