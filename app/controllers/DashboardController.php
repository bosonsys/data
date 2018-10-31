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
		$dates = $dates[0];
		$pWeek = date( 'Y-m-d', strtotime( $date . ' -1 week' ) );
		$prevdate = DB::table('csvdata')->where('TIMESTAMP', '<=', $pWeek)->orderBy('TIMESTAMP', 'desc')->take(5)->get();

		//echo "<pre>"; print_r($prevdate); exit;
		$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($dates));
		$l = iterator_to_array($it, false);
		$lastday = $l[0];
		// $lastDate = DB::table('csvdata')->select('TIMESTAMP')->distinct('TIMESTAMP')
		// ->take(1)->orderby('TIMESTAMP','DESC')->get();
		// $lastDate = $lastDate[0]->TIMESTAMP;

		$cDate = DB::table('csvdata')
				->select('SYMBOL as n', 'CLOSEP as c', 'CLOSE as cl', 'TIMESTAMP as t')
				->whereIn('SERIES', ['EQ', 'BE'])
				->where('TIMESTAMP',$lastday)
				->orderby('CLOSEP','DESC')
				// ->take(20)
				->get();
		$positive = array_slice($cDate, 0, 10); 
		$negative = array_slice($cDate, -10);
			//echo "<pre>"; print_r($data); exit;
		// $neg = DB::table('csvdata')
		// 		->select('SYMBOL as n', 'CLOSEP as c', 'TIMESTAMP as t')
		// 		->whereIn('SERIES', ['EQ', 'BE'])
		// 		->where('TIMESTAMP',$lastday)
		// 		->orderby('CLOSEP','ASC')
		// 		//->take(10)
		// 		->get();
			//$negative = array_slice($neg, 0, 10);	
	$pWeek = date( 'Y-m-d', strtotime( $lastday . ' -1 week' ) );
	//$tomorrow = date( 'Y-m-d', strtotime( $lastday . ' -3 day' ) );
	$pMonth = date( 'Y-m-d', strtotime( $lastday . ' -1 month' ) );
	//echo $pMonth; exit;
	//$tomorrow = '2018-09-25'; 
	$arr1 = $this->getTopList($cDate, $pWeek);
	$arr2 = $this->getTopList($cDate, $pMonth);
	// if($arr2['top'] == 0)
	// {
	// 	return false;
	// } elseif($arr2['top'] != 0)
	// {
	// 	echo $arr2 = $this->getTopList($cDate, $pMonth);	
	// }
	//echo "<pre>"; print_r($arr2['top']); exit;

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
			// print_r($v);
			foreach ($data as $ckey => $cv) {
			if ($comp == $cv->SYMBOL) {
				// print_r($cv);
				$v->cvalue = $cv->CLOSE; 
				$v->per = $this->getPercentageChange($v->cl, $v->cvalue);
				$data_per[$key] = $v->per;
				array_push($compList, $v);
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