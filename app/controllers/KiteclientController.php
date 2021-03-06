<?php

require dirname(__FILE__)."/../kiteconnect.php";

class KiteclientController extends \BaseController {

	public $kite;

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */

	public function __construct() {
        $this->kite = new KiteConnect("qw4l9hh030dgujks");
	}
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
			$indData = $this->insertIndicators($v, $id, $ldate, $time);
			if($indData)
			{
				echo $trend = $this->isTrendChange($indData[0], $indData[1], $v['tradingsymbol']);
				$this->watchSwing($v['tradingsymbol'], $trend, $ldate, $time);
				return $this->callWatch($v, $trend, $ldate, $time);
			}
        // echo "<pre>"; print_r($a);
    }
    public function getCompanyList()
    {
        // FtYrjNkcigvxfBRuid5NX3pEVKqqnkoQ
		// echo $this->kite->getLoginURL(); exit;
		return Redirect::to($this->kite->getLoginURL());
        // try {
        //     $user = $kite->generateSession("WBOMkJMGUQb2da3B5dZY9eWFUNvz6V62", "l5ztksspq9jslkvp5gx9nq44qcdzvwdy");
        //     $kite->setAccessToken($user->access_token);
        // } catch(Exception $e) {
        //     echo "Authentication failed: ".$e->getMessage();
        //     throw $e;
        // }
        $insList = $this->kite->getInstruments('NSE');
        foreach ($insList as $val) {
            if(/* $val->instrument_type == 'EQ' &&  */$val->expiry == '' && $val->segment == 'NSE')
            {
                $d['instrument_token'] = $val->instrument_token;
                $d['exchange_token']   = $val->exchange_token;
                $d['tradingsymbol']    = $val->tradingsymbol;
                $d['name']             = $val->name;
                $d['segment']          = $val->segment;
                $d['exchange']         = $val->exchange;
                $d['instrument_type']  = $val->instrument_type;
                DB::table('kite_comp')->insert($d);
            }
        }
	}
	public function getKey($var = null)
	{
		// request_token
		echo $var = Input::get('request_token');
		$key = $this->kite->generateSession($var, 'l5ztksspq9jslkvp5gx9nq44qcdzvwdy');
		// print_r($key);
		// $set = Setting::find(1);
		// $set->key = 'john@foo.com';
		// $set->save();
	}

	public function updown()
	{
		// $user = $this->kite->generateSession("gEtauIHimeMJjIzk74ycCsv5rBV9mflm", "your_api_secret");
		// print_r($user);
		$this->kite->setAccessToken('7v6zCvRLrKrKtGCp8OwVSd7voX4Ta3Sy');

		$stock = DB::table('kite_margin')
		// ->where('SERIES','EQ')
		->get();
		// print_r($stock);
		$data = array();
		echo "<pre>";
		foreach ($stock as $row) {
			// echo $row->Scrip;
			array_push($data, 'NSE:'.$row->Scrip);
		}
		// print_r($data);
	// Retrieve quote and market depth for list of instruments.
		echo "Quote: \n";
		$k = $this->kite->getQuote($data);
		print_r($k);

	}

	function getPercentageChange($oldNumber, $newNumber){
		$decreaseValue = $oldNumber - $newNumber;
		return ($decreaseValue / $oldNumber) * 100;
	}
}