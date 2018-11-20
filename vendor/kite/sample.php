<?php
	include dirname(__FILE__)."/kiteconnect.php";

	// Initialise.
	$kite = new KiteConnect("qw4l9hh030dgujks");

    // Assuming you have obtained the `request_token`
	// after the auth flow redirect by redirecting the
	// user to $kite->login_url()
	echo $kite->getLoginURL();
	
	// exit;
	echo "<pre>";
	print_r($kite->getInstruments());
	/* try {
		$user = $kite->generateSession("eGojJl3vqw6mnZEzRZAcg1AegVPc231v", "l5ztksspq9jslkvp5gx9nq44qcdzvwdy");

		echo "Authentication successful. \n";
		print_r($user);

		$kite->setAccessToken($user->access_token);
	} catch(Exception $e) {
		echo "Authentication failed: ".$e->getMessage();
		throw $e;
	}

	echo $user->user_id." has logged in";

	// Get the list of positions.
	echo "Positions: \n";
	print_r($kite->getPositions());

	// Get the list of holdings.
	echo "Holdings: \n";
	print_r($kite->getHoldings());

	// Retrieve quote and market depth for list of instruments.
	echo "Quote: \n";
	print_r($kite->getQuote(["NSE:INFY", "NSE:SBIN"]));

	// Place order.
/* 	$order_id = $kite->placeOrder("regular", [
		"tradingsymbol" => "INFY",
		"exchange" => "NSE",
		"quantity" => 1,
		"transaction_type" => "BUY",
		"order_type" => "MARKET",
		"product" => "NRML"
	])["order_id"];
 
	echo "Order id is ".$order_id; */
?>
