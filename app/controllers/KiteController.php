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
            // Insert Into Table
			$id = DB::table('kite_watch')->insertGetId($insert);
			$c[] = $this->marketwatch($v, $id);
			//echo "<pre>"; print_r($id);
		}
		//$indicators = $this->insertIndicators(); 

		if($input['nifty'])
			$this->insertNifty($input['nifty']);
		return json_encode($c);
		//echo "<pre>"; print_r($c); exit;
	}
	public function marketwatch($v, $id, $ldate=null, $time=null)
	{
		//echo '<pre>'; print_r($id); 
			$indData = $this->insertIndicators($v['tradingsymbol'], $id, $ldate, $time);
			if($indData)
			{
				echo $trend = $this->isTrendChange($indData[0], $indData[1], $v['tradingsymbol']);
				$this->watchSwing($v['tradingsymbol'], $trend, $ldate, $time);
				return $this->callWatch($v, $trend, $time);
			}
        // echo "<pre>"; print_r($a);
	}

	public function watchSwing($script, $trend, $ldate = null, $time=null)
	{
		if($trend){
			// echo "Swing Entry - $script | $ldate --- ";
			if (!$ldate)
				$ldate = date('Y-m-d');
				$swing = DB::table('swingdata')->where('script','=', $script)->where('status','=', 0)->take(1)->get();
			if (isset($swing[0])) {
					$sd = $this->getSwing($script, $ldate, $time);
					$sTrend = $this->getCTrend($script);
					if($sTrend == 'downtrend'){
						$eprice = $sd['sHigh'];
						$epriceT = $sd['sHighT'];
					} elseif($sTrend == 'uptrend'){
						$eprice = $sd['sLow'];
						$epriceT = $sd['sLowT'];
					}
					$len = $this->getPercentageChange($eprice, $swing[0]->sprice);
					DB::table('swingdata')
					->where('id', $swing[0]->id)
					->update(array('status' => 1, 'eprice' => $eprice, 'etime' => $epriceT, 'sLenth' => $len));
				}
		//	else {		
				// echo "Swing : $sLow -  $sLowT | $sHigh - $sHighT";
				$sd = $this->getSwing($script, $ldate, $time);
				$sTrend = $this->getCTrend($script);
				if($sTrend == 'uptrend'){
					$sprice = $sd['sHigh'];
					$spriceT = $sd['sHighT'];
				} elseif($sTrend == 'downtrend'){
					$sprice = $sd['sLow'];
					$spriceT = $sd['sLowT'];
				}
				DB::table('swingdata')->insert(array('script' => $script, 'trend' => $sTrend, 'sprice' => $sprice, 'stime' => $spriceT));
			// }
		}
	}
	public function getSwing($script, $ldate, $time)
	{
		$sw = DB::table('kite_watch')
		->select('mHigh','mLow','lastPrice', 'insert_on')
		->where('tradingsymbol','=', $script)
		->where('insert_on', '>',  $ldate.' 09:14:00');
		if ($time) {
			$sw = $sw->where('insert_on', '<=',  $time);
		}
		$sw = $sw->orderBy('id', 'DESC')
		->take(15)
		->get();
		//echo '<pre>'; print_r($sw);

		$sHigh = $sLow = NULL;
		$sHighT = $sLowT = NULL;
		foreach ($sw as $key => $row) {
			if (!$sHigh || $sHigh < $row->mHigh ) {
				$sHigh = $row->mHigh;
				$sHighT = $row->insert_on;
			}
			if (!$sLow || $sLow > $row->mLow ) {
				$sLow = $row->mLow;
				$sLowT = $row->insert_on;
			}
		}
		// $sTrend = $this->getCTrend($script);
		// if($sTrend == 'uptrend'){

		// } elseif($sTrend == 'downtrend'){

		// }
		return array('sHigh' => $sHigh,'sHighT' => $sHighT,'sLow' => $sLow,'sLowT' => $sLowT );
	}
	public function callWatch($data, $trend, $time = NULL)
	{
		$calls = DB::table('intra_call')->where('nse','=', $data['tradingsymbol'])->where('status','=', 0)->take(1)->get();
		if (isset($calls[0])) {
			$r = $this->closeCall($calls[0], $data, $time);
		}
		else {
			if($trend)
				return $this->callEnter($data['tradingsymbol'], $data, $time);
		}
	}

	public function insertNifty($nifty)
	{
		//echo '<pre>'; print_r($nifty); exit();
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
		$r = null;
		$sTrend = $this->getCTrend($script);
		$primaryTrend = $this->getPrimaryTrend($script, $data['lastPrice'], $i);
		echo "<br>Entry - $i | $primaryTrend | ". $sTrend;
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
	public function getCTrend($script)
	{
		$sdata = Session::get($script);
		$sTrend = null;
		if ($sdata['trend']) {
			$sTrend = $sdata['trend'];
		}
		return $sTrend;
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
		//  echo $u."|".$diff."<br>";
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
	}

	public function insertIndicators($script, $ref_id, $ldate = NULL, $time = NULL)
	{
	if (!$ldate)
		$ldate = date('Y-m-d');
	  $sum = 0;
	  $i = 1;
	  $sma1 = 21;
	  $sma2 = 9;
	  $rsi = 28;
	  $r = NULL;
	  $smaAvg2 = $smaAvg1 = null;
	  $historyData = DB::table('kite_watch')
			->select('lastPrice')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00');
			if ($time) {
				// echo "<br>$time";
					$historyData = $historyData->where('insert_on', '<=',  $time);
				}
			$historyData = $historyData->orderBy('id', 'DESC')
			->take($sma1)
			->get();
			// echo "<pre>"; print_r($historyData);
			$t =  new RecursiveIteratorIterator(new RecursiveArrayIterator($historyData));
			$s = iterator_to_array($t, false);
			// echo "Array - 45";
			// echo '<pre>'; print_r($s);
			if(count($s) >= $sma1){
				$r = trader_rsi($s, $rsi);
				$s1 = trader_sma($s, $sma1);
				$s2 = trader_sma($s, $sma2);
				$a = $this->adx($script, $ldate, $time);
			//  print_r($r); print_r($s1); print_r($s2);
			// echo "<pre> SMA"; print_r($s2);
			DB::table('indicators')->insert(array('ref_id' => $ref_id, 'tradingsymbol' => $script, 'sma1' => $s1[($sma1 - 1)], 'sma2' => $s2[($sma2 - 1)], 'indicator3' => $r[($sma1 - 1)], 'insert_on' => $time, 'indicator4' => $a[0], 'indicator5' => $a[1]));
			return array($s1[($sma1 - 1)], $s2[($sma2 - 1)], $r[($sma1 - 1)]);
		}
		return NULL;
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
		$sma3 = 80;
		$ldate = date('Y-m-d');
		$last50 = DB::table('kite_watch')
			->select('lastPrice')
			->where('tradingsymbol','=', $script)
			->orderBy('id', 'DESC')
			//->orderBy('insert_on')
			->take($sma3);
			if ($time) {
				$last50 = $last50->where('insert_on', '<',  $time);
			} else {
				$last50 = $last50->where('insert_on', '>',  $ldate.' 09:14:00');
			}
			$last50 = $last50->get();
			$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($last50));
			$l = iterator_to_array($it, false);
			// print_r($l);
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

	public function adx($script, $ldate, $time)
	{
		$range = 14;
		$d = null;
		$adx = DB::table('kite_watch')
			->select('mHigh', 'mLow', 'lastPrice', 'insert_on')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00');
			// ->where('insert_on', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 MINUTE)'))
		if ($time) {
			$adx = $adx->where('insert_on', '<=',  $time);
		}
		$adx = $adx->orderBy('id', 'DESC')->take($range*2)->get();
			
		// echo "<pre>$script"; 
		// print_r($adx);
		// exit;
			$high = array();
			$low = array();
			$ltp = array();
			foreach($adx as $b)
			{
			    array_push($high, $b->mHigh);
			    array_push($low, $b->mLow);
				array_push($ltp, $b->lastPrice);
			}
			//echo "<pre>"; print_r($high); 
			//print_r($low); 
			//echo "<pre>"; print_r(array($high[0])); 
		  // $d = trader_adx(array($high), array($low), array($ltp), $range);
		//   echo count($ltp);
		  if (count($ltp) >= $range) {
		 	$d = trader_adx($high, $low, $ltp, $range);
		 	$atr = trader_atr($high, $low, $ltp, $range);
			//  echo "<pre>"; print_r(array($d[($range*2)-1], $atr[($range)]));
			return array($d[($range*2)-1], $atr[($range)]); 
			//return $atr[($range)];
		  }
		return null;
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
				//print_r($dif);
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
	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $oldNumber - $newNumber;
		return ($decreaseValue / $oldNumber) * 100;
	}
}