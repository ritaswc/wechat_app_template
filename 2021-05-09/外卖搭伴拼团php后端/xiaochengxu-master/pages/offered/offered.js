
var app = getApp();

Page({
  data : {
    groupInfo : {},
    animationData : {}
  },
  deg : 0,
  group_id : 0,
  onLoad : function(options){
    this.group_id = options.group_id;
    this.uid = wx.getStorageSync('uid');
  },
  onHide : function(){
    clearInterval(this.timer);
  },
  onUnload : function(){
    clearInterval(this.timer);
  },
  onShow : function(){
    // var animation = wx.createAnimation();
    // var page = this;
    // var data = wx.getStorageSync('groupInfo');

    // var timer = setInterval(function(){
    //   var time = page.timeFormat(data.goodsinfo.group_off_time * 1000);
    //   data.goodsinfo.group_end_time = time;
    //   if(!time){
    //     page.setData({
    //       groupInfo:data
    //     })
    //     return ;
    //   }
    //   page.deg += 6;console.log(3333)
    //   animation.rotate(page.deg).step();
    //   page.setData({
    //     groupInfo:data,
    //     animationData:animation.export()
    //   })
    // },1000)  
    // this.timer = timer;
    this.loadGroupInfo();
  },
  loadGroupInfo : function(){
    if(wx.showLoading){
      wx.showLoading({
        title : '加载中...',
        mask : true
      })
      setTimeout(function(){
        wx.hideLoading()
      },1500)
    }
    var animation = wx.createAnimation();
    var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goods/goinGroup',
        data: {data:JSON.stringify({"group_id":this.group_id})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){console.log(res.data.data)
          // page.setData({
          //   groupInfo:res.data.data
          // })
          page.offered_goods_id = res.data.data.goodsinfo.goods_id;
          var data  = res.data.data;
          var timer = setInterval(function(){
          var time = page.timeFormat(data.goodsinfo.group_off_time * 1000);
            data.goodsinfo.group_end_time = time;
            data.goodsinfo.has_person = data.goodsinfo.group_low_person - data.goodsinfo.group_already_person;
            if(!time){
              page.setData({
                groupInfo:data
              })
              return ;
            }
            page.deg += 6;console.log(3333)
            animation.rotate(page.deg).step();
            page.setData({
              groupInfo:data,
              animationData:animation.export()
            })
          },1000)  
          page.timer = timer;
        }
      })
  },
  pay : function(){
    var goods_id = this.offered_goods_id;
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Order/checkOrder',
      data: {data:JSON.stringify({"uid":this.uid,"openid":null,"group_id":page.group_id,"goods_id":goods_id})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){console.log(res.data)
        if(res.data.status == -3){
          wx.showToast({
            title : res.data.msg
          })
          return ;
        }
        wx.setStorageSync('goods_id',goods_id);
        wx.navigateTo({
          url: '/pages/order/checkout/checkout?group_id=' + page.group_id
        })
      }
    })
  },
  showTenant : function(e){
    var mid = e.currentTarget.dataset.mid;
    wx.navigateTo({
      url: '/pages/tenant/tenant?mid=' + mid
    })
  },
  timeFormat : function(timestamp){
    var page = this;
    var timestamp = timestamp;
    // setInterval(function(){
      var currentTime = (new Date()).getTime();
      var time = timestamp - currentTime;
      if(time <= 0) return false;
      var times = Math.floor(time / (1000 * 60 * 60))+':'+Math.floor(time / (1000 * 60) % 60)+':'+Math.floor(time / 1000  % 60);
    return times;
    // },1000)

  },
  backHome : function(e){
    app.backHome();
  }
})