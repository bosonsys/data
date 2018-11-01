<?php

class KiteController extends \BaseController {

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
            if ($sc[0]) {
                $insert['sma1'] = $sc[0];
				$insert['sma2'] = $sc[1];
				$insert['rsi'] = $sc[2];
				$this->callWatch($insert);
			}
            // Insert Into Table
            DB::table('kite_watch')->insert($insert);
		}
		if($input['nifty'])
			$this->insertNifty($input['nifty']);
        return json_encode($c);
	}

	public function callWatch($data, $time = NULL, $sma50 = NULL)
	{
		$calls = DB::table('intra_call')->where('nse','=', $data['tradingsymbol'])->where('status','=', 0)->take(1)->get();
		if (isset($calls[0])) {
			$r = $this->closeCall($calls[0], $data, $time);
		}
		else {
			$sma1 = $sma50?$sma50:$data['sma1'];
			$trend = $this->isTrendChange($sma1, $data['sma2'], $data['tradingsymbol']);
			if($trend)
				$c[] = $this->callEnter($data['tradingsymbol'], $data, $time);
		}
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

	public function callEnter($script, $data, $i=null)
	{
		echo "<br>Entry". $i;
		$r = null;
		$sdata = Session::get($script);
		$sTrend = null;
		if ($sdata['trend']) {
			$sTrend = $sdata['trend'];
		}
		$primaryTrend = $this->getPrimaryTrend($script, $data['lastPrice'], $i);
		if ($sTrend == "uptrend") {
			if ($primaryTrend == "Uptrend")
				$r = $this->insIntraCall($script, $data['lastPrice'], $data['change'],'1',$sTrend, $i);
		}
		else if($sTrend == "downtrend") {
			 if ($primaryTrend == "Downtrend")
				$r = $this->insIntraCall($script, $data['lastPrice'], $data['change'],'2',$sTrend, $i);
		}
		return $r;
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
		// echo $u."|".$diff."<br>";
		if ($diff >= $target) {
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => 1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change'], 'updated_on' => $u));
		} else if ($diff <= $stop) {
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => -1, 'cPrice' => $data['lastPrice'], 'cPer' => $data['change'], 'updated_on' => $u));
		}
		return $callData;
		//echo "<pre>"; print_r($diff); print_r($callData);
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
		if(count($s) > 20){
			$r = trader_rsi($s, $rsi);
			$s1 = trader_sma($s, $sma1);
			$s2 = trader_sma($s, $sma2);
			return array($s1[($sma1 - 1)], $s2[($sma1 - 1)], $r[($sma1 - 1)]);
		}
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

	public function getPrimaryTrend($script, $cPrice, $time = NULL)
	{
		$sum = 0;
		$sma3 = 100;
		$ldate = date('Y-m-d');
		$last50 = DB::table('kite_watch')
			->select('lastPrice')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00')
			->orderBy('id', 'DESC')
			//->orderBy('insert_on')
			->take($sma3);
			if ($time) {
				$last50 = $last50->where('insert_on', '<',  $time);
			}
			$last50 = $last50->get();
			$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($last50));
			$l = iterator_to_array($it, false);
			if (count($l) == $sma3) {
			$sma50 = trader_sma($l, $sma3);
			if($sma50[($sma3 - 1)] > $cPrice)
			{
				return 'Downtrend';
			}
			elseif($sma50[($sma3 - 1)] < $cPrice)
			{
				return 'Uptrend';
			}
		} else {
			return NULL;
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
					// print_r($c);
					// print_r($comp);

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
				      ->update(array('status' => -1, 'cPrice' => $comp->lastPrice, 'cPer' => $comp->change, 'updated_on' => $comp->insert_on));
				}
				//exit;
			}
		}
}