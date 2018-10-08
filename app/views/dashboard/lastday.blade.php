@extends('layout.index')

@section('content')
    <table class="table table-striped table-hover" border="1">
        <tr>
            <th>GAINERS</th>
            <th>LOSERS</th>
        </tr>
        <tr>
        <td>Last Day
        <table border="1" width="100%">
        <td>Name</td>
        <td>%</td>
        </table>
        </td>
        <td></td>
        </tr>
    </table>

@stop