@extends('layout.index')
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

    <table class="table table-bordered" style="width:40%;margin-right:50px;" align="left">
        <tr style="background-color:#2ee80c">
            <!-- <th>Strategy</th> -->
            <!-- <th style="text-align:center">Success</th>
            <th style="text-align:center">Fail</th>
            <th style="text-align:center">Not Yet Completed</th> -->
            
            <td style="text-align:center">Success = {{$buy_success}}</td>
            <td style="text-align:center">Fail = {{$buy_fail}}</td>
            <td style="text-align:center">Not Yet Completed = {{$buy_notex}}</td>
        
        </tr>
        <!-- <tr>
            <td style="text-align:center">{{$buy_success}}</td>
            <td style="text-align:center">{{$buy_fail}}</td>
            <td style="text-align:center">{{$buy_notex}}</td>
        </tr> -->
    </table>  
    

    <table class="table table-bordered" style="width:40%" align="left">
        <tr style="background-color:#f90505">
            <!-- <th>Strategy</th> -->
            <!-- <th style="text-align:center">Success</th>
            <th style="text-align:center">Fail</th>
            <th style="text-align:center">Not Yet Completed</th> -->
            <td style="text-align:center">Success = {{$sell_success}}</td>
            <td style="text-align:center">Fail = {{$sell_fail}}</td>
            <td style="text-align:center">Not Yet Completed = {{$sell_notex}}</td>
        </tr>
        <!-- <tr>
            <td style="text-align:center">{{$sell_success}}</td>
            <td style="text-align:center">{{$sell_fail}}</td>
            <td style="text-align:center">{{$sell_notex}}</td>
        </tr> -->
    </table>


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
            <th style="text-align:center">Profit/Loss (%)</th>
            <th style="text-align:center">Success/Fail</th>            
        </tr>
        </thead>
        <tbody>
    <?php
    
    foreach ($calldetail as $key => $k)
    { 
        
        
    ?>
        <tr>
            <td style="text-align:center">{{$k->id}}</td>
            <td style="text-align:center">{{$k->inserted_on}}</td>
            <td style="text-align:center">{{$k->nse}}</td>
            <td style="text-align:center">
            <?php
             if($k->call == '1'){
                 echo "Buy";
                 $pl = $k->cPer - $k->per;
             }
             if($k->call == '2')
             {
                echo "Sell";
                $pl = $k->per - $k->cPer;
             }
            ?></td>
            <td style="text-align:center">{{$k->price}}</td>
            <td style="text-align:center">{{$k->cPrice}}</td>
           <!-- <td style="text-align:center">{{number_format(($k->price - $k->cPrice), 2)}}</td> -->
            <td style="text-align:center">{{$pl}}</td>   
            <td style="text-align:center">
            <?php  
                 if($k->status == '1')
                    echo "Success";
                 if($k->status == '-1')
                    echo "Fail";
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
    <script>
        $(document).ready(function() {
            $('#sort').DataTable({
            "order": [[ 0, "desc" ]]
        });
        } );
    </script>
@stop
