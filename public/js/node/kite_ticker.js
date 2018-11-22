var KiteTicker = require("kiteconnect").KiteTicker;
var ticker = new KiteTicker({
	api_key: "qw4l9hh030dgujks",
	access_token: "tqxUKEhJRYg1o3Ovvp1hzy7IJhnh4eMl"
});

ticker.connect();
ticker.on("ticks", onTicks);
ticker.on("connect", subscribe);

function onTicks(ticks) {
	console.log("Ticks", ticks);
}

function subscribe() {
	var items = [738561];
	ticker.subscribe(items);
	ticker.setMode(ticker.modeFull, items);
}