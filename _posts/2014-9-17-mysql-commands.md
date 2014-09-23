---
date: 2014-9-17 12:00:31+00:00
layout: post
title: Graffiti of MySQL Commands
categories: doc
tags: MYSQL学习
---

----------

#####建多个名字不同但是结构相同的表
 
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

	
	
 

