@extends('layout.index')
@section('content')
 <?php
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
    foreach ($sell as $key => $v) {
        if($v->status == '-1')
            $sell_success = $v->total;
        if($v->status == '1')
            $sell_fail = $v->total;
        if($v->status == '0')
            $sell_notex = $v->total;
    }

    foreach ($buy as $key => $v) {
        if($v->status == '1')
            $buy_success = $v->value;
        if($v->status == '-1')
            $buy_fail = $v->value;
        if($v->status == '0')
            $buy_notex = $v->value;
    }
    ?>
    <h1 style="text-align:center">Buy Call</h1>
<!--<table class="table table-striped table-hover">-->
<table class="table table-bordered" style="width:70%" align="center">
        <tr style="background-color:#e0e0e0">
            <!-- <th>Strategy</th> -->
            <th style="text-align:center">Success</th>
            <th style="text-align:center">Fail</th>
            <th style="text-align:center">Not Complet</th>
            
        </tr>
        <tr>
            <td style="text-align:center">{{$buy_success}}</td>
            <td style="text-align:center">{{$buy_fail}}</td>
            <td style="text-align:center">{{$buy_notex}}</td>
        </tr>
    </table>    
    <h1 style="text-align:center">Sell Call</h1>

<table class="table table-bordered" style="width:70%" align="center">
        <tr style="background-color:#e0e0e0">
            <!-- <th>Strategy</th> -->
            <th style="text-align:center">Success</th>
            <th style="text-align:center">Fail</th>
            <th style="text-align:center">Not Complet</th>
            
        </tr>
        <tr>
            <td style="text-align:center">{{$sell_success}}</td>
            <td style="text-align:center">{{$sell_fail}}</td>
            <td style="text-align:center">{{$sell_notex}}</td>
        </tr>
    </table>    

@stop
