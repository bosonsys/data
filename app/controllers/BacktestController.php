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

	public function backtest($ldate=null)
	{
	if (!$ldate)
		$ldate = date('Y-m-d');

		// $compList = DB::table('kite_watch')
		// 			->where('insert_on', '>',  $ldate.' 09:14:00')
		// 			->where('insert_on', '<',  $ldate.' 15:20:00')
		// 			->select('tradingsymbol')
		// 			->distinct()
		// 			->get();
		// foreach ($compList as $key => $c) {
		// 	$this->runTest($c->tradingsymbol, $ldate);
		// 	// exit;
		// }
			$this->runTest('IBULHSGFIN', $ldate);

	}
	public function runTest($script, $ldate)
	{
		$test = DB::table('kite_watch')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00')
			->where('insert_on', '<',  $ldate.' 15:20:00')
			->orderBy('id', 'ASC')
			->get();

		$result = array_map(function ($value) {
    					return (array)$value;
				}, $test);

		foreach($result as $key => $v)
		{
			$SMA = $this->getSMA($v['tradingsymbol'], $v['insert_on']);
			$trend = $this->isTrendChange($SMA, $v['sma2'], $v['tradingsymbol']);
			if($trend)
				$call[] = $this->screenCall($script, $v, $v['insert_on']);
		}
		// echo '<pre>'; print_r($call);
	}
	public function getSMA($script, $time, $sma = 100)
	{
		$ldate = date('Y-m-d');
		$last50 = DB::table('kite_watch')
					->select('lastPrice')
					->where('tradingsymbol','=', $script)
					->where('insert_on', '>',  $ldate.' 09:14:00')
					->where('insert_on', '<',  $time)
					->orderBy('id', 'DESC')
					->take($sma)
					->get();
			$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($last50));
			$l = iterator_to_array($it, false);
			if (count($l) == $sma) {
				$sma50 = trader_sma($l, $sma);
				return $sma50[($sma - 1)];
			}
		return NULL;
	}
}
