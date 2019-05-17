---
layout: post
categories: doc
tags: redis
---


> 在看redis源码时，看到了hyperloglog.c这个文件，去搜了下，发现自己之前没用过，甚至是用的走了弯路，在这里记录下。

先发几个场景，估计大家日常都会用到：

1. 想统计某个运营活动页面的pv/uv
2. 想看看两个页面的总uv，即去重后的uv，非单纯的加

一般遇到这类问题，我们这边的解决方案大多是这么几种思路
1. 前端下发打点统计，日志集中落在打点服务器上，解析调度入库至底层hive，研发通过hive表的hql来查询，业界通用的还有ELK这类，是入库至es，通过es检索查询也可。
2. 存redis，设置string做incr，设置set做去重。

以上两种，尤其是第一种，比较适合用作通用的一套日志采集系统，但是我们的场景是临时搞个运营活动，或是作为一种数据bak去使用时，那么uv的统计用HyperLogLog更为合适，会自动帮我们做去重的统计。

redis官方称HyperLogLog为基数统计，它提供不精确的去重计数方案，虽然不精确但是也不是非常不精确，标准误差是 0.8%左右波动，这样的精确度已经可以满足基本的 UV 统计需求了。

 [https://www.runoob.com/redis/redis-hyperloglog.html](https://www.runoob.com/redis/redis-hyperloglog.html) 

具体的官方的解释可以去看 redis/src/hyperloglog.c文件，文件中的大意我简单描述下

首先是基于两个核心论文：
1. Heule, Nunkesser, Hall: HyperLogLog in Practice: Algorithmic Engineering of a State of The Art Cardinality Estimation Algorithm
2. P. Flajolet, Éric Fusy, O. Gandouet, and F. Meunier. Hyperloglog: The analysis of a near-optimal cardinality estimation algorithm

著名的*HyperLogLog*

[神奇的HyperLogLog算法 · rainybowe](http://www.rainybowe.com/blog/2017/07/13/%E7%A5%9E%E5%A5%87%E7%9A%84HyperLogLog%E7%AE%97%E6%B3%95/index.html)


Redis中是基于 hllhdr 结构体来实现的
```
struct hllhdr {
    char magic[4];      /* "HYLL" */
    uint8_t encoding;   /* HLL_DENSE or HLL_SPARSE. */
    uint8_t notused[3]; /* Reserved for future use, must be zero. */
    uint8_t card[8];    /* Cached cardinality, little endian. */
    uint8_t registers[]; /* Data bytes. */
};
```
todo
