<?php

class WatchlistController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        // $calls = DB::table('calls')->get();
        // return View::make('stock.calllist')
        //     ->with('calls', $calls);
        //$collection = collect(DB::table('kite_watch')->get());
	}
	public function watchList($nse)
	{
	$list = DB::table('kite_margin')->select('Scrip','Multiplier')
				->join('cnx500', 'kite_margin.Scrip', '=', 'cnx500.symbol')
				->where('kite_margin.Scrip',$nse)
				->get();
	//echo "<pre>"; print_r($list); exit;	
	
	return json_encode($list);
	}
}