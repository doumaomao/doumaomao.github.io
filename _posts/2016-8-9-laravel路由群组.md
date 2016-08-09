---
date: 2016-8-9 12:38:31+00:00
layout: post
title: 【laravel路由总结】路由群组
categories: doc
tags: php
---

## 目录列表

>本节主要记录路由群组基础概念



- 中间件
- 命名空间
- 子域名路由
- 路由前缀
- 命名路由



> 路由群组允许我们在多个路有中共享一些路有属性，避免我们为每一个路由单独定义属性。共享属性以数组的形式作为第一个参数被传递给 Route::group 方法。接下来的中间件、命名空间、子域名路由、路由前缀、命名路由这几个均是可以共享的属性。



### 中间件



```
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        // 使用 Auth 中间件
    });

    Route::get('doumao/test', function () {
        // 使用 Auth 中间件
    });
});
```


一个中间件定义可以作用于整个路由组下的所有连接匹配。




### 命名空间

默认我们的controller都在App\Http\Controllers\下，加入命名空间之后，等同于默认进入我们所定义的文件夹下。


```
Route::group(['namespace' => 'Admin'], function(){
    // 控制器在 "App\Http\Controllers\Admin" 命名空间下

    Route::group(['namespace' => 'User'], function(){
        // 控制器在 "App\Http\Controllers\Admin\User" 命名空间下
    });
});
```




### 子域名路由

关键字段是domain



```
Route::group(['domain' => '{account}.myapp.com'], function () {
    Route::get('user/{id}', function ($account, $id) {
        //
    });
});
```




### 路由前缀

关键词是prefix。


```
Route::group(['prefix' => 'admin'], function () {
    Route::get('users', function () {
        // 匹配 "/admin/users" URL
    });
});
```



### 命名路由

单个命名路由获取到的是单个url.
路有助获取到的是url数组。




```
Route::group(['as' => 'admin::'], function () {
    Route::get('dashboard', ['as' => 'dashboard', function () {
        // 路由名称为「admin::dashboard」
    }]);
});
```



