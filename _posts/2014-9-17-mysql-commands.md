---
date: 2014-9-17 12:00:31+00:00
layout: post
title: Graffiti of MySQL Commands
categories: doc
tags: MYSQL学习
---

----------

## 建多个名字不同但是结构相同的表
 
在mysql中执行如下命令，即可实现创建32个类似test_memo_user的表。

    create procedure pro_create (i int)
	begin  
    declare j int default 0;  
    while j < i do  
        set @str1 = CONCAT('create table test_memo_user', j ,' like test_memo_user');
        prepare stmt1 from @str1; 
        EXECUTE stmt1;
        SET j = j + 1;
    end while;  
	end$$
	
	delimiter ;
	
	call pro_create(32);`

## 数据库备份

将forum_role下的forum_user_role表导出到mysql.dbname文件中

    ./bin/mysqldump -u root -proot forum_role forum_user_role > mysql.dbname 

将forum_info文件中的数据导入forum_role数据库中

    /home/xxx/mysql5/bin/mysql -uroot -proot -h127.0.0.1 -P3346 forum_role < forum_info           

从forum_info中获取数据写入文件名中

    select * from forum_info into outfile  '文件名'

将文件名中的数据导入表中

    load data local infile "/home/database/mysql5.0.51b/doumaoli.sql" into table forum_info;
	
 

