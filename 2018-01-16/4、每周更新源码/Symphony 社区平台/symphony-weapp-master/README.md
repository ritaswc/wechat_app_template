# symphony-weapp

[Symphony](https://github.com/b3log/symphony) 社区平台的微信小程序，提供一些实用工具服务，比如[『书单』](https://hacpai.com/tag/book_share)。

## 登录

![login](https://cloud.githubusercontent.com/assets/873584/21684780/0bc06672-d39a-11e6-9580-5b9839bbc3a6.png)

* 对 md5.js 进行封装，使其可以在小程序中进行使用
* 微信提交登录请求
* 使用微信数据缓存存储用户标识以实现类似 Cookie 的作用

## 书单

![isbn](https://cloud.githubusercontent.com/assets/873584/21684779/0bb8084c-d39a-11e6-99df-1093694ded09.png)

* 调用微信扫码接口读取书籍 ISBN
* 微信提交书籍信息查询

## 共享

![share](https://cloud.githubusercontent.com/assets/873584/21706876/2dc126ba-d405-11e6-9e59-dc3bab775f49.png)

* 微信提交书籍共享请求

## 其他微信 API

* 设置导航条
* 导航
* 交互反馈
* 网络状态

## 服务端

服务端接口请参考 [Symphony](https://github.com/b3log/symphony) 项目 :)
