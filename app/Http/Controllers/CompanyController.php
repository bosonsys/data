<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Company;
use App\UserCompany;
use DB;



class CompanyController extends Controller
{
    public function index()
    {
    	$user = auth()->user();
				
		$data1 = DB::table('user_company')
		->select('kite_comp.tradingsymbol', 'kite_comp.tradingsymbol', 'user_company.id', 'kite_comp.name', 'kite_comp.exchange')
		->join('kite_comp', 'user_company.company_id', '=', 'kite_comp.id')
		->where('user_company.user_id', $user->id)
		->orderBy('user_company.id', 'DESC')
		->get();
		$data = ['data1' => $data1];

		return view('company/index', compact('data'));
    }

    public function create(Request $request)
    {
		$user = auth()->user();
		// $request->validate([
        // 	'name'=>'required',
      	// ]);

		$UserCompany = new UserCompany([
	        'company_id' =>$request->get('name'),
	        'user_id' => $user->id,
	        'status' => 1
	    ]);
    	$UserCompany->save();
    	return redirect()->back();
    }

    public function getCompany(Request $request)
    {
    	$exchangename = $request->value;
    	$company = Company::where('exchange', $exchangename)->get();
		return response()
            ->json($company);   	                   
    	                   
	}

	function searchCompany(Request $request)
	{
		 if($request->get('query'))
		 {
		  $query = $request->get('query');
		  $exVal = $request->get('excVal');
		  $data = Company::where('tradingsymbol', 'LIKE', "%{$query}%")
			->where('exchange', $exVal)
			->get();
		  $output = '<ul class="dropdown-menu" style="display:block; position:relative">';
		  if(count($data) != 0)
		  {
			  foreach($data as $row)
			  {
			   $output .= '
			   <li><a href="#">'.$row->tradingsymbol.'</a></li>
			   ';
			  }
		  }
		  else
		  {
			  $output .= '
			   <li><a href="#">No Search Result</a></li>';
		  }
		  $output .= '</ul>';
		  echo $output;
		 }
	}	
	
    public function searchCompany_old(Request $request)
    {
		$query = $request->input('query');

    	$company = Company::where('tradingsymbol', 'like', '%' . $query . '%')->get();
		return response()->json($company);   	                   
    	                   
	}
	
    public function getUserCompany(Request $request)
    {
		$user = auth()->user();
		// print($user);
		// SELECT * FROM `kite_margin`, kite_comp WHERE kite_margin.Multiplier > 3 and kite_margin.Scrip = kite_comp.tradingsymbol and kite_comp.exchange = 'NSE'

		$data = DB::table('user_company')
		->select('kite_comp.instrument_token as instrument_token', 'kite_comp.tradingsymbol as tradingsymbol')
		->join('kite_comp', 'user_company.company_id', '=', 'kite_comp.id')
		->where('user_company.user_id',  $user->id)
		->get();

		return response()
            ->json($data);   	                   
    	                   
	}

	public function delComp($id)
	{
		// echo $id;
		$data = DB::table('user_company')->where('id', $id)->delete();
		return response()
		->json($data);
	}
}
