---
date: 2015-11-16 14:20:31+00:00
layout: post
title: open-resty一期（安装/技术链接）
categories: doc
tags: nginx
---




----------


## openresty安装

 1. 从https://openresty.org/的download页面下载对应版本的openresty包
 2. `tar zxvf ngx_openresty-1.7.10.2.tar.gz`  
 3. 解压后检查自己的编译依赖
	 - 确保gcc版本大于等于4.0.0以上（`export PATH="${JUMBO_ROOT}/opt/gcc5/bin:$PATH"`）
	 - 保证有openssl/pcre/luajit/lua依赖（ http://blog.58share.com/?p=275 ）
 4. 确保各类编译依赖都具备之后，进入ngx_openresty-1.9.3.1目录下，执行`./configure --prefix=/home/users/openresty`。该命令是指将生成nginx包的路径指定到`/home/users/openresty`下。同时还可以指定各类参数，比如说`--with-luajit`,`--without-pcre`等。
 5. 在执行完configure之后，进行`gmake && gmake install`
 6. 执行完后，会发现在/home/users/openresty目录展现如下：

```
 bin  client_body_temp  fastcgi_temp  logs  luajit  lualib  nginx  proxy_temp  scgi_temp  uwsgi_temp
```

7. 其中luajit为采用C语言写的Lua的解释器的代码，主要是提高运行效率；lualib里面是openresty本身提供的一些方法，包括redis,db等；nginx内则包含conf、sbin、logs等基本配件。
8. 新建独立的文件夹openrestry_test文件夹，新建conf子文件夹。新建nginx.conf文件，内容如下：


```
worker_processes  1;        #nginx worker 数量
error_log logs/error.log;   #指定错误日志文件路径
events {
    worker_connections 1024;
}
http {
    server {
        #监听端口，若你的6699端口已经被占用，则需要修改
        listen 6699;
        location / {
            default_type text/html;
            content_by_lua ngx.say("hello doumao")
        }
    }
}
```

9. 针对上述6999端口要特别注意，一般办公网内对于端口会做一些安全限制，这块我就踩了坑。。。注意选取合适端口即可。
10. 搭建成功，访问，展现“hello doumao”

## openresty会议要点

>针对openresty 围绕三个主题进行展开。分别是：整体架构、openresty的发展历程以及未来构划、业务应用场景。


a fast web app server by extending nginx - 这段英文形象解释了openresty。
具体内容待整理笔记后追加。

## open-resty实战项目

- https://github.com/doumaomao/apigateway
- https://github.com/openresty/nginx-systemtap-toolkit
- https://github.com/openresty/stapxx
- https://github.com/openresty/nginx-gdb-utils
- https://github.com/agentzh/code2ebook

## 偶像们的博客

- https://github.com/agentzh?tab=repositories
- https://github.com/yaoweibin/
- http://jinnianshilongnian.iteye.com/
- https://twitter.com/agentzh

## resty开源社区

- http://openresty.org/
- http://www.oschina.net/question/tag/openresty

