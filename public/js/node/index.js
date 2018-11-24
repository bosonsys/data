var webSocketServer = new (require('ws')).Server({port: (process.env.PORT || 5000)}),
    webSockets = {} // userID: webSocket

// CONNECT /:userID
// wscat -c ws://localhost:5000/1

webSocketServer.on('connection', function (webSocket, req) {
  var userID = parseInt(req.url.substr(1), 10)
   // const url = req.url;
   console.log(userID);
  webSockets[userID] = webSocket
  console.log('connected: ' + userID + ' in ' + Object.getOwnPropertyNames(webSockets))

  // Forward Message
  //
  // Receive               Example
  // [toUserID, text]      [2, "Hello, World!"]
  //
  // Send                  Example
  // [fromUserID, text]    [1, "Hello, World!"]
  webSocket.on('message', function(message) {
    console.log('received from ' + userID + ': ' + message)
    var messageArray = JSON.parse(message)
    var toUserWebSocket = webSockets[messageArray[0]]
    if (toUserWebSocket) {
      console.log('sent to ' + messageArray[0] + ': ' + JSON.stringify(messageArray))
      messageArray[0] = userID
      toUserWebSocket.send(JSON.stringify(messageArray))
    }
  })

  webSocket.on('close', function () {
    delete webSockets[userID]
    console.log('deleted: ' + userID)
  })
})