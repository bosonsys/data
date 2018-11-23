<?php

class ScreenerController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        return View::make('screener.screener');
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
        $sma2 = 14;
        $sma3 = 80;
        $rsi = 14;
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
                 ->select('CLOSE')
                 ->where('SYMBOL',$d[2])
                 ->orderBy('TIMESTAMP', 'desc')
                 ->take($sma3)
                 ->get();
                 $t =  new RecursiveIteratorIterator(new RecursiveArrayIterator($close));
                 $s = iterator_to_array($t, false);
                if(count($s) >= $sma3)
                {
                    $r = trader_rsi($s, $rsi);
                    // echo '<pre>'; print_r($r); exit();
                    $trend1 = $this->getSMA($s, $sma1);
                    $trend2 = $this->getSMA($s, $sma2);
                    $trend3 = $this->getSMA($s, $sma3);
                  //echo "<pre>"; print_r($r);
                    //echo "<pre>"; print_r($trend1); print_r($trend2);
                    // if($s[($sma1-1)] <  $s2[($sma1-1)])
                    //     echo "Up";
                    // elseif($p['close'] > $p['sma2'])
                    //     echo "Down"; 

                    array_push($arr, array('symbol' => $d[2], 'sma1' => $trend1, 'sma2' => $trend2, 'sma3' => $trend3, 'close' => $s[($sma3-1)], 'rsi' => $r[($sma3-1)]));
                    //array_push($arr, array('symbol' => $d[2], 'sma1' => $s1[($sma1-1)], 'sma2' => $s2[($sma1-1)], 'close' => $s[($sma1-1)], 'rsi' => $r[($sma1-1)]));

                } else {
                    // echo $d[2]."<br>";
                }
               //echo "<pre>"; print_r($arr);
                //echo $s1[20]. ">" .$s[20];
                 //echo $s2[20]. ">" .$s[20]; 
                // if($s1[20] > $s[20])
                // {
                //     return true;
                // }
                // elseif($s2[20] > $s[20])
                // {
                //     return false;
                // } 
           }
          // return json_encode($arr);  
       return View::make('screener.screener')->with('arr',$arr);
        }

        function getSMA($ltp, $sma)
        {
            $s = trader_sma($ltp, $sma);
            if($ltp[($sma-1)] <  $s[($sma-1)])
                        return 'Up';
            elseif($ltp[($sma-1)] >  $s[($sma-1)])
                        return 'Down'; 
        }
        //return array($l->SYMBOL, $value->CLOSEP, $s1, $s2);
    }