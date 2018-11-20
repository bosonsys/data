<?php

class DashboardController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
	  return View::make('dashboard.dashboard');
	}
    // public function stock($nse)
	// {
    //     $stock = DB::table('csvdata')->select('OPEN as o', 'CLOSE as c', 'HIGH as h', 'LOW as l', 'TIMESTAMP as t', '')
    //     ->where('SYMBOL',$nse)
    //     ->where('SERIES','EQ')->get();
    //    return View::make('stock.view')->with('d', $stock)->with('nse', $nse);
    //     //return json_encode($stock);
    // }
    
    public function dashboard()
	{
		$date = date( 'Y-m-d');
		$dates = DB::table('csvdata')->distinct('TIMESTAMP')->orderBy('TIMESTAMP', 'desc')->take(1)->get(array('TIMESTAMP'));
		//$dates = $dates[0];
		$pWeek = date( 'Y-m-d', strtotime( $date . ' -1 week' ) );
		
		//$yesterday = date( 'Y-m-d', strtotime( $date . ' +2 day' ) );
		$pMonth = date( 'Y-m-d', strtotime( $date . ' -1 month' ) );
		$p3Month = date( 'Y-m-d', strtotime( $date . ' -3 month' ) );
		//$p6Month = date( 'Y-m-d', strtotime( $date . ' -6 month' ) );
		// echo "<pre>"; print_r($p6Month); exit;
		
		//$last3month = DB::table('csvdata')->distinct('TIMESTAMP')->where('TIMESTAMP', '<=', $p3Month)->orderBy('TIMESTAMP', 'desc')->get();
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
	    ksort($cDate);
		$negative = array_reverse(array_slice($cDate, -10, 10, true));
	   
	
		$arr1 = $this->getTopList($cDate, $pWeek);
		//echo "<pre>"; print_r($arr1); exit;
		$arr2 = $this->getTopList($cDate, $pMonth);
		//echo "<pre>"; print_r($arr2); exit;
	    $arr3 = $this->getTopList($cDate, $p3Month);
		
	return View::make('dashboard.dashboard')->with('lastday',$lastday)->with('positive',$positive)->with('negative',$negative)
		->with('top',$arr1['top'])->with('last',$arr1['last'])->with('tMonth',$arr2['top'])->with('lMonth',$arr2['last'])
		->with('t3Month', $arr3['top'])->with('l3Month', $arr3['last']);
		 //return json_encode($stock);
	}
	function getTopList($cDate, $d)
	{
		$date = $this->getdate($d);
		$data = $this->compData($date);
		$compList = array();
		$data_per = array();
        foreach($cDate as $key => $v)
		{ 
			$comp = $v->n;
			foreach ($data as $ckey => $cv) {
				if ($comp == $cv->SYMBOL) {
					//echo "<pre>"; print_r($comp);
				//$v->cval = $cv->CLOSEP; 
				$v->cvalue = $cv->CLOSE; 
				$v->per = $this->getPercentageChange($v->cl, $v->cvalue);
				$data_per[$key] = $v->per;
				array_push($compList, $v);
				// echo $comp. " = ".$v->cvalue." | $cv->SYMBOL = ".$v->c."<br>";
				//  				 exit;
                 //print_r($compList); exit;
				}
			}
		}
		//echo $data_per;
		// echo "<pre>";
		// print_r($data_per);
		array_multisort($data_per, SORT_DESC, SORT_NUMERIC, $compList);
		$top = array_slice($compList, 0, 5);
		
		array_multisort($data_per, SORT_ASC, SORT_NUMERIC, $compList);
		$last = array_slice($compList, 0, 5);
		//$top = array_slice($compList, 0, 5, true);

		//$last = array_slice($compList, 0, 5, true);
		//echo "<pre>"; print_r($top); print_r($last); exit;
		unset($data_per, $compList);
		return array('top' => $top , 'last' => $last );
	}

	function compData($date)
	{
		// $date = date('Y-m-d');
		//echo $date; exit;
		return DB::table('csvdata')
						->select('SYMBOL', 'CLOSE', 'TIMESTAMP')
						->whereIn('SERIES', ['EQ', 'BE'])
						->where('TIMESTAMP', $date)
						->orderby('TIMESTAMP','DESC')
						->orderby('CLOSE','DESC')
						->get();
	}
	public function getdate($d)
	{
		// echo $d;
		$date = DB::table('csvdata')->distinct('TIMESTAMP')->where('TIMESTAMP', '<=', $d)->take(1)->orderBy('TIMESTAMP', 'desc')->get(array('TIMESTAMP'));
		// print_r($date);
		if (isset($date[0]->TIMESTAMP)) {
			return $date[0]->TIMESTAMP;
		}
		return false;
	}
	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $oldNumber - $newNumber;
		return ($decreaseValue / $oldNumber) * 100;
	}
}