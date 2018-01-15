let req = require( '../../requests/request.js' );
let util = require( '../../utils/util.js' );

let app = getApp()

Page({
  data: {
    obj: {num: '',key: 'AUTO', name: ''},
    wWidth: 0,
    wHeight: 0,
    showLoadingImg: false,
    showRightIcon: false,
    radioValue: "AUTO",
    checkedRadioName: "自动匹配快递公司",
    radioArr: [],
    epxressType: '',
    animationError: {},
    errorShow: false,
    isGetMsg: false,
    isFind: false,
    shortList: [],
    isNoFind: true,
    expressNum: '',
    scale: 1
  },
  onLoad(obj) {
    const wSize =  util.getWindowSize();
    this.setData({wWidth: wSize.wWidth, wHeight: wSize.wHeight, scale: wSize.scale, obj});
  },
  onShow() {
     const obj = this.data.obj
     if(obj.key && obj.name) {
       this.setData({radioValue: obj.key, checkedRadioName: obj.name})
     }
     this.hotMatchEpxpress()
  },
  clearNum() {
    let obj = this.data.obj
    obj.num = ''
    this.setData({obj})
  },
  changeExpressNum(e){
    let obj = this.data.obj
    obj.num = e.detail.value
    this.setData({expressNum: e.detail.value, obj})
    this.hotMatchEpxpress()
  },
  goToCompany() {
    const num = this.data.expressNum || this.data.obj.num
    wx.redirectTo({url: '../company/company?num=' + num})
  },
  goToDetails(){
    const unixTime = new Date().getTime()
    const num = this.data.expressNum || this.data.obj.num
    const key = this.data.radioValue ? this.data.radioValue : 'AUTO'
    const name = this.data.checkedRadioName

    const params = {
      data: {tid: unixTime,companyCode: key, logisticCode: num},
      method: 'GET'
    }

    util.showLoading("加载中","loading", 10000)

    req.getEpxressData(params, data => {
      
      const status = parseInt(data.status)
      const msg = data.msg || '数据查询异常，请稍后再试'
      if(data.data === null) { 
         this.showError(msg)
        return
      }
      const state = (data.data.state === null) ? data.data.state : parseInt(data.data.state)
      const traces = data.data.traces

      if(!data.data.success) {
        this.showError(msg)
        return
      }
      if(traces === null) {
        this.showError('无该单号信息, 请确认单号')
        return
      }
      if(state === null || state < 0) {
        this.showError('无该单号信息, 请确认单号')
        return
      }
      if(status !== 1){
        this.showError(data.details)
        return
      }

      if(status === 1 && !!data.data.success && !!traces && parseInt(state) >= 0){
        wx.redirectTo({url: '../details/details?num=' + num + '&key=' + key + "&name=" + name})
      }
    }, data => {
    }, data => {
      util.hideToast()
    })
  },
  hotMatchEpxpress() {
    const num = this.data.expressNum || this.data.obj.num
    const params = {
      data: {logisticsNo: num},
      method: 'GET'
    }
    util.showLoading("加载中...","loading", 60000)
    req.getCompany100(params, data => {
      let radioArr = data.data
      this.setData({radioArr})
    },data => {

    },data => {
      util.hideToast()
      this.getExpressImg()
    })
  },
  getHotListLen() {
    const width = this.data.wWidth
    const height = this.data.wHeight
    let shortList = this.data.radioArr
    const len = height - ((width / 750) * 532)
    let hotListLen = len / ((width / 750) * 87)
    hotListLen = parseInt(hotListLen)
    shortList = shortList.splice(0, hotListLen)
    this.setData({shortList})
  },
  radioChange(e) {
    const index = e.currentTarget.dataset.index
    const arr = this.data.shortList[index]
    this.setData({radioValue: arr.code, checkedRadioName: arr.name})
    this.goToDetails()
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
  scanCode() {
    wx.scanCode({
      success: (res) => {
       this.setData({expressNum: res.result})
       this.hotMatchEpxpress()
      }
    })
  },
  getExpressImg() {
      let radioArr = this.data.radioArr
      const params = {
        data: {},
        method: 'GET'
      }

      req.getEpxressList(params, data => {
        const arr = data.data
        const expressObj = []

        for(let i = 0; i < arr.length; i ++) {
          for(let val in arr[i]) {
            for(let j = 0; j < arr[i][val].length; j ++) {
              expressObj.push(arr[i][val][j])
            }
          }
        }

        for(let i = 0; i < radioArr.length; i ++) {
          radioArr[i].image = '../../images/gray-block.png';
          for(let j = 0; j < expressObj.length; j ++) {
            if(radioArr[i].code === expressObj[j].code) {
              radioArr[i].image = expressObj[j].image
            }
          }
        }

        this.setData({radioArr})
      }, data => {
      }, data => {
        this.getHotListLen()
      })
    }

})
