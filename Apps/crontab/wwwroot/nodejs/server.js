/**
var http = require('http');

http.createServer(function (request, response) {

	// 发送 HTTP 头部 
	// HTTP 状态值: 200 : OK
	// 内容类型: text/plain
	response.writeHead(200, {'Content-Type': 'text/plain'});

	// 发送响应数据 "Hello World"
	response.end('Hello World\n');
}).listen(8888);

// 终端打印如下信息
console.log('Server running at http://127.0.0.1:8888/');
*/

//var ffi = require('ffi');

// int send_msg(char * phone, char * content)
/*
var libm = ffi.Library(__dirname + '/quoteinterface', {
    'send_msg': ['int', ['string', 'string']]
});
*/

/*
var ffi = require('ffi');

var libm = ffi.Library('libm', {
  'ceil': [ 'double', [ 'double' ] ]
});
libm.ceil(1.5); // 2

// You can also access just functions in the current process by passing a null
var current = ffi.Library(null, {
  'atoi': [ 'int', [ 'string' ] ]
});
current.atoi('1234'); // 1234
*/

// 引入需要的模块：http和socket.io
var http = require('http'), io = require('socket.io');
//创建server
var server = http.createServer(function(req, res){ 
  // Send HTML headers and message
  res.writeHead(200,{ 'Content-Type': 'text/html' }); 
  res.end('<h1>Hello Socket Lover!</h1>');
});
//端口8000
server.listen(8081);
//创建socket
var socket = io.listen(server);
//添加连接监听
socket.on('connection', function(client){   
        //连接成功则执行下面的监听
        client.on('message',function(event){ 
                console.log('Received message from client!',event);
                socket.emit('Message', event);
        });	   
        //断开连接callback
        client.on('disconnect',function(){
                console.log('Server has disconnected');
        });
});