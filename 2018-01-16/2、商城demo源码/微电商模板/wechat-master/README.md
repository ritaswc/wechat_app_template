# 微信小程序商城开发---真机测试有用！！！！

## 目录

- [官方文档](#官方文档)
- [更多代码](#代码)
- [分析轮子](#分析轮子)



- [加入QQ群 564956472【微信小程序开发交流】(如果群满了加我个人QQ号(1009756987)我拉你入群)](http://jq.qq.com/?_wv=1027&k=40K4X8z)
## 官方文档

- [小程序开发文档](https://mp.weixin.qq.com/debug/wxadoc/dev/index.html)
- [小程序设计指南](https://mp.weixin.qq.com/debug/wxadoc/design/index.html)
- [小程序开发者工具](https://mp.weixin.qq.com/debug/wxadoc/dev/devtools/download.html)
- [从搭建一个微信小程序开始(腾讯云)](https://www.qcloud.com/act/event/yingyonghao.html)



## 实例教程
本实例纯属个人编写，侵删
### 一、环境搭建 ###

- 微信小程序后台开发，请参照[https://github.com/htmlk/express](https://github.com/htmlk/express)


在官网文档找到自己电脑适合的版本下载，直接安装（本人不建议使用破解版)

- [微信小程序最新工具下载](https://mp.weixin.qq.com/debug/wxadoc/dev/devtools/download.html)

接下来会提示使用微信二维码登录，直接扫码登录
### 二、下载demo ###
直接使用git工具clone 上述代码，点击添加项目，将本程序添加到项目中即可！

（appid选择无AppID，项目名随便取不一定是文件名，选择下载下来的目录）
![](http://i.imgur.com/yCAGELe.png)
### 三、开始编写代码 ###
进入调试页面（左边是调试预览，右边是类似于谷歌网页调试的工具）
![](http://i.imgur.com/xCKThm2.png)
进入编辑代码页面
![](http://i.imgur.com/w2l2YJQ.png)
1、app.json是项目的配置文件，如右图显示，

第一部分（黑色框）是pages是整个里的页面，每添加页面一个页面，都要把路径写在这里：

第二部分tabbar只要配置这些文件就可以产生app底部的导航（具体可参见文档）

第三部分是widows全局配置

2、pages是指你每个页面里面有四个文件json（配置文件)，js（自己编写的js），wxml（相当于html），wxss（相当于css）

3、公共文件可以一般存储在远端，目前开发可以在本地新建，例如images（不能使用icon文件）

4、更多详细可以联系博主

### 项目演示 ###

[点击查看，项目动态演示](http://7xn9on.com1.z0.glb.clouddn.com/video.mp4 "项目动态演示")


## 更多代码

- [微信小应用示例代码(phodal/weapp-quick)](https://github.com/phodal/weapp-quick)
- [微信小应用地图定位demo(giscafer/wechat-weapp-mapdemo)](https://github.com/giscafer/wechat-weapp-mapdemo)
- [微信小应用- 掘金主页信息流(hilongjw/weapp-gold)](https://github.com/hilongjw/weapp-gold)
- [微信小程序（应用号）示例：微信小程序豆瓣电影(zce/weapp-demo)](https://github.com/zce/weapp-demo)
- [微信小程序-豆瓣电影(hingsir/weapp-douban-film)](https://github.com/hingsir/weapp-douban-film)
- [小程序 hello world 尝鲜(kunkun12/weapp)](https://github.com/kunkun12/weapp)
- [微信小程序版2048小游戏(natee/wxapp-2048)](https://github.com/natee/wxapp-2048)
- [微信小程序-微票(wangmingjob/weapp-weipiao)](https://github.com/wangmingjob/weapp-weipiao)
- [微信小程序购物车DEMO(SeptemberMaples/wechat-weapp-demo)](https://github.com/SeptemberMaples/wechat-weapp-demo)
- [微信小程序V2EX(jectychen/wechat-v2ex)](https://github.com/jectychen/wechat-v2ex)
- [微信小程序-知乎日报(myronliu347/wechat-app-zhihudaily)](https://github.com/myronliu347/wechat-app-zhihudaily)
- [微信小程序-公众号热门文章信息流(hijiangtao/weapp-newsapp)](https://github.com/hijiangtao/weapp-newsapp)
- [微信小程序版Gank客户端(lypeer/wechat-weapp-gank)](https://github.com/lypeer/wechat-weapp-gank)
- [微信小程序集成Redux实现的Todo list(charleyw/wechat-weapp-redux-todos)](https://github.com/charleyw/wechat-weapp-redux-todos)
- [微信小程序-番茄时钟(kraaas/timer)](https://github.com/kraaas/timer)
- [微信小程序项目汇总](http://javascript.ctolib.com/categories/javascript-wechat-weapp.html)
- [微信小程序版聊天室(ericzyh/wechat-chat)](https://github.com/ericzyh/wechat-chat)
- [微信小程序-HiApp(BelinChung/wxapp-hiapp)](https://github.com/BelinChung/wxapp-hiapp)
- [小程序Redux绑定库(charleyw/wechat-weapp-redux)](https://github.com/charleyw/wechat-weapp-redux)
- [微信小程序版微信(18380435477/WeApp)](https://github.com/18380435477/WeApp)
- [小程序开发从布局开始(hardog/wechat-app-flexlayout)](https://github.com/hardog/wechat-app-flexlayout)
- [微信小程序-音乐播放器(eyasliu/wechat-app-music)](https://github.com/eyasliu/wechat-app-music)
- [微信小程序-简易计算器-适合入门（dunizb/wxapp-sCalc）](https://github.com/dunizb/wxapp-sCalc)
- [微信小程序-github(zhengxiaowai/weapp-github)](https://github.com/zhengxiaowai/weapp-github)
- [微信小程序-小熊の日记(harveyqing/BearDiary)](https://github.com/harveyqing/BearDiary)
- [微信小程序(Seahub/PigRaising)](https://github.com/SeaHub/PigRaising)
- [微信小程序(WeChatMeiZhi妹子图)](https://github.com/brucevanfdm/WeChatMeiZhi)
- [微信小程序快速开发骨架](https://github.com/zce/weapp-boilerplate)
- [微信小程序 - Artand 最专业的艺术设计平台](https://github.com/SuperKieran/weapp-artand)

## 分析轮子

- [微信小程序倒计时组件(微信公众号)](http://mp.weixin.qq.com/s?__biz=MzI0MjYwMjM2NQ==&mid=2247483670&idx=1&sn=5aa5da2fff2415e9b19f848712ddf480&chksm=e9789904de0f1012159332fda391c3eec0bb3d1c0db2c34ab557208ff0c04806a40d00e844fe&mpshare=1&scene=1&srcid=1007cWRXdd0ug9oAceCsIWp6#rd)
- [微信小程序下拉筛选组件(微信公众号)](http://mp.weixin.qq.com/s?__biz=MzI0MjYwMjM2NQ==&mid=2247483674&idx=1&sn=2bf242b391144f3f0e57e0ed0ebce36f&chksm=e9789908de0f101ee23f7c125c9a48c4f9ba3f242a3b1c89b05ca5b9e8e68262c02b47fe3d12&mpshare=1&scene=1&srcid=1008NvO9oI8wWGp4XBxlpLeL#rd)

### 经验分享
1、出现空白，没有报错，有可能是因为json是空。加一个{}就好了
2、出现问题直接加博主微信。
3、技术交流、商务合作请加微信。

![](http://7xn9on.com1.z0.glb.clouddn.com/weixin.jpg)