<?php

class StrategyController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index()
	{
        // return View::make('watch.gainers');
        // SELECT symbol, max(ltP), updated_on  FROM `intra_data` WHERE updated_on < '2018-08-16 09:30:00' GROUP by symbol ORDER BY `id` ASC LIMIT 100

        return View::make('watch.watch');
	}
	public function first15()
	{
        $data = array();
        $ldate = date('Y-m-d');
                $maxPr = DB::table('intra_data')
                    ->select(DB::raw('symbol, max(ltP) as maxLTP, updated_on' ))
                     ->where('updated_on', '<',  $ldate.' 09:30:00')
                     ->where('updated_on', '>',  $ldate.' 09:00:00')
                     ->groupBy('symbol')
                     ->get();
//                      $queries = DB::getQueryLog();
// $last_query = end($queries);
// print_r($last_query); 
// exit;
                     foreach ($maxPr as $m) {
                        //  print_r($m);
                         $res = DB::table('intra_data')
                        //  ->join('company', 'intra_data.symbol', '=', 'company.symbol')
                        ->select(DB::raw('intra_data.symbol, max(intra_data.ltP) as maxLTP, min(intra_data.ltP) as minLTP, intra_data.updated_on' ))
                        ->where('intra_data.symbol', '=',  $m->symbol)
                        ->where('intra_data.ltP', '>',  $m->maxLTP)
                        ->where('intra_data.updated_on', '>',  $ldate.' 09:30:00')
                        ->where('intra_data.updated_on', '<',  $ldate.' 16:00:00')
                        ->groupBy('symbol')
                        ->get();
                        // echo '<pre>'; print_r($res[0]); exit();
                        if (isset($res[0])) {
                            $per = round(((($res[0]->maxLTP - $m->maxLTP) / $m->maxLTP) * 100),2);
                            $res[0]->firstMax = $m->maxLTP;
                            $res[0]->per = $per;
                            $data[] = $res[0];
                        }
                     }
                    //  exit;
        return View::make('strategy.first50')->with('data', $data);
	}
	public function upTrend()
	{
        $ldate = date('Y-m-d');
        $data = DB::table('intra_data')
        ->where('updated_on', '>',  '(now() - interval 10 minute)')
        ->select(DB::raw('intra_data.symbol, AVG(intra_data.diff) as avgDiff, intra_data.nse_time' ))
        //  ->where('type', '=',  'NSE50')
         ->groupBy('symbol')
         ->orderBy('avgDiff', 'desc')
         ->take(15)
         ->get();

         // Top percentage
        $topP = DB::table('intra_data')
        ->whereRaw('id IN (select MAX(id) FROM intra_data GROUP BY symbol)')
        ->take(15)
         ->orderBy('per', 'DESC')->get();
        return View::make('strategy.upTrend')->with('data', $data)->with('topP', $topP);
    }
    public function upDown()
    {
        // $ldate = date('Y-m-d');
        $ldate = '2018-08-31 ';
        $up = DB::table('intra_data')
        //  ->join('company', 'intra_data.symbol', '=', 'company.symbol')
        ->select(DB::raw('intra_data.symbol, max(intra_data.per) as maxPer, intra_data.updated_on' ))
        // ->where('intra_data.symbol', '=',  $m->symbol)
        // ->where('intra_data.ltP', '>',  $m->maxLTP)
        ->where('intra_data.updated_on', '>',  $ldate.' 09:30:00')
        ->where('intra_data.updated_on', '<',  $ldate.' 16:00:00')
        ->groupBy('symbol')
        ->get();

         // Top and Down percentage
         $down = DB::table('intra_data')
         ->whereRaw('id IN (select MAX(id) FROM intra_data GROUP BY symbol)')
        //  ->take(15)
          ->orderBy('per', 'DESC')->get();
        $upDown = $this->getMaxDiff($up,$down);
        // echo "<pre>";
        // print_r($upDown);
        // exit;
         return View::make('strategy.upDown')->with('data', $upDown);
    }
    public function getMaxDiff($up, $c)
    {
        foreach($up as $v){
            foreach($c as $cv){
                if($v->symbol == $cv->symbol){
                    if (($v->maxPer - $cv->per)>=3) {
                        $arr[] = $v;
                    }
                    break;
                }
            }
        }
        return $arr;
    }

    function getCalls()
    {
        $url = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/niftyStockWatch.json';
        $data = $this->getCURL($url);
        $callData = $this->getCallData($data->data);
          // Junior Nifty
        $junior = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/juniorNiftyStockWatch.json';
          $junior_data = $this->getCURL($junior);
          $callData1 = $this->getCallData($junior_data->data);
        //   array_push($callData, $callData1);

          // Midcap50 Nifty
          $Midcap50 = 'https://www.nseindia.com/live_market/dynaContent/live_watch/stock_watch/niftyMidcap50StockWatch.json';
          $Midcap50_data = $this->getCURL($Midcap50);
          $callData2 = $this->getCallData($Midcap50_data->data);

          $r = array_merge($callData, $callData1, $callData2);
        //   echo "<pre>";
        //   print_r($r);
        //   exit;
        return View::make('strategy.calls')
        ->with('data', $r);
    }


    function getCallData($d)
    {
        
        foreach($d as $v){
            if($v->open == $v->high){
                $v->call = "Sell";
                $arr[] = $v;
            } else if($v->open == $v->low){
                $v->call = "Buy";
                $arr[] = $v;
            } 
        }
        return $arr;
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
