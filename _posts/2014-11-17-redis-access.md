---
date: 2014-11-17 17:20:31+00:00
layout: post
title: Practical examples of the Redis
categories: doc
tags: Redis
---
####Redis基础知识&使用


----------

#####Redis的基本结构

 - string类型

> string类型是最简单的类型。一个key对应一个value。属于二进制安全类型。string可以包含任何数据，比如说jpg图片或者序列化的对象

 - list类型

> 头元素和尾元素
> 
> 头元素指的是列表左端/前端第一个元素，尾元素指的是列表右端/后端第一个元素。
> 
> 举个例子，列表list包含三个元素：x, y, z，其中x是头元素，而z则是尾元素。
> 
> 空列表
> 
> 指不包含任何元素的列表，Redis将不存在的key也视为空列表。

 - set类型

> A = {'a', 'b', 'c'} B = {'a', 'e', 'i', 'o', 'u'}
> 
> inter(x, y): 交集，在集合x和集合y中都存在的元素。 inter(A, B) = {'a'}
> 
> union(x, y): 并集，在集合x中或集合y中的元素，如果一个元素在x和y中都出现，那只记录一次即可。 union(A,B) =
> {'a', 'b', 'c', 'e', 'i', 'o', 'u'}
> 
> diff(x, y): 差集，在集合x中而不在集合y中的元素。 diff(A,B) = {'b', 'c'}
> 
> card(x): 基数，一个集合中元素的数量。 card(A) = 3
> 
> 空集： 基数为0的集合。

 - zset类型

 - hash类型

#####string类型操作

 - SET
解释：设置key对应的值为string类型的value 
举例：添加一个name为豆毛的键值对''set name doumao''

 - SETNX
解释：将key的值设为value，当且仅当key不存在
举例：SETNX names "doumao"

 - SETEX
解释：将值value关联到key，并将key的生存时间设为seconds(以秒为单位)。如果key 已经存在，SETEX命令将覆写旧值。
举例：SETEX cd 3000 "goodbye my love"

 - SETRANGE
解释：用value参数覆写(Overwrite)给定key所储存的字符串值，从偏移量offset开始。
举例：SETRANGE greeting 6 "Redis"

 - MSET
解释：同时设置一个或多个key-value对
举例：MSET date "2011.4.18" time "9.09a.m." weather "sunny"

 - MSETNX
解释：同时设置一个或多个key-value对，当且仅当key不存在
举例：MSETNX rmdbs "MySQL" nosql "MongoDB" key-value-store "redis"

 - APPEND
解释：如果key已经存在并且是一个字符串，APPEND命令将value追加到key原来的值之后。如果key不存在，APPEND就简单地将给定key设为value，就像执行SET key value一样。
举例：APPEND myphone "nokia"

 - GET
解释：返回key所关联的字符串值。如果key不存在则返回特殊值nil。假如key储存的值不是字符串类型，返回一个错误，因为GET只能用于处理字符串值。
举例：GET name

 - MGET
解释：返回所有(一个或多个)给定key的值。如果某个指定key不存在，那么返回特殊值nil。因此，该命令永不失败。
举例：MGET name twitter

 - GETRANGE
解释：GETRANGE key start end返回key中字符串值的子字符串，字符串的截取范围由start和end两个偏移量决定(包括start和end在内)。负数偏移量表示从字符串最后开始计数，-1表示最后一个字符，-2表示倒数第二个，以此类推。GETRANGE通过保证子字符串的值域(range)不超过实际字符串的值域来处理超出范围的值域请求。
举例：GETRANGE greeting 0 -1

 - GETSET
解释：GETSET key value将给定key的值设为value，并返回key的旧值。当key存在但不是字符串类型时，返回一个错误。
举例：GETSET mail xxx@google.com

 - STRLEN
解释：返回key所储存的字符串值的长度。当key储存的不是字符串值时，返回一个错误。不存在的key长度视为0
举例：STRLEN mykey

 - DECR
解释：将key中储存的数字值减一。如果key不存在，以0为key的初始值，然后执行DECR操作。
举例：DECR failure_times

 - DECRBY
解释：DECRBY key decrement将key所储存的值减去减量decrement。如果key不存在，以0为key的初始值，然后执行DECRBY操作。
举例：DECRBY count 20

 - INCR
解释：INCR key将key中储存的数字值增一。如果key不存在，以0为key的初始值，然后执行INCR操作。
举例：INCR page_view

 - INCRBY
解释：INCRBY key increment将key所储存的值加上增量increment。如果key不存在，以0为key的初始值，然后执行INCRBY命令。
举例：INCRBY rank 20

#####list类型操作

 - LPUSH
解释：LPUSH key value [value ...]将一个或多个值value插入到列表key的表头。如果有多个value值，那么各个value值按从左到右的顺序依次插入到表头：比如对一个空列表(mylist)执行LPUSH mylist a b c，则结果列表为c b a，等同于执行执行命令LPUSH mylist a、LPUSH mylist b、LPUSH mylist c。如果key不存在，一个空列表会被创建并执行LPUSH操作。当key存在但不是列表类型时，返回一个错误。
举例：LPUSH mylist a b c

 - LPUSHX
解释：LPUSHX key value将值value插入到列表key的表头，当且仅当key存在并且是一个列表。和LPUSH命令相反，当key不存在时，LPUSHX命令什么也不做。
举例：LPUSHX greet "hello"

 - RPUSH
解释：RPUSH key value [value ...]将一个或多个值value插入到列表key的表尾。如果有多个value值，那么各个value值按从左到右的顺序依次插入到表尾：比如对一个空列表(mylist)执行RPUSH mylist a b c，则结果列表为a b c，等同于执行命令RPUSH mylist a、RPUSH mylist b、RPUSH mylist c。
举例：RPUSH mylist a b c

 - RPUSHX
解释：RPUSHX key value将值value插入到列表key的表尾，当且仅当key存在并且是一个列表。
举例：RPUSHX greet "hello"

 - LPOP
解释：LPOP key移除并返回列表key的头元素。
举例：LPOP name

 - RPOP
解释：移除并返回列表key的尾元素。当key不存在时，返回nil
举例：RPOP mylist

 - LLEN
解释：返回列表key的长度。
举例：LLEN name

 - LRANGE
解释：返回列表key中指定区间内的元素，区间以偏移量start和stop指定
举例：LRANGE key start stop

 - LREM
解释：根据参数count的值，移除列表中与参数value相等的元素。count的值可以是以下几种：
count > 0: 从表头开始向表尾搜索，移除与value相等的元素，数量为count。
count < 0: 从表尾开始向表头搜索，移除与value相等的元素，数量为count的绝对值。
count = 0: 移除表中所有与value相等的值。
举例：LREM greet 2 morning

 - LSET
解释：将列表key下标为index的元素的值甚至为value。
举例：LSET key index value

 - LINDEX
解释：返回列表key中，下标为index的元素。
举例：LINDEX key index

 - LINSERT
解释：将值value插入到列表key当中，位于值pivot之前或之后。
举例：LINSERT key BEFORE|AFTER pivot value

#####set类型操作

 - SADD
解释：将一个或多个member元素加入到集合key当中，已经存在于集合的member元素将被忽略。
举例：SADD bbs "tianya.cn" "groups.google.com"

 - SREM
解释：移除集合key中的一个或多个member元素，不存在的member元素会被忽略。
举例：SREM languages ruby


 - SMEMBERS
解释：返回集合key中的所有成员。
举例：SMEMBERS programming_language


 - SISMEMBER
解释：判断member元素是否是集合key的成员。
举例：SISMEMBER joe's_movies "Fast Five"

 - SCARD
解释：返回集合key的基数(集合中元素的数量)
举例：SCARD tool

 - SMOVE
解释：将member元素从source集合移动到destination集合
举例：SMOVE source destination member

 - SPOP
解释：移除并返回集合中的一个随机元素
举例：SPOP my_sites

 - SRANDMEMBER
解释：返回集合中的一个随机元素。
举例：SRANDMEMBER key

#####zset类型操作

 - ZADD
解释：将一个或多个member元素及其score值加入到有序集key当中
举例：ZADD key score member
 - ZREM
解释：移除有序集key中的一个或多个成员，不存在的成员将被忽略
举例：ZREM key member [member ...]
 - ZCARD
解释：返回有序集key的基数
举例：ZCARD key
 - ZCOUNT
解释：返回有序集key中，score值在min和max之间(默认包括score值等于min或max)的成员
举例：ZCOUNT key min max
 - ZSCORE
解释：返回有序集key中，成员member的score值
举例：ZSCORE key member
 - ZINCRBY
解释：为有序集key的成员member的score值加上增量increment。
举例：ZINCRBY key increment member
 - ZRANGE
解释：返回有序集key中，指定区间内的成员
举例：ZRANGE key start stop [WITHSCORES]
 - ZRANK
解释：返回有序集key中成员member的排名。其中有序集成员按score值递增(从小到大)顺序排列
举例：ZRANK key member

#####hash类型操作

 - HSET
解释：将哈希表key中的域field的值设为value。
举例：HSET key field value
 - HSETNX
解释：将哈希表key中的域field的值设置为value，当且仅当域field不存在。
举例：HSETNX key field value
 - HMSET
解释：同时将多个field - value(域-值)对设置到哈希表key中。
举例：HMSET key field value [field value ...]
 - HGET
解释：返回哈希表key中给定域field的值
举例：HGET key field
 - HMGET
解释：返回哈希表key中，一个或多个给定域的值。
举例：HMGET key field [field ...]
 - HGETALL
解释：返回哈希表key中，所有的域和值
举例：HGETALL key
 - HDEL
解释：删除哈希表key中的一个或多个指定域，不存在的域将被忽略。
举例：HDEL key field [field ...]
 - HLEN
解释：返回哈希表key中域的数量。
举例：HLEN key
 - HEXISTS
解释：查看哈希表key中，给定域field是否存在。
举例：HEXISTS key field
 - HINCRBY
解释：为哈希表key中的域field的值加上增量increment。
举例：HINCRBY key field increment
 - HKEYS
解释：返回哈希表key中的所有域。
举例：HKEYS key
 - HVALS
解释：返回哈希表key中的所有值。
举例：HVALS key

#####常用高级命令

#####几大特性



