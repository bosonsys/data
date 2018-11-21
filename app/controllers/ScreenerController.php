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
                //echo "<pre>"; print_r($close); exit;
                 $t =  new RecursiveIteratorIterator(new RecursiveArrayIterator($close));
                 $s = iterator_to_array($t, false);
                if(count($s) >= $sma1)
                {
                    $s1 = trader_sma($s, $sma1);
                    $s2 = trader_sma($s, $sma2);
                   // echo "<pre>"; print_r($s2); exit;
                    //return array($s1, $s2);
                    array_push($arr, array('symbol' => $d[2], 'sma1' => $s1, 'sma2' => $s2));
                   // echo "<pre>"; print_r($arr);
                } 
                $positive = array_slice($cDate, 0, 10);
                echo "<pre>"; print_r($positive); exit;
                // foreach($close as $key => $p)
                // {
                //     // echo "<pre>"; print_r($p->CLOSEP); exit;
                //     if($s1 > $p->CLOSEP)
                //     {
                //         echo "true";
                //     }
                //     elseif($s2 > $p->CLOSEP)
                //     {
                //         echo "false";
                //     }
                // } 
           }
          // return json_encode($arr);  
          return View::make('screener.screener')->with('symbol', $close[0]->SYMBOL);
        }
        //return array($l->SYMBOL, $value->CLOSEP, $s1, $s2);
    }