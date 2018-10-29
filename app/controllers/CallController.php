<?php

class CallController extends \BaseController {

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
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create($nse,$bs=1)
	{
		//
        $d = DB::table('edelid')
            ->join('nse_list', 'edelid.code', '=', 'nse_list.EdelCode')
            ->where('nse_list.NSECode','=', $nse)
            ->get();
//        print_r($d);
//        exit;
        $i['nseCode'] = $d[0]->NSECode;
        $i['edelCode'] = $d[0]->EdelCode;
        $i['edelID'] = $d[0]->eid;
        if(date('G')>=12)
            echo $date = date("Y")."-".date("m")."-".(date("d")+1);
        else
            echo $date = date('Y-m-d');
        $i['day'] = $date;
        $i['bs'] = 1;
        DB::table('calls')->insert($i);
        return Redirect::to('/call');
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
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function getPortfolio()
	{
        $rCall = DB::table('portfolio')
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
	public function updatePortfolio()
	{
		// print_r($id);
		$input = Input::all();
		// echo '<pre>'; print_r($input['id']); exit();
		// echo $input->id; exit;
		return json_encode(DB::update('update portfolio set status = 2 where id = '.$input['id']));
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
		$json = 'C:\xampp\htdocs\market\public\json\results_'.date("d-m-Y").'.json';
        $lastRec = 'C:\xampp\htdocs\market\public\json\watchLast'.date("d-m-Y").'.json';

	if (!file_exists($json)) {
		$table['cols'] = array(array(
		'label' => 'Date Time', 
		'type' => 'datetime'
		));
		foreach ($input['data'] as $key => $value) {
			$t1 = array('label' => $value['TradingSymbol'], 'type' => 'number');
			array_push($table['cols'] , $t1);
			$t2 = array('role' => 'style', 'type' => 'string');
			array_push($table['cols'] , $t2);
		}
		$fh = fopen($json, 'w');
  		fwrite($fh, json_encode($table));
		fclose($fh);
	}

$table['rows'] = array();
				// //open or read json data
				if (!file_exists($lastRec)) {
					foreach ($input['data'] as $key => $value) {
						$t[$value['TradingSymbol']] =  $value['%'];
					}
					$fh = fopen($lastRec, 'w');
  					fwrite($fh, json_encode($t));
					fclose($fh);
				}
                $data_results = file_get_contents($json);
                $lastRec_results = file_get_contents($lastRec);
                $tempArray = json_decode($data_results);
				$lastRecArray = json_decode($lastRec_results);
				// echo '<pre>'; print_r($lastRecArray); exit();
				// //append additional json to json file
				if (isset($tempArray->rows)) {
					$tempRow = $tempArray->rows;
				}
					$date = new DateTime();
					$sub_array[] =  array(
						"v" => 'Date('.$date->getTimestamp().'000)'////////date('Y-m-d H:i:s')
						);
			    foreach ($input['data'] as $k => $v) {
				$update['TradingSymbol'] = $v['TradingSymbol'];
                $update['LTPrice'] = str_replace(',', '', $v['LTPrice']);
                $update['per'] = $v['%'];
				$update['diff'] = $v['%'] - $lastRecArray->$v['TradingSymbol'];
				//$state = $this->getState($v['TradingSymbol'], $update['LTPrice']);
				// $sc = $this->screenCall($v['TradingSymbol'], $update);
				$sc = 0;
				if ($sc[0]) {
					$count = $sc[0]; // not active
					$state = $sc[1];
				} else {
					$state = $count = 0;
				}
				echo $update['state'] = $state;
				echo $update['count'] = $count;
                $update['LTQty'] = $v['LTQty'];
                $update['Vol'] = $v['Vol'];
                $update['Open'] = $v['Open'];
                $update['High'] = $v['High'];
                $update['Low'] = $v['Low'];
                $update['Close'] = $v['Close'];
				$update['LastTraded'] = $v['Last Traded Date'];
				// Insert Into Table
				DB::table('marketwatch')->insert($update);
				$lastRecArray->$v['TradingSymbol'] = $v['%'];
				$sub_array[] =  array("v" => $v['%']);
				$sub_array[] =  array("v" => 'point { size: 18; shape-type: star; fill-color: #a52714; }');
			}
			$tempRow[] =  array("c" => $sub_array);

				// $tempRow[] = $rows;
				file_put_contents($lastRec, json_encode($lastRecArray));  
				
                $tempArray->rows=$tempRow;
                $jsonData = json_encode($tempArray);
				file_put_contents($json, $jsonData);
				// echo $input['nifty'];
				if($input['nifty'])
					$this->insertNifty($input['nifty']);
		return json_encode($sc);
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
public function getState($name, $c)
{
	// Session::flush();
	if (!Session::get($name)) {
		$arr = array('count' => 0, 'LHP' => $c, 'LLP' => $c , 'LTP' => $c );
		Session::put($name, $arr);
	}
	$sdata = Session::get($name);
	$res = "N";
	if ($sdata['LTP'] < $c) {
		$res = $this->getPHigh($name, $c, $sdata);
		$sdata['LHP'] = $c;
	} else if ($sdata['LTP'] > $c) {
		$res = $this->getPLow($name, $c, $sdata);
		$sdata['LLP'] = $c;
	}
	$sdata['LTP'] = $c;
	$sdata['state'] = $res;
	Session::put($name, $sdata);
 return $res;
}

public function getPHigh($name, $c, $sdata)
{
	if(isset($sdata['LHP'])){
		$val = $sdata['LHP'];
		if ($val > $c) {
			$state = "HD";
		} else if ($val < $c) {
			$state = "HU";
		} else {
			$state = "HN";
		}
	} else {
		$state = "NA";
	}
 return $state;
}

public function getPLow($name, $c, $sdata)
{
	if(isset($sdata['LLP'])){
		$val = $sdata['LLP'];
		if ($val > $c) {
			$state = "LD";
		} else if ($val < $c) {
			$state = "LU";
		} else {
			$state = "LN";
		}
	} else {
		$state = "NA";
	}
 return $state;
}

public function updatePosition()
	{
		// print_r($id);
		date_default_timezone_set('Asia/Kolkata');

		$input = Input::all();
		// echo '<pre>'; print_r($input); exit();
		$json = 'C:\xampp\htdocs\market\public\json\position_'.date("d-m-Y").'.json';
		$lastRec = 'C:\xampp\htdocs\market\public\json\position'.date("d-m-Y").'.json';
		
		// //open or read json data
		if (!file_exists($lastRec)) {
			foreach ($input['data'] as $key => $value) {
				$t[$value['Stock Code']] =  $value['LTP'];
			}
			$fh = fopen($lastRec, 'w');
			fwrite($fh, json_encode($t));
			fclose($fh);
		}
		$lastRec_results = file_get_contents($lastRec);
		$lastRecArray = json_decode($lastRec_results);
		// echo '<pre>'; print_r($lastRecArray); exit();
		// //append additional json to json file

		foreach ($input['data'] as $k => $v) {
			$update['code'] = $v['Stock Code'];
			$update['BuyQty'] = str_replace(',', '', $v['Buy Qty']);
			$update['BuyPrice'] =  str_replace(',', '', $v['Avg. Buy Price']);
			$update['SellQty'] =  str_replace(',', '', $v['Sell Qty']);
			$update['SellPrice'] = str_replace(',', '', $v['Avg. Sell Price']);
			$update['NetQty'] =  str_replace(',', '', $v['Net Qty']);
			$update['LTP'] = str_replace(',', '', $v['LTP']);
			$update['diff'] = str_replace(',', '', $v['LTP']) - $lastRecArray->$v['Stock Code'];
			DB::table('position')->insert($update);
			$lastRecArray->$v['Stock Code'] = $v['LTP'];
		}
		file_put_contents($lastRec, json_encode($lastRecArray));
		return json_encode('Success'.date('Y-m-d H:i:s'));
	}

public function updateSinglePosition()
	{
		// print_r($id);
		date_default_timezone_set('Asia/Kolkata');

		$input = Input::all();

		$update['stock'] = $input['stock'];
		$update['per'] = $input['per'];
		 $lastRec = DB::table('singlePosition')
		->where('stock','=', $input['stock'])
		->take(10)
		->orderBy('id','DESC')
        ->get();
		if (isset($lastRec[0]->stock)) {
			$update['diff'] = $input['per'] - $lastRec[0]->per;
		} else {
			$update['diff'] = 0;
		}
		// $update['diff'] = $input['per'] - $lastRec[0]->per;
		DB::table('singlePosition')->insert($update);
		return json_encode($lastRec);
	}

public function insertIntraTableDB()
	{
		$input = Input::all();
		
		// DB::table('intraday_edel')->truncate();
		foreach ($input['data'] as $k => $v) {
			$d['company'] = $v['Edel Code'];
			$d['symbol'] = $v['NSE Symbol'];
			$d['isin'] = $v['ISIN'];
			$d['series'] = 'EQ';
			$d['Margin'] = $v['Intraday Margin'];
			DB::table('intraday_edel')->insert($d);
		}
		return json_encode('Inserted Successfully');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function getCalls()
	{
		$input = Input::all();
/* 		 $d = DB::table('edelid')
            ->join('nse_list', 'edelid.code', '=', 'nse_list.EdelCode')
            ->where('nse_list.NSECode','=', $nse)
			->get(); */
		$calls = DB::table('intra_call')->where('status','=', 1)->take(1)->get();
		// echo '<pre>'; print_r($calls); exit();
		return json_encode($calls);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function redirect($nse)
	{
		//
		// echo $nse;
		$data= DB::table('company')->where('symbol','=', $nse)->take(1)->get();
		// print_r($url);
		$url = "https://www.edelweiss.in".$data[0]->URL;
		return Redirect::to($url);
	}

	public function screenCall($script, $data)
	{
		// Logic 1 - Countinues +/-
		//$this->counLogic($script, $data);
		// Logic 2 - Immediate High
		// $ldate = date('Y-m-d');
		// $data = DB::table('marketwatch')->where('updatedTime', '>',  $ldate.' 09:10:00')->get();
		// echo "<pre>"; print_r($data); exit;
		$CURRENTTIME = new DateTime();
    	$cutOff  = new DateTime('09:30:00');
		if ($CURRENTTIME  > $cutOff) {
			return $this->sma($script,$data);
		}  else {
			return $this->immediatehigh($script,$data);
		}
	}
	public function immediatehigh($script, $data)
	{
		$r = null;
		//echo $script;
		$sData = Session::get($script);
		// print_r($sData);
		$threshold = 1;
		$ldate = date('Y-m-d');
		$calls = DB::table('intra_call')->where('nse','=', $script)->where('status','=', 0)->take(1)->get();
		$his = DB::table('marketwatch')
			->where('TradingSymbol','=', $script)
			->where('updatedTime', '>',  $ldate.' 09:15:00')
			->where('updatedTime', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 MINUTE)'))
			->orderBy('id', 'DESC')
			->take(4)
			->get();
			// echo "<pre>"; print_r($his); exit();
		$sum = 0;
		// $i = 1;
		foreach($his as $key => $values) {
			$sum += $values->diff;
		// 	if ($i == 9) {
		// 		$sma9 = $sum;
		// 	}
		// $i++;
		}
		if (isset($calls[0])) {
			$this->callWatch($calls[0], $data);
			return $r;
		}
		else {
			if ($sum >= $threshold) {
				$r = $this->insIntraCall($script, $data['LTPrice'], $data['per'],'1','IMH-R4T1P1');
			}else if ($sum <= -$threshold) {
				$r = $this->insIntraCall($script, $data['LTPrice'], $data['per'],'2','IMH-R4T1P1');
			}
		}
		return $r;
	}
	public function callWatch($callData, $data)
	{
		$target = 1;
		$stop = -1;
		if ($callData->call == 1) {
			$diff =  (float)$data['per'] -  (float)$callData->per;
		} else if ($callData->call == 2) {
			$diff = (float)$callData->per -  (float)$data['per'];
		}
		//echo $callData->nse;
		//echo  $callData->id."=>id".$callData->nse."=> Entry: ".$callData->per."=> CMP: ".$data['per']."=> Diff: $diff<br>";
		if ($diff >= $target) {
			if($data['diff'] < 0)
			{
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => 1, 'cPrice' => $data['LTPrice'], 'cPer' => $data['per']));
			}
		} else if ($diff <= $stop) {
			DB::table('intra_call')
				->where('id', $callData->id)
				->update(array('status' => -1, 'cPrice' => $data['LTPrice'], 'cPer' => $data['per']));
		}
	}
	public function counLogic($script, $data)
	{
		$sData = Session::get($script);
		$target = 1;
		$stop = -1;
		$calls = DB::table('intra_call')->where('nse','=', $script)->where('status','=', 0)->take(1)->get();
		// $callExist = $calls[0];
		if (isset($calls[0])) {
			$diff = $data['per'] - $calls[0]->per;
			//echo $calls[0]->nse."=> Entry: ".$calls[0]->per."=> CMP: ".$data['per']."=> Diff: $diff<br>";
			if ($diff >= $target) {
				DB::table('intra_call')
					->where('id', $calls[0]->id)
					->update(array('status' => 1, 'cPrice' => $data['LTPrice'], 'cPer' => $data['per']));
			} else if ($diff <= $stop) {
				DB::table('intra_call')
					->where('id', $calls[0]->id)
					->update(array('status' => -1, 'cPrice' => $data['LTPrice'], 'cPer' => $data['per']));
			}
			return 0;
		} else {
			$count = 0;
			if($data['diff'] > 0) {
				if(isset($sData['count'])){
					$count = $sData['count'];
				}
				$count++;
				$sData['count'] = $count;
				Session::put($script, $sData);
			} else if($data['diff'] < 0) {
				$sData['count'] = $count;
				Session::put($script, $sData);
			}
			if ($count == 5) {
				$this->insIntraCall($script, $data['LTPrice'], $data['per'],'1', 'Continues - 5');
			}
			if ($count == 4) {
				$this->insIntraCall($script, $data['LTPrice'], $data['per'],'1', 'Continues - 4');
			}
			return $count;
		}
	}

	public function insIntraCall($script, $price, $per, $call, $str)
	{
		$i['nse'] = $script;
        $i['price'] = $price;
        $i['per'] = $per;
		$i['call'] = $call;
		$i['strategy'] = $str;
		$EdelCode = DB::table('intraday_edel')->where('company','=', $script)->take(1)->get();
		if(isset($EdelCode[0]))
			$i['edel'] = $EdelCode[0]->symbol;
		else
			$i['edel'] = 'NF';
		DB::table('intra_call')->insert($i);
		return $i;
	}

	public function closeCall($callData, $data)
	{
		if ($callData->call == 1) {
			$diff =  (float)$data['per'] -  (float)$callData->per;
		} else if ($callData->call == 2) {
			$diff = (float)$callData->per -  (float)$data['per'];
		}
		if ($diff > 0) {
			$status = 1;
		} else {
			$status = -1;
		}
		DB::table('intra_call')
			->where('id', $callData->id)
			->update(array('status' => $status, 'cPrice' => $data['LTPrice'], 'cPer' => $data['per']));
	}
	public function sma($script, $data)
	{
	  
	  $ldate = date('Y-m-d');
	  $sum = 0;
	  $i = 1;
	  $sma1 = 25;
	  $sma2 = 11;
	  $target = 1;
	  $stop = -1;
	  $smaAvg2 = $smaAvg1 = $sTrend = null;
	  $sdata = Session::get($script);
	  if ($sdata['trend']) {
		$sTrend = $sdata['trend'];
	  }
	  $his = DB::table('marketwatch')
			->where('TradingSymbol','=', $script)
			->where('updatedTime', '>',  $ldate.' 09:14:00')
			// ->where('updatedTime', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 MINUTE)'))
			->orderBy('id', 'DESC')
			->take($sma1)
			->get();

	  $calls = DB::table('intra_call')->where('nse','=', $script)->where('status','=', 0)->take(1)->get();
		foreach($his as $key => $values) {
			$sum += $values->LTPrice;
			if ($i == $sma2) {
				$smaSum = $sum;
				$smaAvg2 = round(($sum / $sma2), 2);
			}
			if ($i == $sma1) {
				$smaAvg1 = round(($sum / $sma1), 2);
			}
		$i++;
		}	
		
			if ($sTrend == "uptrend" || !isset($sTrend)) {
				if ($smaAvg1 > $smaAvg2) {
					$sdata['trend'] = $sTrend = "downtrend";
					Session::put($script, $sdata);
					//$this->insIntraCall($script, $data['LTPrice'], $data['per'],'1', 'Continues - 5');
					if(isset($calls[0])) {
						$this->closeCall($calls[0], $data);
					}
					else {
					$this->insIntraCall($script, $data['LTPrice'], $data['per'],'2',$sTrend);
					}
				}
			}
			if ($sTrend == "downtrend"  || !isset($sTrend)) {
				if ($smaAvg1 < $smaAvg2) {
					$sTrend = "uptrend";
					$sdata['trend'] = $sTrend;
					Session::put($script, $sdata);
					//$this->insIntraCall($script, $data['LTPrice'], $data['per'],'1', 'Continues - 5');
					if(isset($calls[0])) {
						$this->closeCall($calls[0], $data);
					}
					else {
					$this->insIntraCall($script, $data['LTPrice'], $data['per'],'1',$sTrend);
					}
				}
			}
		//}
		return array($smaAvg1, $smaAvg2);
		// $t=trader_rsi( [2,2,3,2,3],4);
        // print_r($t);
	}
	// function calculateRSI() 
	// {
	//      $this->
         
    // }

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
public function report($ldate=null)
{
	if (!$ldate)
		$ldate = date('Y-m-d');
	$buy = DB::table('intra_call')
		->select(DB::raw('count("call") as value, status, strategy'))
		->where('call',1)
		->where('inserted_on', '>',$ldate.' 09:00:00')
		->groupBy('status')
		->get();

	$sell = DB::table('intra_call')
	    ->select(DB::raw('count("call") as total, status, strategy'))
		->where('call',2)
		->where('inserted_on', '>',$ldate.' 09:00:00')
		->groupBy('status')
		->get();
		//echo "<pre>"; print_r($sell); exit();
		$calldetail = DB::table('intra_call')//->select('SYMBOL','HIGH','LOW')
		// ->select('id','nse','price','cPrice','per','cPer','call','status')
		->where('inserted_on', '>',$ldate.' 09:00:00')
		->orderBy('id')
		// ->take(5)
		->get();
	return View::make('report.report')->with(array('buy'=>$buy, 'sell'=>$sell, 'calldetail'=>$calldetail));
}
public function summary()
{
	$ldate = date('Y-m-d');
	$buys = DB::table('intra_call')
		->select(DB::raw('count("call") as value, status, strategy, nse'))
		->where('call',1)
		->where('inserted_on', '>',$ldate.' 09:00:00')
		->groupBy('nse')
		->get();
//echo "<pre>"; print_r($buy); exit;
	$sells = DB::table('intra_call')
	    ->select(DB::raw('count("call") as total, status, strategy, nse'))
		->where('call',2)
		->where('inserted_on', '>',$ldate.' 09:00:00')
		->groupBy('nse')
		->get();
	//echo "<pre>"; print_r($sells); exit();
		$calldetails = DB::table('intra_call')
		->select('call','status','strategy','nse')
		//->select(DB::raw('count("call") as totalcall, status, strategy, nse'))
		->where('inserted_on', '>',$ldate.' 09:00:00')
		->orderBy('id')
		//->groupBy('nse')
		//->groupBy('call')
		// ->take(5)
		->get();
		echo "<pre>"; print_r($calldetails); exit();
		return View::make('report.summary')->with(array('buys'=>$buys, 'sells'=>$sells, 'calldetails'=>$calldetails));
} 

}
