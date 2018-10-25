
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
    // console.log(sData);
    let sd = parseSData(JSON.parse(sData), watchList);
    return sd;
}

function getWatchList(n = 4) {
  let marketwatch = localStorage.getItem('__storejs_kite_marketwatch/marketwatch');
  let watchList = JSON.parse(marketwatch)[n].items;
  return watchList;
}

function parseSData(sd, watchList) {
  // console.log(sd);
  // console.log(watchList);
        let cName = [];
        let sdKey = Object.keys(sd)
        // console.log(sdKey);
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



function placeOrder(s, q, t, p) {
    console.log("placeOrder");
    console.log(s, q, t, p);
    $.ajax({
      url: "https://kite.zerodha.com/api/orders/regular",
      method: "POST",
      data: {   exchange: "NSE",
                tradingsymbol: s,
                transaction_type: t,
                order_type: "MARKET",
                quantity: Math.abs(q),
                price: 0,
                product: p,
                validity: "DAY",
                disclosed_quantity: 0,
                trigger_price: 0,
                squareoff: 0,
                stoploss: 0,
                trailing_stoploss: 0,
                variety: "regular",
                user_id: "DF7292"
            },
      headers: {"x-csrftoken": token},
    }).done(function(result) {
        console.log(result);
        getPositions();
    });
}
