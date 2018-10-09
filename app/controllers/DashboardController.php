<?php

class DashboardController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
	  return View::make('dashboard.lastday');
	}
    // public function stock($nse)
	// {
    //     $stock = DB::table('csvdata')->select('OPEN as o', 'CLOSE as c', 'HIGH as h', 'LOW as l', 'TIMESTAMP as t', '')
    //     ->where('SYMBOL',$nse)
    //     ->where('SERIES','EQ')->get();
    //    return View::make('stock.view')->with('d', $stock)->with('nse', $nse);
    //     //return json_encode($stock);
    // }
    
    public function lastday()
	{
		$lists = DB::table('csvdata')->select('TIMESTAMP')->distinct('TIMESTAMP')
		->take(1)->orderby('TIMESTAMP','DESC')->get();
        $arr = array();
        foreach ($lists as $key => $list) {
            array_push($arr,$list->TIMESTAMP);
        }
		$stock = DB::table('csvdata')
		->select('SYMBOL as n', 'CLOSEP as c', 'TIMESTAMP as t', 'SERIES as s')
        ->whereIn('SERIES', ['EQ', 'BE'])
		->whereIn('TIMESTAMP',$arr)
		->groupBy('SYMBOL')
		->orderby('CLOSEP','DESC')
		->take(10)
        ->get();
    //echo "<pre>"; print_r($stock); exit();
        return View::make('dashboard.lastday')->with('lists', $arr)->with('stock',$stock);
         //return json_encode($stock);
	}
    

}