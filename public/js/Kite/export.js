console.log("Export")
var table = db.table('deep');
exportWatch(table);

// var table = db.table('watch5');
// exportWatch(table);

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