@extends('layout.index')
@section('title')
    Dashboard
@stop
@section('content')
	@if(Session::has('message'))
		<div class="row">
			<div class="span4 offset4 alert alert-info">
				{{ Session::get('message') }}
			</div>
		</div>
	@endif
    <div class="row">
        <div class="col-md-6">Last Updated Date: {{$data['lastdate']}}</div>
        <div class="col-md-6">
            <div class="pull-right">No Data for Past : {{$data['pastdate']}}</div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-5">
            <h2>Data Link</h2>
            <ul>
			@if($data['download'])
            @foreach ($data['download'] as $link)
                <li>{{$link['day']}} : <a href="{{$link['url']}}" target="_blank">{{$link['date']}}</a></li>
            @endforeach
			@else
				<li>Updated up to Date</li>
			@endif
            </ul>
        </div>
        <div class="col-md-5">
            <h2>Actions</h2>
            <div class="col-lg-4"><a href="{{url('/movezip')}}">Unzip</a> </div>
            <!-- <div class="col-lg-4"><a href="{{url('/upload')}}">Upload into DB</a> </div> -->
        </div>
    </div>
    <div class="row">
        <h2>Load DATA</h2>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_nifty50list')}}">Nifty 50</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftynext50list')}}">Nifty Next 50</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_nifty100list')}}">Nifty 100</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_nifty200list')}}">Nifty 200</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_nifty500list')}}">Nifty 500</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftysmallcap50list')}}">Nifty Smallcap 50</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftysmallcap100list')}}">Nifty Smallcap 100</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftysmallcap250list')}}">Nifty Smallcap 250</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftymidcap50list')}}">Nifty Midcap 50</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftymidcap100list')}}">Nifty Midcap 100</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftymidcap150list')}}">Nifty Midcap 150</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftymidsmallcap400list')}}">Nifty Mid Smallcap 400</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftylargemidcap250list')}}">Nifty Large Midcap 250</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_Nifty_Midcap_Liquid15')}}">Nifty Midcap Liquid</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_Nifty50_Value20')}}">Nifty50 Value20</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_Nifty100_Liquid15')}}">Nifty100 Liquid15</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_nifty100Quality30list')}}">Nifty100 Quality30</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_Nifty100LowVolatility30list')}}">Nifty100 LowVolatility30</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/nifty_Low_Volatility50_Index')}}">Nifty100 LowVolatility50</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftydivopp50list')}}">Nifty Divopp 50</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftyconsumptionlist')}}">Nifty Consumption List</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/nifty_High_Beta50_Index')}}">Nifty High Beta</a></li></ul> </div> 
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftycpselist')}}">Nifty CPSE</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftymnclist')}}">Nifty MNC</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftysmelist')}}">Nifty SME</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftypselist')}}">Nifty PSE</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftyenergylist')}}">Nifty Energy</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftyinfralist')}}">Nifty Infra</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/ind_niftyservicelist')}}">Nifty Service</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/EQUITY_L/18')}}">2018 Company</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/pennystock')}}">Penny Company</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updateKiteTable')}}">Intraday Company</a></li></ul> </div>
            <div class="col-lg-4" ><ul><li><a href="{{url('/updatetable/EQUITY_L')}}">All Company</a></li></ul> </div>
    </div>
    
@stop
