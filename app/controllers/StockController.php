<?php

class StockController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
        return View::make('stock.view');
	}
    public function stock($nse)
	{
        $stock = DB::table('csvdata')->select('OPEN as o', 'CLOSE as c', 'HIGH as h', 'LOW as l', 'TIMESTAMP as t')
        ->where('SYMBOL',$nse)
        ->where('SERIES','EQ')->get();
        return View::make('stock.view')->with('d', $stock)->with('nse', $nse);
       // return json_encode($stock);
	}

	/*public function days5()
	{
		$lists = DB::table('csvdata')->select('TIMESTAMP')->distinct('TIMESTAMP')
		->take(5)->orderby('TIMESTAMP','DESC')->get();
        $arr = array();
        foreach ($lists as $key => $list) {
            array_push($arr,$list->TIMESTAMP);
        }
	//echo "<pre>"; print_r($arr); exit();
		//$stock = csvdata::whereIn('TIMESTAMP')->get();
		$stock = DB::table('csvdata')
		// ->select('OPEN as o', 'CLOSE as c', 'HIGH as h', 'LOW as l', 'TIMESTAMP as t')
		->select(DB::raw('SYMBOL, max(HIGH) as maxHIGH' ))
		->where('SERIES','EQ')
		->whereIn('TIMESTAMP',$arr)
		->groupBy('SYMBOL')
		// ->orderby('TIMESTAMP','DESC')
		->take(20)
        ->get();
        echo "<pre>"; print_r($stock); exit();
        return View::make('breakout.5days')->with('lists', $arr);
         //return json_encode($stock);
	}*/

}
