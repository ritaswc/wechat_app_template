/**
 * 本文件主要是工具类函数
 */


/*
 自己基于wx.request封装的一个请求函数（粗陋封装各位不要笑话）
 因为在小程序开发中request是最常用的api所以会造成很多的代码重复
 因此将其在封装之后可以大大的减少代码的复用
*/
let myRequest = function (args = { url: '', methods: 'GET', data: {}, success: function () { }, fail: function () { } }) {
  wx.request({
    url: args.url,
    data: args.data,
    header: { 'content-type': 'application/json' },
    method: args.methods,
    dataType: 'json',
    responseType: 'text',
    success: (res) => {
      
      if (res.statusCode == 200) {
        // 请求成功执行回调函数
        args.success(res)
      } else {
        // 请求失败执行回调函数
        args.fail()
      }
    },
  })
}

function handlerGobackClick(delta) {
  const pages = getCurrentPages();
  if (pages.length >= 2) {
    wx.navigateBack({
      delta: delta
    });
  } else {
    wx.navigateTo({
      url: '/pages/index/index'
    });
  }
}

function handlerGohomeClick() {
  wx.navigateTo({
    url: '/pages/index/index'
  });
}
const handlerClick = { 
  GobackClick: function (delta)
  {
    return handlerGobackClick(delta)
  },
  GohomeClick:function()
  {
    return handlerGohomeClick()
  }
}
// 向外暴露接口
module.exports = {
  myRequest: myRequest,
  handlerClick: handlerClick

}
