---
date: 2014-9-3 10:00:31+00:00
layout: post
title: KEY, INDEX, PRIMARY KEY & UNIQUE KEY in MySQL
categories: doc
tags: MYSQL学习
---

----------

## 1.key和index
查阅了很多资料，看到了类似“unique key”以及“unique index”这类型的名词，很是不解，key和index的区别是啥。简单列两点，这两点我觉得都可以理解

 - key即键值，是关系模型理论中的一部份，比如有主键（Primary Key)，外键（Foreign Key）等，用于数据完整性检验与唯一性约束等。而index则处于实现层面，比如可以对表的任意列建立索引，那么当建立索引的列处于SQL语句中的Where条件中时，就可以得到快速的数据定位，从而快速检索
 - 从mysql创建表结构的角度，可以认为key=index

## 2. mysql有什么键？

 - 主键(primary key)
 - 唯一键(unique key)
 - 外键(foreign key)

## 3. mysql中unique与primary

 1. 定义了unique约束的字段中不能包含重复值，可以为一个或多个字段定义unique约束，因此，unique即可以在字段级也可以在表级定义，在unique约束的字段上可以包含空值. 
 2. 定义primary key约束时可以为它的索引； UNIQUED 可空，可以在一个表里的一个或多个字段定义；primary不可空不可重复，在一个表里可以定义联合主键；简单的说, primary key = unique + not null
 
## 4. unique与primary异同
 1. 唯一性约束所在的列允许空值，但是主键约束所在的列不允许空值。
 
 2. 可以把唯一性约束放在一个或者多个列上，这些列或列的组合必须有唯一的。但是，唯一性约束所在的列并不是表的主键列。
 3. 唯一性约束强制在指定的列上创建一个唯一性索引。在默认情况下，创建唯一性的非聚簇索引，但是，也可以指定所创建的索引是聚簇索引
 
 4. 建立主键的目的是让外键来引用.
 
 5. 一个表最多只有一个主键，但可以有很多唯一键

