// Insert Intraday Company data into MySQL
function insertIntraTable(){
  console.log("Intraday Reader...");
   setTimeout(function () {
      table = $('table').tableToJSON();
      console.log(table);
      insertIntraTableDB(table);
    }, 2000);
  }

//Insert data to MySQL
function insertIntraTableDB(d){
 $.ajax({
            type: "POST",
            url: "http://localhost/market/public/intradayData/kite",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify({data:d}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(res){
              console.log(res)
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}

insertIntraTable();