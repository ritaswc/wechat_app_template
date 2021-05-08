
var app = getApp();

Page({
  data : {
    groupInfo : {},
    animationData : {}
  },
  deg : 0,
  onLoad : function(options){
    //this.loadGroupInfo(options.group_id)
    wx.setStorageSync('info_group_id', options.group_id);
  },
  onHide : function(){
    clearInterval(this.timer);
  },
  onUnload : function(){
    clearInterval(this.timer);
  },
  onShow : function(){
    if(wx.showLoading){
      wx.showLoading({
        title : '加载中...',
        mask : true
      })
      setTimeout(function(){
        wx.hideLoading()
      },1000)
    }
    this.loadGroupInfo(wx.getStorageSync('info_group_id'))
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
  },
  loadGroupInfo : function(group_id){
    var page = this;
    var animation = wx.createAnimation();
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goods/goinGroup',
        data: {data:JSON.stringify({"group_id":group_id})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){//console.log(res)
           var data = res.data.data;
          // groupInfo.goodsinfo.has_person = groupInfo.goodsinfo.group_low_person - groupInfo.goodsinfo.group_already_person;console.log(groupInfo)
          // page.setData({
          //   groupInfo:groupInfo
          // })
          //  wx.setStorageSync('groupInfo', res.data.data)

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