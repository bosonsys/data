<?php
$vendorDir = dirname(dirname(__FILE__));
//require_once $vendorDir.'\..\vendor\dg\rss-php\src\feed.class.php';

class MarketController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */

	public function index($filter,$lim = 40)
	{
        switch ($filter) {

            case 'top':
                $stock = DB::table('eqdata')
                ->join('company', 'eqdata.isin', '=', 'company.isin')
                    ->where('eqdata.series','EQ')
                    ->orderBy('eqdata.closetotal', 'desc');
                break;
            case 'lose':
                $stock = DB::table('eqdata')
                ->join('company', 'eqdata.isin', '=', 'company.isin')
                    ->where('eqdata.series','EQ')
                    ->orderBy('eqdata.closetotal', 'asc');
                break;
            case 'top1':
                $stock = DB::table('eqdata')
                ->join('company', 'eqdata.isin', '=', 'company.isin')
                    ->where('eqdata.series','EQ')
                    ->where('closediff1','>=','0')
                    ->where('closediff2','>=','0')
                    ->where('closediff3','>=','0')
                    ->where('closediff4','>=','0')
                    ->where('closediff5','>=','0')
                    ->orderBy('eqdata.closetotal', 'desc');
                break;
            case 'lose1':
                $stock = DB::table('eqdata')
                ->join('company', 'eqdata.isin', '=', 'company.isin')
                    ->where('eqdata.series','EQ')
                    ->where('closediff1','<=','0')
                    ->where('closediff2','<=','0')
                    ->where('closediff3','<=','0')
                    ->where('closediff4','<=','0')
                    ->where('closediff5','<=','0')
                    ->orderBy('eqdata.closetotal', 'asc');
                break;
            case 'lasttop':
                $stock = DB::table('eqdata')
                ->join('company', 'eqdata.isin', '=', 'company.isin')
                    ->where('eqdata.series','EQ')
                    ->orderBy('eqdata.closediff5', 'desc');
                break;
            case 'lastlose':
                $stock = DB::table('eqdata')
                ->join('company', 'eqdata.isin', '=', 'company.isin')
                    ->where('eqdata.series','EQ')
                    ->orderBy('eqdata.closediff5', 'asc');
                break;
        }
		$stock = $stock
		//->leftjoin('nse_list_new','eqdata.nse','=','nse_list_new.NSECode')
				->take($lim)
				->get();
                // print_r($stock);
        return View::make('stock.index')
            ->with('stocks', $stock);
	}

    public function stock($nse)
	{
        $stock = DB::table('csvdata')->select('OPEN as o', 'CLOSE as c', 'HIGH as h', 'LOW as l', 'TIMESTAMP as t')
        ->where('SYMBOL',$nse)
        ->where('SERIES','EQ')->get();
        return View::make('stock.stock')->with('d', $stock)->with('nse', $nse);
        // return json_encode($stock);
	}

    public function stockJSON($nse)
	{
       
        //$ldate = date('Y-m-d');
        $stock = DB::table('csvdata')->select('Timestamp','High', 'Low', 'Open', 'Close','Tottrdval')
       // ->where('updatedTime', '>',  $ldate.' 09:30:00')
        ->where('Symbol',$nse)
        ->get();
        $arr = array();
        foreach ($stock as $key => $value) {
            // echo $key." - ".$value;
            $rec = array(strtotime($value->Timestamp)*1000 ,$value->High,$value->Low,$value->Open,$value->Close,($value->Tottrdval)*100);
            array_push($arr, $rec);
        }
                    // print_r($arr);
        //return View::make('stock.stock')->with('d', $stock)->with('nse', $nse);
        return Response::json($arr);
// json_encode($arr);
	}

    /*public function stockJSON($nse)
	{
       
        //$ldate = date('Y-m-d');
        $stock = DB::table('marketwatch')->select('updatedTime', 'LTPrice', 'High', 'Low', 'Open', 'Close')
       // ->where('updatedTime', '>',  $ldate.' 09:30:00')
        ->where('TradingSymbol',$nse)
        ->get();
        $arr = array();
        foreach ($stock as $key => $value) {
            // echo $key." - ".$value;
            $rec = array(strtotime($value->updatedTime)*1000 ,$value->LTPrice,$value->High,$value->Low,$value->Open,$value->Close);
            array_push($arr, $rec);
        }
                    // print_r($arr);
        // return View::make('stock.stock')->with('d', $stock)->with('nse', $nse);
        return Response::json($arr);
// json_encode($arr);
	}*/

    public function storeTable($date, $day)
    {
        $company = DB::table('cnx500')->get();
//        $company = DB::table('csvdata')->distinct()->take(5)->get('timestamp');
//        $company = DB::table('company')->distinct()->take(20)->get();
//        echo "<pre>";
        $days = array();
        for($i=0;$i<$day;$i++){
            $days[] = date('Y-m-d', strtotime($date."-$i days"));
        }
        DB::table('eqdata')->truncate();
        foreach($company as $row) {
            $opentotal  = 0;
            $closetotal = 0;
            echo $row->symbol;
            $stock = DB::table('csvdata')
                ->where('isin',$row->isin)
                ->where('series','EQ')
                ->whereIn('timestamp', $days)
                ->get(array('symbol','close','timestamp','openp','closep'));
//            print_r($stock);
            $i = 1;
            foreach($stock as $r) {
                $tmpdate[$i]    = $r->timestamp;
                $tmpcp[$i]      = $r->openp;
                $tmppv[$i]      = $r->closep;
                $price      = $r->close;
                $opentotal+=$r->openp;
                $closetotal+=$r->closep;
                $i++;
            }
            if($opentotal) {
                $openavg = $opentotal / ($i-1);
                $closeavg = $closetotal / ($i-1);

            //`nse`, `date1`, `opendiff1`, `closediff1`, `date2`, `opendiff2`, `closediff2`, `date3`, `opendiff3`, `closediff3`, `date4`, `opendiff4`, `closediff4`, `date5`, `opendiff5`, `closediff5`, `opentotal`, `openavg`, `closetotal`, `closeavg`
                $q = array('nse' => $row->symbol,
                    'isin'=>$row->isin,
                    'series'=>$row->series,
                    'price'=>$price,
                    'date1' => $tmpdate[1],'date2' => $tmpdate[2],'date3' => $tmpdate[3],'date4' => $tmpdate[4],'date5' => $tmpdate[5],
                    'closediff1' => $tmppv[1],'closediff2' => $tmppv[2],'closediff3' => $tmppv[3],'closediff4' => $tmppv[4],'closediff5' => $tmppv[5],
                    'opendiff1' => $tmpcp[1],'opendiff2' => $tmpcp[2],'opendiff3' => $tmpcp[3],'opendiff4' => $tmpcp[4],'opendiff5' => $tmpcp[5],
                    'opentotal' => $opentotal,
                    'closetotal' => $closetotal,
                    'openavg' => $openavg,
                    'closeavg' => $closeavg
                );
            DB::table('eqdata')->insert($q);
            echo " - Done<br>";
            }
            flush();
            ob_flush();
        }
    }

    public function updateTable($script, $year = NULL)
    {
        $data = fopen("C:\\xampp\\htdocs\\market\\public\\data\\".$script.".csv", "r");

        $dates = DB::table('csvdata')->distinct('TIMESTAMP')->take(5)->orderBy('TIMESTAMP', 'desc')->get(array('TIMESTAMP'));
        $it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($dates));
        $l = iterator_to_array($it, false);

        DB::table('eqdata')->truncate();
        $j = 0;
        while (($d = fgetcsv($data)) !== FALSE)
        {
          if ($year) {
            $u = substr($d[1],-2);
                 // echo $u."<br>";
                 if ($u != $year) {
                     continue;
                 }
             }  
          if ($j!=0) {
            $opentotal  = 0;
            $closetotal = 0;
            echo $j.") ".$d[0];
            $stock = DB::table('csvdata')
                ->where('isin',$d[4])
                ->where('series','EQ')
                ->whereIn('timestamp', $l)
                ->orderBy('TIMESTAMP', 'asc')
                ->get(array('symbol','close','timestamp','openp','closep'));
            // print_r($stock);
            // echo $stock->timestamp;
            // exit;
            $i = 1;
            foreach($stock as $r) {
                // print_r($r);
                $tmpdate[$i]    = $r->timestamp;
                $tmpcp[$i]      = $r->openp;
                $tmppv[$i]      = $r->closep;
                $price      = $r->close;
                $opentotal+=$r->openp;
                $closetotal+=$r->closep;
                $i++;
            }
            // exit();
            if($opentotal) {
                $openavg = $opentotal / ($i-1);
                $closeavg = $closetotal / ($i-1);

                $q = array('nse' => $d[2],
                    'isin'=>$d[4],
                    'series'=>$d[3],
                    'price'=>$price,
                    'date1' => $tmpdate[1],'date2' => $tmpdate[2],'date3' => $tmpdate[3],'date4' => $tmpdate[4],'date5' => $tmpdate[5],
                    'closediff1' => $tmppv[1],'closediff2' => $tmppv[2],'closediff3' => $tmppv[3],'closediff4' => $tmppv[4],'closediff5' => $tmppv[5],
                    'opendiff1' => $tmpcp[1],'opendiff2' => $tmpcp[2],'opendiff3' => $tmpcp[3],'opendiff4' => $tmpcp[4],'opendiff5' => $tmpcp[5],
                    'opentotal' => $opentotal,
                    'closetotal' => $closetotal,
                    'openavg' => $openavg,
                    'closeavg' => $closeavg
                );
            DB::table('eqdata')->insert($q);
            echo " - Done</br>";
            }
            flush();
            ob_flush();
        }
            $j++;
        }
		return Redirect::to('/')->with('message', 'Data updated Successfully');
    }

// public function newTable($script, $year = NULL)
//     {
//         $data = fopen("C:\\xampp\\htdocs\\market\\public\\data\\".$script.".csv", "r");

//         $dates = DB::table('csvdata')->distinct('TIMESTAMP')->take(5)->orderBy('TIMESTAMP', 'desc')->get(array('TIMESTAMP'));
//         $it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($dates));
//         $l = iterator_to_array($it, false);
// // echo "<pre>"; print_r($l); exit();
//         DB::table('eqdata')->truncate();
//         $j = 0;
//         while (($d = fgetcsv($data)) !== FALSE)
//         {
//             if ($year) {
//                 $u = substr($d[1],-2);
//                 // echo $u."<br>";
//                 if ($u != $year) {
//                     continue;
//                 }
//             }
 
//             // $t = date('h:i A',$dt);
//            // echo $d,'<br/>',$t;
//           if ($j!=0) {
//             $opentotal  = 0;
//             $closetotal = 0;
//             echo $j.") ".$d[0];
//             $stock = DB::table('csvdata')
//                 ->where('isin',$d[4])
//                 ->where('series','EQ')
//                 ->whereIn('timestamp', $l)
//                 ->orderBy('TIMESTAMP', 'asc')
//                 ->get(array('symbol','close','timestamp','openp','closep'));
//             // print_r($stock);
//             // echo $stock->timestamp;
//             // exit;
//             $i = 1;
//             foreach($stock as $r) {
//                 // print_r($r);
//                 $tmpdate[$i]    = $r->timestamp;
//                 $tmpcp[$i]      = $r->openp;
//                 $tmppv[$i]      = $r->closep;
//                 $price      = $r->close;
//                 $opentotal+=$r->openp;
//                 $closetotal+=$r->closep;
//                 $i++;
//             }
//             // exit();
//             if($opentotal) {
//                 $openavg = $opentotal / ($i-1);
//                 $closeavg = $closetotal / ($i-1);

//                 $q = array('nse' => $d[2],
//                     'isin'=>$d[4],
//                     'series'=>$d[3],
//                     'price'=>$price,
//                     'date1' => $tmpdate[1],'date2' => $tmpdate[2],'date3' => $tmpdate[3],'date4' => $tmpdate[4],'date5' => $tmpdate[5],
//                     'closediff1' => $tmppv[1],'closediff2' => $tmppv[2],'closediff3' => $tmppv[3],'closediff4' => $tmppv[4],'closediff5' => $tmppv[5],
//                     'opendiff1' => $tmpcp[1],'opendiff2' => $tmpcp[2],'opendiff3' => $tmpcp[3],'opendiff4' => $tmpcp[4],'opendiff5' => $tmpcp[5],
//                     'opentotal' => $opentotal,
//                     'closetotal' => $closetotal,
//                     'openavg' => $openavg,
//                     'closeavg' => $closeavg
//                 );
//             DB::table('eqdata')->insert($q);
//             echo " - Done</br>";
//             }
//             flush();
//             ob_flush();
//         }
//             $j++;
//         }
// 		return Redirect::to('/')->with('message', 'Data updated Successfully');
//     }

    public function newsReader()
    {
        // $rss[] = Feed::loadRss('http://www.moneycontrol.com/rss/economy.xml');
//        $rss[] = Feed::loadRss('http://www.moneycontrol.com/rss/business.xml');
        $rss[] = Feed::loadRss('http://www.moneycontrol.com/rss/marketoutlook.xml');
        $rss[] = Feed::loadRss('http://www.moneycontrol.com/rss/technicals.xml');
       // $rss[] = Feed::loadRss('http://www.moneycontrol.com/rss/mostpopular.xml');
      //  $rss[] = Feed::loadRss('http://www.moneycontrol.com/rss/marketedge.xml');
     //   $rss[] = Feed::loadRss('http://feeds.feedburner.com/NDTV-Business?format=xml');
        // $rss[] = Feed::loadRss('http://economictimes.indiatimes.com/rssfeeds/2146842.cms');
        $rss[] = Feed::loadRss('http://economictimes.indiatimes.com/Markets/markets/rssfeeds/1977021501.cms');
        // $rss[] = Feed::loadRss('http://economictimes.indiatimes.com/rssfeeds/594027522.cms');
//        $rss[] = Feed::loadRss('http://economictimes.indiatimes.com/markets/stocks/recos/articlelist/3053611.cms');
//print_r($rss);
//exit;
        return View::make('stock.news')
            ->with('rss', $rss);
    }


    public function getData($stock)
	{
/*        $nseUrl = "http://www.nseindia.com/live_market/dynaContent/live_watch/get_quote/ajaxGetQuoteJSON.jsp?symbol=ACC";

        $uri = "https://www.googleapis.com/freebase/v1/mqlread?query=%7B%22type%22:%22/music/artist%22%2C%22name%22:%22The%20Dead%20Weather%22%2C%22album%22:%5B%5D%7D";
        $response = \Httpful\Request::get($nseUrl)->send();
print_r($response);*/
        //echo 'The Dead Weather has ' . count($response->body->result->album) . " albums.\n";

        /*
        $ch = curl_init($nseUrl);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        echo $output;*/

//        $json = file_get_contents($nseUrl.$stock);
//        $obj = json_decode($json);
        return View::make('stock.stock');
	}

}