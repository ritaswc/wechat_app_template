// pages/systemMassage/systemMassage.js
let requireMassage = require('../../utils/wxrequire').requireMassage;
Page({
  data:{
    massage:[],
    type_img:'',
    nodelLIst:false
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 20000000
    })
    let user_ticket = wx.getStorageSync('user_ticket');
    let _that = this;
    requireMassage( '/user/messages', user_ticket,function(res){
        let status = res.data.status;
        let data = res.data.data.list || [],massage;
        massage = data.map(function(item){
          if( item.type == '2' ){
            if( item.is_read == '1' ){
                return Object.assign({},item,{'imgUrl':'image/_63.png'});
            }else if( item.is_read == '0' ){
                return Object.assign({},item,{'imgUrl':'image/_61.png'});
            }
          }else if( item.type == '1' ){
            if( item.is_read == '1' ){
                return Object.assign({},item,{'imgUrl':'image/_53.png'})
            }else if( item.is_read == '0' ){
              return Object.assign({},item,{'imgUrl':'image/_51.png'})
            }
          }
        })
        // console.log( massage )
        if(status == 1){
            if( data.length == 0 ){  //== 没有数据
                _that.setData({
                    nodelLIst:true
                })
            }else{
                _that.setData({
                  massage:massage,
                  nodelLIst:false
              })
            }
            
            wx.hideToast()
        }else{
          console.log( res )
        }
    },function(){
      console.log( '接口调用失败' )
    })

  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  gomassage:function(e){
    let message_id = e.currentTarget.dataset.message_id;
    wx.navigateTo({
      url: '../massageContant/massageContant?message_id='+message_id,
    })
  },
  onPullDownRefresh:function(){  //下拉刷新 在home.josn中开启;
      let user_ticket = wx.getStorageSync('user_ticket');
      let _that = this;
      requireMassage( '/user/messages', user_ticket,function(res){
        let status = res.data.status;
        let data = res.data.data.list,massage;
        massage = data.map(function(item){
          if( item.type == '2' ){
            if( item.is_read == '1' ){
                return Object.assign({},item,{'imgUrl':'image/_63.png'});
            }else if( item.is_read == '0' ){
                return Object.assign({},item,{'imgUrl':'image/_61.png'});
            }
          }else if( item.type == '1' ){
            if( item.is_read == '1' ){
                return Object.assign({},item,{'imgUrl':'image/_53.png'})
            }else if( item.is_read == '0' ){
              return Object.assign({},item,{'imgUrl':'image/_51.png'})
            }
          }
        })
        console.log( massage )
        if(status == 1){
            _that.setData({
                massage:massage
            })
            wx.stopPullDownRefresh()
        }else{
          console.log( res )
        }
    },function(){
      console.log( '接口调用失败' )
    })
      
  },

})