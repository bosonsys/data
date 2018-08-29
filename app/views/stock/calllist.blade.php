@extends('layout.index')

@section('content')
    <table class="table table-striped table-hover">
        <tr>
            <th>#</th>
            <th>Edel Code</th>
            <th>NSE Code</th>
            <th>Edel ID</th>
            <th>Date</th>
            <th>Buy/Sell</th>
            <th>Enter</th>
            <th>Exit</th>
            <th>Nos</th>
            <th>Profit/Lose</th>
            <th>%</th>
            <th>Status</th>
        </tr>
        <?php
            $tpl = 0;
            $tp = 0;
        foreach($calls as $row) {
            //print_r($row);
            //exit;
            if($row->enter)
                $p = ($row->pl * 100 / $row->enter ) / $row->noshare;
            else
                $p = 0;
            echo "<tr>
        <td>".$row->id."</td>
        <td>".$row->edelCode."</td>
        <td>".$row->nseCode."</td>
        <td>".$row->edelID."</td>
        <td>".$row->day."</td>
        <td>".$row->bs."</td>
        <td>".round($row->enter,2)."</td>
        <td>".round($row->exitat,2)."</td>
        <td>".$row->noshare."</td>
        <td>".round($row->pl,2)."</td>
        <td>".round($p ,2)."</td>
        <td>".$row->status."</td>
        </tr>";
            $tpl +=$row->pl;
            $tp +=$p;
        }
        ?>
        <tr>
            <td colspan="8"></td>
            <td><b>Total Profit/Lose:</b></td>
            <td><b>{{$tpl}}</b></td>
            <td><b>{{round($tp,2)}}</b></td>
            <td></td>
        </tr>
    </table>

@stop
