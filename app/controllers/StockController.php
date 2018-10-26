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
				$cCompany['mmbURL'] = ''; 
				$cCompany['edelURL'] = ''; 
				$cCompany['name'] = $nse; 
        foreach ($cname as $key => $value) {
			array_push($arr, $value->symbol);
			if ($value->symbol == $nse) {
				$cCompany['mmbURL'] = $value->mmbURL; 
				$cCompany['edelURL'] = $value->URL; 
			}
		}		
		return View::make('stock.view')->with(array('cCompany' => $cCompany, 'cname' => $arr));
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
		$lastday = date('Y-m-d');
		$lastday = date( 'Y-m-d', strtotime( $lastday . ' -1 day' ) );
		
		$last = DB::table('csvdata')->select('SYMBOL', 'HIGH', 'LOW', 'LAST', 'TIMESTAMP')
				->join('kite_margin', 'csvdata.SYMBOL', '=', 'kite_margin.Scrip')
				->where('TIMESTAMP',$lastday)
				 ->where('csvdata.series','EQ')
				->get();
				
				$lastval = array();
				foreach($last as $key => $v)
				{
					$high = $v->HIGH;
					$low = $v->LOW;
				$v->per = $this->getPercentageChange($high, $low);
				$data_per[$key] = $v->per;
				array_push($lastval, $v);
				//echo "<pre>"; print_r($lastval); exit;
			}

		array_multisort($data_per, SORT_DESC, SORT_NUMERIC, $lastval);
		$top = array_slice($lastval, 0, 10);

		array_multisort($data_per, SORT_ASC, SORT_NUMERIC, $lastval);
		$last = array_slice($lastval, 0, 10);

		$lasttop = $this->getlastDay($lastday, 'desc');
		$lastlosers = $this->getlastDay($lastday, 'asc');
		return View::make('stock.intra-suggest')->with(array('top'=>$top, 'last'=>$last))->with('lasttop',$lasttop)->with('lastlosers',$lastlosers);
	}
	function getlastDay($lastday, $order)
	{
		return DB::table('csvdata')->select('SYMBOL', 'CLOSEP', 'TIMESTAMP')
				->join('kite_margin', 'csvdata.SYMBOL', '=', 'kite_margin.Scrip')
				->where('TIMESTAMP',$lastday)
				->where('csvdata.series','EQ')
				->take(10)
				->orderBy('csvdata.CLOSEP', $order)
				->get();
	}

	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $newNumber -  $oldNumber;
		return ($decreaseValue / $oldNumber) * 100;
	}
}
