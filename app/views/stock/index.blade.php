@extends('layout.index')
@section('title')
    History Data
@stop
@section('content')
<?php
/*
echo "<pre>";
print_r($stocks);
exit;
*/
?>
<table class="table table-striped table-hover">
    <thead>
    <tr><th>No#</th>
    <th>Action</th>
    <th>Name</th>
        <th>Price</th>
        <th>Close1</th>
        <th>Close2</th>
        <th>Close3</th>
        <th>Close4</th>
        <th>Close5</th>
        <th>Total</th>
        <th>Avg</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($stocks as $stock) {
        echo "<tr>
        <td><a href='https://www.edelweiss.in$stock->URL' target='_blank'>$stock->id</td>
        <td>
            <a href='https://mmb.moneycontrol.com$stock->mmbURL' target='_blank'>MMB</a>
            <a href='https://in.tradingview.com/symbols/NSE-$stock->nse/technicals/' target='_blank'>TV</a>
            <a href='https://in.tradingview.com/chart/?symbol=NSE%3A$stock->nse' target='_blank'>Chart</a>
            <a href='https://www.screener.in/company/$stock->nse/consolidated/' target='_blank'>Screener</a>
        </td>
        <td><a href='".url('stock/'.$stock->nse)."' target='_blank'>$stock->nse</a></td>
        <td><a href='http://www.moneycontrol.com/stocks/cptmarket/compsearchnew.php?search_data=&cid=&mbsearch_str=&topsearch_type=1&search_str=".$stock->isin."' target='_blank'>".round($stock->price,2)."</a></td>
        <td>".number_format((float)$stock->closediff1, 2, '.', '')."</td>
        <td>".number_format((float)$stock->closediff2, 2, '.', '')."</td>
        <td>".number_format((float)$stock->closediff3, 2, '.', '')."</td>
        <td>".number_format((float)$stock->closediff4, 2, '.', '')."</td>
        <td>".number_format((float)$stock->closediff5, 2, '.', '')."</td>
        <td><a href='https://mmb.moneycontrol.com$stock->mmbURL' target='_blank'>".round($stock->closetotal,2)."</a></td>
        <td>".number_format((float)$stock->closeavg, 2, '.', '')."</td>
        </tr>";
    }
    ?>
<!--   
		<td><a href='https://www.edelweiss.in/tools/Charting.aspx?Exchange=NSE&co_code=".$stock->eid."' target='_blank'>".round($stock->closetotal,2)."</a></td>
 <td>$stock->date1 - $stock->date5</td>-->

    </tbody>
</table>

@stop