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
        <td>SYMBOL</td>
        <td>PERCENTAGE</td>
        </tr>
        <?php
    foreach ($top as $key => $p)
    { 
        
    ?>
        <tr>
        <td>{{$p->SYMBOL}}</td>
        <td> <?php echo round(($p->per),2) ?></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        <td style="text-align:center">Last Day
        <table border="1" width="50%" align="center">
        <tr>
        <td>SYMBOL</td>
        <td>PERCENTAGE</td>
        </tr>
        <?php
    foreach ($last as $key => $n)
    { 
        
    ?>
        <tr>
        <td>{{$n->SYMBOL}}</td>
        <td>
        <?php echo round(($n->per),2) ?> </td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        </tr>
   </table>

@stop