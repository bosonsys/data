@extends('layout.index')
@section('title')
    Summary
@stop
@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
 <?php

//print_r($call);
// print_r($buy);
// print_r($sell);
//$count = DB::table('intra_call')->where('status','=','-1')->count();
//echo "<pre>"; print_r($count); exit();
         //echo "<pre>"; print_r($sell); exit();
         $sell_success = 0;
         $sell_fail = 0;
         $sell_notex = 0;

         $buy_success = 0;
         $buy_fail = 0;
         $buy_notex = 0;

    foreach ($buys as $key => $v) {
        if($v->status == '1')
            $buy_success = $v->value;
        if($v->status == '-1')
            $buy_fail = $v->value;
        // if($v->status == '0')
        //     $buy_notex = $v->value;
    }
    
    foreach ($sells as $key => $v) {
        if($v->status == '1')
            $sell_success = $v->total;
        if($v->status == '-1')
            $sell_fail = $v->total;
    }

    ?>

    <table id="sort" class="table table-bordered">
    <thead>
    <tr style="background-color:#c1c5c5">
    <th style="text-align:center">S.No.</th>
            <th style="text-align:center">Company</th>
            <th style="text-align:center">Total</th>
            <th style="text-align:center">Buy Success/Fail</th>
            <th style="text-align:center">Sell Success/Fail</th>            
        </tr>
        </thead>
        
        <tbody>
    <?php
    $j=0;
    foreach ($calldetails as $key => $k)
    { 
        $j++;
    ?>
        <tr>
            <td style="text-align:center">
            <?php
            echo $j;
            ?>
            </td>
            <td style="text-align:center">{{$k->nse}}</td>
            <td style="text-align:center"></td>
            <td style="text-align:center">{{count($buy_success)}} / {{count($buy_fail)}}</td>
            <td style="text-align:center">{{count($sell_success)}} / {{count($sell_fail)}}</td>
        </tr>
        
    <?php
        }
    ?>
    </tbody>
    </table>
    

<!-- <br><br><br><br><table class="table table-bordered" style="width:100%;" align="center"> -->
    
@stop
