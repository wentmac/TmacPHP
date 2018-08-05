#!/bin/bash
#初始化新手房间的红包总数
/usr/local/bin/redis-cli set lucky_money_new_room_overage_count 50000
#初始化新手房间的红包总额度
/usr/local/bin/redis-cli set lucky_money_new_room_overage_money 2000

#初始化小房间的红包总数
/usr/local/bin/redis-cli set lucky_money_small_room_overage_count 10000
#初始化小房间的红包总额度
/usr/local/bin/redis-cli set lucky_money_small_room_overage_money 2500

#初始化大房间的红包总数
/usr/local/bin/redis-cli set lucky_money_big_room_overage_count 2000
#初始化大房间的红包总额度
/usr/local/bin/redis-cli set lucky_money_big_room_overage_money 5000

#初始化土豪区的红包总数
/usr/local/bin/redis-cli set lucky_money_rich_room_overage_count 2000
#初始化土豪区的红包总额度
/usr/local/bin/redis-cli set lucky_money_rich_room_overage_money 24000


#初始化所有用户连续玩游戏次数
/usr/local/bin/redis-cli keys "continue_trade_new_room_uid_*" | xargs /usr/local/bin/redis-cli del
/usr/local/bin/redis-cli keys "continue_trade_small_room_uid_*" | xargs /usr/local/bin/redis-cli del
/usr/local/bin/redis-cli keys "continue_trade_big_room_uid_*" | xargs /usr/local/bin/redis-cli del
/usr/local/bin/redis-cli keys "continue_trade_rich_room_uid_*" | xargs /usr/local/bin/redis-cli del

#初始化所有用户今日所得到的红包总数
/usr/local/bin/redis-cli keys "continue_trade_total_money_uid_*" | xargs /usr/local/bin/redis-cli del

#删除废旧的 K线历史数据。1分钟的【保存1天的】 5分钟 15分钟 30分钟 60分钟
#删除hash中的数据值
#/usr/local/bin/redis-cli zrange pmag_k_line_1 0 0 | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
#删除sorted set有序集合中的数据
#/usr/local/bin/redis-cli ZREMRANGEBYRANK pmag_k_line_1 0 0
#/usr/local/bin/redis-cli ZREMRANGEBYSCORE pmag_k_line_1 -inf ${timeStamp}

#/usr/local/bin/redis-cli zrange pmag_k_line_1 0 1440 | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
#/usr/local/bin/redis-cli ZREMRANGEBYRANK pmag_k_line_1 0 1440

#/usr/local/bin/redis-cli zrange pmag_k_line_5 0 288 | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
#/usr/local/bin/redis-cli ZREMRANGEBYRANK pmag_k_line_5 0 288

#/usr/local/bin/redis-cli zrange pmag_k_line_15 0 96 | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
#/usr/local/bin/redis-cli ZREMRANGEBYRANK pmag_k_line_15 0 96

#/usr/local/bin/redis-cli zrange pmag_k_line_30 0 48 | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
#/usr/local/bin/redis-cli ZREMRANGEBYRANK pmag_k_line_30 0 48

#/usr/local/bin/redis-cli zrange pmag_k_line_60 0 24 | xargs /usr/local/bin/redis-cli Hdel pmag_k_line_map
#/usr/local/bin/redis-cli ZREMRANGEBYRANK pmag_k_line_60 0 24

#/usr/local/bin/redis-cli LTRIM pmag_k_1 0 1440
#/usr/local/bin/redis-cli LTRIM pmag_k_5 0 288
#/usr/local/bin/redis-cli LTRIM pmag_k_15 0 96
#/usr/local/bin/redis-cli LTRIM pmag_k_30 0 48
#/usr/local/bin/redis-cli LTRIM pmag_k_60 0 24
