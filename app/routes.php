<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|


Route::get('/', function()
{
	return View::make('hello');
});
*/
Route::get('/', 'HomeController@dashboard');
Route::get('/movezip', 'HomeController@download');
Route::get('/upload', 'HomeController@upload');
Route::get('/market/{filter}', 'MarketController@index');
// Route::get('/updatetable/{date}/{day}', 'MarketController@storeTable');
Route::get('/market/stockJSON/{nse}', 'MarketController@stockJSON');
Route::get('/market/stock/{nse}', 'MarketController@stock');
Route::get('/updatefrtable/{table}', 'MarketController@updateFrTable');
Route::get('/updateKiteTable', 'MarketController@updateKiteTable');
Route::get('/updatetable/{script}/{year?}', 'MarketController@updateTable');
Route::get('/pennystock', 'MarketController@prevday');
//Route::get('/newtable/{script}/{year?}', 'MarketController@newTable');
Route::get('/marketwatch', 'MarketwatchController@index');
Route::get('/marketJSON', 'MarketwatchController@marketJSON');
Route::get('/marketwatch1', 'MarketwatchController@v2');
Route::get('/marketwatch/nsedata', 'MarketwatchController@getNSDdata');
Route::get('/marketwatch/nse50', 'MarketwatchController@nse50');
Route::get('/marketwatch/ETG500', 'MarketwatchController@ETGainersData');
Route::get('/news', 'MarketController@newsReader');
Route::get('/buysell', 'MarketController@callsReader');
Route::get('/trade/day', 'TradeController@index');
Route::post('/trade/add', 'TradeController@create');
Route::get('/trade/add', 'TradeController@create');
Route::get('/trade/settings', 'TradeController@getSettings');
Route::get('/call/{nse}', 'CallController@create');
Route::get('/call', 'CallController@index');
Route::get('/call/json/getRunningCall', 'CallController@getRunningCall');
Route::get('/call/json/portfolio', 'CallController@getPortfolio');
Route::post('/call/update/portfolio', 'CallController@updatePortfolio');
Route::post('/call/update/marketwatch', 'CallController@updateMarketwatch');
Route::post('/call/update/position', 'CallController@updateSinglePosition');
Route::post('/call/insert/intradayData', 'CallController@insertIntraTableDB');
Route::post('/intradayData/kite', 'KiteController@insertIntraKite');
Route::get('/call/edel/{nse}', 'CallController@redirect');
Route::get('/call/json/marketwatch', 'MarketwatchController@getData');
Route::get('/store/nsedata', 'MarketwatchController@getAllData');
// Route::get('/store/nsedata', 'MarketwatchController@gainerLoser');
Route::get('/call/json/gainers', 'MarketwatchController@getGainers');
Route::get('/call/json/nse50', 'MarketwatchController@getNSE50');
Route::get('/trade/calls', 'CallController@getCalls');
Route::get('/strategy/first15', 'StrategyController@first15');
Route::get('/strategy/uptrend', 'StrategyController@upTrend');
Route::get('/strategy/open', 'StrategyController@getCalls');
Route::get('/strategy/upDown', 'StrategyController@upDown');
Route::get('/breakout/5days', 'BreakoutController@days5');
Route::get('/last5days/{nse}', 'BreakoutController@last5days');
Route::get('/stock/{nse}', 'StockController@index');
Route::get('/callreport/{ldate?}', 'CallController@report');
//Route::get('/report', 'CallController@calldetails');
Route::get('/dashboard/lastday', 'DashboardController@lastday');
//Route::get('/dashboard/last5days', 'DashboardController@last5days');
Route::post('/update/marketwatch', 'KiteController@updateMarketwatch');
Route::get('/intra-suggest', 'StockController@lastday');
Route::get('/backtest', 'BacktestController@backtest');