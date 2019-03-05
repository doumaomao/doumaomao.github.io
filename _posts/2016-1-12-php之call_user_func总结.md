---
date: 2016-1-12 15:58:31+00:00
layout: post
title: php之call_user_func相关
categories: php
tags: php
---




----------



     
    

> 之前用过，但是没体系化总结。如果现在问我，这两个函数的使用场景，与别的调用方式的优劣势，感觉自己并不能完整说出。


## 应用场景
call_user_func以及call_user_func_array应用场景均是当需要动态调用函数时才使用的。
**动态**调用函数有两种

1. 调用独立的函数
2. 调用类内部的函数

**折射到实际使用场景中那就是**

1. 你要调用的函数名是未知的
2. 要调用函数的参数类型及个数也是未知的

因为未知，所以得用call_user_func_array这样的函数，这也同时是它的优势所在。

## 区别
上述二者区别是什么呢？
其实区别真不大，主要区别在于参数传入方式不同。我个人认为，call_user_func_array的参数结构更加清晰些。但总体来说，结合个人倾向来定即可。


## call_user_func

**方法原型**：

    mixed call_user_func ( callable $callback [, mixed $parameter [, mixed $... ]] )

**参数**
callback
将被调用的回调函数（callable）。

parameter
0个或以上的参数，被传入回调函数。


**应用举例**
一、调用普通函数

    <?php
    function barber($type)
    {
        echo "You wanted a $type haircut, no problem\n";
    }
    call_user_func('barber', "mushroom");
    call_user_func('barber', "shave");
    ?>

二、调用类的实例

    <?php
    class a {
        function b($i) 
        {
            echo $i;
        }
    }
    
    //当php <5.3时，可以如下使用，此时会把 b()方法当作是a的一个静态方式。
    call_user_func(array("a", "b"),"111");
    //当php >=5.3时，类的公开的非静态的方法必须在类实例化后方可被调用，否则会提示Strict性错误（为了兼容先前及以后的版本，还是用对象方法传入）。
    $obj=new a;
    call_user_func(array($obj, "b"),"111");//显示 111 
    ?>

三、调用类的静态方法

    <?php
    class a {
        public static c($k)
        {
            echo $k;
        }
    }
    //静态方法可以如下方式调用
    call_user_func(array("a", "b"),"111");
    //或
    call_user_func("a::b","111");
    ?>

**疑惑点**

官方网站的解释是，传入call_user_func()的参数不能为引用传递。
但亲自测试了下，引用传递是生效的。这块没大搞懂。


## call_user_func_array

**方法原型**

    mixed call_user_func_array ( callable $callback , array $param_arr )

**参数**
callback
被调用的回调函数。

param_arr
要被传入回调函数的数组，这个数组得是索引数组。


**应用举例**

与call_user_func除了参数变为数组外，其余场景基本一致。

    <?php
    function foobar($arg, $arg2) {
        echo __FUNCTION__, " got $arg and $arg2\n";
    }
    class foo {
        function bar($arg, $arg2) {
            echo __METHOD__, " got $arg and $arg2\n";
        }
    }
    
    
    // Call the foobar() function with 2 arguments
    call_user_func_array("foobar", array("one", "two"));
    
    // Call the $foo->bar() method with 2 arguments
    $foo = new foo;
    call_user_func_array(array($foo, "bar"), array("three", "four"));
    ?>


## 实际项目中使用

一、封装通用接口
比如说
 `phplib_class::call('module','function',$arrInput);`

二、场景化
举一个不恰当的例子，比如说博客页面。
每个人的博客风格自带不同属性，当我
1、想要统一收敛这些属性到一个模块，同时做到隔离
2、支持用户自定义自己想要哪些属性
满足这两点，底层可以通过call_user_func系列来实现。

