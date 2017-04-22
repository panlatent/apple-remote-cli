Apple Remote CLI
================
[![Build Status](https://travis-ci.org/panlatent/apple-remote-cli.svg)](https://travis-ci.org/panlatent/apple-remote-cli)
[![Latest Stable Version](https://poser.pugx.org/panlatent/apple-remote-cli/v/stable.svg)](https://packagist.org/packages/panlatent/apple-remote-cli) 
[![Total Downloads](https://poser.pugx.org/panlatent/apple-remote-cli/downloads.svg)](https://packagist.org/packages/panlatent/apple-remote-cli) 
[![Latest Unstable Version](https://poser.pugx.org/panlatent/apple-remote-cli/v/unstable.svg)](https://packagist.org/packages/panlatent/apple-remote-cli) 
[![License](https://poser.pugx.org/panlatent/apple-remote-cli/license.svg)](https://packagist.org/packages/panlatent/apple-remote-cli)

Apple Remote protocol console application. Using console control your iTunes.

![](http://wx1.sinaimg.cn/mw690/005LUFJRly1fetdabsq1yg30ic029dh2.gif)

![](http://wx3.sinaimg.cn/mw690/005LUFJRly1fetdacxy49g30gd03s76j.gif)

![](http://wx3.sinaimg.cn/mw690/005LUFJRly1fetdaeildig30gd041773.gif)

What's this
------------
Apple Remote Cli like `Apple Remote App（ iOS ）`. It is a command-line program, 
so you can use it to control your iTunes, it's cool!

This tool includes a command line character ui. It can display song and states.
You can use a shortcut key like `Vim` to control iTunes. 

About Matches
-------------
This project not supported with iTunes recognition and matching. Very sad. You 
need to add a `--auth=` parameter，requires you to manually capture matching 
data.

    (!) 由于使用 PHP 编写，无法直接调用相关系统API，也没有找到与 Bonjour 服务通信的方法，
    更无法抢占设备的 mDNS 端口。所以该命令实现目前需要使用 iOS 设备遥控器配对数据。**

Requirements
-------------
+ PHP 5.6 or later

Installation
-------------
Download the library using composer:

```bash
$ composer require panlatent/apple-remote-cli
```

Usage
-----
Use a command:
```bash
$ apple-remote-cli play/next/last/vol [-+]value
```

Run player:
```bash
$ apple-remote-cli player
```
The `player` command will open a character UI. Use a key control iTunes:

 `q` Quit | `p` Play/Pause | `j` Next | `k` Last | `s` Switch Shuffle | `r` Switch Repeat
 
Character UI is a single process. This means that it is affected by network (HTTP Request).

We have an experimental option `--gui`, it will open a GUI window. :) Help we improve.

License
-------
The Apple Remote CLI is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
