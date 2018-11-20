<?php

class ScreenerController extends \BaseController {

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
        //$collection = collect(DB::table('kite_watch')->get());
    }
    public function screener($name=null)
	{
		if(!$name)
			$name = 'ind_nifty50list';	
		$data = fopen("C:\\xampp\\htdocs\\market\\public\\data\\".$name.".csv", "r"); 
        //echo "<pre>"; print_r($data); exit;
        
        $sma1 = 21;
        $sma2 = 9;
         $arr = array();
         $j = 0;
         while(($d = fgetcsv($data)) !== FALSE)
         {
             $j++;
             if($j == 1)
             {
                continue; 
             }
            $close = DB::table('csvdata')
                 ->select('CLOSEP')
                 ->where('SYMBOL',$d[2])
                 ->orderBy('TIMESTAMP', 'desc')
                 ->take($sma1)
                 ->get();
                 $t =  new RecursiveIteratorIterator(new RecursiveArrayIterator($close));
                 $s = iterator_to_array($t, false);
                if(count($s) >= $sma1)
                {
                    $s1 = trader_sma($s, $sma1);
                    $s2 = trader_sma($s, $sma2);
                    //return array($s1, $s2);
                    array_push($arr, array('symbol' => $d[2], 'sma1' => $s1, 'sma2' => $s2));
                }    
           }
           return json_encode($arr);  
        }
        //return array($l->SYMBOL, $value->CLOSEP, $s1, $s2);
    }