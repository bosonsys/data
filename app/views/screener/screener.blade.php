@extends('layout.index')
@section('title')
    Screener
@stop
@section('content')
  
 
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
        <table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Lastday Gainers</td>
        </tr>
          <?php
          foreach($arr as $key => $a)
          {
              $symbol = $a['symbol'];
              $sma1 = $a['sma1'];
             // echo "<pre>"; print_r($sma1);
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$symbol)."' target='_blank'>$symbol</a>"; ?></td>
        <td>
        <?php echo round(($sma1),2) ?>%</td>
        </tr>
        <?php
        }
        ?>
        </table>
		</div>
    </div>
</div>