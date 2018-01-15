//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    motto: 'Hello World',
    cjhDisplay:"none",
    fdjDisplay:"none",
    cjhInputTip:"请输入车架号",
    fdjInputTip:"请输入发动机号",
    hphm:"",//号牌号码
    cjh:"",//车架号
    cdjh:"",//发动机号
    lstype:"02",//车辆类型
    lstypeName:"小车",//车辆类型名
    errorTip:"",
    typeArray:["",],
    windowHeigh:0,
    queryCSUrl:"https://api.chinadatapay.com/government/traffic/167/1",//获取支持的城市的信息
    queryWZUrl:"https://api.chinadatapay.com/government/traffic/167/3",//获取违章记录url
  queryCPUrl:"https://api.chinadatapay.com/government/traffic/167/2",//获取车号牌信息
    myProvince:"",//ZH SC这种
    myProvinceZWJX:"",//省的中文简写
    myProvinceALL:"",//省的全称
    myCityPYJX:"",// 川A  的A
    myCityPY:"",//chengdu这种
    engine:"",//是否需要发动机号0:不需要 1:需要
    engineno:"",//需要几位发动机号0:全部 1-9 :需要发动机号后N位
    classa:"",//是否需要车架号0,不需要 1,需要
    classno:"",//需要几位车架号0:全部 1-9: 需要车架号后N位
    provinceAbbr:"",//省的缩写 比如京、川等
    cityAbbr:"",//市的缩写，比如A、B、C, 
    supportCodes:[//支持的车号牌信息
    {"code": "02", "name": "小车"},
    {"code": "01","name": "大车"},
    {"name":"挂车","code":"15"},
    {"name":"教练车","code":"16"},
    {"name":"警车","code":"23"},
    {"name":"小型新能源车","code":"52"},
    {"name":"大型新能源车","code":"51"}
    ]
  },
  onLoad: function () {
    var that = this
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
      that.getIsSupportCity();
     // that.getChePai();//获取支持的车牌信息
    })
    wx.getSystemInfo({
      success: function(res) {
        that.setData({
          windowHeigh:res.windowHeight
        })
      }
    })
    //从缓存里面获取数据
    wx.getStorage({
      key: 'cphm',
      success: function(res) {
          that.setData({
            cphm:res.data
          })
      } 
    })
    wx.getStorage({
      key: 'cjh',
      success: function(res) {
          that.setData({
            cjh:res.data
          })
      } 
    })
    wx.getStorage({
      key: 'fdjh',
      success: function(res) {
          that.setData({
            fdjh:res.data
          })
      } 
    })
  },
  getChePai:function(){
    var that = this;
    wx.request({
      url: this.data.queryCPUrl, 
      data: {
        key: app.globalData.appKey
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {console.log(res.data);
         if(res.data.code == "10000"){
           that.setData({
             supportCodes:res.data.data
           })
         }
      }
    })
  },
  getIsSupportCity:function(){
    var that = this;
    wx.request({
      url: this.data.queryCSUrl, 
      data: {
        onlysupport: 1,
        key: app.globalData.appKey
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {console.log(res.data);
        var myCityZhongWen = null;
        var myCityPYJX = null;
        var myCityPY = null;
        var myProvinceZWJX = null;
        var engineno = null;
        var classno = null;
        if(res.data.code == "10000"){
        for(var i=0;i<res.data.data.data.length;i++){
              var province = res.data.data.data[i];
              if(province.carorg ==    that.data.userInfo.province.toLowerCase()){
                //获取到当前的省的信息
                myProvinceZWJX = province.lsprefix;
                for(var j=0;j<province.list.length;j++){
                    var city = province.list[j];
                    //获取到当前的市的信息
                    if(city.carorg == that.data.userInfo.city.toLowerCase()){
                       myCityZhongWen = city.city;
                       myCityPYJX = city.lsnum;
                       myCityPY
                       engineno = city.engineno;
                       classno = city.frameno;
                       myCityPY = that.data.userInfo.city;
                    }
                }
              }
          }
          that.setData({
            myCityZhongWen:myCityZhongWen,
            myCityPYJX:myCityPYJX,
            myProvinceZWJX:myProvinceZWJX,
            engineno:engineno,
            classno:classno,
            myCityPY:myCityPY
          })
          console.log("myCityPYJX="+that.data.myCityPYJX);
          console.log("myProvinceZWJX="+that.data.myProvinceZWJX);
          console.log("engineno="+that.data.engineno);
          console.log("classno="+that.data.classno);
          console.log("myCityPY="+that.data.myCityPY);
        //设置车架号是否显示和输入提示
      /*  if(that.data.classno != "0"){
            that.setData({
                cjhDisplay:"block"
            })
            var classnoC = "";
            switch(that.data.classno){
                case "1":classnoC="一"; break;
                case "2":classnoC="二"; break;
                case "3":classnoC="三"; break;
                case "4":classnoC="四"; break;
                case "5":classnoC="五"; break;
                case "6":classnoC="六"; break;
                case "7":classnoC="七"; break;
                case "8":classnoC="八"; break;
                case "9":classnoC="九"; break;
            }
            that.setData({
              cjhInputTip:"请输入"+classnoC+"位车架号"
            })
            if(that.data.classno == "100"){
              that.setData({
              cjhInputTip:"请输入完整的车架号车架号"
            })
            }
        }*/
        //设置发动机号是否显示和输入提示
     /*   if(that.data.engineno != "0"){
            that.setData({
                fdjDisplay:"block"
            })
            var enginenoNoC = "";
            switch(that.data.engineno){
                case "1":enginenoNoC="一"; break;
                case "2":enginenoNoC="二"; break;
                case "3":enginenoNoC="三"; break;
                case "4":enginenoNoC="四"; break;
                case "5":enginenoNoC="五"; break;
                case "6":enginenoNoC="六"; break;
                case "7":enginenoNoC="七"; break;
                case "8":enginenoNoC="八"; break;
                case "9":enginenoNoC="九"; break;
            }
            that.setData({
              fdjInputTip:"请输入发动机后"+enginenoNoC+"位"
            })
        }*/
        }
      }
    })
  },
  queryData:function(){
      var that = this
      that.setData({
        errorTip:""
      })
      var lsprefix = this.data.myProvinceZWJX;
      var cphm = this.data.cphm;
      var cjh = this.data.cjh;
      var fdjh = this.data.fdjh;
      var lstype = this.data.lstype;
      var myCityPY = this.data.myCityPY;
      //检查输入的数据
      if(cphm == "" || cphm == null || cphm.length != 5){
         this.setData({
            errorTip:"请输入五位车牌号码"
         })
         return;
      }/*
      if(this.data.classno!="0" && (cjh==null || cjh.length != parseInt(this.data.classno))){
        var classNoC = "";
        switch(that.data.classno){
            case "1":classNoC="一"; break;
            case "2":classNoC="二"; break;
            case "3":classNoC="三"; break;
            case "4":classNoC="四"; break;
            case "5":classNoC="五"; break;
            case "6":classNoC="六"; break;
            case "7":classNoC="七"; break;
            case "8":classNoC="八"; break;
            case "9":classNoC="九"; break;
        }
        this.setData({
            errorTip:"请输入车架号后"+classNoC+"位"
         })
         return;
      }*//*
      if(this.data.engineno!="0" && (fdjh==null || fdjh.length != parseInt(this.data.engineno))){
        var enginenoNoC = "";
        switch(that.data.engineno){
            case "1":enginenoNoC="一"; break;
            case "2":enginenoNoC="二"; break;
            case "3":enginenoNoC="三"; break;
            case "4":enginenoNoC="四"; break;
            case "5":enginenoNoC="五"; break;
            case "6":enginenoNoC="六"; break;
            case "7":enginenoNoC="七"; break;
            case "8":enginenoNoC="八"; break;
            case "9":enginenoNoC="九"; break;
        }
        this.setData({
            errorTip:"请输入发动机号后"+enginenoNoC+"位"
         })
         return;
      }*/
      console.log("cphm="+cphm);
      console.log("cjh="+cjh);
      console.log("fdjh="+fdjh);
      console.log("lstype="+lstype);
      console.log("lsprefix="+lsprefix);
      console.log("myCityPY="+myCityPY);
      //缓存到本机，供查询页面调用
      wx.setStorage({
        key:"cphm",
        data:cphm
      })
      wx.setStorage({
        key:"cjh",
        data:cjh
      })
      wx.setStorage({
        key:"fdjh",
        data:fdjh
      })
      wx.setStorage({
        key:"lstype",
        data:lstype
      })
      wx.setStorage({
        key:"lsprefix",
        data:lsprefix
      })
      wx.setStorage({
        key:"myCityPY",
        data:myCityPY
      })
      //放到globaldata里面取
      app.globalData.cphm=cphm;
      app.globalData.cjh=cjh;
      app.globalData.fdjh=fdjh;
      app.globalData.lstype=lstype;
      app.globalData.lsprefix=lsprefix+this.data.myCityPYJX;
      app.globalData.carorg=myCityPY;
      //在这里先查询一次，看看如果查询结果错误，提示错误信息
      wx.request({
      url: this.data.queryWZUrl, 
      data: {
        key: app.globalData.appKey,
        lsprefix:lsprefix+this.data.myCityPYJX,
        lsnum:cphm,
        lstype:lstype,
        frameno:cjh,
        carorg:myCityPY,
        engineno:fdjh
      },
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {console.log(res.data);
        if(res.data.error_code==10000 || res.data.code=="10000"){
          that.setData({
              errorTip:""
            })
            app.globalData.hphm = res.data.data.lsprefix+res.data.data.lsnum;
            if(res.data.data.list.length != 0){ 
              app.globalData.resultData=res.data.data.list;
              wx.navigateTo({
                url: '../queryResult/queryResult'
              }) 
            }else{
              that.setData({
              errorTip:"没有查询到违章记录"
            })
            }
        }else{
           app.globalData.resultData=null;
        }
        switch(parseInt(res.data.code)){
          case 10001:
            that.setData({
              errorTip:"交管局为空"
            })
            break;
          case 10002:
            that.setData({
              errorTip:"交管局不存在"
            })
            break;
          case 10003:
            that.setData({
              errorTip:"车牌前缀为空"
            })
            break;
          case 10004:
            that.setData({
              errorTip:"车牌前缀有误"
            })
            break;
          case 10005:
            that.setData({
              errorTip:"车牌号为空"
            })
            break;
          case 10006:
            that.setData({
              errorTip:"车牌号有误"
            })
            break;
          case 10007:
            that.setData({
              errorTip:"发动机号为空"
            })
            break;
          case 10008:
            that.setData({
              errorTip:"发动机号有误"
            })
            break;
          case 10009:
            that.setData({
              errorTip:"车架号为空"
            })
            break;
          case 10010:
            that.setData({
              errorTip:"车架号有误"
            })
            break;
          case 10011:
            that.setData({
              errorTip:"登记证书号为空"
            })
            break;
          case 10012:
            that.setData({
              errorTip:"登记证书号有误"
            })
            break;
          case 10013:
            that.setData({
              errorTip:"其他为空"
            })
            break;
          case 10014:
            that.setData({
              errorTip:"交管局服务器错误"
            })
            break;
          case 10015:
            that.setData({
              errorTip:"APPKEY为空或不存在"
            })
            break;
          case 10016:
            that.setData({
              errorTip:"APPKEY已过期"
            })
            break;
          case 10017:
            that.setData({
              errorTip:"APPKEY无请求此数据权限"
            })
            break;
          case 10018:
            that.setData({
              errorTip:"请求超过次数限制"
            })
            break;
          case 10019:
            that.setData({
              errorTip:"IP被禁止"
            })
            break;
          case 10020:
            that.setData({
              errorTip:"IP请求超过限制"
            })
            break;
          case 10021:
            that.setData({
              errorTip:"接口维护中"
            })
            break;
            case 10022:
            that.setData({
              errorTip:"接口已停用"
            })
            break;
          case 10230:
            that.setData({
              errorTip:"车辆信息有误"
            })
            break;
        }
      }
      })
  },
  bindPickerChange:function(e){
    var that = this;
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      lstypeName: that.data.supportCodes[e.detail.value].name,
      lstype:that.data.supportCodes[e.detail.value].code
    })
    console.log("lstype="+that.data.lstype);
  },
  setCPHM:function(e){
    this.setData({
       cphm:e.detail.value.toUpperCase()
    })
  },
  setCJH:function(e){
    this.setData({
       cjh:e.detail.value
    })
  },
  setFDJH:function(e){
    this.setData({
       fdjh:e.detail.value
    })
  }
})
