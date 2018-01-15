# 有调微信小程序

目前的问题：

## 一. 在安卓下会报错（4.4.4/5.1/6.0.1/7.1.1），可能是部分ES6 API在地版本X5内核下识别不出来
 ###　解决办法
 1. 使用ES5 polyfill，但试了2个库还是有问题（https://github.com/es-shims/es5-shim / https://github.com/inexorabletash/polyfill）会报错
 2. 等待微信更新
   1. 微信小程序本身提供ES5 polyfill
   2. 微信X5内核更新到TBS3.0（基于Chrome53）
