<?php

class BreakoutController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
		//echo "asdfasdfasD";

        return View::make('breakout.5days');
	}

	public function days5()
	{
		$lists = DB::table('csvdata')->select('TIMESTAMP')->distinct('TIMESTAMP')
		->take(5)->orderby('TIMESTAMP','DESC')->get();
        $arr = array();
        foreach ($lists as $key => $list) {
            array_push($arr,$list->TIMESTAMP);
        }
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
	}

	
public function last5days($nse)
	{   //$array= array_merge($day, $stock);
		
		$day = DB::table('csvdata')
		->select('TIMESTAMP','SYMBOL','OPEN','HIGH','LOW','CLOSE')
		->where('SERIES','EQ')
		->where('SYMBOL',$nse)
		->take(1)->orderby('TIMESTAMP','DESC')->get();
		echo "<pre>"; print_r($day);
		$d = $day[0];
		$per = (($d->HIGH - $d->OPEN)/$d->OPEN)*100;
		$percent = (($d->LOW - $d->OPEN)/$d->OPEN)*100;
		$p = $per;
		$c = $percent;
		echo $p."<br>";
		echo $c;
		$lists = DB::table('csvdata')->select('TIMESTAMP')->distinct('TIMESTAMP')
		->take(5)->orderby('TIMESTAMP','DESC')->get();
        $arr = array();
        foreach ($lists as $key => $list) {
            array_push($arr,$list->TIMESTAMP);
		}
		
		$stock = DB::table('csvdata')//->select('SYMBOL','HIGH','LOW')
		->select(DB::raw('SYMBOL, max(HIGH) as maxHIGH, min(LOW) as minLOW'))
		->where('SERIES','EQ')
		->where('SYMBOL',$nse)
		->whereIn('TIMESTAMP',$arr)
		->orderBy('SYMBOL')
		->take(5)
        ->get();
        echo "<pre>"; print_r($stock); exit();
        return View::make('breakout.last5days')->with('lists', $arr)->with('nse', $nse);
         //return json_encode($stock);
	}

}
