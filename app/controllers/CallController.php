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
		'type' => 'string'
		));
		foreach ($input['data'] as $key => $value) {
			$t = array('label' => $value['TradingSymbol'], 'type' => 'number');
			array_push($table['cols'] , $t);
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

					$sub_array[] =  array(
						"v" => date('Y-m-d H:i:s')
						);
			    foreach ($input['data'] as $k => $v) {
				$update['TradingSymbol'] = $v['TradingSymbol'];
                $update['LTPrice'] = str_replace(',', '', $v['LTPrice']);
                $update['per'] = $v['%'];
                $update['diff'] = $v['%'] - $lastRecArray->$v['TradingSymbol'];
                $update['LTQty'] = $v['LTQty'];
                $update['Vol'] = $v['Vol'];
                $update['Close'] = $v['Close'];
                $update['LastTraded'] = $v['Last Traded Date'];
				DB::table('marketwatch')->insert($update);
				$lastRecArray->$v['TradingSymbol'] = $v['%'];
					$sub_array[] =  array(
						"v" => $v['%']
						// "v" => $v['LTPrice']
						);
					}
					$tempRow[] =  array(
						"c" => $sub_array
						);

				// $tempRow[] = $rows;
				file_put_contents($lastRec, json_encode($lastRecArray));  
				
                $tempArray->rows=$tempRow;
                $jsonData = json_encode($tempArray);
            	file_put_contents($json, $jsonData);

		return json_encode('Success'.date('Y-m-d H:i:s'));
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


}
