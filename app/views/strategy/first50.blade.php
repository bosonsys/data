@extends('layout.index')
@section('content')
@section('title')
    First 15 MIN Breakout
@stop
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://code.jquery.com/jquery-1.x-git.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

<table id="sort" class="table table-hover">
<thead>
    <tr>
        <th>Symbol</th>
        <th>Max LTP</th>
        <th>Min LTP</th>
        <th>First 15min Max</th>
        <th>Max High</th>
        <th>Updated on</th>
    </tr>
</thead>
<tbody>
<?php
//    print_r($data);
//    exit;
foreach($data as $row) {
// echo '<pre>'; print_r($row); exit();
    if ($row->per > 0.5) {
        $cc = "bg-success";
    } else {
        $cc = "bg-danger";
    }
    echo "<tr class='$cc'>
    <td><a href='http://localhost/market/public/call/edel/".$row->symbol."' target='_blank'>".$row->symbol."</a></td>
    <td><a href='http://localhost/market/public/market/stock/".$row->symbol."' target='_blank'>".$row->maxLTP."</a></td>
            <td>$row->minLTP</td>
            <td>$row->firstMax</td>
            <td>$row->per</td>
            <td>$row->updated_on</td>
        </tr>";
}

?>
</tbody>
</table>
<script>
	$(document).ready(function() {
		$('#sort').DataTable({
        "order": [[ 4, "desc" ]]
    });
	} );
</script>
@stop