---
date: 2015-1-21 15:08:31+00:00
layout: post
title: written examination about php
categories: doc
tags: php
---




# php相关问题整理


----------
引用url: 
http://www.nowamagic.net/librarys/veda/detail/1936

 **基本知识点**

 - HTTP协议中几个状态码的含义:503,500,401,200,301,302

> 503:服务器超时
> 500:服务器内部错误
> 401:请求要求身份验证
> 200:正常响应
> 301:请求的网页已永久移动到新位置
> 302:类似301.但非永久性

 - include,require,include_once,require_once 的区别

> http://www.myxzy.com/post-389.html 参加这篇文章

 

 - PHP/Mysql中几个版本的进化史，比如mysql4.0到4.1，PHP4.x到5.1的重大改进等等

> php进化史参见：http://developer.51cto.com/art/201105/260447.htm
> mysql进化史参见：http://www.cnblogs.com/reveyjay/archive/2012/03/09/2387493.html

 
 

 - HEREDOC介绍

> 一种表达字符串的形式。
> 具体可参见：http://php.net/manual/zh/language.types.string.php#language.types.string.syntax.heredoc

 - 写出一些php魔术方法

> Blockquote

 - 一些编译php时的configure 参数

> http://blog.csdn.net/niluchen/article/details/41513217
> 之前自己编译也有不少坑，路径、扩展、mysql相关都需要考虑进去

 - 向php传入参数的两种方法
> 这个感觉不知道怎么回答。命令行的方式，调用传参等。自行谷歌吧

 - (mysql)请写出数据类型(int char varchar datetime text)的意思; 请问varchar和char有什么区别;

> varchar是可变长字符串，char是定长字符串。相对来说，varchar对于空间的节省度高些。text其实就是一个0-65535字节的长文本数据。
> DATETIME 8 1000-01-01 00:00:00/9999-12-31 23:59:59 YYYY-MM-DD HH:MM:SS 混合日期和时间值 

 - error_reporting 等调试函数使用

> error_reporting(0); -- 禁用错误报告
> error_reporting(E_ERROR | E_WARNING | E_PARSE); --报告时运行错误
> error_reporting(E_ALL); -- 报告所有错误

 - 写代码来解决多进程/线程同时读写一个文件的问题。

> 使用php的flock加锁函数实现。

 - 写一段上传文件的代码。

 - Mysql 的存储引擎,myisam和innodb的区别
 

> 参考这个对比 ：http://www.ha97.com/4197.html
