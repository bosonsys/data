console.log("Export")
exportWatch();

function exportWatch(){
	db.watch
			.reverse()
			// .limit(10)
			.toArray()
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