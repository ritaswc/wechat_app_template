//请求喜好页数据,传入用户选择喜好的数据
const AV = require('./leancloud.js');
//读取一个标签的数据
function getNewLove(){
    de();
    var data;
     wx.request({
        url: 'https://route.showapi.com/109-35', //仅为示例，并非真实的接口地址
      data:{
          channelId:"",
          channelName:"",
          maxResult:"20",
          needAllList:"",
           needContent:"",
          needHtml:"",  
          page:"1",
          showapi_appid:"30851",
          title:"头条",          
          showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
          showapi_timestamp:"",
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
        console.log(res)
        data=res.data
        //1.处理有，返回check=true的array[{message}]
        //2.赋值给array
            ok();
        },
        fail:function(){
            error();
        }
    })
    return data;
}
//获取用户喜好列表
function getLove(){
     de();
    var data;
     wx.request({
        url: 'test.php', //仅为示例，并非真实的接口地址
      data:demo,
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
        console.log(res.data)
        data=res.data
        //1.处理有，返回check=true的array[{message}]
        //2.赋值给array
            ok();
        },
        fail:function(){
            error();
        }
    })
    return data;
}
//提示
function de(){
     wx.showToast({
        title: '加载中',
        icon: 'loading',
        duration: 10000
      })
    setTimeout(function(){
        wx.hideToast()
    },2000)
}
function ok(){
     wx.showToast({
        title: '成功',
        icon: 'success',
        duration: 2000
     })
     setTimeout(function(){
        wx.hideToast()
    },1000)
}
function error(){
     wx.showToast({
        title: '失败',
        icon: 'loading',
        duration: 1000
    })
    setTimeout(function(){
        wx.hideToast()
    },1000)
}
//上传图片
function images(){
    console.log("上传图片")
   new AV.File('file-name', {  
          blob: {  
            uri: "../images/00.jpg",  
          },  
        }).save().then(  
          file => console.log(file.url())  
          ).catch(console.error);  
}
//得到图片
function getimg(){
var query = new AV.Query('_File');
      query.get('58857ea4b123db16a33582e6').then(function (todo) {
        // 成功获得实例
        console.log("xx",todo.attributes)
        // data 就是 id 为 57328ca079bc44005c2472d0 的 Todo 对象实例
      }, function (error) {
        // 异常处理
      });
}
module.exports={
  de:de,
  ok:ok,
  error:error  
}