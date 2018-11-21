@extends('layout.index')
@section('title')
    Screener
@stop
@section('content')
<div class="container-fluid">
	<div class="row">
		<div class="col-md-3">
        <table border="1" width="100%" align="center">
        <tr>
        <td style="text-align:center" colspan="2">Lastday Gainers</td>
        
        </tr>
        <?php
            foreach($close as $v)
            {
                echo $v;
            }
            ?>
           
        </table>
		</div>
    </div>
</div>