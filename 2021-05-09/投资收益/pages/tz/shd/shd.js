// var app =getApp()
var s_data = require("../../../utils/FormateDate.js")
Page({
  data:{
    "text":"投资详情",
    tab0Show:true
  },
  
  onLoad:function(options){

    var that = this;
      // console.log(options);
      wx.request({
      url: 'https://www.phyt88.com/v2/project/obtain_invest_record_by_sid.jso?pageSize=15&pageIndex=1&sid='+options.sid,
      data: "sid=" + options.sid,
      method:"POST",
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        var len=res.data.rows.length;
        for(var i=0;i<len;i++){
          res.data.rows[i].time = s_data.FormateDate(res.data.rows[i].time,'Y-m-d h:m:s')
        }
        that.setData({
            investList:res.data.rows,
            sid : options.sid
          })
      }
    }),
    // 商行贷
    wx.request({
      url: 'https://www.phyt88.com/v2/project/obtain_big_section_list.jso?pageSize=6&pageIndex=1',
      data: "",
      method:"POST",
      header: {
          'Content-Type': 'application/json'
      },
      success: function(res) {
        for(var i=0,num=res.data.rows.length;i<num;i++){
          // 给json数据里面的的 amount重新取值，其实就是利用字符串截取了整数部分
          res.data.rows[i].conSn = res.data.rows[i].conSn.substring(3,10);
          res.data.rows[i].last = res.data.rows[i].conSn.substring(-1,1);
          res.data.rows[i].amount = res.data.rows[i].amount.substring(-1,6);
          res.data.rows[i].percent = ((res.data.rows[i].raisedAmount / res.data.rows[i].amount)*100).toFixed(1)
        }
        that.setData({
            shdList:res.data.rows,
          })  
      }
    })
  },
  change:function(e){
      var type = [
          "tab0","tab1","tab2"
      ];

      var data = {};
      var id = e.currentTarget.id;
      //   console.log(id)
      for(var i=0,len = type.length;i<len;i++){
          data[type[i]+"Show"] = false ;
      };
      
      // data[id+"Show"] = !this.data[id+"Show"];
      // 点击自身的时候防止显示隐藏
      if(data[id+"Show"]==false){
        data[id+"Show"] = true;
      }else{
        data[id+"Show"] = !this.data[id+"Show"];
      }
      this.setData(data);
  },
  ShowHide:function(e){
    var id = e.currentTarget.id;
    var data = {};
    data[id + "Show" ] = !this.data[id + "Show" ];
    this.setData(data)
    // console.log(data)

  }
})