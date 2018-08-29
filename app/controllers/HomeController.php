<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
        echo "Muhtu";
		return View::make('hello');
	}

    public function dashboard() {
        $stock = DB::table('csvdata')
            ->orderBy('timestamp', 'desc')
            ->take(1)
            ->get(array('timestamp'));
//        print_r($stock);
//        exit;

		if($stock)
			$date1=(date_create($stock[0]->timestamp));
		else
			$date1=date_create('2014-02-23');
//			$date1=date_create('2014-02-23');
        $date2=date_create(date("Y-m-d"));
        $diff=date_diff($date1,$date2);
        $date = new DateTime();
        $data['lastdate'] = $date1->format('Y-m-d');
        $data['pastdate'] = $diff->format("%R%a days");
        // echo $diff->format("%d");
// echo "<pre>"; print_r($diff->days); exit();
        $maxDate = $diff->days<8?$diff->days:8;
        for($i = $maxDate;$i>0;$i--) {
            //if($date->format("l") != 'Sunday' && $date->format("l") != 'Saturday')
            {
                // echo "i = $i<br>";
                $download[$i]['date'] = $date->format("Y-m-d");
                $download[$i]['day'] = $date->format("l");
                $download[$i]['url'] = "http://www.nseindia.com/content/historical/EQUITIES/" . $date->format("Y") . "/" . strtoupper($date->format("M")) . "/" . "cm" . $date->format("d") . strtoupper($date->format("M")) . $date->format("Y") . "bhav.csv.zip";
                $date->modify("-1 day");
            }
        }
        if(isset($download))
            $data['download'] = $download;
        else
            $data['download'] = "";
        return View::make('stock.dashboard')->with('data', $data);
    }

    public function download() {
		//echo "asdf";
        $zip = new ZipArchive;
		//print_r($zip);
		//exit;
        //$path = "C:\\wamp\\www\\Muthu\\market\\public\\zip\\";
        // $path = "C:\\Users\\Administrator\\Downloads\\";
        $path = "C:\\Users\\muthu\\Downloads\\";
        foreach(glob($path.'*bhav.csv.zip') as $filename){
            echo "<br>File Name : ".$filename;
            $res = $zip->open($filename);
        if ($res === TRUE) {
            $zip->extractTo('C:\\xampp\\htdocs\\market\\public\\unzip\\');
            $zip->close();
            unlink($filename);
            echo ' - Done!';
        } else {
            echo ' - Error!';
        }
		  $this->upload();
        }
        return Redirect::to('/')->with('message', 'Data Added Successfully');
    }

    public function upload() {
//        csvToArray('C:\\wamp\\www\\Muthu\\market\\public\\unzip\\cm21AUG2014bhav.csv');
        $path = "C:\\xampp\\htdocs\\market\\public\\unzip\\";
        foreach(glob($path.'*.csv') as $filename){
        $row = 1;
        $flag = true;
        if (($handle = fopen($filename, "r")) !== FALSE) {
            while (($data = fgetcsv($handle, 2000, ",")) !== FALSE) {
                if($flag) { $flag = false; continue; }
                $num = count($data);
                //echo "$num fields in line $row: <br>";
                echo "</br>Row Inserted : $row";
                $row++;
                if($data[7]) {
                     $a = array(
                         'SYMBOL' => $data[0],
                         'SERIES' => $data[1],
                         'OPEN' => $data[2],
                         'HIGH' => $data[3],
                         'LOW' => $data[4],
                         'CLOSE' => $data[5],
                         'LAST' => $data[6],
                         'PREVCLOSE' => $data[7],
                         'TOTTRDQTY' => $data[8],
                         'TOTTRDVAL' => $data[9],
                         'TIMESTAMP' => date("Y-m-d", strtotime($data[10])),
                         'TOTALTRADES' => $data[11],
                         'ISIN' => $data[12],
                         'OPENP' => round(($data[2]-$data[7]) * (100/$data[7]),2),
                         'CLOSEP' => round(($data[5]-$data[7]) * (100/$data[7]),2)
                     );
                DB::table('csvdata')->insert($a);
                }
            }
            }
            fclose($handle);
            unlink($filename);
        }
    }
}