@extends('layouts.app')

@section('content')
<style>
  .ui-autocomplete-loading {
    background: white url("images/ui-anim_basic_16x16.gif") right center no-repeat;
  }
  </style>
<div class="container">
  <form method="post" action="{{ route('company.create') }}">
    {{ csrf_field() }}
    <div class="row">
    <div class="col-md-3">
     <div class="form-group">
      <label for="exampleFormControlSelect1">Select Exchange</label>
      <select class="form-control category" id="category" name="category" value="" placeholder="Select Company" required="" onchange="exchange(this.value)">
        <option value ="0">Select Exchange</option>
        <option value ="NSE">NSE</option> 
        <option value ="BSE">BSE</option>               
      </select>
	  <input type="hidden" name="hidExchange" id="hidExchange" value='0' class="form-control input-lg" />
    </div>
      <div class="form-group">
        <label for="exampleFormControlSelect1">Select Company </label>
        <input type="text" name="company_name" id="company_name" class="form-control input-lg" placeholder="Enter Company Name" />
		<div id="companyList"></div>
      </div>	
    </div>

    <div class="col-md-3">
      <div class="ui-widget">
        <label for="birds">Birds: </label>
        <input id="birds">
      </div>
      
      <div class="ui-widget" style="margin-top:2em; font-family:Arial">
        Result:
        <div id="log" style="height: 200px; width: 300px; overflow: auto;" class="ui-widget-content"></div>
      </div>
    </div>
    </div>
    <div class="col-md-2">
    <div class="form-group">
    <br>
      <button class="btn btn-primary" type="submit">Add</button>  
    </div>
    </div>
    <div class="col-md-4">
      <h2>User Company List</h2>
    </div>
  </div>
  </form>
  <table id="example" class="table table-striped table-bordered nowrap" style="width:100%">
    <thead>
      <tr>
        <th>No#</th>
        <th>Symbol</th>
        <th>Compnay Name</th> 
        <th>Exchange</th>                
        <th>Action</th>                
      </tr>
    </thead>
    <tbody>
      @php ($i = 0)
        @foreach($data['data1'] as $val)
          <tr>
            <td>{{++$i}}</td>
            <td>{{$val->tradingsymbol}}</td>
            <td>{{$val->name}}</td>
            <td>{{$val->exchange}}</td>                 
            <td><a href="Javascript:del({{$val->id}})">Delete</a></td>                 
          </tr>
        @endforeach
    </tbody>   
  </table>
</div>
<script>
  
  function del(id) {
    console.log(id);
    $.ajax(
      {
        url: "{{ url('/user_comp') }}/"+ id,
        success: function(result){
          if (result == 1) {
            alert("Deleted Successfully")
            location.reload();
          } else {
            alert("Error in deleted")
          }
        }
    });
  }
  
function exchange(id)
{
	$('#hidExchange').val(id);
	$('#company_name').val('');  
    $('#companyList').fadeOut(); 	
}	
$(document).ready(function(){

		$('#company_name').keyup(function(){ 
			var query = $(this).val();
			var exchange = $('#hidExchange').val();
			if(exchange != 0)
			{
				if(query != '')
				{				
					 var _token = $('input[name="_token"]').val();
					 $.ajax({
					  url:"{{ route('selectCompany') }}/",
					  method:"POST",
					  data:{query:query,excVal:exchange, _token:_token},
					  success:function(data){
							$('#companyList').fadeIn();  
							$('#companyList').html(data);
						}
					 });
				}
			}
			else
			{
				alert('Please Select Exchange');
				$('#company_name').val('');
			}				
		});	
    $(document).on('click', 'li', function(){  
        $('#company_name').val($(this).text());  
        $('#companyList').fadeOut();  
    });  
	
   $(document).on('click',  function(){  
        $('#companyList').fadeOut();  
    });	
	
	

});  
</script>
@endsection
