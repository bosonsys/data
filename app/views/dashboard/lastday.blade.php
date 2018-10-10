@extends('layout.index')

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
    foreach ($pos as $key => $p)
    { 
        
    ?>
        <tr>
        <td>{{$p->n}}</td>
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
    foreach ($neg as $key => $n)
    { 
        
    ?>
        <tr>
        <td>{{$n->s}}</td>
        <td>{{$n->p}}</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        </tr>
    </table>

@stop