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
        if($v->status == '-1')
            $buy_success = $v->value;
        if($v->status == '1')
            $buy_fail = $v->value;
        if($v->status == '0')
            $buy_notex = $v->value;
    }
    ?>
    <h1>Buy Call</h1>
<table class="table table-striped table-hover">
        <tr>
            <!-- <th>Strategy</th> -->
            <th>Success</th>
            <th>Fail</th>
            <th>Not Complet</th>
            
        </tr>
        <tr>
            <td>{{$buy_success}}</td>
            <td>{{$buy_fail}}</td>
            <td>{{$buy_notex}}</td>
        </tr>
    </table>    
    <h1>Sell Call</h1>

<table class="table table-striped table-hover">
        <tr>
            <!-- <th>Strategy</th> -->
            <th>Success</th>
            <th>Fail</th>
            <th>Not Complet</th>
            
        </tr>
        <tr>
            <td>{{$sell_success}}</td>
            <td>{{$sell_fail}}</td>
            <td>{{$sell_notex}}</td>
        </tr>
    </table>    

@stop