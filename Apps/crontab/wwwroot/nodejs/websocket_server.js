var http = require('http');
var WebSocketServer = require('websocket').server;
 
var app = http.createServer(function(req, res){
    //res.writeHead(200, {'Content-Type': 'text/plain'});
    res.end('hello,world');
});
 
app.listen(3000, '0.0.0.0');
 
console.log('app listen on 127.0.0.1:3000');
 
var wss = new WebSocketServer({httpServer : app});
wss.on('request', function(req){
    var connection = req.accept('echo-protocol', req.origin);    
    console.log('open websocket');
    connection.send('open websocket '+connection);

    connection.on('message', function(msg) {
        console.log(msg);
        connection.send(JSON.stringify({time: Date()}));
    });
 
    connection.on('close', function(connection) {
        console.log('close one connection ... ');
    });

 
    connection.on('error', function(connection) {
        console.log('error one connection ... ');
    });    
});
