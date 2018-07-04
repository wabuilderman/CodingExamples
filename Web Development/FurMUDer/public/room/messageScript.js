var lastID = 0;
var socket = io('http://localhost:3000');

socket.on('messagePosted', function (data) {
    console.log("Message Posted:");
    console.log(data);
    postMessageIntoPage(data.Message);
});

function insertAfter(el, referenceNode)
{
  referenceNode.parentNode.insertBefore(el, referenceNode.nextSibling);
}

function postMessageIntoPage(message)
{
  var newMessage = document.getElementById("MESSAGE_PROTOTYPE").cloneNode(true);
  newMessage.getElementsByTagName('p')[0].textContent = message;
  // Place the new node into the document
  var currentDiv = document.getElementById("Lastest_Message");
  currentDiv.id = '';
  newMessage.id = "Lastest_Message";
  insertAfter(newMessage, currentDiv);
}

function LoadRoom()
{/*
  getDatabase(function(i, json)
  {
    postMessageIntoPage(json[i].message);
    if(i == json.length - 1)
      lastID = json[i].messageID;
  });*/
}

function UpdateRoom()
{
  getDatabase(function(i, json)
  {
    if(i <= lastID)
      return;
    postMessageIntoPage(json[i].message);
    if(i == json.length - 1)
      lastID = json[i].messageID;
  });
}

function getDatabase(callback)
{
  var xhttp = new XMLHttpRequest();

  xhttp.open("POST", "Server\\messageHandler.php", true);
  xhttp.setRequestHeader("Content-Type", "application/json");

  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200)
    {
      console.log("Message Recieved: " + xhttp.responseText);
      var json = JSON.parse(xhttp.responseText);
      
      for (var i=0;i<json.length;i++)
      {
        callback(i, json);
      }
      
    }
  };

  var timestamp = (new Date()).toUTCString();

  var data = JSON.stringify(
  {
    "PostType": "getMessageData",
    "message": "",
    "date": timestamp,
    "messageID": 0
  });
  
  console.log("Message Sent: " + data);
  
  xhttp.send(data);
}

function sumbitTextbox(key)
{
  if(key.keyCode == 13)
  {
    var textBox = document.getElementById("Text_Entry_Box");
    var message = textBox.value;
    
    textBox.value = '';
    if(event.preventDefault)
      event.preventDefault();
    
    console.log("Posting Message... ");
    socket.emit('messagePosted', { Message : message });
    /*
    var connection = new WebSocket('ws://' + window.location.host + '/data', ['soap', 'xmpp']);
    // When the connection is open, send some data to the server
    connection.onopen = function () {
      connection.send('Ping'); // Send the message 'Ping' to the server
    };

    // Log errors
    connection.onerror = function (error) {
      console.log('WebSocket Error ' + error);
    };

    // Log messages from the server
    connection.onmessage = function (e) {
      console.log('Server: ' + e.data);
    };*/
  }
}
  /*
  if(key.keyCode == 13)
  {
    var textBox = document.getElementById("Text_Entry_Box");
    var message = textBox.value;
    
    textBox.value = '';
    if(event.preventDefault)
      event.preventDefault();
      
    var xhttp = new XMLHttpRequest();
    
    xhttp.open("POST", "Server\\messageHandler.php", true);
    xhttp.setRequestHeader("Content-Type", "application/json");
    
    xhttp.onreadystatechange = function() {
      if (xhttp.readyState == 4 && xhttp.status == 200)
      {
        var json = JSON.parse(xhttp.responseText);
        console.log("Message Recieved: " + xhttp.responseText);
        //postMessageIntoPage(json.message);
        UpdateRoom();
      }
    };
    
    var timestamp = (new Date()).toUTCString();
    
    var data = JSON.stringify(
    {
      "PostType": "message",
      "message": message,
      "date": timestamp,
      "messageID": 0
    });
    
    console.log("Message Sent: " + data);
    
    xhttp.send(data);
  }
}
  */