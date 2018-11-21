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
        <td style="text-align:center" colspan="2">Lastday Gainers</td>
        
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
        <td style="text-align:center" colspan="2">Lastday Losers</td>
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
        <td style="text-align:center" colspan="2">Last5Days Gainers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
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
        <td style="text-align:center" colspan="2">Last5Days Losers</td>
        <!-- <td>%</td> -->
        </tr>
        <?php
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
        <td style="text-align:center" colspan="2">Last30Days Gainers</td>
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
        <td style="text-align:center" colspan="2">Last30Days Losers</td>
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
        <td style="text-align:center" colspan="2">Last3Months Gainers</td>
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
        <td style="text-align:center" colspan="2">Last3Months Losers</td>
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
        <td style="text-align:center" colspan="2">Last6Months Gainers</td>
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
        <td style="text-align:center" colspan="2">Last6Months Losers</td>
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
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Last1Year Gainers</td>
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
        <td style="text-align:center" colspan="2">Last1Year Losers</td>
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
</div>
    

@stop