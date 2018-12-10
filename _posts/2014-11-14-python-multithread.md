---
date: 2014-11-14 10:20:31+00:00
layout: post
title: python-multithreading
categories: doc
tags: PYTHON
---




# python线程编程实现

----------

## 1、CPU/进程/线程
看了好几篇类似文章，最后提取出来的就是这些，应该还有更深入的
### 1.1、进程与线程的区别

>  进程：程序的一次执行
>  线程：CPU的基本调度单位

 - 线程是进程的一部分
 - 一个没有线程的进程是可以被看作单线程的，如果一个进程内拥有多个进程，进程的执行过程不是一条线（线程）的，而是多条线（线程）共同完成的
 - 系统在运行的时候会为每个进程分配不同的内存区域，但是不会为线程分配内存（线程所使用的资源是它所属的进程的资源），线程组只能共享资源。那就是说，除了CPU之外（线程在运行的时候要占用CPU资源），计算机内部的软硬件资源的分配与线程无关，线程只能共享它所属进程的资源。
 - 进程是系统所有资源分配时候的一个基本单位，拥有一个完整的虚拟空间地址，并不依赖线程而独立存在。

### 1.2、线程间通信

 - 线程同步
 - 线程互斥

### 1.3、线程与CPU的关系

 - cpu调度单位为线程
 - 操作系统会协调cpu处理不同进程下的线程。通常来说，线程也多的时候，cpu多的相对处理的快
 - 在查阅时，看到了一个问题，一个多核cpu与多个单核cpu选择哪个的问题，挺有趣的。用了一个比喻，你为什么不在家里上班却要去公司。各自在家里上班的你们是多个单核cpu，去公司上班的你们是一个多核cpu。想想就有点懂了利弊。
## 2、python线程
重点看python提供的threading库
引用以下文章，描述的很详实：
http://www.cnblogs.com/huxi/archive/2010/06/26/1765808.html

### 2.1、单队列多线程

     #!/usr/bin/env python
              import Queue
              import threading
              import urllib2
              import time
          
          hosts = ["http://yahoo.com", "http://google.com", "http://amazon.com",
          "http://ibm.com", "http://apple.com"]
          
          queue = Queue.Queue()
          
          class ThreadUrl(threading.Thread):
          """Threaded Url Grab"""
            def __init__(self, queue):
              threading.Thread.__init__(self)
              self.queue = queue
          
            def run(self):
              while True:
                #grabs host from queue
                host = self.queue.get()
            
                #grabs urls of hosts and prints first 1024 bytes of page
                url = urllib2.urlopen(host)
                print url.read(1024)
            
                #signals to queue job is done
                self.queue.task_done()
          
          start = time.time()
          def main():
          
            #spawn a pool of threads, and pass them queue instance 
            for i in range(5):
              t = ThreadUrl(queue)
              t.setDaemon(True)
              t.start()
              
           #populate queue with data   
              for host in hosts:
                queue.put(host)
           
           #wait on the queue until everything has been processed     
           queue.join()
          
          main()
          print "Elapsed Time: %s" % (time.time() - start)

针对上述code,过程如下：

 1. 创建一个 Queue.Queue() 的实例，然后使用数据对它进行填充
 2. 将经过填充数据的实例传递给线程类，后者是通过继承 threading.Thread 的方式创建的
 3. 生成守护线程池
 4. 每次从队列中取出一个项目，并使用该线程中的数据和 run 方法以执行相应的工作
 5. 在完成这项工作之后，使用 queue.task_done() 函数向任务已经完成的队列发送一个信号
 6. 对队列执行 join 操作，实际上意味着等到队列为空，再退出主程序

> join()的意义
保持阻塞状态，直到处理了队列中的所有项目为止。在将一个项目添加到该队列时，未完成的任务的总数就会增加。当使用者线程调用 task_done() 以表示检索了该项目、并完成了所有的工作时，那么未完成的任务的总数就会减少。当未完成的任务的总数减少到零时，join() 就会结束阻塞状态。


### 2.2、多队列多线程

    import Queue
    import threading
    import urllib2
    import time
    from BeautifulSoup import BeautifulSoup

    hosts = ["http://yahoo.com", "http://google.com", "http://amazon.com",
            "http://ibm.com", "http://apple.com"]
    
    queue = Queue.Queue()
    out_queue = Queue.Queue()
    
    class ThreadUrl(threading.Thread):
        """Threaded Url Grab"""
        def __init__(self, queue, out_queue):
            threading.Thread.__init__(self)
            self.queue = queue
            self.out_queue = out_queue

    def run(self):
        while True:
            #grabs host from queue
            host = self.queue.get()

            #grabs urls of hosts and then grabs chunk of webpage
            url = urllib2.urlopen(host)
            chunk = url.read()

            #place chunk into out queue
            self.out_queue.put(chunk)

            #signals to queue job is done
            self.queue.task_done()

    class DatamineThread(threading.Thread):
        """Threaded Url Grab"""
        def __init__(self, out_queue):
            threading.Thread.__init__(self)
            self.out_queue = out_queue

    def run(self):
        while True:
            #grabs host from queue
            chunk = self.out_queue.get()

            #parse the chunk
            soup = BeautifulSoup(chunk)
            print soup.findAll(['title'])

            #signals to queue job is done
            self.out_queue.task_done()

    start = time.time()
    def main():

    #spawn a pool of threads, and pass them queue instance
    for i in range(5):
        t = ThreadUrl(queue, out_queue)
        t.setDaemon(True)
        t.start()

    #populate queue with data
    for host in hosts:
        queue.put(host)

    for i in range(5):
        dt = DatamineThread(out_queue)
        dt.setDaemon(True)
        dt.start()


    #wait on the queue until everything has been processed
    queue.join()
    out_queue.join()

    main()
    print "Elapsed Time: %s" % (time.time() - start)


在上面添加了另一个队列实例，然后将该队列传递给第一个线程池类 ThreadURL。接下来，对于另一个线程池类 DatamineThread，几乎复制了完全相同的结构。

在这个类的 run 方法中，从队列中的各个线程获取 Web 页面、文本块，然后使用 Beautiful Soup 处理这个文本块。

在这个示例中，使用 Beautiful Soup 提取每个页面的 title 标记、并将其打印输出。

可以很容易地将这个示例推广到一些更有价值的应用场景

## 3、文件操作
 1. open
 2. file
 3. read
 4. readline
 5. close

## 4、编码相关

checkCode = chardet.detect(rpContent)

