<?php

class KitetestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        // $calls = DB::table('calls')->get();
        // return View::make('stock.calllist')
        //     ->with('calls', $calls);
        $collection = collect(DB::table('kite_watch')->get());
    }

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function getRunningCall()
	{
        $rCall = DB::table('calls')
        ->where('status','=', 1)
        ->get();
       // print_r($rCall);
        return json_encode($rCall);
    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function updateMarketwatch()
	{
		// print_r($id);
		date_default_timezone_set('Asia/Kolkata');

		$input = Input::all();
		$c = array();
        foreach ($input['data'] as $k => $v) {
            unset($v['mode']);
            unset($v['token']);
            unset($v['isTradeable']);
            unset($v['closePrice']);
            unset($v['tickChange']);
			$sc = $this->sma($v['tradingsymbol'],$v);
			$trend = $this->isTrendChange($sc[0], $sc[1], $v['tradingsymbol']);
			if($trend)
				$c[] = $this->screenCall($v['tradingsymbol'], $v);
            if ($sc[0]) {
                $v['sma1'] = $sc[0];
                $v['sma2'] = $sc[1];
            }
            // Insert Into Table
            DB::table('back_test')->insert($v);
		}
		if($input['nifty'])
			$this->insertNifty($input['nifty']);
        return json_encode($c);
	}
	public function insertNifty($nifty)
	{
		// echo '<pre>'; print_r($nifty); exit();
		if (Session::get('nifty')) {
			$pCpoint = Session::get('nifty');
			$update['diff'] = ($nifty[3] - $pCpoint);
		}
		$update['point'] = $nifty[2];
		$update['cpoint'] = $nifty[3];
		$update['per'] = number_format(($nifty[3] /  ($nifty[2] + $nifty[3]))*100, 2);
		Session::put('nifty', $nifty[3]);
		DB::table('nifty')->insert($update);
	} 
	public function screenCall($script, $data)
	{
		$r = null;
		$calls = DB::table('intra_call')->where('nse','=', $script)->where('status','=', 0)->take(1)->get();
		if (isset($calls[0])) {
			$r = $this->closeCall($calls[0], $data);
		}
		else {
			//echo $breakout = $this->breakout($script, $data);
			$sdata = Session::get($script);
			$sTrend = null;
			if ($sdata['trend']) {
				$sTrend = $sdata['trend'];
			}
			//if ($breakout == 'Up') {
				if ($sTrend == "uptrend") {
					$r = $this->insIntraCall($script, $data['lastPrice'], $data['change'],'1','breakout10');
				}
			//}
			//else if($breakout == 'Down') {
			 else if($sTrend == "downtrend") {
					$r = $this->insIntraCall($script, $data['lastPrice'], $data['change'],'2','breakout10');
				}
			//}
		}
		return $r;
    }
	
	// public function breakout($callData, $data)
	// {
	// 	$ldate = date('Y-m-d');
	// 	$lastRec = DB::table('kite_watch')
	// 				->select('lastPrice')
	// 				->where('insert_on', '>',  $ldate.' 09:14:00')
	// 				->where('tradingsymbol', $data['tradingsymbol'])
	// 				->orderBy('id', 'DESC')
	// 				->take(10)
	// 				->get();
	// 	$max = max($lastRec);
	// 	$min = min($lastRec);
	// 	// echo "<pre>"; print_r($lastRec); 
	// 	// echo "<pre>"; print_r($max);
	// 	// echo "<pre>"; print_r($min);
	// 	//echo $max->lastPrice;
	// 	// exit;
	// 	if($data['lastPrice'] < $min->lastPrice)
	// 	{
	// 		return "Down";
	// 	} else if($data['lastPrice'] > $max->lastPrice)
	// 	{
	// 		return "Up";
	// 	}
	// 	return false;
	// }
	
	public function callWatch($callData, $data)
	{
		$target = 1;
		$stop = -1;
		if ($callData->call == 1) {
			$diff =  (float)$data['change'] -  (float)$callData->per;
		} else if ($callData->call == 2) {
			$diff = (float)$callData->per -  (float)$data['change'];
		}
		//echo $callData->nse;
		//echo  $callData->id."=>id".$callData->nse."=> Entry: ".$callData->per."=> CMP: ".$data['change']."=> Diff: $diff<br>";
		if ($diff >= $target) {
			if($data['diff'] < 0)
			{
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => 1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change']));
			}
		} else if ($diff <= $stop) {
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => -1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change']));
		}
	}

    public function insIntraCall($script, $price, $per, $call, $str)
	{
		$i['nse'] = $script;
        $i['price'] = $price;
        $i['per'] = $per;
		$i['call'] = $call;
		$i['strategy'] = $str;
		DB::table('intra_call')->insert($i);
		return $i;
	}

	public function closeCall($callData, $data)
	{
		$target = 1;
		$stop = -1;
		if ($callData->call == 1) {
			$diff =  (float)$data['change'] -  (float)$callData->per;
		} else if ($callData->call == 2) {
			$diff = (float)$callData->per -  (float)$data['change'];
		}
		if ($diff >= $target) {
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => 1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change']));
		} else if ($diff <= $stop) {
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => -1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change']));
		}

		// if ($callData->call == 1) {
		// 	$diff =  (float)$data['change'] -  (float)$callData->per;
		// } else if ($callData->call == 2) {
		// 	$diff = (float)$callData->per -  (float)$data['change'];
		// }
		// if ($diff > 0) {
		// 	$status = 1;
		// } else {
		// 	$status = -1;
		// }
		// DB::table('intra_call')
		// 	->where('id', $callData->id)
		// 	->update(array('status' => $status, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change']));
		return $callData;
	}
	public function sma($script, $data)
	{
	  $ldate = date('Y-m-d');
	  $sum = 0;
	  $i = 1;
	  $sma1 = 21;
	  $sma2 = 9;
	  $smaAvg2 = $smaAvg1 = null;
	  $his = DB::table('back_test')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00')
			// ->where('insert_on', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 MINUTE)'))
			->orderBy('id', 'DESC')
			->take($sma1)
            ->get();
            // array_unshift($a,"blue");
            // array_pop($a);
		foreach($his as $key => $values) {
			$sum += $values->lastPrice;
			if ($i == $sma2) {
				$smaSum = $sum;
				$smaAvg2 = round(($sum / $sma2), 2);
			}
			if ($i == $sma1) {
				$smaAvg1 = round(($sum / $sma1), 2);
			}
		$i++;
		}	
		return array($smaAvg1, $smaAvg2);
	}
	public function isTrendChange($smaAvg1, $smaAvg2, $script)
	{
		$sTrend = null;
		$sdata = Session::get($script);
		if ($sdata['trend']) {
			$sTrend = $sdata['trend'];
		}
		if ($sTrend == "uptrend" || !isset($sTrend)) {
			if ($smaAvg1 > $smaAvg2) {
				$sdata['trend'] = $sTrend = "downtrend";
				Session::put($script, $sdata);
				return true;
			}
		}
		if ($sTrend == "downtrend"  || !isset($sTrend)) {
			if ($smaAvg1 < $smaAvg2) {
				$sTrend = "uptrend";
				$sdata['trend'] = $sTrend;
				Session::put($script, $sdata);
				return true;
			}
		}
		return false;
	}
	public function insertIntraKite()
	{
		$input = Input::all();
		DB::table('kite_margin')->truncate();
		foreach ($input['data'] as $k => $v) {
			$script = explode(":",$v['Scrip']);
			$mis = rtrim($v['MIS Multiplier'],'x');
			$d['Multiplier'] = $mis;
			$d['Scrip'] = $script[0];
			DB::table('kite_margin')->insert($d);
		}
		return json_encode('Inserted Successfully');
	}
}
