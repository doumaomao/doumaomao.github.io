---
date: 2014-9-23 10:00:31+00:00
layout: post
title: Detailed Usage of cURL
categories: doc
tags: php
---

----------


### curl命令使用

- curl www.baidu.com                   查看网页源码，前提是有权限
- curl -o test www.baidu.com           查看网页代码，并且保存至test文件
- curl example.com/form.cgi?data=xxx   发送GET类型的请求，会直接返回该url的返回值
- curl --data "method=xxx" test/envup  发送POST类型的请求，会直接返回该url的返回值
- --data-urlencode 该参数与--data的唯一区别是经过url编码
- curl --user-agent "[User Agent]" [URL] 模拟以自己设定的useragent来访问url
- curl --cookie "name=xxx" www.example.com curl发送cookie给对应的url
- curl --header "Content-Type:application/json" http://example.com  curl增加头信息
- curl --user name:password example.com 对于一些需要用户名密码认证的url，可以通过该方式认证

### python中的使用
