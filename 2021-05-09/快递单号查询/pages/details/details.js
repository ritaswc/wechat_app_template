let req = require( '../../requests/request.js' );
let util = require( '../../utils/util.js' );

let app = getApp()

Page({
  data: {
    expressList: [],
    expressNumber: 0,
    expressType: "",
    message: "",
    detail: null,
    state: 0,
    code: "AUTO",
    userInfo: {},
    getDataSuccess: true,
    animationError: {},
    wWidth: 0,
    wHeight: 0,
    obj: {},
    expressStatusText: ["待揽件","待发送","发送中...","已接收"],
    expressChangeList: [],
    imageUrl: "../../images/holder.png",
    title: "",
    amount: 1,
    isTop: false,
    isNeedKey: false    
  },
  onLoad(obj) {
    const wSize =  util.getWindowSize();
    this.setData({wWidth: wSize.wWidth, wHeight: wSize.wHeight, scale: wSize.scale, obj});

    this.getUserInfo();
  },
  onShareAppMessage() {
    const obj = this.data.obj;
    let url = '/pages/details/details?num=' + obj.num;
    if(obj.key) {
       url += '&key=' + obj.key + '&name=' + obj.name;
    }

    return {
      title: '物流速查',
      desc: '快速查询您的快递信息',
      path: url
    }
  },
  onShow() {
     let obj = this.data.obj
     let objLists = wx.getStorageSync("objLists") || "[]";
     objLists = JSON.parse(objLists);
     for(let i in objLists) {
       if(objLists[i].num == obj.num) {
         this.setData({isTop: objLists[i].top})
       }
     }
     this.setData({expressNumber: obj.num})
     if(obj.key) {
       this.setData({expressType: obj.name})
       this.getExpressNeedKey(obj.num, obj.key)
     }else{
       this.getExpressNoKey(obj.num)
     }
  },
  getUserInfo(){
    app.getUserInfo((userInfo) => {
      this.setData({
        userInfo:userInfo
      })
    })
  },
  getExpressNeedKey(num, key) {
    const unixTime = new Date().getTime();
    util.showLoading('加载中', "loading", 10000);

    const params = {
      data: {tid: unixTime, companyCode: key, logisticCode: num},
      method: "GET"
    }
    
    req.getEpxressData(params, data => {
      let expressList = "";
      if(data.data && data.data.traces && !!data.data.traces && data.data.traces.length > 1) {
        expressList = data.data.traces;
      }
      const state = parseInt(data.data.state);
      this.setData({expressList, 
                    state, 
                    message: data.msg, 
                    detail: data.detail,
                    isNeedKey: true})
    }, data => {
      console.log(data)
    }, data => {
      util.hideToast()
      this.changeData()
      this.getMessageMaiDao()
    })
  },
  getExpressNoKey(num) {
    util.showLoading('加载中', "loading", 10000);

    const params = {
      data: {logisticsNo: num},
      method: "GET"
    }

    req.getExpress100(params, data => {
      let expressList = "";
      if(data.data && data.data.traces && !!data.data.traces && data.data.traces.length > 1) {
        expressList = data.data.traces;
      }

      const state = parseInt(data.data.state);
      this.setData({expressList, 
                    state, 
                    message: data.msg, 
                    detail: data.detail,
                    expressType: data.data.logisticCode,
                    isNeedKey: false})

    },data => {
    }, data => {
      util.hideToast()
      this.changeData()
      this.getMessageMaiDao()
    })
  },
  getMessageMaiDao() {
    const params = {
      data: {logisticsNo: this.data.expressNumber},
      method: 'GET'
    }
    req.getMDMessage(params, data => {
      if(data.status != 1 || data.data === null || data.data.length || data.data.logisticsNo === null) {
        this.setData({imageUrl: '../../images/holder.png',title:'',amount: 1})
      }else {
        const imageUrl = data.data.imageUrl || '../../images/holder.png';
        this.setData({imageUrl, title:data.data.productTitle,amount:data.data.productNumber})
      }
    }, data => {
    }, data => {
      util.hideToast()
      this.saveDataLocal()
    })
  },
  changeData() {
    let data = this.data.expressList
    let expressChangeList = []
    let dateTime = ''
    let time = []
    let date = []
    const weekArray = ["周日", "周一", "周二", "周三", "周四", "周五", "周六"];

    for(let i = 0; i < data.length; i ++) {
      let length = expressChangeList.length
      let week = new Date(data[i].acceptTime).getDay()
      week = weekArray[week]
      dateTime = data[i].acceptTime
      dateTime = dateTime.split(" ")
      date = dateTime[0].split("-")[2]
      date = date + "日"
      time = dateTime[1].split(":")
      time = time[0] + ":" + time[1]

      if(expressChangeList.length === 0){
        expressChangeList[0] = {"date": date,"week": week, list:[{"time": time, "acceptTime": data[i].acceptTime, "acceptStation": data[i].acceptStation}]}
      }else if(expressChangeList.length > 0){
        if(expressChangeList[length-1].date === date){
          expressChangeList[length-1].list.push({"time": time, "acceptTime": data[i].acceptTime, "acceptStation": data[i].acceptStation})
        }else {
          expressChangeList[length] = {"date": date,"week": week, list:[{"time": time,"week": week, "acceptTime": data[i].acceptTime, "acceptStation": data[i].acceptStation}]}
        }
      }
    }

    this.setData({expressChangeList})
  },
  saveDataLocal(num, code) {
    const list = this.data.expressChangeList[0].list
    let objLists = wx.getStorageSync("objLists") || "[]"
    objLists = JSON.parse(objLists)

    let objList = {
      num: this.data.expressNumber,
      key: this.data.code,
      name: this.data.expressType,
      imageUrl: this.data.imageUrl,
      title: this.data.title,
      amount: this.data.amount,
      statusText: this.data.expressStatusText[this.data.state],
      data: list[0],
      top: this.data.isTop,
      isNeedKey: this.data.isNeedKey
    }

    for(let i in objLists) {
      if(objLists[i].num == objList.num) {
        objLists.splice(i,1)
      }
    }

    objLists.unshift(objList)
    objLists = JSON.stringify(objLists)

    wx.setStorageSync('objLists', objLists)
  },
  markTop() {
    this.setData({isTop: !this.data.isTop})
    let objLists = wx.getStorageSync('objLists')
    objLists = JSON.parse(objLists)
    let objList = objLists.shift()
    objList.top = !objList.top
    objLists.unshift(objList)
    objLists = JSON.stringify(objLists)
    wx.setStorageSync('objLists', objLists)
  }
})
