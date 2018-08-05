document.write("<script src='reconnecting-websocket.js'></script>");

		var ws = null;
 		var mKeyContainer = null;
		function encrypt(sp,pubKey, text){
			setMaxDigits(130);
			var key = new RSAKeyPair(sp, "", pubKey);
			return encryptedString(key, text);
		}
		function startServer() {			
			var url = "ws://"+vars.pushRealIp+"/?WeiTrader/tradebiz.ws";			
			if (!('WebSocket' in window)) {
				alert('Unsupported.');
				return;
			}
			
			ws = new ReconnectingWebSocket(url);			
 
            ws.onopen = function() {
				login();
            };

            ws.onmessage = function(event) {

				var str = JSON.parse(event.data);
				console.log(str);
				var respCmd = str[1][10];
				/*将发送查询行情的连接放到这里*/
				if(respCmd == "20481"){
					regsymbol();
				}
				
				if(respCmd == "20482"){
					var symbol = "";
					var buy = 0; 	/*买价 137 */
					var sell = 0;	/*卖价 138 */
					var hqtime = 0;
					var months = 0;
					var days   = 0;
					var years  = 0;
					var newhqtime;
					var hqtimestr = '';
					var hangqingtime;
					for(var o in str[2]){  
						symbol 	= str[2][o][105];
						buy 	= str[2][o][137];
						sell 	= str[2][o][138];
						hqtime  = str[2][o][132] + "000";
						
						hqtime  = parseInt(hqtime);
						newhqtime = new Date(hqtime);			

						$("." + symbol + "_buy").html(parseFloat(buy).toFixed(2));
						$("." + symbol + "_sell").html(parseFloat(sell).toFixed(2));
						$("." + symbol + "_hqtime").html(hqtimestr);
					}
				}
            };

            ws.onclose = function() {
				
            };
        }
		
		function sendJson(msg) {
			if(ws == undefined || ws.readyState == undefined || ws.readyState < 1) return;
			ws.send(msg);
		}
		
		function CloseSocket() {
			ws.close();
		}
		function login(){
			if(!vars.accountid)return;
			//sendJson("{\"1\":{\"10\":\"20481\", \"11\":\"1\", \"12\":\"1\"}, \"2\":{\"100\":\"abc\",\"101\":\"abc\", \"140\":\""+vars.accountid+"\"}}");
			//sendJson("XAGUSD",1,1475156190);
			var userId = "7147";
                var sendTime = "1475186339";
                var key = "9c8549e5ca05a51f8f79811715b423f3";
				sendJson(userId,sendTime,key);
		}
		function regsymbol(){
			sendJson("{\"1\":{\"10\":\"20483\", \"11\":\"1\", \"12\":\"1\"}, \"2\":{\"135\":\""  + symbollist +  "\"}}");
		}
		
		function sendMyMessage() {
            var textMessage = document.getElementById('textMessage').value;
            if(ws != null && textMessage != '') {
                ws.send(textMessage);
            }
        }
		
		function ExcepStr() {
			
		}
		