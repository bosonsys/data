@extends('layout.index')

@section('content')
    <table class="table table-striped table-hover" border="1">
        <tr>
            <th style="text-align:center">GAINERS</th>
            <th>LOSERS</th>
        </tr>
        <tr>
        <td style="text-align:center">Last Day
        <table border="1" width="50%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($stock as $key => $s)
    { 
        
    ?>
        <tr>
        <td>{{$s->n}}</td>
        <td>{{$s->c}}</td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        <td></td>
        </tr>
    </table>

@stop