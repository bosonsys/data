console.log("getPositions Started..."); 
var token = getCookie("public_token");
let target = 1;
let stop = -1;
var positions;
var orders;
// let watchList = getWatchList(4);
// console.log(watchList);
getPositions();
getOrderBook();
setInterval(function () {
  let d = positionWatch();
}, 10 * 1000);

function getPositions() {
    $.ajax({
      url: "https://kite.zerodha.com/api/portfolio/positions",
      headers: {"x-csrftoken": token},
    }).done(function(result) {
        // console.log(result);
      positions = getMISPosition(result.data.day);
    });
}

function getOrderBook() {
  $.ajax({
    url: "https://kite.zerodha.com/api/orders",
    headers: {"x-csrftoken": token},
  }).done(function(result) {
      console.log("Orders", result);
      orders = result.data;
    // positions = getMISPosition(result.data.day);
  });
}

function getMISPosition(r) {
  // console.log(r);
  let rs = r.filter(function (e) {
    return e.product == "MIS" && e.quantity != 0
  });
  return rs;
}

function getOrderPrice(q, t) {
  let rs = orders.filter(function (e) {
    return e.product == "MIS" && e.tradingsymbol == t && e.quantity == q
  });
  // console.log(rs.length);
  return rs[(rs.length - 1)].average_price;
}
function positionWatch() {
    let sData = localStorage.getItem('__storejs_kite_ticker/ticks');
    let sd = parsePData(JSON.parse(sData));
    return sd;
}

function parsePData(sd) {
  positions.forEach(function(a) {
          a.last_price = sd[a.instrument_token].lastPrice;
          if (a.buy_quantity > a.sell_quantity) {
            a.call = "BUY";
            let op = getOrderPrice(a.buy_quantity - a.sell_quantity, a.tradingsymbol);
            // console.log(op);
            a.percentage = (a.last_price - op) / op * 100;
            checkTarget(a,'SELL');
            // console.log(a.tradingsymbol + ' Buy at ' + a.buy_price + " qty " + a.buy_quantity +
            //   " cValue " + a.last_price + " % " + a.percentage);
          } else if(a.buy_quantity < a.sell_quantity) {
            a.call = "SELL";
            let op = getOrderPrice(a.sell_quantity - a.buy_quantity, a.tradingsymbol);
            a.percentage = (op - a.last_price) / op * 100;
            checkTarget(a, 'BUY');
            // console.log(a.tradingsymbol + ' Sell at ' + a.sell_price + " qty " + a.sell_quantity + 
            //   " cValue " + a.last_price + " % " + a.percentage);
          }
      });
  return watchList;
}

function checkTarget(d, a) {
  console.log(d.tradingsymbol, parseFloat(d.percentage).toFixed(2));
  if (d.percentage >= target) {
    // squareOFF(d.tradingsymbol, (d.buy_quantity - d.sell_quantity), a, 't')
  } else if (d.percentage <= stop) {
    // squareOFF(d.tradingsymbol, (d.buy_quantity - d.sell_quantity), a, 's')
  }
}


function squareOffSell(d) {
  console.log(d.tradingsymbol, parseFloat(d.percentage).toFixed(2));
  if (d.percentage >= target) {
    squareOFF(d.tradingsymbol, (d.buy_quantity - d.sell_quantity), 'BUY', 't')
  } else if (d.percentage <= stop) {
    squareOFF(d.tradingsymbol, (d.buy_quantity - d.sell_quantity), 'BUY', 's')
  }
}

function squareOFF(s, q, t, f) {
  console.log(s, q, t, f);
  // delete myArray["lastname"];
    placeOrder(s,q, t, 'MIS');
}

// setInterval(function () {
// 	let d = marketWatch(watchList);
// 	insertWatch(d, 'watch5');
// }, (30 * 1000));


// setTimeout(function(){
//     placeOrder(token);
// }, 2000);

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