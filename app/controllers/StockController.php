<?php

class StockController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index($nse)
	{
	  $cname = DB::table('company')->select('id','URL','symbol','mmbURL')
			  ->get();
			   $arr = array();
        foreach ($cname as $key => $value) {
			array_push($arr, $value->symbol);
			if ($value->symbol == $nse) {
				$cCompany['mmbURL'] = $value->mmbURL; 
				$cCompany['edelURL'] = $value->URL; 
				$cCompany['name'] = $value->symbol; 
			}
		}
		
			  //echo "<pre>"; print_r($cname); exit();
		return View::make('stock.view')->with(array('cCompany' => $cCompany, 'cname' => $arr));
		// return View::make('stock.view')->with('sname', 'Stock Name');
	}
    public function stock($nse)
	{
        $stock = DB::table('csvdata')->select('OPEN as o',  'HIGH as h', 'CLOSE as c', 'LOW as l', 'TIMESTAMP as t')
        ->where('SYMBOL',$nse)
		->where('SERIES','EQ')
		->orderBy('TIMESTAMP')
		->get();
       return View::make('stock.view')->with('d', $stock)->with('nse', $nse);
        //return json_encode($stock);
	}
	public function lastday()
	{
		$lastDate = date('Y-m-d');
		//$lastday = date( 'Y-m-d', strtotime( $lastDate . ' -1 day' ) );
		
		$lasttop = DB::table('csvdata')->select('SYMBOL', 'HIGH', 'LOW', 'LAST', 'TIMESTAMP')
				->join('kite_margin', 'csvdata.SYMBOL', '=', 'kite_margin.Scrip')
				->where('TIMESTAMP',$lastDate)
				->take(10)
				->get();
		echo "<pre>"; print_r($lasttop); exit;
	}

}
