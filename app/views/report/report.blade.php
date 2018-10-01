@extends('layout.index')
@section('content')
 <?php
print_r($buy);
print_r($sell);
//$count = DB::table('intra_call')->where('status','=','-1')->count();
//echo "<pre>"; print_r($count); exit();
         //echo "<pre>"; print_r($sell); exit();
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
<table class="table table-striped table-hover">
        <tr>
            <!-- <th>Strategy</th> -->
            <th>Total</th>
            <th>Success</th>
            <th>Fail</th>
            
        </tr>
        <tr>
            <td>{{$buy[0]->value}}</td>
            <td>{{$buy[0]->value}}</td>
            <td>{{$buy[0]->value}}</td>
        </tr>
    </table>    
<table class="table table-striped table-hover">
        <tr>
            <!-- <th>Strategy</th> -->
            <th>Total</th>
            <th>Success</th>
            <th>Fail</th>
            
        </tr>
        <tr>
        </tr>
    </table>    

@stop