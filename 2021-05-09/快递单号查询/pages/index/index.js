let req = require( '../../requests/request.js' );
let util = require( '../../utils/util.js' );

let app = getApp()
Page({
  data: {
    userInfo: {},
    expressNum: "",
    wWidth: 0,
    wHeight: 0,
    errorText: '出错',
    errorShow: false,
    animationError: {},
    startX: 0,
    showTop: [],
    showSend: [],
    showTransport: [],
    showSign: [],
    localLists: [],
    listTop: [],
    listSend: [],
    listTransport: [],
    listSign: [],
    moreSign: [],
    signLength: 0,
    amimateSlider: [],
    isNumNull: true,
    scale: 1,
    showType: "",
    showIndex: 0
  },
  onShareAppMessage() {
    return {
      title: '物流速查',
      desc: '快速查询您的快递信息',
      path: '/pages/index/index'
    }
  },
  onLoad() {
    const wSize =  util.getWindowSize();
    this.setData({wWidth: wSize.wWidth, wHeight: wSize.wHeight, scale: wSize.scale});

    app.getUserInfo(userInfo => {this.setData({userInfo})});
  },
  onShow() {
    util.showLoading("加载中","loading", 1000)

    this.getDataLocal()
  },
  clearNum() {
    this.setData({expressNum : ''})
  },
  inputing(e) {
     this.setData({expressNum : e.detail.value})
  },
  goToDetails(e) {
    let arr = e.currentTarget.dataset.numKey
    arr[0] = parseInt(arr[0])
    arr[1] = arr[1] || "AUTO"

    let url = '../details/details?num=' + arr[0]
    if(arr[2]) {
      url += '&key=' + arr[1] + '&name=' + arr[3]
    }
    
    wx.navigateTo({url})
  },
  getExressNum(e) {
    const value = e.detail.value
    const expressNum = value.replace(/\s+/g,"")
    this.setData({expressNum})
  },
  searchExpress() {
   const num = this.data.expressNum;
   const reg = /^[A-Za-z0-9]{6,30}$/; 
   this.setData({showHolder: true})
   if(!num) {
     return
   }else if(!reg.test(num)) {
     this.showError("订单号错误")
     return
   }
   this.goToDetailsAuto()
  },
  goToDetailsAuto() {
    const num = this.data.expressNum;
    const params ={
      data: {logisticsNo: num},
      method: 'GET'
    }
    util.showLoading('加载中','loading', 10000)
    req.getExpress100(params, data => {
      if(data.status !== 1) {
        const errorText = data.msg || '查询数据异常'
        this.showError(data.msg)
      }else if(!data.data){
        wx.navigateTo({url: '../lists/lists?num=' + num})
      }else if(!data.data.success || data.data.success !== 'ok'){
        wx.navigateTo({url: '../lists/lists?num=' + num})
      }else if(data.data.traces.length <= 0) {
        this.showError('未查到该快递信息，跳转到精确查找')
        wx.navigateTo({url: '../lists/lists?num=' + num})
      }else {
        wx.navigateTo({url: '../details/details?num=' + num})
      }
    }, data => {
      this.showError(data.msg)
    }, data => {
      util.hideToast()
    })
  },
  getDataLocal() {
    let objLists = wx.getStorageSync('objLists') || '[]'
    objLists = JSON.parse(objLists)
    let listTop = []
    let listSend = []
    let listTransport = []
    let listSign = []
    let signLength = []

    for(let val in objLists) {
      if(objLists[val].top) {
        listTop.push(objLists[val])
      }else {
        if(objLists[val].state === 1) {
          listTransport.push(objLists[val])
        }else if(objLists[val].state === 2) {
          listSend.push(objLists[val])
        }else {
          listSign.push(objLists[val])
          signLength ++
        }
      }
    }
    this.setData({localLists: objLists, listTop, listSend, listTransport, listSign, signLength})
    this.setData({moreSign: []})
    this.getMoreSign()
  },
  deleteDataLocal(e) {
    this.closeDelete()
    let objLists = wx.getStorageSync('objLists')
    objLists = JSON.parse(objLists)
    let value = e.currentTarget.dataset.value
    if(objLists.length === 1) {
      objLists = []
    }else {
      for(let i in objLists) {
        if(objLists[i].num == value){
          objLists.splice(i, 1)
          break
        }
      } 
    }
    objLists = JSON.stringify(objLists)
    wx.setStorageSync('objLists', objLists)
    this.getDataLocal()
  },
  getMoreSign(){
    let len = this.data.signLength
    let listSign = this.data.listSign
    let moreSign = this.data.moreSign
    if(!len) {
      moreSign = []
    }else {
      if(listSign.length === 0){
        return
      }else if(listSign.length < 5) {
        moreSign.push(...listSign)
        listSign = []
      }else {
        moreSign.push(...listSign.splice(0,5))
      }
    }

    this.setData({moreSign, listSign})
  },
  showError(text) {
    this.animateError(1)
    this.setData({errorShow: true,errorText: text})
    setTimeout(() => {
      this.animateError(0)
    }, 2000)
    setTimeout(() => {
      this.setData({errorShow: false})
    }, 3000)
  },
  animateError(opacity) {
    var animation = wx.createAnimation({
        duration: 1000,
        timingFunction: 'ease'
    })

    animation.opacity(opacity).step()

    this.setData({
      animationError:animation.export()
    })

  },
  touchStart(e) {
    this.closeDelete()
    let arr = e.currentTarget.dataset.listIndex
    const startX = e.touches[0].clientX
    this.setData({startX,showTop: [], showSend: [], showTransport: [], showSign: [], showType: arr[0], showIndex: arr[1]})
  },
  touchMove(e){
    const index = this.data.showIndex;
    const showType = this.data.showType;
    const scale = 750 / this.data.wWidth
    let x = e.touches[0].clientX
    let len = this.data.startX - x
    let showSome = this.data[showType]
    const obj = {}

    if(len > 0 && len <= 50) {
      showSome[index] = true
      this.sliderDelete(-len)
    }else if(len > 50){
      showSome[index] = true
      this.sliderDelete(-150 / scale)
    }else if(len <= 0) {
      showSome[index] = true
      this.sliderDelete(0)
    }

    obj[showType] = showSome
    this.setData(obj)
  },
  touchEnd(e) {
    const index = this.data.showIndex;
    const showType = this.data.showType;
    let showSome = this.data[showType]
    const scale = 750 / this.data.wWidth
    let x = e.changedTouches[0].clientX
    let len = (this.data.startX - x) * scale
    const obj = {}

    if(len < 50 ) {
      this.sliderDelete(0)
    }else if(len >= 50){
      this.sliderDelete(-150 / scale)
    }
    obj[showType] = showSome
    obj.startX = 0;
    obj.showType = '';
    obj.showIndex = 0;
    this.setData(obj)
  },
  sliderDelete(x) {
    let animation = wx.createAnimation({
      duration: 100,
      timingFunction: 'ease',
    })
    this.animation = animation
    animation.translateX(x).step()
    this.setData({
      amimateSlider: animation.export()
    })
  },
  closeDelete() {
    this.sliderDelete(0)
  },
  scanCode() {
    wx.scanCode({
      success: (res) => {
       this.setData({expressNum: res.result})
       this.searchExpress()
       console.log(res)
      }
    })
  }
})
