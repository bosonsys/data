@extends('layout.index')
@section('title')
    Dashboard View
@stop
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
        <table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Last day Gainers</td>
        
        </tr>
            <?php
            foreach ($positive as $key => $p)
            { 
                
            ?>
                <tr style="text-align:center">
                <td><?php echo "<a href='".url('stock/'.$p->n)."' target='_blank'>$p->n</a>" ?></td>
                <td>{{$p->c}}%</td>
                </tr>
                <?php
            }
            ?>
        </table>
		</div>
		<div class="col-md-3">
        <table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Last day Losers</td>
        <!-- <td></td> -->
        </tr>
        <?php
    foreach ($negative as $key => $n)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->n)."' target='_blank'>$n->n</a>" ?></td>
        <td>{{$n->c}}%</td>
        </tr>
        <?php
    }
    ?>
        </table>
		</div>
		<div class="col-md-3">
        <table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Weekly Gainers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php

for($j = 0; $j < count($top5); $j++)
 {
    for($i = 0; $i < count($top5)-1; $i++)
	{
 
        if($top5[$i]->per < $top5[$i+1]->per)
		 {
            $temp = $top5[$i+1]->per;
            $top5[$i+1]->per=$top5[$i]->per;
            $top5[$i]->per=$temp;

            $temp = $top5[$i+1]->n;
            $top5[$i+1]->n=$top5[$i]->n;
            $top5[$i]->n=$temp;
        }       
    }
}

    foreach ($top5 as $key => $t)
    { 
    //    if($t->per)
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$t->n)."' target='_blank'>$t->n</a>" ?></td>
        <td>
        <?php
        echo round(($t->per), 2)
        ?>%</td>
        </tr>
        <?php
    }
    ?>
        </table>
		</div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Weekly Losers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php

for($j = 0; $j < count($last5); $j++)
 {
    for($i = 0; $i < count($last5)-1; $i++)
	{
 
        if($last5[$i]->per < $last5[$i+1]->per)
		 {
            $temp = $last5[$i+1]->per;
            $last5[$i+1]->per=$last5[$i]->per;
            $last5[$i]->per=$temp;

            $temp = $last5[$i+1]->n;
            $last5[$i+1]->n=$last5[$i]->n;
            $last5[$i]->n=$temp;
        }       
    }
}

    foreach ($last5 as $key => $l)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$l->n)."' target='_blank'>$l->n</a>" ?></td>
        <td>
        <?php
        echo round(($l->per), 2)
        ?>%</td>
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
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Monthly Gainers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
        foreach ($tMonth as $key => $p)
        { 
            
        ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$p->n)."' target='_blank'>$p->n</a>" ?></td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?>%</td>
        </tr>
        <?php
        }
        ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Monthly Losers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
    foreach ($lMonth as $key => $n)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->n)."' target='_blank'>$n->n</a>" ?></td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?>%</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">3 Months Gainers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
    foreach ($t3Month as $key => $p)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$p->n)."' target='_blank'>$p->n</a>" ?></td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?>%</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">3 Months Losers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
    foreach ($l3Month as $key => $n)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->n)."' target='_blank'>$n->n</a>" ?></td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?>%</td>
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
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">6 Months Gainers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
    foreach ($t6Month as $key => $p)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$p->n)."' target='_blank'>$p->n</a>" ?></td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?>%</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		        <table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">6 Months Losers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
    foreach ($l6Month as $key => $n)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->n)."' target='_blank'>$n->n</a>" ?></td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?>%</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Yearly Gainers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
    foreach ($t1Year as $key => $p)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$p->n)."' target='_blank'>$p->n</a>" ?></td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?>%</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		  <table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Yearly Losers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
    foreach ($l1Year as $key => $n)
    { 
        
    ?>
        <tr style="text-align:center">
        <td><?php echo "<a href='".url('stock/'.$n->n)."' target='_blank'>$n->n</a>" ?></td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?>%</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
	</div>
</div>
    

@stop