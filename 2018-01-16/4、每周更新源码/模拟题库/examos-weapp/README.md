# ExamOS-WeApp

---------
### `ExamOS-WeApp` ExamOS微信小程序

此页面用于记录`ExamOS-WeApp`个人相关进度

----------
### ExamOS-WeApp相关信息
- 开始时间：2016.12.30
- 结束时间：undefined
- 面向平台：微信小程序
- 插件： 'wxParse 0.3'

------------
#### AJAX请求模板

```
var that = this;
wx.request({
url: app.url.host + app.url.categories,
method: 'GET',
data: {
  pid:-1
},
header: {
  Authorization: wx.getStorageSync('Authorization'),
  // 'content-type': 'application/x-www-form-urlencoded'
},
success: function(res){
  if(res.statusCode == '200') {
    var data = res.data, Arr = [];
    for (var i = 0; i < data.length; i++) {
      Arr.push();
    }
    that.setData({xxx: Arr});
  } else {
    app.unauthorized(res.statusCode);
  } //statusCode-else结束
}
});
```

------------
#### 使用wxParse插件直接解析从后台获取的html

原理（个人理解）：
- 利用了微信小程序中的`<template"/> `标签
- 在模板wxml中已经按照正常html文档的格式，将wxml中的`<template"/> `扩展，达到解析html的效果
- *都是小程序不能插入dom的锅*

基本使用方法：
1.  Copy文件夹`wxParse`
  ```
    - wxParse/
      -wxParse.js(必须存在)
      -html2json.js(必须存在)
      -htmlparser.js(必须存在)
      -showdown.js(必须存在)
      -wxDiscode.js(必须存在)
      -wxParse.wxml(必须存在)
      -wxParse.wxss(必须存在)
      -emojis(可选)
  ```
2. 引入必要文件
  ```
      //在使用的View中引入WxParse模块
      var WxParse = require('../../wxParse/wxParse.js');
      //在使用的Wxss中引入WxParse.css,可以在app.wxss
      @import "/wxParse/wxParse.wxss";
  ```
3. 数据绑定
  ```
    var article = '<div>我是HTML代码</div>';
    /**
    * WxParse.wxParse(bindName , type, data, target,imagePadding)
    * 1.bindName绑定的数据名(必填)
    * 2.type可以为html或者md(必填)
    * 3.data为传入的具体数据(必填)
    * 4.target为Page对象,一般为this(必填)
    * 5.imagePadding为当图片自适应是左右的单一padding(默认为0,可选)
    */
    var that = this;
    WxParse.wxParse('article', 'html', article, that,5);
  ```
4.  模版引用
  ```
    //这里data中article为bindName
    <template is="wxParse" data="{{wxParseData:article.nodes}}"/>
  ```

------------
#### 微信小程序使用font-awesome图标库

[参考方法](http://www.wxapp-union.com/forum.php?mod=viewthread&tid=1211)

1. 下载`font-awesome`字体包
2. 打开[Transfonter](http://transfonter.org/)网站，上传字体`fontawesome-webfont.ttf`（理论其它文件格式也可以转换，并未尝试），选择base64编码，convert后下载
3. 下载得到的包中有style文件，打开后获得以下代码，并对照`font-awesome.css`中的内容，加入到微信小程序的`app.wxss`文件中

  ```
  @font-face {
    font-family: 'fa';
    src: url(data:font/truetype;charset=utf-8;base64,AAEAAAANAIAAAwBQRkZUTXLOMIUAAlXMAAAAHEdERUYAJwKrAAJVrAAAAB5PUy8yiDJ6IwAAAVgAAABgY21hcJ0vdNQAAAw4AAADAmdhc3D//wADAAJVpAAAAAhnbHlmHejPwQAAGdQAAh3kaGVhZAbB4eAAAADcAAAANmhoZWEO+QqbAAAB......long long) format('truetype');
    font-weight: normal;
    font-style: normal;
  }

  .fa {
    font-family: "fa" !important;
    font-size: 16px;
    -webkit-font-smoothing: antialiased;
    -webkit-text-stroke-width: 0.2px;
  }

  /* makes the font 33% larger relative to the icon container */
  .fa-lg {
    font-size: 1.33333333em;
    line-height: 0.75em;
    vertical-align: -15%;
  }
  .fa-2x {
    font-size: 2em;
  }

  //long long long...........

  ```

4. 然后在小程序中使用``` class="fa fa-user" ```即可，如```<text class="fa fa-user"></text>```
