@extends('layout.index')
@section('content')
<table class="table table-striped table-hover">
        <tr>
            <!-- <th>Strategy</th> -->
            <th>Buy Call</th>
            <th>Sell Call</th>
        </tr>
        <?php
//$count = DB::table('intra_call')->where('status','=','-1')->count();
//echo "<pre>"; print_r($count); exit();
// $buy = DB::table('intra_call')
//         ->select(DB::raw('count(call) as count'),'call')
//         ->orderBy('count')
//         ->get();
// //          echo "<pre>"; print_r($buy); exit();
//          $buy = DB::table('intra_call')
//          ->select(DB::raw('strategy, count(call) as count, status'))
//          ->where('call',1)
//          ->groupBy('status')
//          ->get();
         
        //  echo "<pre>"; print_r($buy);
         $sell = DB::table('intra_call')->select(DB::raw('count("call") as total, status'))
         ->where('call',2)
         ->groupBy('status')
         ->get();
         //echo "<pre>"; print_r($sell); exit();
         foreach($sell as $row) {
            //print_r($row);
            //exit;
            // if($row->enter)
            //     $p = ($row->pl * 100 / $row->enter ) / $row->noshare;
            // else
            //     $p = 0;
            echo "<tr>
        <td>".$row->total."</td>
        <td>".$row->status."</td>
        </tr>";
        }
        ?>
        
    </table>    

@stop