---
date: 2014-11-7 00:00:31+00:00
layout: post
title: Getting python
categories: doc
tags: TEST
---
####python入门


----------


#####输出打印

 1. 常规打印

    i = 5 
    print i
    s = '''this is a test'''
    print s
    length = 5.2 
    breadth = 2.2 
    area =  length * breadth
    print 'area is',area
    print 'hello world' 

结果如下：

    5
    this is a test
    area is 11.44
    hello world


 2. 格式化输出整数

    strHello = "the length of (%s) is %d" %('hello world',len('hello world'))
    print strHello


结果如下：

    the length of (hello world) is 11

 3. 格式化输出字符串

    print "%.3s " % ("jcodeer")
    print "%10.3s" % ("jcodeer")

结果如下：

    jco
              jco

#####循环遍历&判断

 1. 普通while循环

     count = 0 
    while (count < 3): 
        print 'the count is',count
        count = count+1
    print 'end the game

结果如下：

    the count is 0
    the count is 1
    the count is 2
    end the game

 2. 含有break的while循环

    i = 1 
    while 1:
        print i
        i += 1
        if i > 4:
            break

结果如下

    1
    2
    3
    4

 3. 含有continue的while循环

    i = 1 
    while i < 4:
        i += 1
        if i%2 > 0:
            continue
        print i

结果如下：

    2
    4

 4. 带有else的while循环

    count = 0 
    while count < 3:
        print count, 'is less than 3'
        count = count + 1 
    else:
        print count, 'is not less than 3'

结果如下：

    0 is less than 3
    1 is less than 3
    2 is less than 3
    3 is not less than 3

 5. 普通for循环

    for letter in 'dou':
        print 'the current letter is',letter
    fruits = ['hello','world']
    for fruit in fruits:
        print 'the current fruit is',fruit

结果如下：

    the current letter is d
    the current letter is o
    the current letter is u
    the current fruit is hello
    the current fruit is world

 6. 序列索引迭代的for循环

    fruits = ['banana','apple','mango']
    for index in range(len(fruits)):
        print index
        print 'current fruit :',fruits[index]

结果如下：

    0
    current fruit : banana
    1
    current fruit : apple
    2
    current fruit : mango

 7. 元组for循环

    x = [('html','css'),('php','python')]
    for a in x:
        print a[0]

结果如下：

    html
    php

 8. 带有else的for循环

    x = [1,2]
    for i in x:
        print i
    else:
        print 'finished!'

结果如下：

    1
    2
    finished!

#####字符串操作

 1. 去空格及特殊符号
 

    s = ',"  \nello,world   '
    s = s.lstrip(',"').strip().rstrip('')
    print s


结果如下：

    ello,world

 2. 原生字符串打印
 
    s = r',"  \nello,world   '
    print s

 3. 复制字符串

    str1 = 'doumao'
    str2 = str1
    print str2

结果如下：

    doumao

 4. 连接字符串
 

    str1 = 'hello'
    str2 = 'doumao'
    str1 += str2
    print str1

结果如下：

    hellodoumao

 5. 查找字符

    str1 = 'hello doumao'
    str2 = 'l' 
    pos =  str1.index(str2)
    print pos 

结果如下：

    2

 6. 比较字符串

    str1 = 'abcde'
    str2 = 'abcd'
    print(cmp(str1,str2))

结果如下：

    1

 7. 两个字符串长度的最小值

    str1 = 'lidoumao'
    str2 = 'haha'
    print(len(str1 and str2))

结果如下：

    4

 8. 字符串长度

    str = 'nihaodoumao'
    print len(str)

结果如下：

    11

 9. 将字符串中的大小写转换

    str = 'ABCabc'
    str1 = str.upper()
    str2 = str.lower()
    print str1
    print str2

结果如下：

    ABCABC
    abcabc

 10. 追加指定长度的字符串

    str1 = '12345'
    str2 = 'abcde'
    n = 3 
    str1 += str2[0:n]
    print str1

结果如下：

    12345abc

 11. 字符串指定长度比较

    str1 = 'abedaaaa'
    str2 = 'abcd'
    n = 3 
    print(cmp(str1[0:n],str2[0:n]))

结果如下：

    1

 12. 复制指定长度的字符

    str1 = 'doumaomao'
    n = 6 
    str2 = str1[0:n]
    print str2

结果如下：

    doumao

 13. 将字符串前n个字符替换为指定的字符

    str1 = '12345'
    ch = 'r' 
    n = 3 
    str1 = n * ch + str1[2:]
    print str1

结果如下：

    rrr345

 14. 扫描字符串

    str1 = 'abcdefg'
    str2 = 'cf'
    pos = -1
    for c in str1:
        if c in str2:
            pos = str1.index(c)
            print pos 
            continue

结果如下：

    2
    5

 15. 翻转字符串

    str1 = 'abcdefg'
    str1 = str1[::-1]
    print str1

结果如下：

    gfedcba

 16. 查找字符串

    str1 = 'abcdefg'
    str2 = 'ef'
    print str1.find(str2)

结果如下：

    4

 17. 分割字符串

    str = 'dou,mao'
    print(str.split(','))

结果如下：

    ['dou', 'mao']

 20. 截取字符串

    str = ’0123456789′
    print str[0:3] #截取第一位到第三位的字符
    print str[:] #截取字符串的全部字符
    print str[6:] #截取第七个字符到结尾
    print str[:-3] #截取从头开始到倒数第三个字符之前
    print str[2] #截取第三个字符
    print str[-1] #截取倒数第一个字符
    print str[::-1] #创造一个与原字符串顺序相反的字符串
    print str[-3:-1] #截取倒数第三位与倒数第一位之前的字符
    print str[-3:] #截取倒数第三位到结尾

 21. 字符串替换

    a = 'hello world'
    print a.replace('world','doumao')

结果如下：

    hello doumao

 22. 字符串正则替换

    import re
    a = 'hello 123 world 345'
    str = re.sub('\d+','doumao',a)
    print str

结果如下：

    hello doumao world doumao


#####文件操作

 1. open
 2. file
 3. read
 4. readline
 5. close


