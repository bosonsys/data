var KiteConnect = require("kiteconnect").KiteConnect;

var kc = new KiteConnect({
	api_key: "qw4l9hh030dgujks"
});

kc.generateSession("tqxUKEhJRYg1o3Ovvp1hzy7IJhnh4eMl", "l5ztksspq9jslkvp5gx9nq44qcdzvwdy")
	.then(function(response) {
		init();
	})
	.catch(function(err) {
		console.log(err);
	});

function init() {
	// Fetch equity margins.
	// You can have other api calls here.
	kc.getMargins()
		.then(function(response) {
			// You got user's margin details.
			console.log(response);
		}).catch(function(err) {
			// Something went wrong.
		});
}

