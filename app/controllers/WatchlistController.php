<?php

class WatchlistController extends \BaseController {

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
	public function watchList($name = null)
	{
		if(!$name)
			$name = 'ind_nifty500list';	
		$data = fopen("C:\\xampp\\htdocs\\market\\public\\data\\".$name.".csv", "r"); 
		//echo "<pre>"; print_r($data); exit;
		$list = DB::table('kite_margin')
				->select('Scrip','Multiplier')
				->get();
				$arr = array();
		while(($d = fgetcsv($data)) !== FALSE)
		{
			//  echo "<pre> ".$d[2];
			 foreach ($list as $key => $value) 				// print_r($value->Scrip);
             {
				if ($d[2] == $value->Scrip ) {
					array_push($arr, array($value->Scrip, $value->Multiplier));
				}
			 }
		}
	
	return json_encode($arr);
	}
}