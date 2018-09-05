@extends('layout.index')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.css"/>
<script type="text/javascript" src="https://code.jquery.com/jquery-1.x-git.min.js"></script> 
<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.18/datatables.min.js"></script>

<table id="sort" class="table table-striped table-hover">
    <thead>
    <tr><th>Symbol</th>
    <th>Open</th>
        <th>High</th>
        <th>Low</th>
        <th>ltP</th>
        <th>%</th>
        <th>wkhi</th>
        <th>wklo</th>
        <th>Calls</th>
    </thead>
    <tbody>
    <?php
     foreach($data as $d) {
    //        print_r($d);
    // exit;
        echo "
        <td><a href='http://localhost/market/public/call/edel/".$d->symbol."' target='_blank'>".$d->symbol."</a></td>
        <td><a href='http://localhost/market/public/market/stock/".$d->symbol."' target='_blank'>".$d->open."</a></td>
        <td>".$d->high."</td>
        <td>".$d->low."</td>
        <td>".$d->ltP."</td>
        <td>".$d->per."</td>
        <td>".$d->wkhi."</td>
        <td>".$d->wklo."</td>
        <td> <div class='alert1 alert-success1'>".$d->call."</div></td>
        </tr>";
    }
    ?>
    </tbody>
</table>
<script>
	$(document).ready(function() {
		$('#sort').DataTable({
        "order": [[ 5, "desc" ]]
    });
	} );
</script>
@stop