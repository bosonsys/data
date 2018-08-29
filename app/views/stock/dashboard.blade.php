@extends('layout.index')
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
            <div class="col-lg-4"><a href="{{url('/updatetable/company')}}">Load All Company</a> </div>
            <div class="col-lg-4"><a href="{{url('/updatetable/cnx500')}}">Load CNX 500</a> </div>
            <div class="col-lg-4"><a href="{{url('/updatetable/cnx200')}}">Load CNX 200</a> </div>
            <div class="col-lg-4"><a href="{{url('/updatetable/cnx100')}}">Load CNX 100</a> </div>
            <div class="col-lg-4"><a href="{{url('/updatetable/niftymidcap50')}}">Load Nifty Midcap 50</a> </div>
            <div class="col-lg-4"><a href="{{url('/updatetable/intraday_edel')}}">Load Intraday Company</a> </div>
    </div>
@stop
