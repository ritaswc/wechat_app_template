//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    windowHeigh:0,
    hphm:"",//号牌号码
    score:"",//违章扣分总数
    listCount:0,//未处理违章的总条数
    moneyCount:0,//总罚款
    queryWZUrl:"https://api.chinadatapay.com/government/traffic/167/3",//获取违章记录url
    resultData:[
            {
                "time": "2016-07-08 07:16:32",
                "address": "[西湖区]长江路_长江路竞舟北路口(长江路)11111111",
                "content": "不按规定停放影响其他车辆和行人通行的1111111111111",
                "legalnum": "7003",
                "price": "150",
                "score": "0",
                "number": "",
                "illegalid": "4821518"
            }
        ]//数据
  },
  onLoad: function () {
    console.log(app.globalData.resultData.lists)
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
    wx.getSystemInfo({
      success: function(res) {
        that.setData({
          windowHeigh:res.windowHeight
        })
      }
    })
    if(app.globalData.resultData == null){
        that.queryData();
    }else{
        that.setResultData(app.globalData.resultData);
    }
  },
  queryData:function(){
    var that = this;
    var cphm = app.globalData.cphm;
    var cjh = app.globalData.cjh;
    var fdjh = app.globalData.fdjh;
    var lstype = app.globalData.lstype;
    var lsprefix = app.globalData.lsprefix;
    var carorg = app.globalData.carorg;
    wx.request({
        url: this.data.queryWZUrl, 
        data: {
          key: app.globalData.appKey,
          lsprefix:lsprefix,
          lsnum:cphm,
          lstype:lstype,
          frameno:cjh,
          engineno:fdjh,
          carorg:carorg
        },
        header: {
            'content-type': 'application/json'
        },
        success: function(res) {
          console.log(res.data);
          if(res.data.code == "10000"){
            that.setResultData(res.data.data.list);
          }else{
             that.setData({
               errorTip:res.data.message
             })
          }
        }
      })
  },
  setResultData:function(resultData){
    var that = this;
    console.log("resultData="+resultData);
    var hphm = null;
    var score = 0;
    var listCount = resultData.length;
    var moneyCount = 0;
    hphm = app.globalData.hphm;
    var myDate = new Date();
    for(var i=0;i<resultData.length;i++){
      if(resultData[i].handled != "1"){//未处理
        score = score+parseInt(resultData[i].score);
        moneyCount = moneyCount+ parseInt(resultData[i].price);
      }
      if(resultData[i].handled == "0"){
        resultData[i].handled="未处理";
      }else if(resultData[i].handled == "1"){
        resultData[i].handled="已处理";
      }else{
        resultData[i].handled="未处理";
      }
    }
    that.setData({
      hphm:hphm,
      score:score,
      listCount:listCount,
      moneyCount:moneyCount,
      resultData:resultData
    });
    console.log("hphm="+that.data.hphm);
    console.log("score="+that.data.score);
    console.log("listCount="+that.data.listCount);
    console.log("moneyCount="+that.data.moneyCount);
  },
  call:function(){
     wx.makePhoneCall({
        phoneNumber: '13563955627' 
      })
  }
})
