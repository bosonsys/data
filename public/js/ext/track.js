console.log("Tracker Started..."); 

var settings;
var company;
var runningcall;
var portfolio;
var pData = [];
var followUpCount = 0;
var refreshTime = 20*1000;
var pr = [];
getSettings();
portfolioCall();

setInterval(function() {
  refreshTime  = getRefresh();
  console.log(refreshTime);
  var d = $('#buttondiv > center > div:nth-child(1)').text().trim();
  var b = $('#requestComplete > form > center > div > div > div:nth-child(1)').text().trim();
  var p = $("#requestComplete > form > div > div:nth-child(1) > div:nth-child(1) > span").text().trim();
  var intraPage = $("#stockList3").text().trim();
  // console.log(intraPage);
  if (d == 'Market Watch') {
          if ($('#Name1').val()){
            BuyCall();
          } else {
            MarketWatchReader();
          }
      } else if (p == "Position Book" ) {
          positionReader();
      } else if (b == "BUY ORDER" ) {
        getBuyCalls();
      } else if (intraPage == "Intraday & Normal (T+5)" ) {
        insertIntraTable();
      }
 }, refreshTime);


function MarketWatchReader(){
  console.log("MarketWatchReader Reader...");
  // $('#buttondiv > center > div:nth-child(7) > input[type="image"]').trigger( "click" );
  location.href="Javascript:fn_chngStyl('four');RequestPage('MarketWatch3.jsp?section=nse_cm')";
   setTimeout(function () {
      table = $('table').tableToJSON();
      indi = $('#indexdiv > div:nth-child(1) > span').text();
      // console.log(indi);
      let nifty = indi.match(/[+-]?\d+(\.\d+)?/g).map(n => parseFloat(n));
      // console.log(nifty);
      updateMarketWatch(table, nifty);
          // storePosition(table);
          // percentCalc(table);
    }, 2000);
  }

function BuyCall(p = null, type = 1) {
  // console.log("Buy Call", p);
  // $('#Name1').val();
  if (p == null) {
    var LTP = $('#LastTradedPrice0').val();
  } else {
    var LTP = parseFloat(p);
  }
  // console.log(LTP);
  var qty = parseInt(20000 / LTP);
  // console.log(qty);
  $('#ProductCode0').val('MIS');
  if (type == '2') {
    $('#TransactionType0').val('S');
  } else {
    $('#TransactionType0').val('B');
  }
  $('#Quantity0').val(qty);
  $('#PriceType0').val('MKT');
  $('#Price0').val(LTP);
  setTimeout(function(){
    $("#orderplace > form > div > div:nth-child(2) > div:nth-child(7) > input").trigger("click");
  }, 500);
  setTimeout(function(){
    // $("#Submit").trigger( "click" );
  }, 1000);
  //  All_Data = Get_All_Page_Data();
  // console.log(All_Data);
}

function getBuyCalls() {
   $.get('http://localhost/market/public/index.php/trade/calls',{ "_": $.now() }, function(result) {
       console.log("Get Ajax Call");
       var r = JSON.parse(result);
       if (r) {
       // console.log(r[0].edel);
       // console.log(r[0].price);
      // window.settings = JSON.parse(result);
        buyPage(r[0].edel, r[0].price);
      }       
   });
}

function buyPage(n,p) {
  console.log("Buy Page");
  $("input:radio:first").prop("checked", true).trigger("click");
  $('#LastTradedPrice0').val(p);
  $('#Exchange0').val('nse_cm');
  $('#Name0').val(n);
  var qty = parseInt(20000 / p);
  $('#ProductCode0').val('MIS');
  $("#MKT").trigger( "click" );
  $('#Quantity0').val(qty);
  $('#PriceType0').val('MKT');
  $('#Price0').val(p);
  $('#blot0').val(1);
}

function sell(a, b) {
  console.log('Sell',a);
  $('#SearchName0').val(a['TradingSymbol']);
  $("#orderplace > form > div > div:nth-child(1) > div:nth-child(3) > img").trigger( "click" );
  
  
  setTimeout(function(){
  $('#ProductCode0').val('CNC');
  $('#TransactionType0').val('S');
  $('#Quantity0').val(b['qty']);
  $('#PriceType0').val('MKT');
  $("#orderplace > form > div > div:nth-child(2) > div:nth-child(7) > input").trigger("click");
}, 1000);
  
  // setTimeout(function(){
  //   $("#Submit").trigger( "click" );
  // updatePortfolio(b);
  // }, 2000);
}

function updatePortfolio(p) {
  console.log(p);
 $.ajax({
            type: "POST",
            url: "http://localhost/market/public/call/update/portfolio",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify({id:p['id']}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){
              // console.log(data)
               portfolioCall();
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}

function updateMarketWatch(d, n) {
  // console.log(d);
 $.ajax({
            type: "POST",
            url: "http://localhost/market/public/call/update/marketwatch",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify({data:d, nifty:n}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(res){
              if (res != null) {
                console.log(res, d);
                let index = d.findIndex(x => x.TradingSymbol==res.nse);
                enterCall(index,res);
                 // portfolioCall();
             }
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}

function enterCall(i, d) {
  let index = parseInt(i) + 1;
  console.log(index,d);
  $('#TR'+index+' > td:nth-child(1) > input[type="radio"]').trigger( "click" );
  setTimeout(function(){
      BuyCall(d.price, d.call);
  }, 500);
}

function updatePosition(d) {
  // console.log(d);
 $.ajax({
            type: "POST",
            url: "http://localhost/market/public/call/update/position",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify({data:d}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(res){
              console.log(res)
               // portfolioCall();
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}

function positionReader(){
	console.log("Position Reader...");
  // $('#requestComplete > form > div > div:nth-child(1) > div:nth-child(3) > a > img').trigger( "click" );
      location.href="Javascript:fn_chngStyl('nine');RequestPage('PositionBookNSEBSE.jsp?Exchange=all&pcode=ALL&Type=NET&S=C'); void 0";
			setTimeout(function () {
          table = $('table').tableToJSON();
					percentCalc(table);
				}, 4000);
	}

// Calculate Percentage
function percentCalc(table) {
	var bid;
  // updatePosition(table);
	$.each( table, function( key, value ) {
    // Margin Trading (MTF )
if (value['Product Type']=='Intraday (MIS)' && value['Net Qty']!='0' ) {
		if(value["Avg. Buy Price"]==0)
			bid = value["Avg. Sell Price"];
		else
			bid = value["Avg. Buy Price"];

		value['LTP'] = removeComma(value['LTP']);
		bid = removeComma(bid);
		percent =  parseFloat((value['LTP'] - bid) * 100 / bid).toFixed(2);
		console.log(value['Stock Code']+" :"+percent);
    // superFollowUP(percent, key, value['Stock Code']);
    AI(percent, key, value['Stock Code']);
  }
	});
}


// AI 
function superFollowUP(p,c,s) {
  var d = new Date();
  var hr = d.getHours();
  var min = d.getMinutes();
  
  var pData = window.pData;
  var t = hr+":"+min;
  var lv = getLastRecVal(pData,s);
  var diff = 0;

  if (lv) {
    diff = (p - lv);
  }

  // pdate[s][t] = p;
  pData.push({ Name : s,
                  Price : p,
                  LastDiff: diff,
                  time: t
              });
  console.log(pData);
  // var last = insertPositionOne(s,p)
}

// AI 
function AI(p,c,s) {
  // console.log(window.settings);
  T1 = parseFloat(window.settings[2].value);
  T2 = parseFloat(window.settings[4].value);
  S = parseFloat(window.settings[3].value);

    if(p >= T1){
      followTriger(s,p,c);
      // squareOff(c);
      // console.log("Target Reached:"+p);
    } else if(p <= S) {
      // followTriger(s,p,c);
      squareOff(c);
      console.log("Stop loss Trigged:"+p);
    } else {
      console.log(getLastRec(s));
    }
}
// Follow and Triger strategy
function followTriger(s,p,c) {
  console.log(s,p,c);

  var df = getLastRec(s);
  var dfp = 0;
    if (df) {
    // console.log(s, df);
    if (df['Name'] == s) {
      dfp = (p - df['Price']).toFixed(2);
    }
    if (dfp > 0) {
      fval = (p - ((p*12)/100)).toFixed(2);
    } else {
      fval = parseFloat(df['FollowTarget']);
    }
    if (fval >= p) {
      squareOff(c);
      console.log("Trigged Followup", fval, p);
    }
  } else {
    fval = parseFloat(p - ((p*12)/100)).toFixed(2);
  }
  // console.log("Follow UP", window.pr);
  window.pr.push({ Name : s,
                  Price : p,
                  LastDiff: dfp,
                  FollowTarget: fval });
  // console.log(window.pr);
}
function getLastRec(s) {
  wpr = window.pr.filter(c => c.Name == s);
  // getTargetVal(p, pro);
  // console.log(wpr);
  if (wpr.length > 0) {
    df = wpr[wpr.length - 1];
    return df;
  } else
  return false;
}

function getLastRecVal(d, s) {
  wpr = d.filter(c => c.Name == s);
  // getTargetVal(p, pro);
  if (wpr.length > 0) {
    df = wpr[wpr.length - 1];
    return df.Price;
  } else
  return false;
}

// stepCounter strategy function
function stepCounter(s,p,c) {
  var df = "";
  wpr = window.pr;
  if (wpr.length > 0) {
    df = wpr[wpr.length - 1];
    if (df['Name'] == s) {
      df = (p - df['Price']).toFixed(2);
    }
    if (df < 0) {
      window.followUpCount++;
    } else if (df!=0) {
      window.followUpCount = 0;
    }
    if (window.followUpCount == 3) {
      squareOff(c);
      console.log("Trigged Followup");
    }
  }
  console.log("Follow UP", window.followUpCount, df);
  wpr.push({ Name : s,
                  Price : p,
                  LastDiff: df });
  // console.log(wpr);
}

// Square Off function
function squareOff(r) {
  console.log(r);
  $('#TR'+r+' > td:nth-child(14) > input[type="radio"]').trigger( "click" );
  $('#squareoff').trigger( "click" );
}

//Insert data to MySQL
function storePosition(table){
  console.log(JSON.stringify(table));
        $.ajax({
            type: "POST",
            url: "http://localhost/market/public/index.php/trade/add",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify(table),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(data){console.log(data)},
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}
// Insert Intraday Company data into MySQL
function insertIntraTable(){
  console.log("Intraday Reader...");
  location.href="Javascript:fn_chngStyl_stockList('stockList3');LoadStockList('../StockList/EquityIntraday.jsp')";
   setTimeout(function () {
      table = $('table').tableToJSON();
      // console.log(table);
      insertIntraTableDB(table);
    }, 2000);
  }

//Insert data to MySQL
function insertIntraTableDB(d){
 $.ajax({
            type: "POST",
            url: "http://localhost/market/public/call/insert/intradayData",
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
//Insert data to MySQL
function insertPositionOne(s,p){
 $.ajax({
            type: "POST",
            url: "http://localhost/market/public/call/update/position",
            // The key needs to match your method's input parameter (case-sensitive).
            data: JSON.stringify({stock:s, per:p}),
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(res){
              console.log(res)
               // portfolioCall();
            },
            failure: function(errMsg) {
                alert(errMsg);
            }
        });
}

//Get Setting data
function getSettings() {
       $.get('http://localhost/market/public/index.php/trade/settings',{ "_": $.now() }, function(result) {
           // this will call the callback and pass the result
           console.log("Make Ajax Call"); 
           window.settings = JSON.parse(result);
           console.log(window.settings);
       });
}
//Get Company Info 
function getCompanyInfo(company) {
		url = 'http://localhost/market/public/index.php/company/'+company;
       $.get(url, function(result) {
           // this will call the callback and pass the result
           console.log("Make Ajax Call: "+url); 
           window.company = JSON.parse(result); 
       });
}
 function makeAjaxCall() {
       $.get('http://localhost/market/public/index.php/call/json/getRunningCall',{ "_": $.now() }, function(result1) {
           // this will call the callback and pass the result
           console.log("Get Running Call"); 
           window.portfolio = JSON.parse(result1); 
       });
   }
 // portfolio details
function portfolioCall() {
  $.get('http://localhost/market/public/index.php/call/json/portfolio',{ "_": $.now() }, function(result1) {
     // this will call the callback and pass the result
    console.log("Get Running portfolio"); 
    window.portfolio = JSON.parse(result1); 
    console.log(window.portfolio);
  });
}
function removeComma(rawstring){
  return rawstring.replace(/[^\d\.\-\ ]/g, '');
}   
function getValues(r){
  var subStr = r.match('value="(.*)" style');
  return subStr[1].split('|');
}		

//XPath Data Reader
function getElementByXpath(path) {
    return document.evaluate(path, document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
}


var anUrl = "http://localhost/market/public/index.php/trade/settings";
var myRequest = new XMLHttpRequest();

//callAjax(anUrl);

function callAjax(url) {
     myRequest.open("GET", url, true);
     myRequest.onreadystatechange = responseAjax;
            myRequest.setRequestHeader("Cache-Control", "no-cache");
     myRequest.send(null);
}

function responseAjax() {
     if(myRequest.readyState == 4) {
        if(myRequest.status == 200) {
            result = myRequest.responseText;
            console.log(result);
        } else {
            alert( " An error has occurred: " + myRequest.statusText);
        }
     }
}


function add() {
        var request = window.db.transaction(["dayWatch"], "readwrite")
                .objectStore("dayWatch")
                .add({ id: "00-03", name: "Kenny", age: 19, email: "kenny@planet.org" });
                                 
        request.onsuccess = function(event) {
                alert("Kenny has been added to your database.");
        };
         
        request.onerror = function(event) {
                alert("Unable to add data\r\nKenny is aready exist in your database! ");       
        }
         
}

function read() {
        var transaction = db.transaction(["dayWatch"]);
        var objectStore = transaction.objectStore("dayWatch");
        var request = objectStore.get("00-03");
        request.onerror = function(event) {
          alert("Unable to retrieve daa from database!");
        };
        request.onsuccess = function(event) {
          // Do something with the request.result!
          if(request.result) {
                alert("Name: " + request.result.name + ", Age: " + request.result.age + ", Email: " + request.result.email);
          } else {
                alert("Kenny couldn't be found in your database!"); 
          }
        };
}

function readAll() {
        var objectStore = db.transaction("dayWatch").objectStore("dayWatch");
  
        objectStore.openCursor().onsuccess = function(event) {
          var cursor = event.target.result;
          if (cursor) {
                alert("Name for id " + cursor.key + " is " + cursor.value.name + ", Age: " + cursor.value.age + ", Email: " + cursor.value.email);
                cursor.continue();
          }
          else {
                alert("No more entries!");
          }
        };     
}

function remove() {
        var request = db.transaction(["dayWatch"], "readwrite")
                .objectStore("dayWatch")
                .delete("00-03");
        request.onsuccess = function(event) {
          alert("Kenny's entry has been removed from your database.");
        };
}

function getTargetVal(p , pro) {
  pro.forEach(function(e , i) {
    console.log(e , i);
    if (p>e) {
      return i;
    }
  });
}

// Create Dynamic refresh time
function getRefresh() {
  var MT = trainTime();
  var ms = 1000;
  console.log('MT: ',MT);
  if (MT == 'fastTrade' || MT == 'closing' ) {
    return ms*10;
  } else if(MT == 'closed') {
    return ms*60;
  }
   else {
    return ms*30;
  }
}

// Traning function for Market Times
function trainTime() {
  var d = new Date();
  var hr = d.getHours();
  var min = d.getMinutes();
  // console.log(hr,min);
  if (hr == 9) {
    if (min == 14) {
      MT = 'open';
    } else if (min>=30){
      MT = 'fastTrade';
    } else {
      MT = 'normal';
    }
  } else if (hr == 14) {
    if (min > 30) {
      MT = 'closing';
    } else {
      MT = 'normal';
    }
  }
  else if (hr >= 16) {
    MT = 'closed';
  }
  else {
    MT = 'normal';
  }
  return MT;
}
// Get All input data from page
function Get_All_Page_Data()
{
    var All_Page_Data = {};
    $('input, select, textarea').each(function(){  
        var input = $(this);
        var Element_Name;
        var Element_Value;

        if((input.attr('type') == 'submit') || (input.attr('type') == 'button'))
        {
            return true;
        }

        if((input.attr('name') != undefined) && (input.attr('name') != ''))
        {
            Element_Name = input.attr('name');
        }
        else if((input.attr('id') != undefined) && (input.attr('id') != ''))
        {
            Element_Name = input.attr('id');
        }
        else if((input.attr('class') != undefined) && (input.attr('class') != ''))
        {
            Element_Name = input.attr('class');
        }

        if(input.val() != undefined)
        {
            if(input.attr('type')  == 'radio')
            {
                Element_Value = jQuery('input[name='+Element_Name+']:checked').val();
            }
            else
            {
                Element_Value = input.val();
            }
        }
        else if(input.value() != undefined)
        {
            Element_Value = input.value();
        }
        else if(input.text() != undefined)
        {
            Element_Value = input.text();
        }

        if(Element_Name != undefined)
        {
            All_Page_Data[Element_Name] = Element_Value;
        }
    });
    return All_Page_Data;
}

//    location.href="Javascript:fn_chngStyl('six');RequestPage('OrderBookNSEBSE.jsp?Exchange=all&OrderType=ALL&Trans=All&S=C')";


              // executeorder(
              //                  $('sExchange').value,
              //                  $('sTokenNo').value,
              //                  $('sTradeSymbol').value,
              //                  $('sTransType').value,
              //                  $('sProductCode').value,
              //                  $('sPriceType').value,
              //                  $('sRetention').value,
              //                  $('sQty').value,
              //                  $('sPrice').value,
              //                  $('sBLQty').value,
              //                  $('sTrigPrice').value,
              //                  $('sValidDate').value,
              //                  $('stratid').value,
              //                  $('secFlag').value);

                    //squareOff($('sExchange').value,
      // $('sToken').value,
      // $('sTradSym').value,
      // $('sProdCode').value,
      // $('sNETQty').value,
      // $('sBDLot').value,
      // $('sCompName').value,'C');
// <input type=\"radio\" name=\"Select\" value=\"nse_cm|4911|MADRASFERT|MADTED|CNC|200|1\" style=\"background-color:[Ljava.lang.String;@1082082\" onclick=\"$('RadioValue').value='nse_cm|4911|MADRASFERT|MADTED|CNC|200|1';
       // $('sExchange').value='nse_cm';
       // $('sToken').value='4911';
       // $('sTradSym').value='MADTED';
       // $('sProdCode').value='CNC';
       // $('sNETQty').value='200';
       // $('sBDLot').value='1';
       // $('sCompName').value='MADRASFERT';
       // if('Delivery (CNC)'=='CO'){$('squareoff').disabled = true;}else{$('squareoff').disabled = false;}\">
      //      $('RadioValue').value='nse_cm|13142|PUNJLLOYD|PUNLLO|MIS|100|1';
            //$('sExchange').value='nse_cm';
            //$('sToken').value='13142';
            //$('sTradSym').value='PUNLLO';
            //$('sProdCode').value='MIS';
            //$('sNETQty').value='100';
      //$('sBDLot').value='1';
      //$('sCompName').value='PUNJLLOYD';

          // location.href="Javascript:fn_chngStyl_stockList('stockList3');LoadStockList('../StockList/EquityIntraday.jsp')";
    // location.href="Javascript:fn_chngStyl('ten');RequestPage('Holding.jsp');";
    // location.href="Javascript:fn_chngStyl('four');RequestPage('MarketWatch3.jsp?section=nse_cm')";
    // Javascript:fn_chngStyl_stockList('stockList1');LoadStockList('../StockList/NSEList.jsp');
    // Javascript:fn_chngStyl_stockList('stockList3');LoadStockList('../StockList/EquityIntraday.jsp');
