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
			$name = 'ind_nifty500list';	
		$data = fopen("C:\\xampp\\htdocs\\market\\public\\data\\".$name.".csv", "r"); 
        //echo "<pre>"; print_r($data); exit;
        $sma1 = 21;
        $sma2 = 9;
        
        $list = DB::table('csvdata')
                 ->select('SYMBOL')
                 ->orderBy('TIMESTAMP', 'desc')
                 ->take(1)
                 ->get();
                 //echo "<pre>"; print_r($list); exit;

        
        $close = DB::table('csvdata')
                 ->select('CLOSEP')
                 ->where('SYMBOL',$list[0]->SYMBOL)
                 ->orderBy('TIMESTAMP', 'desc')
                 ->take($sma1)
                ->get();
                //echo "<pre>"; print_r($close); exit;
                $arr = array();
        $t =  new RecursiveIteratorIterator(new RecursiveArrayIterator($close));
        $s = iterator_to_array($t, false);
        $s1 = trader_sma($s, $sma1);
        $s2 = trader_sma($s, $sma2);
                echo "<pre>"; print_r($s1); print_r($s2); 
        $arr = array();
         $a = array();
         while(($d = fgetcsv($data)) !== FALSE)
         {
             //echo "<pre> ".$d[2];
             foreach ($list as $key => $l) 
             {
                 foreach ($close as $key => $value) 				// print_r($value->Scrip);
                 {  
                if ($d[2] == $l->SYMBOL) 
                {
                     array_push($a, array($l->SYMBOL));
                     array_push($arr, array($value->CLOSEP));
				}
			 }
        }
        return json_encode($a);
	return json_encode($arr);
    }
}
}