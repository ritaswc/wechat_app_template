# weapp-weatherfine

It is a demp of weapp for weather forecast. Weapp is a small web application by wechat.

TODO:
- Customerized switcher with different cities
- Customerized languange setting
- Customerized temperature unit setting

# 微信小程序笔记二：天气应用

> 源码github地址在此，记得点星：
https://github.com/brandonxiang/weapp-weatherfine

## 构思

### 查询用户位置的天气

我在构思要做一款基于位置的天气预报的程序。小程序需要获取位置数据，获取该城市的天气信息。这样可以让全国各个城市的人查询到属于自己的天气信息。

但是问题来了，在调用微信小程序的定位服务的过程中，只会返回坐标位置，并不会返回城市信息。由于天气状况的查询是根据城市的信息来查询。这是我们需要进行一次“把坐标转换为城市名称”的转换，依靠高德地图的反地址编码。

### 自定义城市

根据用户登陆后的用户信息，每个用户可以自定义自己所选的城市。由于每个用户的信息不同，这需要用户信息的区分。

这个功能并未完成开发，只满足北京和上海城市天气的切换。

### 设置

针对语言和温度单位进行个性定制化的设置。其中，语言包括中文和英语，温度单位包括华氏度和摄氏度。

## 界面

![界面](http://upload-images.jianshu.io/upload_images/685800-9d2a7b75a81c9b03.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

## 优缺点

经过一番的“小程序”体验后，说说它的优缺点：

- 优点
  - 接口完整度高
  - 极其容易上手，如果有vue的经验更佳

- 缺点
   - 缺乏第三方库支持，不能用包管理工具
   - 很其他框架缺乏通用性，想转成web app非常麻烦
   -  css语法可以，但是并没有扩展sass等
   
总体用下来，`wx.request`和`wx.login`等接口给人一种似曾相识的感觉，但是小程序有很多的限制，可说明了它的不成熟性。

有人说微信有野心做成一款wechatOS，但是我觉得还是不太可能。从两个方面，第一，不是所有厂家愿意重新写一款应用去满足现有的功能。因为小程序对用户的“黏度”不大，还不如自己开发一款App，满足ios和安卓的用户。往往小程序不过成为“试验品”，不过是一款简化版的应用。第二，代码的不通用性，代码不能重复用于网页版等。同时，其扩展性也非常差，就像少了几个“键”的键盘，用的时候会捉襟见肘。所以，这样引发了关于小程序和PWA之间的讨论。

详情参考[说说 PWA 和微信小程序](https://zhuanlan.zhihu.com/p/22578965)，PWA（Progressive Web App）是Google在2015年提出来，还不过是网页应用，但是实现类似原生App的功能，包括**消息推送**，**后台加载**，**离线使用**，**原生应用界面**和**桌面图标**等类似桌面应用的功能。当然前提是浏览器对它的支持。PWA与生俱来的优点就是它的**代码通用性**，这是小程序所不具有的。如需了解更多请关注[]()。

##注意

由于高德地图和心知天气的key是个人开发者的key，如果你需要fork，请自行更换key。

转载，请表明出处。[总目录跨平台快速开发](http://www.jianshu.com/p/0348e33fb9d0)

![请关注我的微信公众号](http://upload-images.jianshu.io/upload_images/685800-b90086f21952919c.jpg?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)
