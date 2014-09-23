---
date: 2014-09-01 15:08:31+00:00
layout: post
title: php匹配替换趣事
categories: 文档
tags: php
---

####记遇到的一个php匹配问题


----------

#####1.神奇的问题

最近有用户说，发帖内容含有“醉風淸”这个词时就悲剧，表现为：发不出去或者发出去变成空白。然后我就试了试 ，输入含有这三个字的内容，结果发现我真的发不出去！！前提是别的字都行。。。然后开始拆分，发“醉”可以发出去，发“風”可以发出去，发“淸”就傻眼了。。。


#####2.追查过程

 - 先看对应的日志，发现在发“淸”这个字时，默认为长度为0。为什么会是0，不管是用gbk或者utf-8都不应该为0啊！
 - 继续看长度“0”这个结果出自哪里，找了半天，发现了这个入参在经过preg_replace后就悲剧了！
 - 查看这一行，preg_replace也是使用的其常规用法，主要是将“淸”这词加点html渲染，做成有链接的。想想这也没问题啊！
 - 打印入参也没问题。实在不行了，单挑出来preg系列的preg_match来看是不是match上了，要是都match不上，那还替换啥！
 - 最后写了如下代码执行后，返回的false,并且报错说我缺了个']'与其匹配！
 
代码如下：

    <?php 
    $pattern='/淸/';
    $txt='淸_哈哈_我来试试';
    $match = preg_match($pattern, $txt);
    if ($match===false) {
        echo 'fail';
    }
    ?> 


#####3.真相浮出,UTF-8才是王道
 - 在执行该段代码后，我先确认了下我的编码格式是**GBK**，接着我又试了下将文件编码整体改为**UTF-8**，执行后就通过了。这么看是编码问题了啊。
忍不住想，php_match、preg_replace这样的原生函数啊，为啥会有这种问题呢？
 - 突然想到php提示我error，说是缺了**']'**，看了这短短6行代码，哪里来的**'['**呢，将**‘淸’**字的gbk码转换出来为**'%9c%5b'**，再将**'['**的ascii码解析出来，有点懂了，原来**'['**的ascii码值为：**'%5b'**。
 - 此时已经基本明朗了，preg_match、preg_replace函数在匹配GBK编码时为单字节匹配，因为**GBK**编码不像**UTF-8**编码，在读取第一个字节时便已经给出标识并已知具体一个字符占用的字节长度。
 - **GBK**不会给出特殊编码标示，当部分汉字解码的两个字节中有和ascii码本身的一些特殊字符有冲突时，preg_match做出的单个解析会导致上述出现提示缺了**']'**的error，从而导致整个功能匹配替换功能的不可用。
 - 后来我陆陆续续在**GBK**编码下试了下，淺、歔、沎这几个字，果真都是false，也报error。
 - 最后解决办法两种
	 - 一种短期解决，即把preg_replace替换成str_replace
	 - 一种就是长线方案，即代码UTF-8化

#####4.小结
上述排查让我很有兴趣去查看下，php的preg等匹配替换系列的函数以及mb_ereg系列的学习。并且再一次深深体会，还是utf-8设计贴心！！

####php替换函数的学习


----------

#####php替换函数有哪些

 - str_replace
 - substr_replace
 - preg_replace
 - preg_split
 - str_split


#####str_replace

 - **作用**：str_replace() 函数使用一个字符串替换字符串中的另一些字符。
 - **参数描述** 
	 - find 必需。规定要查找的值。 
	 - replace 必需。规定替换 find 中的值的值。 
	 - string 必需。规定被搜索的字符串。 
	 - count 可选。一个变量，对替换数进行计数。
 - **举例-1**

代码如下：

    <?php
        $arr = array("blue","red","green","yellow");
        print_r(str_replace("red","pink",$arr,$i));
        echo "Replacements: $i";
    ?>
	

 - **输出**


    Array
    (
        [0] => blue
        [1] => pink
        [2] => green
        [3] => yellow
    )
    Replacements: 1
	
	
	

#####substr_replace
 - **作用**：substr_replace() 函数把字符串的一部分替换为另一个字符串。
 - **参数描述** 
	 - string 必需。规定要检查的字符串。 
	 - replacement 必需。规定要插入的字符串。 
	 - start 必需。规定在字符串的何处开始替换。
		 - 正数 - 在第 start 个偏移量开始替换
		 - 负数 - 在从字符串结尾的第 start 个偏移量开始替换
		 - 0 - 在字符串中的第一个字符处开始替换
	 - charlist 可选。规定要替换多少个字符。
		 - 正数 - 被替换的字符串长度
		 - 负数 - 从字符串末端开始的被替换字符数
		 - 0 - 插入而非替换
		 
		 

 - **举例-1**


    <?php
        echo substr_replace("Hello world","earth",6);
    ?>
    

	
 - **输出**：

 
    Hello earth
	
	

#####preg_replace

 - **作用**：执行一个正则表达式的搜索和替换

 - **参数描述** 
	 - pattern 必需。需要搜索的模式。 
	 - replacement 必需。用于替换的字符串或数组。 
	 - subject 必需。需要替换的字符串或数组。 
	 - limit 替换的次数。-1为无限 
	 - count 完成替换的次数，变量

 - **举例-1**
 
 
    <?php
        $string = 'April 15, 2003';
        $pattern = '/(w+) (d+), (d+)/i';
        $replacement = '${1}1,$3';
        echo preg_replace($pattern, $replacement, $string);
    ?>

	
 - **输出**
 
 
    April1,2003

	
	
 - **举例-2**
 

    <?php
        $string = 'The quick brown fox jumped over the lazy dog.';
        $patterns = array();
        $patterns[0] = '/quick/';
        $patterns[1] = '/brown/';
        $patterns[2] = '/fox/';
        $replacements = array();
        $replacements[2] = 'bear';
        $replacements[1] = 'black';
        $replacements[0] = 'slow';
        echo preg_replace($patterns, $replacements, $string);
    ?>

	
	
 - **输出**
 
 
    The bear black slow jumped over the lazy dog.


#####preg_split

 - **作用**：通过正则表达式分割字符串
 - **参数描述** 
	 - pattern 必需。需要搜索的模式。 
	 - replacement 必需。用于替换的字符串或数组。 
	 - subject 必需。需要替换的字符串。 
	 - limit 被分割的字符串最多limit。
	 - flag 模式

	 
- **举例-1** 


	 <?php
        //使用逗号或空格(包含" ", \r, \t, \n, \f)分隔短语
        $keywords = preg_split("/[\s,]+/", "hypertext language, programming");
        print_r($keywords);
    ?>
	
- **输出** 
	
	
    Array
    (
        [0] => hypertext
        [1] => language
        [2] => programming
    )
	
- **举例-2** 

    <?php
        $str = 'string';
        $chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);
        print_r($chars);
    ?>


- **输出** 
	
	
    Array
    (
        [0] => s
        [1] => t
        [2] => r
        [3] => i
        [4] => n
        [5] => g
    )
	
	
- **举例-3**

 	
    <?php
        $str = 'hypertext language programming';
        $chars = preg_split('/ /', $str, -1, PREG_SPLIT_OFFSET_CAPTURE);
        print_r($chars);
    ?>
	
- **输出**


    Array
    (
    [0] => Array
        (
            [0] => hypertext
            [1] => 0
        )

    [1] => Array
        (
            [0] => language
            [1] => 10
        )

    [2] => Array
        (
            [0] => programming
            [1] => 19
        )

    )
	
	
#####str_split

 - **作用**：将字符串分割成数组
 - **参数描述** 
	 - subject 字符串。 
	 - length 每一段的长度。


 - **举例-1**
 
 
    <?php
        print_r(str_split("Hello"));
    ?>
	
	
 - **输出**
 
 
    Array
    (
        [0] => H
        [1] => e
        [2] => l
        [3] => l
        [4] => o
    )
	
	
 - **举例-2**
 
 
    <?php
        print_r(str_split("Hello",3));
    ?>
	
	
 - **输出**
 
 
    Array
    (
        [0] => Hel
        [1] => lo
    )
	
	