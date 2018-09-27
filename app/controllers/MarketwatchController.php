<?php

class MarketwatchController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
        // return View::make('watch.gainers');
        return View::make('watch.watch');
    }
    public function v2()
	{
        $ldate = date('Y-m-d');
        $names = DB::table('marketwatch')
            ->select('TradingSymbol', 'per', 'updatedTime')
            ->where('updatedTime', '>',  $ldate.' 09:30:00')
            // ->groupBy('TradingSymbol')
            ->get();
        $arr = array();
        echo '<pre>'; print_r($names); exit();
        foreach ($names as $key => $name) {
            array_push($arr,$name->TradingSymbol);
        }
        $datas = DB::table('marketwatch')
               ->select('updatedTime')
               ->where('updatedTime', '>',  $ldate.' 09:30:00')
               ->groupBy('updatedTime')
               ->get();
        $a = array();
        foreach ($datas as $key => $data){
            array_push($a,$data->updatedTime);
        }

//  for($i=0; $i<=$datas; $i++){
//  echo "<pre>"; print_r($datas); exit();
//  }
        // return View::make('watch.watchv2')->with('name', $stocks);
        return View::make('watch.watchv2')->with('names',$arr)->with('datas',$a);
	}
	public function nse50()
	{
        return View::make('watch.nse50');
	}
	public function ETGainersData()
	{
        $url = "https://etmarketsapis.indiatimes.com/ET_Stats/gainers?pagesize=25&pid=0&pageno=1&sort=intraday&sortby=percentchange&sortorder=desc&duration=1d&callback=ajaxResponse&totalpages=7&index=2371";
        $ET_data = $this->getCURL($url);
       // echo '<pre>'; print_r($ET_data); exit();
        // return View::make('watch.nse50');
        $this->insertETG($ET_data);
	}
	public function getNSDdata()
	{
        return View::make('watch.nsedata');
    }
    public function getAllData()
    {
        // NSE 50
        $nse50 = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/niftyStockWatch.json';
        $nse50_data = $this->getCURL($nse50);
        $this->parseData($nse50_data,'NSE50');


        // Junior Nifty
        $junior = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/juniorNiftyStockWatch.json';
        $junior_data = $this->getCURL($junior);
        $this->parseData($junior_data,'Junior');

        // Midcap50 Nifty
        $Midcap50 = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/niftyMidcap50StockWatch.json';
        $Midcap50_data = $this->getCURL($Midcap50);
        $this->parseData($Midcap50_data,'Midcap50');

        // $this->getAllSector();
        // $this->gainerLoser();
    }
    public function marketJSON()
    {
        $rows = array();
        $table = array();
        $ldate = date('Y-m-d');

        $table['cols'] = array(array(
            'label' => 'Date Time', 
            'type' => 'datetime'
        ));
        $dt = DB::table('marketwatch')
        ->select('updatedTime')
        ->where('updatedTime', '>',  $ldate.' 09:00:00')
        ->groupBy('updatedTime')
        ->get();
        echo "<pre>";
        foreach ($dt as $key => $datetime) {
            $data = DB::table('marketwatch')->select('per', 'TradingSymbol', 'updatedTime')
            ->where('updatedTime',$datetime->updatedTime)->get();
        }
        exit;

            $date = new DateTime();
            $sub_array[] =  array(
				"v" => 'Date('.$date->getTimestamp().'000)'
            );
            $c = array();
            echo "<pre>"; print_r($d); exit();
            foreach ($d as $k => $v) {

                $new = array($v->updatedTime, $v->per);
                array_push($c, $new);
                $sub_array[] =  array(
                    "v" => $v->per
                );
                $rows[] =  array(
                    "c" => $sub_array
                );
            }
            $table['rows'] = $rows;
            //return Response::json($table);
            echo '<pre>'; print_r($table); exit();
            //return View::make('watch.marketJSON');
    }
    public function gainerLoser()
    {
        // Gainer
        $gainer = 'https://www.nseindia.com/live_market/dynaContent/live_analysis/gainers/niftyGainers1.json';
        $gainer_data = $this->getCURL($gainer);
        // echo '<pre>'; print_r($gainer_data); exit();
        $this->parseData($gainer_data,'Gainer');
        // Loser
        $loser = 'https://www.nseindia.com/live_market/dynaContent/live_analysis/losers/niftyLosers1.json';
        $loser_data = $this->getCURL($loser);
        $this->parseData($loser_data,'Loser');

    }

    public function getAllSector()
    {
        // Auto
        $Auto = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxAutoStockWatch.json';
        $Auto_data = $this->getCURL($Auto);
        $this->parseData($Auto_data,'Auto');

        // Bank
        $Bank = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/bankNiftyStockWatch.json';
        $Bank_data = $this->getCURL($Bank);
        $this->parseData($Bank_data,'Bank');

        // Energy
        $Energy = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxEnergyStockWatch.json';
        $Energy_data = $this->getCURL($Energy);
        $this->parseData($Energy_data,'Energy');

        // Finance
        $Finance = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxFinanceStockWatch.json';
        $Finance_data = $this->getCURL($Finance);
        $this->parseData($Finance_data,'Finance');
        
        // FMCG
        $FMCG = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxFMCGStockWatch.json';
        $FMCG_data = $this->getCURL($FMCG);
        $this->parseData($FMCG_data,'FMCG');
        
        // IT
        $IT = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxitStockWatch.json';
        $IT_data = $this->getCURL($IT);
        $this->parseData($IT_data,'IT');
        
        // Media
        $Media = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxMediaStockWatch.json';
        $Media_data = $this->getCURL($Media);
        $this->parseData($Media_data,'Media');
        
        // Metal
        $Metal = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxMetalStockWatch.json';
        $Metal_data = $this->getCURL($Metal);
        $this->parseData($Metal_data,'Metal');

        // Pharma
        $Pharma = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxPharmaStockWatch.json';
        $Pharma_data = $this->getCURL($Pharma);
        $this->parseData($Pharma_data,'Pharma');

        // PSU
        $PSU = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxPSUStockWatch.json';
        $PSU_data = $this->getCURL($PSU);
        $this->parseData($PSU_data,'PSU');
        
        // Realty
        $Realty = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/cnxRealtyStockWatch.json';
        $Realty_data = $this->getCURL($Realty);
        $this->parseData($Realty_data,'Realty');
        
        // PvtBank
        $PvtBank = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/niftyPvtBankStockWatch.json';
        $PvtBank_data = $this->getCURL($PvtBank);
        $this->parseData($PvtBank_data,'PvtBank');
    }

    public function parseData($data, $type)
    {
        $updateTime = Session::get($type);
        if (isset($updateTime)) {
           if ($updateTime == $data->time) {
                return Response::json('Data Exist: '.$updateTime);
                exit;
           }
        }
        Session::put($type, $data->time);
        $this->insertTable($data, $type);
        return Response::json('Inserted: '.$data->time);
    }
    public function insertTable ($data, $type)
    {
        // echo '<pre>'; print_r($data); exit();
        $lastRec = 'C:\xampp\htdocs\market\public\json\nsedata_'.date("d-m-Y").'.json';
        // //open or read json data
        if (!file_exists($lastRec)) {
                $t['TradingSymbol'] =  10;
            $fh = fopen($lastRec, 'w');
                fwrite($fh, json_encode($t));
            fclose($fh);
        }
        $lastRec_results = file_get_contents($lastRec);
        $lastRecArray = json_decode($lastRec_results);
        		
        foreach ($data->data as $k => $v) {
            $update['symbol'] = $v->symbol;
            $update['ltP'] = str_replace(',', '', isset($v->ltP) ? $v->ltP : $v->ltp);
            $update['trdVol'] =str_replace(',', '', isset($v->trdVol) ? $v->trdVol : $v->tradedQuantity);
            $update['per'] =isset($v->per) ? $v->per : $v->netPrice;
            $update['type'] = $type;
            $update['nse_time'] = $data->time;
            if (isset($lastRecArray->$update['symbol'])) {
                $update['diff'] =  ($update['per'] - $lastRecArray->$update['symbol']);
            } else {
                $update['diff'] = 0;
            }
            $lastRecArray->$update['symbol'] = $update['per'];
            // echo '<pre>'; print_r($update); exit();
            DB::table('intra_data')->insert($update);
        }
        file_put_contents($lastRec, json_encode($lastRecArray));
    }

    public function insertETG($data)
    {
        foreach($data->searchresult as $row)
        {
            $dt = $row->updatedDateTime;
            $dt = strtotime(str_replace('|', '', $dt));
            $d = date('Y-m-d',$dt);
            $t = date('h:i A',$dt);
           // echo $d,'<br/>',$t;

            // echo $row->companyShortName."<br>";
            // print_r($row);
            $update['companyShortName'] = $row->companyShortName;
            $update['nseScripCode'] = substr($row->nseScripCode,0,-2);
            $update['series'] = substr($row->nseScripCode,strlen($update['nseScripCode']));
            $update['volume'] = $row->volume;
            $update['current'] = $row->current;
            $update['high'] = $row->high;
            $update['low'] = $row->low;
            $update['percentChange'] = $row->percentChange;
            $update['sectorName'] = $row->sectorName;
            $update['t_date'] = $d;
            $update['t_time'] = $t;
            DB::table('etg500')->insert($update);
        }
        // $data = DB::select($ET_data)->get();
        //$data = json_decode(json_encode((array)$ET_data), true);
        // foreach ($data->ET_data as $k => $row) {
        //     $update['companyShortName'] = $v->DenaBank;
        //     $update['nseScripCode'] = $v->DENABANKEQ;
            //     /*$update['ltP'] = str_replace(',', '', $v->ltp);
            //     $update['trdVol'] =str_replace(',', '', $v->tradedQuantity);
            //     $update['per'] = $v->netPrice;
            //     $update['type'] = $type;
            //     $update['nse_time'] = $data->time;*/
        //     echo '<pre>'; print_r($update);exit();
        // DB::table('etg500')->insert($update);
        //  }
    }
	public function getData()
	{
        $rows = array();
        $table = array();

        $table['cols'] = array(array(
        'label' => 'Date Time', 
        'type' => 'string'
        ));

        $data = DB::table('marketwatch')->where('epoch','!=', '')->groupBy('TradingSymbol')->take(3)->get();
        foreach ($data as $key => $value) {
            $t = array('label' => $value->TradingSymbol, 'type' => 'number');
            array_push($table['cols'] , $t);
        }
        $d = DB::table('marketwatch')->where('epoch','!=', '')->groupBy('updatedTime')->take(3)->get();
            foreach ($d as $k1 => $v1) {
                $c = DB::table('marketwatch')->select('TradingSymbol', 'updatedTime', 'LTPrice')->where('updatedTime','=', $v1->updatedTime)->get();
            }
            echo '<pre>'; print_r($d); exit();
        foreach ($c as $k => $v) {
            $new = array((int)$v->updatedTime, $v->LTPrice);
            array_push($t , $new);
            $sub_array = array();
            // $datetime = $v->epoch;
            // $dt = new DateTime("@$datetime");
            // $datetime = $dt->format('Y-m-d H:i:s'); 
            $sub_array[] =  array(
                "v" => $v->updatedTime
                );
            $sub_array[] =  array(
                "v" => $v->LTPrice
                );
            $rows[] =  array(
                "c" => $sub_array
                );
        }
     $table['rows'] = $rows;
    return Response::json($table);
    }
    
     function getGainers()
    {
		$url = 'https://www.nseindia.com/live_market/dynaContent/live_analysis/gainers/niftyGainers1.json';
        $json = 'C:\xampp\htdocs\market\public\json\gainers_'.date("d-m-Y").'.json';
        $data = $this->getCURL($url);
        
        $updateTime = Session::get('gainers_time');
        if (isset($updateTime)) {
           if ($updateTime == $data->time) {
                return Response::json('Data Exist'.$updateTime);
                exit;
           }
        }
        Session::put('gainers_time', $data->time);
        
	if (!file_exists($json)) {
            $table['cols'] = array(array(
            'label' => 'Date Time', 
            'type' => 'string'
            ));
        // echo '<pre>'; print_r($data); exit();
		foreach ($data->data as $key => $value) {
			$t = array('label' => $value->symbol, 'type' => 'number');
            array_push($table['cols'] , $t);
        }
		$fh = fopen($json, 'w');
  		fwrite($fh, json_encode($table));
        fclose($fh);
        }

        $table['rows'] = array();
            // //open or read json data
            $data_results = file_get_contents($json);
            $tempArray = json_decode($data_results);
            // //append additional json to json file
            if (isset($tempArray->rows)) {
                $tempRow = $tempArray->rows;
            }

                $sub_array[] =  array(
                    "v" => $data->time
                    );
            foreach ($data->data as $k => $v) {
                $update['symbol'] = $v->symbol;
                $update['price'] = str_replace(',', '', $v->ltp);
                $update['per'] = $v->netPrice;
                DB::table('gainers')->insert($update);
                $sub_array[] =  array(
                    "v" => $v->netPrice
                    // "v" => $v['LTPrice']
                    );
                }
                $tempRow[] =  array(
                    "c" => $sub_array
                    );

            // $tempRow[] = $rows;
            // echo '<pre>'; print_r($tempRow); exit();
            $tempArray->rows=$tempRow;
            $jsonData = json_encode($tempArray);
        file_put_contents($json, $jsonData);  
    return Response::json($tempArray);
    }
     function getNSE50()
    {
		$url = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/niftyStockWatch.json';
        $json = 'C:\xampp\htdocs\market\public\json\nse50_'.date("d-m-Y").'.json';
        $data = $this->getCURL($url);

        $updateTime = Session::get('nse50_time');
        if (isset($updateTime)) {
           if ($updateTime == $data->time) {
                return Response::json('Data Exist'.$updateTime);
                exit;
           }
        }
        Session::put('nse50_time', $data->time);
		
	if (!file_exists($json)) {
            $table['cols'] = array(array(
            'label' => 'Date Time', 
            'type' => 'string'
            ));
        // echo '<pre>'; print_r($data); exit();
		foreach ($data->data as $key => $value) {
			$t = array('label' => $value->symbol, 'type' => 'number');
            array_push($table['cols'] , $t);
        }
		$fh = fopen($json, 'w');
  		fwrite($fh, json_encode($table));
        fclose($fh);
        }

        $table['rows'] = array();
            // //open or read json data
            $data_results = file_get_contents($json);
            $tempArray = json_decode($data_results);
            // //append additional json to json file
            if (isset($tempArray->rows)) {
                $tempRow = $tempArray->rows;
            }

                $sub_array[] =  array(
                    "v" => $data->time
                    );
            foreach ($data->data as $k => $v) {
                $update['symbol'] = $v->symbol;
                $update['ltP'] = str_replace(',', '', $v->ltP);
                $update['trdVol'] = $v->trdVol;
                $update['per'] = $v->per;
                DB::table('intra_nse50')->insert($update);
                $sub_array[] =  array(
                    "v" => $v->per
                    // "v" => $v['LTPrice']
                    );
                }
                $tempRow[] =  array(
                    "c" => $sub_array
                    );

            // $tempRow[] = $rows;
            // echo '<pre>'; print_r($tempRow); exit();
            $tempArray->rows=$tempRow;
            $jsonData = json_encode($tempArray);
        file_put_contents($json, $jsonData);  
    return Response::json($tempArray);
    }
    
    function getCURL($url)
    {
        //  Initiate curl
        $ch = curl_init();
        // Disable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // Will return the response, if false it print the response
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // Set the url
        curl_setopt($ch, CURLOPT_URL,$url);
        // Execute
        $result=curl_exec($ch);
        // Closing
        curl_close($ch);

        return json_decode($result);
    }

}
