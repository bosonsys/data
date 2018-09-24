<?php

class StockController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index($nse)
	{
	  $cname = DB::table('company')->select('symbol')
			  ->get();
			   $arr = array();
        foreach ($cname as $key => $value) {
            array_push($arr, $value->symbol);
		}
		
			  //echo "<pre>"; print_r($cname); exit();
			  return View::make('stock.view')->with('sname', $nse)->with('cname', $arr);
		// return View::make('stock.view')->with('sname', 'Stock Name');
	}
    public function stock($nse)
	{
        $stock = DB::table('csvdata')->select('OPEN as o', 'CLOSE as c', 'HIGH as h', 'LOW as l', 'TIMESTAMP as t', '')
        ->where('SYMBOL',$nse)
        ->where('SERIES','EQ')->get();
       return View::make('stock.view')->with('d', $stock)->with('nse', $nse);
        //return json_encode($stock);
	}
    

}
