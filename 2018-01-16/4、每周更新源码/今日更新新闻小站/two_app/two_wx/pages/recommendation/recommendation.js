// pages/new_love/new_love.js
var request = require('../../utils/request.js');
// const AV = require('../../utils/leancloud.js');
// var new = require('News.js')
var urls="/pages/details/details?title=";
Page({
  data:{
     array: [{
      title: '前端',
      img:'https://dn-L4CYuorC.qbox.me/2c690eff34bc56ae1ab6.jpg',
      check:true
    }, {
      title: 'NBA',
      img:'https://dn-L4CYuorC.qbox.me/7fe3649fe6948f18b6f3.jpg',
      check:true
    },{
      title: '军事',
      img:'https://dn-L4CYuorC.qbox.me/e091e25f247187b83326.jpg',
      check:true
    },{
      title: '赵丽颖',
      img:'https://dn-L4CYuorC.qbox.me/532ee17a5e4e055f9515.jpg',
      check:false
    }
    ],
    flag:true,
    animationData:{},
    le:5,
    newqian:{title:"",img:"",check:true},
    imgSelect:[],
    imgIndex:0,
    trues:true,
    tempFilePaths:["https://dn-L4CYuorC.qbox.me/532ee17a5e4e055f9515.jpg"]
  },
  h2s:function(){
     var _this=this;
    _this.data.imgSelect=[];
    _this.data.newqian={title:"",img:"",check:true},
    _this.setData({imgSelect:_this.data.imgSelect,newqian:_this.data.newqian});
    if(this.data.flag){
       this.setData({
        flag:false
        })
    }else{
       this.data.le=0;
      for(var i=0;i<this.data.array.length;i++){
        if(this.data.array[i].check)
        {
           this.data.le++;
        }
      }
      console.log(this.data.le);
      this.setData({ array:this.data.array,le:this.data.le});
      this.setData({
        flag:true
        })
    }
  }
  ,
  checkSelect:function(e){
    var k=e.target.id.split('-')[1];
    this.data.array[k].check=! this.data.array[k].check;
    this.setData({ array:this.data.array});
  },
  tourl:function(e){
    console.log(e.target.dataset.title);
    getApp().title=e.target.dataset.title;
    wx.navigateTo({url:"/pages/newsItem/newsItem"});
  },
  //检查是否有数据
  addQian:function(e){
    var _this=this;
    _this.data.imgSelect=[];
    _this.setData({imgSelect:_this.data.imgSelect});
    if(this.data.newqian.title!=e.detail.value&& e.detail.value!=""){
      this.data.newqian.title= e.detail.value;
      //检查是否相同
      
      wx.request({
        url: 'https://route.showapi.com/109-35', 
      data:{
          channelId:"",
          channelName:"",
          maxResult:"15",
          needAllList:"",
          needContent:"0",
          needHtml:"",  
          page:"1",
          showapi_appid:"30851",
          title:e.detail.value,          
          showapi_sign:"f729add89f4c4851b8da64f6936ff6f6",
          showapi_timestamp:"",
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {  
          var qian=res.data.showapi_res_body.pagebean.contentlist;
          console.log(qian);
          // 判断是否存在数据
          if(qian.length==0){
              //  wx.showToast({
              //     title: '该订阅关联数据为零',
              //     icon: 'loading',
              //     duration: 1000
              // });
          }
          else{
            //汲取图片并且显示出来
                for(var i=0;i<qian.length;i++){
                  if(qian[i].imageurls.length!=0){
                    for(var j=0;j<1&&j<qian[i].imageurls.length;j++){
                        _this.data.imgSelect.push({url:qian[i].imageurls[j].url});
                  }}
                  if(_this.data.imgSelect.length>=7){
                    break;
                  }
                }
                _this.setData({imgSelect:_this.data.imgSelect});
                _this.data.newqian.img=_this.data.imgSelect[0].url;
                 _this.setData({newqian:_this.data.newqian,trues:_this.data.trues});
              }
        },
        fail:function(){
           request.error();
     }})
    }
    
  },
  subQian:function(){
        var _this=this;
      if(_this.data.newqian.title!=""){
      //本地保存自定义状态
     
      //判断是否有相同的
      var index=0;
      for(var i=0;i< _this.data.array.length;i++){
          if(_this.data.array[i].title==_this.data.newqian.title){
            _this.data.trues=false;
            _this.data.array[i]=_this.data.newqian;//存在
             wx.showToast({
            title: '替换成功',
            icon: 'loading',
            duration: 1000
            });
            break;
          }
      }
      // 不存在
        if(_this.data.trues){
            _this.data.array.push(_this.data.newqian);
             wx.showToast({
            title: '添加成功',
            icon: 'loading',
            duration: 1000
            });
        }
        _this.setData({array:_this.data.array,trues:true});
                //本地保存自定义状态
          wx.setStorage({
              key:"Qians",
              data:_this.data.array
          })
        }
      else{
            wx.showToast({
            title: '该订阅不能为空',
            icon: 'loading',
            duration: 1000
            });
          
      }
  },
  closeQian:function(){
      //本地保存自定义状态
      var _this=this;
       wx.setStorage({
                        key:"Qians",
                        data:_this.data.array
      })
      this.h2s();
  },
  check_img:function(e){
        var index=e.target.dataset.index;
        this.data.newqian.img=this.data.imgSelect[index].url;
        this.setData({newqian:this.data.newqian,imgIndex:index});
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    var _this=this;
    wx.getStorage({
        key: 'Qians',
        success: function(res) {
          if(res.data.length!=0){
            _this.setData({array:res.data});
            }
        } 
    }) ;
  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow: function(){
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  }
  ,chooseimage: function () {  
    var _this = this;  
    wx.chooseImage({  
      count: 9, // 默认9  
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有  
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有  
      success: function (res) {  
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片  
        _this.setData({  
          tempFilePaths: res.tempFilePaths  
        })  
       var tempFilePath = res.tempFilePaths[0];  
        new AV.File('file-name', {  
          blob: {  
            uri: tempFilePath,  
          },  
        }).save().then(  
          file => console.log(file.url())  
          ).catch(console.error);  
      }  
    })  
  },clear:function(){
    wx.clearStorage();
     var _this=this;
    wx.getStorage({
        key: 'Qians',
        success: function(res) {
          if(res.data.length!=0){
            _this.setData({array:res.data});
            }
        } 
    }) ;
  }
})