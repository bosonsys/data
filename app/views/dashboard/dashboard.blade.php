@extends('layout.index')
@section('title')
    Dashboard View
@stop
@section('content')
    <table class="table table-striped table-hover" border="1">
        <tr>
            <th style="text-align:center">GAINERS</th>
            <th style="text-align:center">LOSERS</th>
        </tr>
        <tr>
        <td style="text-align:center">Last Day
        <table border="1" width="50%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
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
        </td>
        <td style="text-align:center">Last Day
        <table border="1" width="50%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
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
        </td>
        </tr>
        <tr>
        <td style="text-align:center">Last 5 Days
        <table border="1" width="50%" align="center">
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
        </td>
        <td style="text-align:center">Last 5 Days
        <table border="1" width="50%" align="center">
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
        </td>
        </tr>
        <tr>
        <td style="text-align:center">Last 30 Days
        <table border="1" width="50%" align="center">
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
        </td>
        <td style="text-align:center">Last 30 Days
        <table border="1" width="50%" align="center">
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
        </td>
        </tr>
        
    </table>

@stop