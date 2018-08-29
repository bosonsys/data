<?php

class TradeController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
        $stock =  "asdf";
        return View::make('trade.day')
            ->with('stocks', $stock);
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        //$intra = new Intraday;
        $inputA = Input::all();
//        Log::info($inputA);
        $i = 0;
//        $msg = "";
//        $intra = "";

        foreach($inputA as $input) {
            Log::info($i);
            Log::info($input);
            $intra[$i]['code'] = $input['Stock Code'];
            $intra[$i]['ltp'] = $input['LTP'];
            $intra[$i]['buy_qty'] = $input['Buy Qty'];
            $intra[$i]['avg_buy'] = $input['Avg. Buy Price'];
            $intra[$i]['buy_amt'] = str_replace(',', '', $input['Buy Amt']);
            $intra[$i]['sell_qty'] = $input['Sell Qty'];
            $intra[$i]['avg_sell'] = $input['Avg. Sell Price'];
            $intra[$i]['sell_amt'] = str_replace(',', '', $input['Sell Amt']);
            $intra[$i]['net_qty'] = $input['Net Qty'];
            $intra[$i]['type'] = $input['Product Type'];
            DB::table('intraday')->insert($intra[$i]);
            $i++;
//        $intra->rpl = $input['Realized Profit\/Loss'];
//        $intra->upl = $input['Unrealized Profit\/Loss'];
//        $intra->tpf = $input['Total Profit\/Loss'];
//        $intra->avg_sell = $input['Avg. Buy Price'];
        }

/*
        foreach($inputA as $input) {
            $intra['EdelCode'] = $input['Edel Code'];
            $intra['NSESymbol'] = $input['NSE Symbol'];
            $intra['ISIN'] = $input['ISIN'];
            $intra['IntradayMargin'] = $input['Intraday Margin'];
            DB::table('intraday_list1')->insert($intra);
        }
*/
        Log::info($intra);
        //$user = Intraday::create($intra);

        $msg = "Added Successfully";
        echo json_encode($msg);
	}

    public function getSettings(){
        $settings = DB::table('setting')->get();
       echo json_encode($settings);
    }


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}
