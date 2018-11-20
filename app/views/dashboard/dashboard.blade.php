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
                <tr>
                <td><?php echo "<a href='".url('stock/'.$p->n)."' target='_blank'>$p->n</a>" ?></td>
                <td>{{$p->c}}</td>
                </tr>
                <?php
            }
            ?>
        </table>
		</div>
		<div class="col-md-3">
        <table border="1" width="100%" align="center">
        <tr>
        <td>Lastday Losers</td>
        <td></td>
        </tr>
        <?php
    foreach ($negative as $key => $n)
    { 
        
    ?>
        <tr>
        <td><?php echo "<a href='".url('stock/'.$n->n)."' target='_blank'>$n->n</a>" ?></td>
        <td>{{$n->c}}</td>
        </tr>
        <?php
    }
    ?>
        </table>
		</div>
		<div class="col-md-3">
        <table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($top as $key => $t)
    { 
       if($t->per)
    ?>
        <tr>
        <td>{{$t->n}}</td>
        <td>
        <?php
        echo round(($t->per), 2)
        ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
		</div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($last as $key => $l)
    { 
        
    ?>
        <tr>
        <td>{{$l->n}}</td>
        <td>
        <?php
        echo round(($l->per), 2)
        ?>
        </td>
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
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
        foreach ($tMonth as $key => $p)
        { 
            
        ?>
        <tr>
        <td>{{$p->n}}</td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?></td>
        </tr>
        <?php
        }
        ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($lMonth as $key => $n)
    { 
        
    ?>
        <tr>
        <td>{{$n->n}}</td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($t3Month as $key => $p)
    { 
        
    ?>
        <tr>
        <td>{{$p->n}}</td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($l3Month as $key => $n)
    { 
        
    ?>
        <tr>
        <td>{{$n->n}}</td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?></td>
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
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($t3Month as $key => $p)
    { 
        
    ?>
        <tr>
        <td>{{$p->n}}</td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		        <table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($l3Month as $key => $n)
    { 
        
    ?>
        <tr>
        <td>{{$n->n}}</td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		<table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($t3Month as $key => $p)
    { 
        
    ?>
        <tr>
        <td>{{$p->n}}</td>
        <td>
        <?php
        echo round(($p->per), 2)
        ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
		<div class="col-md-3">
		  <table border="1" width="100%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($l3Month as $key => $n)
    { 
        
    ?>
        <tr>
        <td>{{$n->n}}</td>
        <td>
        <?php
        echo round(($n->per), 2)
        ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </div>
	</div>
</div>
    

@stop