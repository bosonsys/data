@extends('layout.index')
@section('title')
    Summary
@stop
@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>

    <table id="sort" class="table table-bordered">
    <thead>
    <tr style="background-color:#c1c5c5">
    <th style="text-align:center">S.No.</th>
            <th style="text-align:center">Company</th>
            <th style="text-align:center">Total Success/Fail</th>
            <th style="text-align:center">Buy Success/Fail</th>
            <th style="text-align:center">Sell Success/Fail</th>            
        </tr>
        </thead>
        
        <tbody>
    <?php
    $j=0;
    //echo "<pre>";
    foreach($list as $l)
    {
        $j++;
        $bs = $bf = $ss = $sf = 0;
       $data = CallController::printD($l->nse, $ldate);
        foreach ($data as $v) {
           // echo "<pre>"; print_r($v);
            if ($v->call == 1) {
                if ($v->status == 1) {
                    $bs++;
                } else {
                    $bf++;
                }
            }
             if ($v->call == 2) {
                if ($v->status == 1) {
                    $ss++;
                } else {
                    $sf++;
                }
            }
        }
        // exit;
    ?>
        <tr>
            <td style="text-align:center">
            <?php
            echo $j;
            ?>
            </td>
            <td style="text-align:center">{{$l->nse}}</td>
             <td style="text-align:center">{{$bs+$ss}} / {{$bf+$sf}}</td>
            <td style="text-align:center">{{$bs}} / {{$bf}}</td>
            <td style="text-align:center">{{$ss}} / {{$sf}}</td>
        </tr>
        
    <?php
    }    
    ?>
    </tbody>
    </table>
    

<!-- <br><br><br><br><table class="table table-bordered" style="width:100%;" align="center"> -->
    
@stop
