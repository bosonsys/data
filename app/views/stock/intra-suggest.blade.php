@extends('layout.index')
@section('title')
    Intra-Suggest List View
@stop
@section('content')
   <table class="table table-striped" border="1">
        <tr>
            <th style="text-align:center">GAINERS</th>
            <th style="text-align:center">LOSERS</th>
        </tr>
        <tr>
        <td style="text-align:center">High Volatility
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
        <td><?php echo "<a href='".url('stock/'.$n->SYMBOL)."' target='_blank'>$n->SYMBOL</a>"; ?></td>
        <td>
        <?php echo round(($n->per),2) ?> </td>
        </tr>
        <?php
        }
        ?>
        </table>
        </td>
        <td style="text-align:center">Low Volatility
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
        <td><?php echo "<a href='".url('stock/'.$p->SYMBOL)."' target='_blank'>$p->SYMBOL</a>" ?></td>
        <td> <?php echo round(($p->per),2) ?></td>
        </tr>
        <?php
        }
        ?>
        </table>
        </td>
        </tr>
        <tr>
        <td style="text-align:center">Last High
        <table border="1" width="50%" align="center">
        <tr>
        <td>SYMBOL</td>
        <td>PERCENTAGE</td>
        </tr>
        <?php
        foreach ($lasttop as $key => $p)
        { 
        ?>
        <tr>
        <td><?php echo "<a href='".url('stock/'.$p->SYMBOL)."' target='_blank'>$p->SYMBOL</a>" ?></td>
        <td>{{$p->CLOSEP}}</td>
        </tr>
        <?php
        }
        ?>
        </table>
        </td>
        <td style="text-align:center">Last Low
        <table border="1" width="50%" align="center">
        <tr>
        <td>SYMBOL</td>
        <td>PERCENTAGE</td>
        </tr>
        <?php
        foreach ($lastlosers as $key => $n)
        {     
        ?>
        <tr>
        <td><?php echo "<a href='".url('stock/'.$n->SYMBOL)."' target='_blank'>$n->SYMBOL</a>"; ?></td>
        <td>{{$n->CLOSEP}}</td>
        </tr>
        <?php
        }
        ?>
        </table>
        </td>
        </tr>
   </table>

@stop