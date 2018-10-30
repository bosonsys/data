<?php

class KiteController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $calls = DB::table('calls')->get();
        return View::make('stock.calllist')
            ->with('calls', $calls);
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
			$insert['absoluteChange'] = $v['absoluteChange'];
			$insert['averagePrice'] = $v['averagePrice'];
			$insert['change'] = $v['change'];
			$insert['highPrice'] = $v['highPrice'];
			$insert['lastPrice'] = $v['lastPrice'];
			$insert['lastQuantity'] = $v['lastQuantity'];
			$insert['lowPrice'] = $v['lowPrice'];
			$insert['openPrice'] = $v['openPrice'];
			$insert['totalBuyQuantity'] = $v['totalBuyQuantity'];
			$insert['totalSellQuantity'] = $v['totalSellQuantity'];
			$insert['tradingsymbol'] = $v['tradingsymbol'];
			$insert['volume'] = $v['volume'];
			$insert['mHigh'] = $v['mHigh'];
			$insert['mLow'] = $v['mLow'];
			$sc = $this->sma($v['tradingsymbol'],$v);
			$primaryTrend = $this->getPrimaryTrend($v['tradingsymbol'], $v['lastPrice']);
			// exit;
			//echo $v['tradingsymbol']." - $primaryTrend <br>";
			$trend = $this->isTrendChange($sc[0], $sc[1], $v['tradingsymbol']);
			// $opencall = $this->autoclose();
			if($trend)
				$c[] = $this->screenCall($v['tradingsymbol'], $v);
            if ($sc[0]) {
                $insert['sma1'] = $sc[0];
				$insert['sma2'] = $sc[1];
				$insert['rsi'] = $sc[2];
				// echo "<pre>"; print_r($sc[0]);
				// print_r($sc[1]); print_r($sc[2]);
            }
            // Insert Into Table
            DB::table('kite_watch')->insert($insert);
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
	public function screenCall($script, $data, $i=null)
	{
		$r = null;
		$calls = DB::table('intra_call')->where('nse','=', $script)->where('status','=', 0)->take(1)->get();
		
		if (isset($calls[0])) {
			$r = $this->closeCall($calls[0], $data, $i);
		}
		else {
			//echo $breakout = $this->breakout($script, $data);
			$sdata = Session::get($script);
			$sTrend = null;
			if ($sdata['trend']) {
				$sTrend = $sdata['trend'];
			}
			$primaryTrend = $this->getPrimaryTrend($script, $data['lastPrice']);

			//if ($breakout == 'Up') {
				if ($sTrend == "uptrend") {
					if ($primaryTrend == "Uptrend")
						$r = $this->insIntraCall($script, $data['lastPrice'], $data['change'],'1',$primaryTrend, $i);
				// echo '<pre>'; print_r($r);
				// exit;
				}
			//}
			//else if($breakout == 'Down') {
			 else if($sTrend == "downtrend") {
					 if ($primaryTrend == "downtrend")
					 //$t = $this->getPrimaryTrend($script, $cPrice);
						$r = $this->insIntraCall($script, $data['lastPrice'], $data['change'],'2',$data['absoluteChange'], $i);
					 if ($primaryTrend == "Downtrend")
						$r = $this->insIntraCall($script, $data['lastPrice'], $data['change'],'2',$primaryTrend, $i);
				}
			//}
		}
		return $r;
    }
	
	
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

    public function insIntraCall($script, $price, $per, $call, $str, $in=null)
	{
		$i['nse'] = $script;
        $i['price'] = $price;
        $i['per'] = $per;
		$i['call'] = $call;
		$i['strategy'] = $str;
		if ($in)
			$i['inserted_on'] = $in;
		DB::table('intra_call')->insert($i);
		return $i;
	}

	public function closeCall($callData, $data, $u=null)
	{
		// print_r($data);
		if (!$u) {
			$u = date('Y-m-d H:i:s');
		}
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
				->update(array('status' => 1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change'], 'updated_on' => $u));
		} else if ($diff <= $stop) {
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => -1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change'], 'updated_on' => $u));
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
	  $rsi = 14;
	  $r = NULL;
	  $smaAvg2 = $smaAvg1 = null;
	  $his = DB::table('kite_watch')
			->select('lastPrice')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00')
			// ->where('insert_on', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 MINUTE)'))
			->orderBy('id', 'DESC')
			->take($sma1)
			->get();
			$t =  new RecursiveIteratorIterator(new RecursiveArrayIterator($his));
			$s = iterator_to_array($t, false);
		// print_r($s);
			$r = trader_rsi($s, $rsi);
			$s1 = trader_sma($s, $sma1);
			$s2 = trader_sma($s, $sma2);
			// echo "<pre>"; print_r($s2);
			// print_r($s1); print_r($r);
			// echo "<pre>"; print_r($r); exit;
            // array_unshift($a,"blue");
            // array_pop($a);
		// foreach($his as $key => $values) {
		// 	$sum += $values->lastPrice;
		// 	if ($i == $sma2) {
		// 		$smaSum = $sum;
		// 		$smaAvg2 = round(($sum / $sma2), 2);
		// 	}
		// 	if ($i == $sma1) {
		// 		$smaAvg1 = round(($sum / $sma1), 2);
		// 	}
		// 	//echo "<pre>"; print_r($smaAvg1); print_r($smaAvg2); exit;
		// $i++;
		// }	
		return array($s1[($sma1 - 1)], $s2[($sma1 - 1)], $r[($sma1 - 1)]);
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

	public function getPrimaryTrend($script, $cPrice)
	{
		$sum = 0;
		$sma3 = 50;
		$ldate = date('Y-m-d');
		$last50 = DB::table('kite_watch')
			->select('lastPrice')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00')
			->orderBy('id', 'DESC')
			//->orderBy('insert_on')
			->take($sma3)
			->get();
			$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($last50));
			$l = iterator_to_array($it, false);
			if (count($l) == $sma3) {
			$sma50 = trader_sma($l, $sma3);
			// echo $sma50[($sma3 - 1)];
			// echo $cPrice;
			// exit;
			// print_r($sma50);
			// exit;
			//print_r($last50); exit;
			//foreach($last50 as $key => $values)
			// 	$sum += $values->lastPrice;
			// $smaAvg3 = round(($sum / $sma3), 2);
			//if($smaAvg3 > $values->lastPrice)
			if($sma50[($sma3 - 1)] > $cPrice)
			{
				return 'Downtrend';
			}
			elseif($sma50[($sma3 - 1)] < $cPrice)
			{
				return 'Uptrend';
			}
		}
		return 'Range';
	}
	public function autoclose()
	{
		$ldate = date('Y-m-d');
	    $opencalls = DB::table('intra_call')
				//->where('nse','=', $script)
				->where('inserted_on', '>',  $ldate.' 09:20:00')
				->where('status','=', 0)
				->get();
		
				echo "<pre>"; 
				// print_r($opencalls);
				foreach($opencalls as $c)
				{
				    $comp = DB::table('kite_watch')
						->where('tradingsymbol','=', $c->nse)
						->where('insert_on', '<',  $ldate.' 15:20:00')
						->take(1)
						->orderBy('id','DESC')
						->get();
					$comp = $comp[0];
					print_r($c);
					print_r($comp);

                //   $call = $c->
				$dif = $comp->lastPrice - $c->price;
				// print_r($dif);
				if($dif > 0)
				{
					DB::table('intra_call')
						->where('id', $c->id)
						->update(array('status' => 1, 'cPrice' => $comp->lastPrice, 'cPer' => $comp->change, 'updated_on' => $comp->insert_on));
				}
				else
				{
					DB::table('intra_call')
				      ->where('id', $c->id)
				      ->update(array('status' => 1, 'cPrice' => $comp->lastPrice, 'cPer' => $comp->change, 'updated_on' => $comp->insert_on));
				}
				// exit;
			}
		}
}