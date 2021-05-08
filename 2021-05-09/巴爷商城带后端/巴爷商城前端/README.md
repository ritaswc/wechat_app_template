![扫一扫使用小程序]( https://baye-media.oss-cn-shanghai.aliyuncs.com/uploads/media_files/170109/fe09e491-6df6-43a1-8529-59d88c8df1f1.jpg)

# 简介

这是一个巴爷商城的微信小应用版本，是[巴爷微信商城](https://wechat.bayekeji.com)的简单版本。

# 实现功能

* 首页商品浏览
* 分类商品浏览
* 加入购物车
* 编辑收货地址
* 通过API与后端通信（获取商品列表、提交订单，付款等）

需要后台 JSON API 应用的配合，我们提供了一个参考的[后台实现](https://github.com/bayetech/wechat_mall_applet_backend)，但您几乎肯定仍然需要自己写一个后端。

# 常见问题

如果遇到问题可以直接提issue:

 - 甄选商品无法显示：rapi-staging.bayekeji.com 是测试服务器，可能会有其他同事新建不完整的测试产品，造成这样的问题
 - 如果填写错误的appid，也是没法实现登陆的，因为和服务器端配置的appid不一致，就没法从微信获取用户数据，另外我好像也不能告诉你们这个小程序的appid吧。。。所以你们的确需要自己写一个后端，后端代码开源地址：https://github.com/bayetech/wechat_mall_applet_backend ，仅供参考。


# 屏幕截图

![a](http://although2013.com/images/wechat_applet_1.png)
![a](http://although2013.com/images/wechat_applet_2.png)
![a](http://although2013.com/images/wechat_applet_3.png)
