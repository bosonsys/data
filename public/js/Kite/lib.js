
function isTrendChange(sma1, sma2, trend)
  {
    if (trend == "uptrend" || !trend) {
      if (sma1 > sma2) {
        trend = "downtrend";
        isChange = true;
      }
    }
    if (trend == "downtrend"  || !trend) {
      if (sma1 < sma2) {
        trend = "uptrend";
        isChange = true;
      }
    }
    return Array(trend, isChange);
  }

function marketWatch(watchList) {
    let sData = localStorage.getItem('__storejs_kite_ticker/ticks');
    let sd = parseSData(JSON.parse(sData), watchList);
    return sd;
}

function getWatchList(n = 4) {
  let marketwatch = localStorage.getItem('__storejs_kite_marketwatch/marketwatch');
  let watchList = JSON.parse(marketwatch)[n].items;
  return watchList;
}

function parseSData(sd, watchList) {
        let cName = [];
        let sdKey = Object.keys(sd)
        sdKey.forEach(function(a) {
        t = watchList.find(function (e) {
                return e.instrument_token == a;
        });
        if (t) {
            sd[a]['tradingsymbol'] = t.tradingsymbol;
            cName.push(sd[a]);
        }

       });
    return cName;
}
// Make all the Watch list as Single Array
function getSingleArr(a) {
    let temp = [];
 a.forEach(function(item) {
    item.items.forEach(function(c) {
        temp.push(c);
    });
 });
 return temp;
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


function getPositions(token) {
    $.ajax({
      url: "https://kite.zerodha.com/api/portfolio/positions",
      headers: {"x-csrftoken": token},
    }).done(function(result) {
        console.log(result);
    });
}

function placeOrder(token) {
    console.log("placeOrder");   
    $.ajax({
      url: "https://kite.zerodha.com/api/orders/regular",
      method: "POST",
      // data: {   exchange: "NSE",
      //           tradingsymbol: "INFY",
      //           transaction_type: "BUY",
      //           order_type: "LIMIT",
      //           quantity: 10,
      //           price: 698,
      //           product: "MIS",
      //           validity: "DAY",
      //           disclosed_quantity: 0,
      //           trigger_price: 0,
      //           squareoff: 0,
      //           stoploss: 0,
      //           trailing_stoploss: 0,
      //           variety: "regular",
      //           user_id: "DF7292"
      //       },
      headers: {"x-csrftoken": token},
    }).done(function(result) {
        console.log(result);
    });
}
