var app =getApp()
Page({
  data:{
    "text":"投资记录",
    investList:[]
  },
  onLoad:function(e){
    var that = this;
    //   console.log(that);
      wx.request({
      url: 'https://www.phyt88.com/v2/project/obtain_invest_record_by_sid.json?pageSize=8&sid=21583&pageIndex=1',
      data:"",
      method:"POST",
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        // console.log(res.data.investRecordOutput.rows);
        that.setData({
            investList:res.data.investRecordOutput.rows,
          })  
      }
    })
  }
})