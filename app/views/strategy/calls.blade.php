@extends('layout.index')
@section('content')
<table class="table table-striped table-hover">
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

@stop