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
//echo $ldate;
// exit;
		$compList = DB::table('kite_watch')
					->where('insert_on', '>',  $ldate.' 09:14:00')
					->where('insert_on', '<',  $ldate.' 15:20:00')
					->select('tradingsymbol')
					->distinct()
					->get();
					//echo "<pre>"; print_r($compList);
		Session::flush();
		// foreach ($compList as $key => $c) {
		// 	$this->runTest($c->tradingsymbol, $ldate);
		// 	// exit;
		// }
		$this->runTest('JETAIRWAYS', $ldate);

	}
	public function runTest($script, $ldate)
	{
		$test = DB::table('kite_watch')
			->where('tradingsymbol','=', $script)
			->where('insert_on', '>',  $ldate.' 09:14:00')
			->where('insert_on', '<',  $ldate.' 15:20:00')
			->orderBy('id', 'ASC')
			->get();
//echo "<pre>"; print_r($test);
		$result = array_map(function ($value) {
    					return (array)$value;
				}, $test);
		foreach($result as $key => $v)
		{
			$this->marketwatch($v, $v['id'], $ldate, $v['insert_on']);
		}
	}
}
