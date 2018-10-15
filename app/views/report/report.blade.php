@extends('layout.index')
@section('title')
    Report
@stop
@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://code.jquery.com/jquery-1.x-git.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

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

    foreach ($sell as $key => $v) {
        if($v->status == '1')
            $sell_success = $v->total;
        if($v->status == '-1')
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

    
    
<?php $total = 0; ?>
    

<!-- <br><br><br><br><table class="table table-bordered" style="width:100%;" align="center"> -->
    <table id="sort" class="table table-bordered">
        <thead>
        <tr style="background-color:#c1c5c5">
            <th style="text-align:center">S.No.</th>
            <th style="text-align:center">Time</th>
            <th style="text-align:center">Company Name</th>
            <th style="text-align:center">Call</th>
            <th style="text-align:center">Entry Price</th>
            <th style="text-align:center">Exit Price</th>
            <th style="text-align:center">Strategy</th>
            <th style="text-align:center">Profit/Loss (%)</th>
            <th style="text-align:center">Success/Fail</th>            
        </tr>
        </thead>
        <tbody>
    <?php
    $i=0;
    foreach ($calldetail as $key => $k)
    { 
        $i++;
          $dt = $k->inserted_on;
            $dt = strtotime(str_replace('|', '', $dt));
            // $d = date('Y-m-d',$dt);
            $t = date('H:i:s',$dt);
        
            $time = $k->updated_on;
            $time = strtotime(str_replace('|', '', $time));
            // $d = date('Y-m-d',$dt);
            $a = date('H:i:s',$time);
        //echo "<pre>"; print_r($t); exit();
    ?>
        <tr>
            <td style="text-align:center">
            <?php
            echo $i;
            ?>
            </td>
            <td style="text-align:center">
                <?php
                echo $t."-". $a;
                ?></td>
            <td style="text-align:center">{{$k->nse}}</td>
            <td style="text-align:center">
            <?php
            $pl = 0;
            
             if($k->call == '1'){
                 echo "Buy";
                if($k->status != '0')
                    $pl = $k->cPer - $k->per;
             }
             if($k->call == '2')
             {
                echo "Sell";
                if($k->status != '0')
                    $pl = $k->per - $k->cPer;
             }
             $total += $pl;
            ?></td>
            <td style="text-align:center">{{$k->price}}</td>
            <td style="text-align:center">{{$k->cPrice}}</td>
            <td style="text-align:center">{{$k->strategy}}</td>
           <!-- <td style="text-align:center">{{number_format(($k->price - $k->cPrice), 2)}}</td> -->
            <td style="text-align:center">{{number_format(($pl), 2)}}%</td>   
            <!-- <td style="text-align:center">{{$pl}}%</td> -->
            <td style="text-align:center">
            <?php  
                 if($k->status == '1'){
                    echo "Success";
                    $i++;
                 }
                 if($k->status == '-1') {
                    echo "Fail";
                    $i++;
                 }
                 if($k->status == '0')
                    echo "Not Yet Completed";  
            ?>
            </td>
        </tr>
        
    <?php
        }
    ?>
    </tbody>
    </table>
    
    <!-- <div align="center" style="border:1px solid #ccc;width:250px;margin:0 auto;padding:7px;background-color:#ccc;">Overall Percentage
    <?php 
    //echo $total; 
    ?></div> -->
    <table class="table table-bordered" style="width:25%;margin-right:10px;" align="left">
        <tr style="background-color:#2ee80c">
            <td style="text-align:center">Success = {{$buy_success}}</td>
            <td style="text-align:center">Fail = {{$buy_fail}}</td>
            <td style="text-align:center">Not Yet Completed = {{$buy_notex}}</td>
        </tr>
    </table>  
    <table class="table table-bordered" style="width:25%;margin-right:10px;" align="left">
        <tr style="background-color:#f90505">
            <td style="text-align:center">Success = {{$sell_success}}</td>
            <td style="text-align:center">Fail = {{$sell_fail}}</td>
            <td style="text-align:center">Not Yet Completed = {{$sell_notex}}</td>
        </tr>
    </table>
    <table class="table table-bordered" style="width:20%;margin-right:10px;" align="left">
        <tr style="background-color:#e0e0e0">
            <td style="text-align:center">Overall Percentage
            <?php 
            echo $total; 
            ?>
            </td>
        </tr>
    </table>
    <table class="table table-bordered" style="width:20%;margin-right:10px;" align="left">
        <tr style="background-color:#e0e0e0">
            <td style="text-align:center">Overall Average
            <?php
            $average = $total / $i;
            echo round(($average), 2);
            ?>
            </td>
        </tr>
    </table>
    <script>
        $(document).ready(function() {
            $('#sort').DataTable({
            "order": [[ 0, "desc" ]]
        });
        } );
    </script>
@stop
