---
date: 2014-9-20 17:00:31+00:00
layout: post
title: Graffiti of Shell
categories: doc
tags: SHELL
---

----------

## 环境变量
 
切记自己写的shell脚本中，自身的变量定义和环境变量名不要冲突，不然威力极大。
比如这样的shell脚本切记不可以写

    PATH=$1
    PATH="/home/users/liyujie"

## 变量替换

shell中的变量替换方式如下：

    TMP_PATH=${OFFLINE_PATH/liyujie/lidoumao}
   
   上述语句是指将OFFLINE_PATH中的liyujie变量替换成lidoumao


## 变量判断

 - 如何判断某个变量是否以xx结尾

 举例如下：

    if [[ $OFFLINE_PATH =~ xx$ ]];
    then
        echo "your path is wrong"
        exit;
    fi

	
 - 如何判断某个变量是否含有xx
 
 举例如下：

 
    if [[ $OFFLINE_PATH =~ "xx" ]];
    then
        echo "your path is wrong"
        exit;
    fi

	
 - 如何判断某个变量是路径还是文件
	 - -e ：判断文件或目录是否存在
	 - -d ：判断是不是目录，并是否存在
	 - -f ：判断是否是普通文件，并存在
	 - -r ：判断文档是否有读权限
	 - -w ：判断是否有写权限
	 - -x ：判断是否可执行
	
举例如下：

    if [ -f "$OFFLINE_PATH" ]; 
    then
        echo "your path is wrong"
        exit
    fi

    if [ ! -d "$OFFLINE_PATH"]; 
    then
        echo "your path is wrong"
        exit
    fi
	
 

