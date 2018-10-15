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
		$lastDate = DB::table('csvdata')->select('TIMESTAMP')->distinct('TIMESTAMP')
		->take(1)->orderby('TIMESTAMP','DESC')->get();
		$lastDate = $lastDate[0]->TIMESTAMP;

		$pos = DB::table('csvdata')
				->select('SYMBOL as n', 'CLOSE as c', 'TIMESTAMP as t')
				->whereIn('SERIES', ['EQ', 'BE'])
				->where('TIMESTAMP',$lastDate)
				->orderby('CLOSE','DESC')
				// ->take(15)
				->get();
		$neg = DB::table('csvdata')
				->select('SYMBOL as s', 'CLOSE as p', 'TIMESTAMP as d')
				->whereIn('SERIES', ['EQ', 'BE'])
				->where('TIMESTAMP',$lastDate)
				->groupBy('SYMBOL')
				->orderby('CLOSE','ASC')
				// ->take(10)
				->get();

    $pWeek = date( 'Y-m-d', strtotime( $lastDate . ' -1 week' ) );

		$last5days = DB::table('csvdata')
						->select('SYMBOL', 'CLOSE', 'TIMESTAMP')
						->whereIn('SERIES', ['EQ', 'BE'])
						->where('TIMESTAMP', $pWeek)
						->orderBy('TIMESTAMP', 'DESC')
						->orderby('CLOSE','DESC')
						->get();
		// echo "<pre>"; print_r($last5days); exit();
		foreach($pos as $key => $v)
		{
			$comp = $v->n;
			foreach ($last5days as $ckey => $cv) {
			// echo "<br>---".$cv->SYMBOL;
				if ($comp == $cv->SYMBOL) {
					$v->cvalue = $cv->CLOSE;
					$v->per = $this->getPercentageChange($v->cvalue, $v->c);
					$data_per[$key] = $v->per;
				}
			}
			//   echo number_format(($per), 2);
			//   echo "<pre>"; print($per); exit();
		}
        array_multisort($data_per, SORT_DESC, $pos);
	    echo "<pre>";
		$top = array_slice($pos, 0, 5);
		array_multisort($data_per, SORT_ASC, $pos);
		$last = array_slice($pos, 0, 5);
		print_r($top); 
		print_r($last); 
		// arsort($pos);
		// print_r($pos); 
		exit;
		return View::make('dashboard.dashboard')->with('lastDate',$lastDate)->with('pos',$pos)->with('neg',$neg)->with('top',$top)->with('last',$last);
		 //return json_encode($stock);
	}

	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $oldNumber - $newNumber;
		return ($decreaseValue / $oldNumber) * 100;
	}
}