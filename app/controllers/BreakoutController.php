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

        return View::make('breakout.last5days');
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
       //echo "<pre>"; print_r($stock); exit();
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
		//echo "<pre>"; print_r($day);
		$arr1 = array();
		foreach($day as $key => $value)
		{
			$open = $value->OPEN;
			$close = $value->CLOSE;
			$high = $value->HIGH;
			$low = $value->LOW;
			array_push($arr1, $value);
			//echo "<pre>"; print_r($arr1); exit;
		}
		$d = $day[0];
		$per = (($d->HIGH - $d->OPEN)/$d->OPEN)*100;
		$percent = (($d->LOW - $d->OPEN)/$d->OPEN)*100;
		$p = $per;
		$c = $percent;
		
		// echo $p."<br>";
		// echo $c;
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
		//echo "<pre>"; print_r($stock); 
		// $data = $this->getwatchData($nse, 'desc');
		// $data1 = $this->getwatchData($nse, 'asc');
		// 	$max = max($data->change);
		// 	$min = min($data);
		//echo "<pre>"; print_r($data); print_r($data1); exit;
		//  echo "<pre>"; print_r($max); print_r($min);
		 $r = array();
		 foreach($stock as $key => $v)
		 {
			 $symbol = $v->SYMBOL;
			 $maxhigh = $v->maxHIGH;
			 $minlow = $v->minLOW;
			 $data = $this->getwatchData($nse, 'desc');
			 //$data1 = $this->getwatchData($nse, 'asc');
			 //$d = $data[0];
			//  $data_per[$key] = $v->data;
			//  $data_per[$key] = $v->data1;
			//$value = array_merge($v->data, $v->data1);
			array_push($r, $v);
			// echo "<pre>"; print_r($data);
			// print_r($data1); exit;
		 }

		 $result = array('nse' => $v->SYMBOL,
		            'absolutechange' => $data[0]->absoluteChange, 
                    'maxHIGH'=>$v->maxHIGH,
                    'minLOW'=>$v->minLOW,
					'openprice' => $data[0]->openPrice,
					'lastprice' => $data[0]->lastPrice,
					'highPrice' => $data[0]->highPrice,
					'lowPrice' => $data[0]->lowPrice,
					'changedesc' => $data[0]->change,
					'open' => $open,
					'close' => $close,
					'high' => $high,
					'low' => $low,
					'perhigh' => $per,
					'perlow' => $percent,
					'insert_on' => $data[0]->insert_on
				);
				echo "<pre>"; print_r($result); exit;

        return View::make('breakout.last5days')->with('lists', $arr)->with('nse', $nse);
         //return json_encode($stock);
	}
	function getwatchData($nse, $order)
	{
		 return DB::table('kite_watch')
		->select('tradingsymbol','openPrice','lastPrice','absoluteChange','change','highPrice','lowPrice','insert_on')
		->where('tradingsymbol',$nse)
		->take(1)
		->orderBy('insert_on',$order)
		->get();

		// if($data1['change'])
		// {
		// 	return "Down";
		// } else if($data['change'])
		// {
		// 	return "Up";
		// }
	}


}
