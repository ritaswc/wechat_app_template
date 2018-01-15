var s_data = require("../../utils/FormateDate.js")
Page({
  data:{
    imgUrl:[
        {img:"https://www.phyt88.com/phyt/wechat/images/new_slider2.jpg",url:"../activity/activity"},
        {img:"https://www.phyt88.com/phyt/wechat/images/new_slider1.jpg",url:"../activity/activity"},
        {img:"https://www.phyt88.com/phyt/wechat/images/new_slider1.png",url:"../activity/activity"}
    ]
  },
  onLoad:function(options){
    var that = this;
      // console.log(options);
      wx.request({
      url: 'https://www.phyt88.com/v2/project/obtain_big_section_list.jso?pageSize=1&pageIndex=1',
      data: "",
      method:"POST",
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
          res.data.rows[0].conSn = res.data.rows[0].conSn.substring(3,10);
          res.data.rows[0].last = res.data.rows[0].conSn.substring(-1,1);
          res.data.rows[0].deadlineStr = res.data.rows[0].deadlineStr.substring(0,1);
          res.data.rows[0].amount = res.data.rows[0].amount.substring(-1,6);
          res.data.rows[0].percent = ((res.data.rows[0].raisedAmount / res.data.rows[0].amount)*100).toFixed(1)
          res.data.rows[0].time = s_data.FormateDate(res.data.rows[0].time,'Y-m-d h:m:s')
          console.log(res.data.rows)
        that.setData({
            investList:res.data.rows
          })
      }
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
  }
})