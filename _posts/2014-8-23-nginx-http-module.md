---
date: 2014-08-23 12:00:31+00:00
layout: post
title: Learning HTTP Module in nginx
categories: doc
tags: NGINX
---

# http框架配置


----------

## 1. 配置项简介
http框架定义了3个级别的配置main,srv,loc.分别表示直接出现在http{},server{},location{}块内的配置项.

> http模块提供三个回调方法create_main_conf、create_srv_conf、create_loc_conf负责把配置项的结构体传递给http框架。由于上述不用配置项的存在，实现这3个回调方法的意义是不同的。

这种设计更能增强http配置的灵活性而不用担心函数调用的耦合性。


## 2. http配置项的解析方式

 
	struct ngx_command_s {
	    ngx_str_t name;
	    ngx_uint_t type;
	    char *(* set)(ngx_conf_t *cf,ngx_command_t *cmd,void *conf);
	    ngx_uint_t conf;
	    ngx_uint_t offset;
	    void *post;
	 };

上述结构体ngx_command_s是nginx的一个基本结构体。其中：

 - `name`是配置项名称，别如扩展中涉及到的的"doumao_print"字符串.
 - `type`决定该配置项可以在哪些块中出现，确切的说是指令的属性，在什么位置出现，带几个参数.
 - `char *(* set)(ngx_conf_t *cf,ngx_command_t *cmd,void *conf)`; 返回值为`char *`的函数指针。这里是指解析指令的回调函数.nginx中提供了14种解析配置项的方法，在自己写扩展的时候可以参考借鉴.
 - `conf`，用于指示配置项所处内存的相对偏移位置。对于http模块，conf的设置是必要的。如上所说，http会调用指令解析函数，自动将解析出的配置项写入http模块代码定义的结构体中，在配置项简介中了解到，http模块可能定义3个结构体，分别存储于main，srv，loc级别的配置项中。（对应于`create_main_conf,create_srv_conf,create_loc_conf`方法创建的结构体），而http框架在自动解析时需要知道应把解析出的配置项值写入哪个结构体中。这点需要依赖conf配置的值.
 - `offset`，表示当前配置项在整个存储配置项的结构体中的偏移位置，以字节为单位。举个例子：我们通过指令解析函数回调时，需要通过conf成员找到应该用哪个结构体存放，然后通过offset成员找到这个结构体中的相应成员，以便存放该配置.
 - `post`，用来辅助指令回调函数，可以使其更加灵活。

举个例子来加深理解，更多可以参见nginx中14种方法的预设解析赋值


    static ngx_command_t  ngx_doumao_print_commands[] = {
	    { 
			  ngx_string("doumao_print"), 
		      NGX_HTTP_LOC_CONF|NGX_CONF_TAKE1,
		      //其中NGX_HTTP_LOC_CONF: 是指指令在location配置部分出现是合法的。
	          //NGX_CONF_TAKE1: 指令读入1个参数 这些参数都定义在nginx的模块指令中，模块的指令是定义在一个叫做ngx_command_t的静态数组中。  
		      ngx_doumao_print_readconf,
		      NGX_HTTP_LOC_CONF_OFFSET, 
		      offsetof(ngx_doumao_print_loc_conf_t, ecdata),
			  NULL 
	    },ngx_null_command
    };

 上面的offset成员，一个很重要的目的是加强nginx对预设配置项解析处理的通用性。nginx配置解析模块，在调用ngx_command_t结构体的set回调方法时，会同时把offset偏移位置传进来。

## 3. http配置模型
nginx的14种预设配置项，支持了包括指令参数为布尔值、数字、空间大小、时间长短、枚举值等多种情况。为nginx的指令配置解析提供极大的方便，如果需要自定义解析配置函数，可以拿预设的14种作为借鉴参考。
      http配置模型涉及到的基础是`ngx_http_conf_ctx_t`结构体。结构体内容如下：
  
    typedef struct{
    /*指针数组，数组中每个元素指向所有http模块create_name_conf、create_srv_conf、create_loc_conf三种方法产生的结构体*/
    void **main_conf;
    void **srv_conf;
    void **loc_conf;
    }ngx_http_conf_ctx_t;

当nginx检测到http{...}这个关键配置项时，http配置模型就启动了，这时会首先建立上述的`ngx_http_conf_ctx_t`结构。http{...}块中通过1个ngx_http_conf_ctx_t结构保存了所有http模块的配置数据结构的入口。

## 4. 解析http配置的流程

  

 1. 当发现配置的http{}关键字时，配置模型开始启动
 2. http框架会初始化所有http模块的序列号。并创建3个数组用于存储所有http模块的create_main_conf,create_loc_conf,create_srv_conf方法返回的指针地址，并把这3个数组的地址保存到ngx_http_conf_ctx_t结构中
 3. 调用每个http模块的create_main_conf,create_loc_conf,create_srv_conf的方法
 4. 把各http模块上述三个方法返回的地址依次保存到ngx_http_conf_ctx_t结构体的3个数组中
 5. 调用每个http模块的preconfiguration方法。该方法主要用于初始化 http 组件和 nginx 其他组件的交互
 6. 如果调用preconfiguration方法失败的话，那么nginx进程停止
 7. 成功后，http框架开始循环解析nginx.conf文件中http{...}里面的所有配置项
 8. 配置文件解析器在检测到第1个配置项后，会遍历所有的http模块，检查它们ngx_command_t数组中的name项是否与配置项名相同
 9. 如果找到1个http模块的name项与该指令相同，则调用ngx_command_t结构中的回调方法进行处理
 10. 回调set方法返回是否成功。若失败，则停止进程
 11. 配置文件解析器继续检测配置项。如果发现有server{...}配置项，就会调用ngx_http_core_module来处理。该模块明确处理server块的配置项
 12. ngx_http_core_module模块在解析server{...}之前，会类似第2步建立ngx_http_conf_ctx_t的结构，并建立数组保存所有http模块返回的指针地址。然后，它会调用每个http模块的create_srv_conf,create_loc_conf方法。
 13. 将12步中的各个http模块返回的指针地址保存到ngx_http_conf_ctx_t中
 14. 开始调用配置解析器来处理server{...}里面的配置项
 15. 继续第8步的过程，此时遍历的是server{...}里面的配置项
 16. 配置文件解析遍历到server块尾部，说明server块内的配置项处理完毕。返回ngx_http_core_module模块
 17. http core此时返回至配置文件解析器继续解析后面的配置项，主要是loc里面的配置
 18. 最后配置文件处理到了http{...}的尾部，返回给http框架继续处理
 19. 配置文件解析器处理完所有的配置项后会告诉nginx主循环配置项解析完毕，这时nginx才会启动web服务器

## 5.http三种create方法的好处
上面几节都在讲如何根据不同的配置使用这三种create方法，但是还未深入讲讲这样的好处。
nginx中对这三种create方法有着独特的内存布局。

通过上述的内容可知，http块下有1个ngx_http_conf_ctx_t结构，而每个server{}块下也有1个ngx_http_conf_ctx_t结构。ngx_http_conf_ctx_t结构中含有main_conf,srv_conf,loc_conf3个指针数组。某个server块下的main_conf数组将通过直接指向来复用所属的http块下的main_conf数组，可以理解为server块下没有main级别的配置。loc和server关系类似如此。按照这种思路，在nginx.conf配置文件中的http{},server{},location{}块的总个数有多少，create_loc_conf方法就会被调用多少次。http{},server{}块总个数有多少，create_srv_conf方法就会被调用多少次。由于只有1个http，所以create_main_conf方法只会被调一次。这样的内存布局，主要是为了解决nginx中的同名配置项的合并问题。
