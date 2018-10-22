
function storeData(data){
	data.forEach(function(e) {
		let pd = getData(e.tradingsymbol);
		pd.push(e.lastPrice);
		console.log(pd);
	});
}

function getData(s) {
	// window.localStorage.setItem('user', JSON.stringify(person));
	return JSON.parse(window.localStorage.getItem(s));
}
//
// Put some data into it
//
function insertWatch1(data) {
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
}
