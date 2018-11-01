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
		$date = date( 'Y-m-d');
		$dates = DB::table('csvdata')->distinct('TIMESTAMP')->take(1)->orderBy('TIMESTAMP', 'desc')->get(array('TIMESTAMP'));
		//$dates = $dates[0];
		$pWeek = date( 'Y-m-d', strtotime( $date . ' -1 week' ) );
		$last5days = DB::table('csvdata')->where('TIMESTAMP', '<', $pWeek)->orderBy('TIMESTAMP', 'desc')->get();
		
		$pMonth = date( 'Y-m-d', strtotime( $date . ' -1 month' ) );
		$lastmonth = DB::table('csvdata')->where('TIMESTAMP', '<', $pMonth)->orderBy('TIMESTAMP', 'desc')->get();
		//echo "<pre>"; print_r($lastmonth); exit;
		$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($dates));
		$l = iterator_to_array($it, false);
		$lastday = $l[0];
		// $lastDate = DB::table('csvdata')->select('TIMESTAMP')->distinct('TIMESTAMP')
		// ->take(1)->orderby('TIMESTAMP','DESC')->get();
		// $lastDate = $lastDate[0]->TIMESTAMP;
		//$p3Month = date( 'Y-m-d', strtotime( $date . ' -3 month' ) );
		//print_r($p3Month); exit;
		$cDate = DB::table('csvdata')
				->select('SYMBOL as n', 'CLOSEP as c', 'CLOSE as cl', 'TIMESTAMP as t')
				->whereIn('SERIES', ['EQ', 'BE'])
				->where('TIMESTAMP',$lastday)
				->orderby('TIMESTAMP','DESC')
				->orderby('CLOSEP','DESC')
				// ->take(20)
				->get();
		$positive = array_slice($cDate, 0, 10); 
		$negative = array_slice($cDate, -10);
	    sort($negative);
       //echo "<pre>"; print_r($negative); exit;
		$arr1 = $this->getTopList($cDate, $pWeek, 'asc');
		$arr2 = $this->getTopList($cDate, $pMonth);
	
		
	return View::make('dashboard.dashboard')->with('lastday',$lastday)->with('positive',$positive)->with('negative',$negative)->with('pos',$cDate)->with('neg',$cDate)
		->with('top',$arr1['top'])->with('last',$arr1['last'])->with('tMonth', $arr2['top'])->with('lMonth', $arr2['last']);
		 //return json_encode($stock);
	}
	function getTopList($cDate, $date)
	{
		$data = $this->compData($date);
		$compList = array();
        foreach($cDate as $key => $v)
		{ 
			$comp = $v->n;
			//print_r($v);
			foreach ($data as $ckey => $cv) {
				if ($comp == $cv->SYMBOL) {
					//print_r($cv); 
				$v->cvalue = $cv->CLOSE; 
				$v->per = $this->getPercentageChange($v->cl, $v->cvalue);
				$data_per[$key] = $v->per;
				array_push($compList, $v);
				//echo "<pre>"; print_r($compList); exit;
				// echo $comp. " = ".$v->cvalue." | $cv->SYMBOL = ".$v->c."<br>";
								// exit;
//print_r($compList); exit;
				}
			}
		}
		//echo $data_per;
        array_multisort($data_per, SORT_DESC, $compList);
		$top = array_slice($compList, 0, 5);
		array_multisort($data_per, SORT_ASC, $compList);
		$last = array_slice($compList, 0, 5);
		//echo "<pre>"; print_r($top); print_r($last); exit;
		return array('top' => $top , 'last' => $last );
	}
	function compData($date)
	{
		// $date = date('Y-m-d');
		//echo $date; exit;
		return $d =  DB::table('csvdata')
						->select('SYMBOL', 'CLOSE', 'TIMESTAMP')
						->whereIn('SERIES', ['EQ', 'BE'])
						->where('TIMESTAMP', $date)
						->orderby('TIMESTAMP','DESC')
						->orderby('CLOSE','DESC')
						->get();
	}
	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $oldNumber - $newNumber;
		return ($decreaseValue / $oldNumber) * 100;
	}
}