Page({
  data:{
    navData : {},
    goodsData : {},
    currentCatId : 0,
    active : 0
  },
  type : 2,
  onLoad : function(options){
    // wx.showLoading({
    //   title : '加载中',
    //   mask : true
    // })
    this.loadNav();
  },
  onShow : function(){
    // var page = this;
    // var timer = setInterval(function(){
    //   var data = wx.getStorageSync('goodsData');
    //   for(var i=0;i<data.length;i++){
    //     data[i].group_end_time = page.timeFormat(data[i].group_off_time * 1000);
    //   }
    //   page.setData({goodsData:data})
    // },1000)
    // page.timer = timer;
    this.loadGroupList();
  },
  onHide : function(){
    clearInterval(this.timer);
  },
  onUnload : function(){
    clearInterval(this.timer);
  },
  // 加载导航菜单
  loadNav : function(){
    var page = this;
    wx.request({
      url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goodscate/index',
      data: {data:JSON.stringify({"parent_id":0})},
      method: 'POST',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
        //  wx.hideLoading(); 
         page.setData({navData:res.data.data})
      }
    })
  },
  loadGroupList : function(){
    if(wx.showLoading){
      wx.showLoading({
        title : '加载中...',
        mask : true
      })
      setTimeout(function(){
        wx.hideLoading()
      },1000)
    }
      var cat_id = 0;
      if(arguments.length > 0){
          cat_id = arguments[0].currentTarget.dataset.catid;
      }
      clearInterval(this.timer);
      var page = this;
      wx.request({
        url: 'https://shizhencaiyuan.com/groupAdmin.php/Home/Goods/group',
        data: {data:JSON.stringify({"cat_id" : cat_id,is_low : this.type})},
        method: 'POST',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        success: function(res){
           var data = res.data.data;console.log(data)
           var timer = setInterval(function(){
               for(var i=0;i<data.length;i++){
                 data[i].group_end_time = page.timeFormat(data[i].group_off_time * 1000);
               }
               page.setData({goodsData:data,currentCatId:cat_id})
             },1000)
             page.timer = timer;
          },
          // page.setData({
          //   goodsData:res.data.data,
          //   currentCatId:cat_id
          // })
          // wx.setStorageSync('goodsData', res.data.data)
          // page.setData({goodsData:res.data.data})
          // wx.hideLoading()
        // }
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
  changeType : function(e){
    var type = e.currentTarget.dataset.type;
    if(type == 'group'){
        this.setData({active:0});
        this.type = 2;
        this.loadGroupList();
    }else{
        this.setData({active:1});
        this.type = 1;
        this.loadGroupList();
    }
  },
  offered : function(e){
    var group_id = e.currentTarget.dataset.groupid;
    wx.navigateTo({
      url: '/pages/offered/offered?group_id=' + group_id
    })
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  }
})