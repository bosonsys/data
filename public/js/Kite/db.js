let db = createDB();
//
// Define your database
//
function createDB() {
	var db = new Dexie("Market");
	db.version(1).stores({
	  position: 'name, ltp, datetime',
	  order: 'orderId, name, type, entry, exit, datetime',
	  watch: `++, absoluteChange, averagePrice, closePrice, highPrice, lastPrice,lastQuantity, 
	  	lowPrice, openPrice, tickChange,totalBuyQuantity, totalSellQuantity, tradingsymbol, volume, 
	  	sma1, sma2, rsi, created_on`
	});
	return db;
}

//
// Put some data into it
//
function insertWatch(data) {
	// console.log(data);
	const now = new Date();
	timestamp = now.getTime();
	data.forEach(function(e) {
		let r = getRec(e.tradingsymbol).then(function(d) {
		// console.log(d);
		const ltpArr = d.map(d1 => d1.lastPrice);
		var sma1 = sma({period : 9, values: ltpArr});
		var sma2 = sma({period : 25, values: ltpArr});
		var inputRSI = {
		  values : ltpArr,
		  period : 14
		};
		let r = RSI.calculate(inputRSI);
		let sma1Val = sma1[sma1.length-1];
		let sma2Val = sma2[sma2.length-1];
		let rsiVal = r[r.length-1];

		// console.log("SMA1", sma1[sma1.length-1]);
		// console.log("SMA2", sma2[sma2.length-1]);
		// console.log("RSI", rsiVal);
			e.created_on = now;
			e.sma1 = sma1Val;
			e.sma2 = sma2Val;
			e.rsi = rsiVal;
			db.watch.add(e).then(function(lastKey) {
			  console.log("Inserted - "+lastKey);
			}).catch(function(error) {
			 	console.log("Ooops: " + error);
			});
		});
		// console.log(r);
	});
	// console.log(data);
	return data;
}

function getRec(s) {
	return db.watch.where({tradingsymbol: s})
	    .reverse()
	    .limit(25)
	    .toArray()
	    .then(function (results) {
	        // console.log (JSON.stringify(results));
	        return results;
	    });
}

// update on MySQL
function updateMarketWatch(d, n) {
  // console.log(d);
 $.ajax({
            type: "POST",
            url: "http://localhost/market/public/update/marketwatch",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify({data:d, nifty:n}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(res){
              if (res != null) {
                console.log(res);
                // let index = d.findIndex(x => x.TradingSymbol==res.nse);
                // 	enterCall(index,res);
                 // portfolioCall();
             }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}
