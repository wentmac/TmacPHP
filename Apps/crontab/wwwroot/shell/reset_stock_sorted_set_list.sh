#!/bin/bash
current=`date "+%Y-%m-%d %H:%M:%S"`     #获取当前时间，例：2015-03-11 12:33:41       
timeStamp=`date -d "$current" +%s`      #将current转换为时间戳，精确到秒
timeStamp=$((timeStamp-43200))         #取昨天的数据

/usr/local/bin/redis-cli ZREMRANGEBYSCORE pmag_stock_sorted_set_list -inf ${timeStamp}

#删除废旧的 K线历史数据。1分钟的【保存1天的】 5分钟 15分钟 30分钟 60分钟
#删除hash中的数据值

/usr/local/bin/redis-cli ZRANGEBYSCORE  pmag_k_line_1 -inf ${timeStamp} | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
/usr/local/bin/redis-cli ZREMRANGEBYSCORE pmag_k_line_1 -inf ${timeStamp}

/usr/local/bin/redis-cli ZRANGEBYSCORE  pmag_k_line_5 -inf ${timeStamp} | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
/usr/local/bin/redis-cli ZREMRANGEBYSCORE pmag_k_line_5 -inf ${timeStamp}

/usr/local/bin/redis-cli ZRANGEBYSCORE  pmag_k_line_15-inf ${timeStamp} | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
/usr/local/bin/redis-cli ZREMRANGEBYSCORE pmag_k_line_15 -inf ${timeStamp}

/usr/local/bin/redis-cli ZRANGEBYSCORE  pmag_k_line_30 -inf ${timeStamp} | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
/usr/local/bin/redis-cli ZREMRANGEBYSCORE pmag_k_line_30 -inf ${timeStamp}

/usr/local/bin/redis-cli ZRANGEBYSCORE  pmag_k_line_60 -inf ${timeStamp} | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
/usr/local/bin/redis-cli ZREMRANGEBYSCORE pmag_k_line_60 -inf ${timeStamp}