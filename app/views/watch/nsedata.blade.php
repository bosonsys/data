@extends('layout.index')
@section('content')
<script type="text/javascript">
    setInterval(getData, (30 * 1000));
    function getData() {
        var jsonData = $.ajax({
            // url: "http://localhost/market/public/store/nsedata",
            url: "http://localhost/market/public/marketwatch/ETG500",
            dataType: "json",
            async: false
        }).responseText;
        console.log(jsonData);
    }
</script>
@stop