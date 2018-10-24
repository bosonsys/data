console.log("Log Started..."); //for debug purpose so i can see it in the console log
var token = getCookie("public_token");

let watchList = getWatchList(4);
// console.log(watchList);

setInterval(function () {
	let d = marketWatch(watchList);
	insertWatch(d, 'watch1');
}, 10 * 1000);

setInterval(function () {
	let d = marketWatch(watchList);
	insertWatch(d, 'watch5');
}, (30 * 1000));


// setTimeout(function(){
//     placeOrder(token);
// }, 2000);

// setInterval(function () {
// 	// getPositions(token);
//     // console.log("Print Data");
//     // let wc = getWatchComp(sd);
//     // storeData(sd);
//     insertWatch(sd);
//     // insetinto MySQL
//     updateMarketWatch(sd, null);
//         // console.log(sd);
// }, 5 * 1000);


// chrome.storage.onChanged.addListener(function(changes, namespace) {
//     for (key in changes) {
//       var storageChange = changes[key];
//       console.log('Storage key "%s" in namespace "%s" changed. ' +
//                   'Old value was "%s", new value is "%s".',
//                   key,
//                   namespace,
//                   storageChange.oldValue,
//                   storageChange.newValue);
//     }
//   });

// chrome.storage.local.set({'key': 'SDAFASDF'}, function() {
//   console.log('Value is set to ' + value);
// });
   
//   setTimeout(function () {
// 	chrome.storage.local.get(['tabex_default_master'], function(result) {
// 	  console.log('Value currently is ' + result.key);
// 	});
// }, 2000);   


//  chrome.storage.local.get(['key'], function(result) {
//  	console.log('Value currently is ' + result.key);
//  });

/*
https://kite.zerodha.com/api/orders/regular

Request Method: POST

:authority: kite.zerodha.com
:method: POST
:path: /api/orders/regular
:scheme: https
accept: application/json, text/plain, */
/*
accept-encoding: gzip, deflate, br
accept-language: en-GB,en-US;q=0.9,en;q=0.8,de;q=0.7,fr;q=0.6,ms;q=0.5,ta;q=0.4
content-length: 230
content-type: application/x-www-form-urlencoded
cookie: __cfduid=dd9705c5f085eb9b91f3ba012d56c7aa91534678020; _ga=GA1.2.525067785.1535698557; kfsession=wRWmF7Xzl2hPEwI1fz7Eld1X3JL4H0SB; public_token=GMDeq5A43aYuz5QwLwF3f5hz0eqa8wCk; user_id=DF7292
origin: https://kite.zerodha.com
referer: https://kite.zerodha.com/holdings
user-agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36
x-csrftoken: GMDeq5A43aYuz5QwLwF3f5hz0eqa8wCk
x-kite-version: 1.10.1


exchange=NSE&tradingsymbol=LEMONTREE&transaction_type=BUY&order_type=MARKET&quantity=1&price=0&product=CNC&validity=DAY&disclosed_quantity=0&trigger_price=0&squareoff=0&stoploss=0&trailing_stoploss=0&variety=regular&user_id=DF7292

exchange: NSE
tradingsymbol: LEMONTREE
transaction_type: BUY
order_type: MARKET
quantity: 1
price: 0
product: CNC
validity: DAY
disclosed_quantity: 0
trigger_price: 0
squareoff: 0
stoploss: 0
trailing_stoploss: 0
variety: regular
user_id: DF7292

https://kite.zerodha.com/api/orders/regular

:authority: kite.zerodha.com
:method: POST
:path: /api/orders/regular
:scheme: https
accept: application/json, text/plain, 
accept-encoding: gzip, deflate, br
accept-language: en-GB,en-US;q=0.9,en;q=0.8,de;q=0.7,fr;q=0.6,ms;q=0.5,ta;q=0.4
content-length: 232
content-type: application/x-www-form-urlencoded
cookie: __cfduid=dd9705c5f085eb9b91f3ba012d56c7aa91534678020; _ga=GA1.2.525067785.1535698557; kfsession=0ZxQA6iVly9195pVGjZPjckp78asifsT; public_token=E3bbU4sreeLJtlPkzg5jaY2qQmLbD519; user_id=DF7292
origin: https://kite.zerodha.com
referer: https://kite.zerodha.com/holdings
user-agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36
x-csrftoken: E3bbU4sreeLJtlPkzg5jaY2qQmLbD519
x-kite-version: 1.10.1

exchange: NSE
tradingsymbol: HDFCBANK
transaction_type: BUY
order_type: LIMIT
quantity: 10
price: 1980
product: MIS
validity: DAY
disclosed_quantity: 0
trigger_price: 0
squareoff: 0
stoploss: 0
trailing_stoploss: 0
variety: regular
user_id: DF7292
*/