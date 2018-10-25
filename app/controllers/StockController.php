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
		$lastday = date( 'Y-m-d', strtotime( $lastDate . ' -1 day' ) );
		
		$lasttop = DB::table('csvdata')->select('SYMBOL', 'HIGH', 'LOW', 'LAST', 'TIMESTAMP')
				->join('kite_margin', 'csvdata.SYMBOL', '=', 'kite_margin.Scrip')
				->where('TIMESTAMP',$lastday)
				 ->where('csvdata.series','EQ')
				->get();
				
				$lastval = array();
				foreach($lasttop as $key => $v)
				{
					
					$high = $v->HIGH;
					$low = $v->LOW;
				$v->per = $this->getPercentageChange($high, $low);
				$data_per[$key] = $v->per;
				array_push($lastval, $v);
				//echo "<pre>"; print_r($lastval); exit;
			}

		array_multisort($data_per, SORT_DESC, $lastval);
		$top = array_slice($lastval, 0, 10);

		array_multisort($data_per, SORT_ASC, $lastval);
		$last = array_slice($lastval, 0, 10);
		//return array('top' => $top , 'last' => $last );
		// echo "<pre>";
		// print_r($top);
		// print_r($last); exit;
		return View::make('stock.intra-suggest')->with(array('top'=>$top, 'last'=>$last));
	}

	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $oldNumber - $newNumber;
		return ($decreaseValue / $oldNumber) * 100;
	}
}
