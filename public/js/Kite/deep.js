console.log("Deep Learning")
// var table = db.table('deep');
// exportWatch(table);

let comp = localStorage.getItem('multiplecharts4');
console.log(comp);

setInterval(function () {
    let sData = localStorage.getItem('__storejs_kite_ticker/ticks');
    // console.log(sData);
    let sd = getDeepData(JSON.parse(sData));
}, 10 * 1000);

function getDeepData(sd) {
    // sd = JSON.parse(jD);
    c = JSON.parse(comp);
    // console.log(c);
    // console.log(sd);
    
    let cName = [];
    c.forEach(function (e) {
        sd[e.token]["name"] = e.name;
        cName.push(sd[e.token]);
    });
    console.log(cName);
    insertWatch(cName, 'deep');
}

function insertWatch(data, t) {
    // console.log(data);
    var table = db.table(t);
    const now = new Date();
    timestamp = now.getTime();
        data.forEach(function (e) {
        if (e.depth) {
            e.depth.buy.forEach(function (a) {
                // console.log(a);
                a.name = e.name;
                a.time = timestamp;
                a.type = 'buy';
                table.add(a);
            });
            e.depth.sell.forEach(function (a) {
                // console.log(a);
                a.name = e.name;
                a.time = e.timestamp;
                a.type = 'sell';
                table.add(a);
            });
        }
        });
    return data;
}

function exportWatch(table){
	table.toArray()
		.then(function (results) {
			// console.log (JSON.stringify(results));
			alasql.promise('SELECT * INTO CSV("watch.csv", {headers:true, separator:","}) FROM ?',[results])
			.then(function(){
				 console.log('Data saved');
			}).catch(function(err){
				 console.log('Error:', err);
			});
		});
}