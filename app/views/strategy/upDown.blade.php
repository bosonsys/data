@extends('layout.index')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
 
<script type="text/javascript" src="https://code.jquery.com/jquery-1.x-git.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>
<div class="row row-list">
    <div class="col-xs-6">
        <table id="sort" class="table table-hover">
        <thead>
            <tr>
                <th>Symbol</th>
                <th>Max Per</th>
                <th>Diff</th>
                <th>Updated on</th>
            </tr>
        </thead>
        <tbody>
        <?php
        //    print_r($data);
        //    exit;
        foreach($data as $row) {
            // $diff = round($row->avgDiff,2);
            echo "<tr>
                <td><a href='http://localhost/market/public/call/edel/".$row->symbol."' target='_blank'>".$row->symbol."</a></td>
                <td><a href='http://localhost/market/public/market/stock/".$row->symbol."' target='_blank'>".$row->maxPer."</a></td>
                <td>$row->diff</td>
                <td>$row->updated_on</td>
            </tr>";
        }

        ?>
        </tbody>
        </table>
    </div>
</div>  
<!-- <script>
	$(document).ready(function() {
		$('#sort').DataTable();
	} );
</script> -->
@stop