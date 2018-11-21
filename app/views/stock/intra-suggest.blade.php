@extends('layout.index')
@section('title')
    Intra-Suggest List View
@stop
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
       <table border="1" width="75%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">High Volatility</td>
        <!-- <td>PERCENTAGE</td> -->
        </tr>
        <?php
        foreach ($last as $key => $n)
        { 
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->SYMBOL)."' target='_blank'>$n->SYMBOL</a>"; ?></td>
        <td>
        <?php echo round(($n->per),2) ?>%</td>
        </tr>
        <?php
        }
        ?>
        </table>
	</div>
    <div class="col-md-3">
        <table border="1" width="75%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Low Volatility</td>
        <!-- <td>PERCENTAGE</td> -->
        </tr>
        <?php
        foreach ($top as $key => $p)
        { 
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$p->SYMBOL)."' target='_blank'>$p->SYMBOL</a>" ?></td>
        <td>
        <?php echo round(($p->per),2) ?>%</td>
        </tr>
        <?php
        }
        ?>
        </table>
		</div>
    <div class="col-md-3">
      <table border="1" width="75%" align="center">
         <tr>
        <td style="text-align:center" colspan="2">Last High</td>
        <!-- <td>PERCENTAGE</td> -->
        </tr>
        <?php
        foreach ($lasttop as $key => $p)
        { 
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$p->SYMBOL)."' target='_blank'>$p->SYMBOL</a>" ?></td>
        <td>{{$p->CLOSEP}}%</td>
        </tr>
        <?php
        }
        ?>
        </table>
    </div>   
    <div class="col-md-3">
      <table border="1" width="75%" align="center">
         <tr>
        <td style="text-align:center" colspan="2">Last Low</td>
        <!-- <td>PERCENTAGE</td> -->
        </tr>
        <?php
        foreach ($lastlosers as $key => $n)
        { 
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->SYMBOL)."' target='_blank'>$n->SYMBOL</a>" ?></td>
        <td>{{$n->CLOSEP}}%</td>
        </tr>
        <?php
        }
        ?>
        </table>
    </div> 
    </div>
    <div class="row">&nbsp</div>
	<div class="row">
    <div class="col-md-3">
     <table border="1" width="75%" align="center">
         <tr>
        <td style="text-align:center" colspan="2">High Volume</td>
        <!-- <td>PERCENTAGE</td> -->
        </tr>
        <?php
        foreach ($lastgain as $key => $p)
        { 
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$p->SYMBOL)."' target='_blank'>$p->SYMBOL</a>" ?></td>
        <td>{{$p->TOTTRDQTY}}</td>
        </tr>
        <?php
        }
        ?>
        </table>
    </div> 
    <div class="col-md-3">
    <table border="1" width="75%" align="center">
         <tr>
        <td style="text-align:center" colspan="2">Low Volume</td>
        <!-- <td>PERCENTAGE</td> -->
        </tr>
        <?php
        foreach ($lastlose as $key => $n)
        {     
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->SYMBOL)."' target='_blank'>$n->SYMBOL</a>"; ?></td>
        <td>{{$n->TOTTRDQTY}}</td>
        </tr>
        <?php
        }
        ?>
        </table>
    </div>  
</div>
@stop