
var app = getApp();

Page({
  data : {
    groupList : {}
  },
  onLoad : function(options){
    this.uid = wx.getStorageSync('uid');
  },
  onHide : function(){
    clearInterval(this.timer);
  },
  onUnload : function(){
    clearInterval(this.timer);
  },
  onShow : function(){
    // var page = this;
    // this.setTimer(page,this.data.groupList);
    this.loadGroupList();
  },
  loadGroupList : function(){
    var post = {
      uid : this.uid,
      openid : null
    }
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Group/myGroup',
      data: {data:JSON.stringify(post)},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
        var data = res.data.data;//console.log(data)
        var timer = setInterval(function(){
          for(var i=0;i<data.length;i++){
             data[i].group_end_time = page.timeFormat(data[i].group_off_time * 1000);
          }console.log(444333)
         page.setData({groupList:data})
        },1000)
        page.timer = timer;
        // page.setTimer(page,data);
      },
      fail: function(res) {
        // fail
      }
    })
  },
  setTimer : function(page,data){
    var timer = setInterval(function(){
      for(var i=0;i<data.length;i++){
          data[i].group_end_time = page.timeFormat(data[i].group_off_time * 1000);
      }console.log(444333)
      page.setData({groupList:data})
    },1000)
    page.timer = timer;
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
  orderDetail : function(e){
    var order_id = e.currentTarget.dataset.orderid;
    wx.navigateTo({
      url: '/pages/mine/order/info/info?order_id=' + order_id
    })
  }, 
  groupDetail : function(e){
    var group_id = e.currentTarget.dataset.groupid;
    wx.navigateTo({
      url: '/pages/mine/group/info/info?group_id=' + group_id
    })
  }, 
  backHome : function(e){
    app.backHome();
  }
})