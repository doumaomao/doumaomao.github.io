---
layout: post
title: Python中module介绍
categories: doc
tags: python
---



# Python中module/package官网翻译
我们日常有如下几种使用python的场景

- 命令行开个python解释器，一次性编写，退出则全无
- 用IDE写个python脚本
- 写一个中大型功能，期望你写的方法可以被人复用

为了满足这些场景，python提出了模块的概念，即把 变量、函数、类名等定义放在一个文件中，当你用命令行、脚本交互时可直接使用该文件中的定义，这样的文件我们称之为module。module中的定义也可以被其他module导入并使用。

准确的说：module就是一个包含各类定义的python文件，文件前缀名称就是module的名称。

## 1 更多关于module的信息

如上文所提到的，module会包含一些可执行的语句比如说函数定义。而这些语句仅会在当该module第一次被import时执行。当然，如果该module被当做脚本运行时，必然也会被执行到。

每个module都有自己的私有符号表，这里的私有符号指的是module中的变量、函数名、类名等定义，只需要保证在你的module中全局唯一即可，不用担心和其他用户是否有冲突。当你使用这些私有符号时，使用如下：`module_name.func_name `

Module可以引入其他module，一般都会放在文件的开头处，导入的module名称，也会作为该module的私有全局符号表中。

假设文件名为：fibo.py。内容如下：

```
# Fibonacci numbers module

def fib(n):    # write Fibonacci series up to n
    a, b = 0, 1
    while b < n:
        print b,
        a, b = b, a+b

def fib2(n):   # return Fibonacci series up to n
    result = []
    a, b = 0, 1
    while b < n:
        result.append(b)
        a, b = b, a+b
    return result
```


`from fibo import fib, fib2`，全局符号表中写入了 fib、fib2

`from fibo import *`，全局符号表中会写入除了以_开头的私有变量方法之外的全部定义名，这种方式官方并不建议，可读性差。

`import fibo as fib`，等同于`import fibo`，唯一的区别是它具备别名


module中的定义也可被别名，如`from fibo import fib as fibonacci`

### 1.1 以脚本形式执行module

我的理解，一个文件我们既希望它可以作为module去提供服务，也可以作为脚本去快速进行执行，比如说用于自测，部分功能实现等。那么此时，只要在文件末尾加上自定义的执行步骤即可。

```
if __name__ == "__main__":
    import sys
    fib(int(sys.argv[1]))
```

采用此种形式，当该文件作为module导入时，if中的逻辑不会被执行。
作为脚本执行时，也可灵活定制脚本的执行语句。


### 1.2 module搜索路径

当一个名为spam的module被导入时，解释器首先会从已有的内置模块中寻找。如果没有找到，进而从sys.path变量中给出的目录列表里依次寻找spam.py文件。
sys.path初始化的目录路径为：

- 该执行脚本当前所在的目录
- PYTHONPATH，类似shell中PATH，可参考[1. 命令行与环境 — Python 2.7.16rc1 文档](https://docs.python.org/zh-cn/2.7/using/cmdline.html#envvar-PYTHONPATH)
- 安装默认路径

初始化之后，sys.path也可以通过程序添加如下：

`sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))`


### 1.3 “编译过的”python文件们

对spam.py进行过字节编译后会生成 spam.pyc文件，用于创建spam.pyc的spam.py版本的修改时间会记录在spam.pyc中。

正常来说，生成spam.pyc用户不需要做任何事情。每当spam.py被成功编译时，都会将对应版本的修改时间写入spam.pyc中。

## 2 标准module
Python附带了一个标准模块库，一些模块内置于解释器中。其中sys模块在每个python解释器中都会内嵌。其中sys.path正是定义了module搜索路径的目录列表，可以使用标准列表操作来修改sys.path
```
import sys
sys.path.append('/ufs/guido/lib/python')
```

## 3 dir() 函数
内置函数 dir() 用于查找模块定义的名称，它返回一个排序过的字符串列表。

```
>>> import fibo, sys
>>> dir(fibo)
['__name__', 'fib', 'fib2']
```

如果不输入参数，则`dir()`默认返回当前的全部定义包括变量、module、函数等。
`dir()`不会输出内置的函数和变量，如果你想要内置函数和变量可以执行
```
>>> import __builtin__
>>> dir(__builtin__)  
```

## 4 包（package）


包是一种通过用“带点号的模块名”来构造 Python 模块命名空间的方法。例如：A.B 代表A包中的B模块。正如模块的使用使得模块作者不用担心模块内变量的冲突，包同样的道理，使用包的作者，不用担心包下的模块会重名冲突。

假设你想为声音文件和声音数据的统一处理，设计一个模块集合（一个“包”）。由于存在很多不同的声音文件格式（通常由它们的扩展名来识别，例如：.wav， .aiff， .au），因此为了不同文件格式间的转换，你可能需要创建和维护一个不断增长的模块集合。 你可能还想对声音数据还做很多不同的处理（例如，混声，添加回声，使用均衡器功能，创造人工立体声效果）， 因此为了实现这些处理，你将另外写一个无穷尽的模块流。这是你的包的可能结构（以分层文件系统的形式表示）：

```
sound/               Top-level package
      __init__.py    Initialize the sound package
      formats/       Subpackage for file format conversions
              __init__.py
              wavread.py
              wavwrite.py
              aiffread.py
              aiffwrite.py
              auread.py
              auwrite.py
              ...
      effects/       Subpackage for sound effects
              __init__.py
              echo.py
              surround.py
              reverse.py
              ...
      filters/       Subpackage for filters
              __init__.py
              equalizer.py
              vocoder.py
              karaoke.py
              ...
```

需要__init__.py文件才能使Python将目录视为包。这样做是为了防止具有通用名称的目录（例如string）无意中隐藏在模块搜索路径上发生的有效模块。在最简单的情况下，__ init__.py可以只是一个空文件，但它也可以执行包的初始化代码或设置__all__变量


<1>  导入形式一


`import sound.effects.echo`

此类导入在代码调用中如下：

`sound.effects.echo.echofilter(input, output, delay=0.7, atten=4)`

<2>  导入形式二

`from sound.effects import echo`

该形式也是加载echo模块，使用过程如下：

`echo.echofilter(input, output, delay=0.7, atten=4)`

<3>  导入形式三

`from sound.effects.echo import echofilter`

使用办法为：

`echofilter(input, output, delay=0.7, atten=4)`

注意当使用`from package import item`形式时，item可以是module、函数、类、变量。当这些定义无法找到时，会报导入错误。

相反，当使用`import item.subitem.subsubitem`之类的语法时，除了最后一项之外的每个项都必须是一个包，最后一项可以是模块或包，但不能是前一项中定义的类、函数或变量。


### 4.1 从包中导入*

现在当用户从`sound.effects import *`中写入时会发生什么？理想情况下，我们希望以某种方式传递给文件系统，找到包中存在哪些子模块，并将它们全部导入。这可能需要很长时间，导入子模块可能会产生不必要的副作用，这种副作用只有在显式导入子模块时才会发生。

唯一的解决方案是让包用户提供包的显式索引。

如何提供显示索引呢？

import语句使用以下约定：

如果包的__init__.py代码定义了名为__all__的列表，则它将被视为遇到包import * 时应导入的模块名称列表。在发布新版本的软件包时，由软件包作者决定是否保持此列表的最新状态。如果包作者没有看到从包装中导入* 的用途，他们也可能决定不支持它。例如，文件sound/effects/__ init__.py可能包含以下代码：

`__all__ = ["echo", "surround", "reverse"]`

这意味着 `from sound.effects import * `将导入 sound 包的三个子模块。

如果未定义__all__，则`sound.effects import *`中的语句不会将包`sound.effects`中的所有子模块导入当前名称空间；它只确保已导入包`sound.effects`

`from Package import specific_submodule`是官方推荐的用法。

### 4.2 子包参考

子包之间也会有互相引用的需求，比如说surround引用echo。这种引用需求非常常见，按照之前所说，引用时会优先查找当前包路径，这类场景用
`import echo` 或者 `from echo import echofilter`这样的方式即可。

当包被构造成子包时（与示例中的 sound 包一样），你可以使用绝对导入来引用兄弟包的子模块。例如，如果模块 `sound.filters.vocoder`需要使用`sound.effects` 包中的 `echo` 模块，可以使用 `from sound.effects import echo` 进行导入。

从Python 2.5开始，除了上面描述的隐式相对导入之外，您还可以使用import语句的from module import name形式编写显式相对导入。这些显式相对导入使用前导点来指示相对导入中涉及的当前和父包。例如，从surround模块，可以使用：

```
from . import echo
from .. import formats
from ..filters import equalizer
```

### 4.3 多个目录中的包

包支持另一个特殊属性__path__。这个变量可以修改，这样做会影响将来对包中包含的模块和子包的搜索。

