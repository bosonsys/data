<?php

class BacktestController extends \KiteController {

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
        $collection = collect(DB::table('kite_watch')->get());
    }

    public function backtest()
    {
		$script = 'IBULHSGFIN';
        $ldate ='2018-10-26';
        $test = DB::table('kite_watch')
                    ->where('tradingsymbol','=', $script)
                    ->where('insert_on', '>',  $ldate.' 09:14:00')
                    // ->where('insert_on', '>=', \DB::raw('DATE_SUB(NOW(), INTERVAL 1 MINUTE)'))
					->orderBy('id', 'DESC')
					// ->toArray();
					->get();
		//$backtest = array();
		$result = array_map(function ($value) {
    					return (array)$value;
				}, $test);


		foreach($result as $key => $v)
		{
			$trend = $this->isTrendChange($v['sma1'], $v['sma2'], $v['tradingsymbol']);
			// $dataArray=$data->toArray();
			if($trend)
				$call[] = $this->screenCall($script, $v);
		}
		echo "<pre>";
        print_r($call);
    }
}
