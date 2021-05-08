let req = require( '../../requests/request.js' );
let util = require( '../../utils/util.js' )

Page({
    data:{
      radioValue: "",
      radioName: "",
      epxressLists: [],
      saveExpressLists: [],
      words: ["#","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z"],
      selectedWord: "top",
      wWidth: 0,
      wHeight: 0,
      isShowModal: false,
      currentTop: 0,
      obj: {},
      animationError: {},
      errorShow: false,
      scale: 1,
      expressObj: [],
      searchInput: ''
    },
    onLoad(obj){
      const wSize =  util.getWindowSize();
      this.setData({wWidth: wSize.wWidth, wHeight: wSize.wHeight, scale: wSize.scale, obj});
      this.getExpressLsit();
    },
    showModal(e) {
      this.setDefault(e)
      this.setData({isShowModal: true})
    },
    changeModalText(e) {
      this.setDefault(e)
    },
    hideModal() {
      this.setData({isShowModal: false})
    },
    setDefault(e){
      const wWidth =  parseFloat(this.data.wWidth)
      const currentTop = parseFloat(e.touches[0].clientY);
      const startTop = (wWidth / 750) * 200;
      const eachLen = (wWidth / 750) * 25;
      let len = currentTop - startTop;
      let selectedWord = [];
      len = len / eachLen
      len = parseInt(len)
      this.setData({selectedWord: this.data.words[len], currentTop})
    },
    radioChange(e) {
      const index = e.currentTarget.dataset.index.split("-")
      const lists = this.data.epxressLists[index[0]]
      const arr = lists.list[index[1]];
      const obj = this.data.obj
      this.setData({radioValue: arr.code, radioName: arr.name})
      obj.key = arr.code
      obj.name = arr.name
      this.setData({obj})
      this.goToDetails()
    },
    goToDetails(){
      const unixTime = new Date().getTime()
      const num =  this.data.obj.num
      const key = this.data.radioValue
      const name = this.data.radioName

      const params = {
        data: {tid: unixTime, companyCode: key, logisticCode: num},
        method: 'GET'
      }

      util.showLoading("加载中...","loading", 10000)

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

        if(status === 1 && !!data.data.success && !!traces && parseInt(state) > 0){
          wx.redirectTo({url: '../details/details?num=' + num + '&key=' + key + "&name=" + name})
        }
      }, data => {
      }, data => {
        util.hideToast()
      })
    },
    getExpressLsit() {
      const params = {
        data: {},
        method: 'GET'
      }

      req.getEpxressList(params, data => {
        const lists = data
        const arr = lists.data
        let epxressLists = []
        const expressObj = []

        for(let i = 0; i < arr.length; i ++) {
          for(let val in arr[i]) {
            for(let j = 0; j < arr[i][val].length; j ++) {
              expressObj.push(arr[i][val][j])
            }
          }
        }

        this.setData({expressObj})

        for(let i = 0; i < arr.length; i ++) {
          for(let val in arr[i]) {
            epxressLists[i] = {key: val, list:arr[i][val]}
          }
        }
        this.setData({epxressLists, saveExpressLists: epxressLists})
      })
    },
    inputChange(e) {
      let searchInput = e.detail.value;
      this.setData({searchInput})
    },
    clearNum() {
      this.setData({searchInput: ''})
    },
    inputConfirm(e) {
      let words = [];
      let firstWord = e.detail.value;
      let searchList = this.data.searchList;
      firstWord = firstWord.replace(/(^s*)|(s*$)/g, "");
      const expressObj = this.data.expressObj;

      if(firstWord.length >= 2) {
        firstWord = firstWord.substring(0,2);
      }

      for(let i = 0; i < expressObj.length; i ++) {
        if(expressObj[i].name.indexOf(firstWord) > -1) {
          this.setData({selectedWord: expressObj[i].code})
          break;
        }
      }
    },
    selectWord(e) {
      let firstWord = e.target.dataset.value
      this.getOneList(firstWord)
    },
    getOneList(firstWord) {
      if(!firstWord) return
      firstWord = firstWord[0]
      firstWord = firstWord.toUpperCase()
      this.setData({selectedWord: firstWord})
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
      let animation = wx.createAnimation({
          duration: 1000,
          timingFunction: 'ease'
      })
      animation.opacity(opacity).step()
      this.setData({
        animationError:animation.export()
      })

    }

})