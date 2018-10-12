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
        <tr>
        <td style="text-align:center">Last 5 Days
        <table border="1" width="50%" align="center">
        <tr>
        <td>Name</td>
        <td>%</td>
        </tr>
        <?php
    foreach ($pos5 as $key => $p)
    { 
        
    ?>
        <tr>
        <td>{{$p->SYMBOL}}</td>
        <td>{{$p->CLOSEP}}</td>
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
    foreach ($neg5 as $key => $n)
    { 
        
    ?>
        <tr>
        <td>{{$n->SYMBOL}}</td>
        <td>{{$n->CLOSEP}}</td>
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
    foreach ($pos as $key => $p)
    { 
        
    ?>
        <tr>
        <td></td>
        <td></td>
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
    foreach ($neg as $key => $n)
    { 
        
    ?>
        <tr>
        <td></td>
        <td></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        </tr>
        <tr>
        <td style="text-align:center">Last 3 Months
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
        <td></td>
        <td></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        <td style="text-align:center">Last 3 Months
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
        <td></td>
        <td></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        </tr>
        <tr>
        <td style="text-align:center">Last 6 Months
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
        <td></td>
        <td></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        <td style="text-align:center">Last 6 Months
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
        <td></td>
        <td></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        </tr>
         <tr>
        <td style="text-align:center">Last 1 Year
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
        <td></td>
        <td></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        <td style="text-align:center">Last 1 Year
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
        <td></td>
        <td></td>
        </tr>
        <?php
    }
    ?>
        </table>
        </td>
        </tr>
    </table>

@stop