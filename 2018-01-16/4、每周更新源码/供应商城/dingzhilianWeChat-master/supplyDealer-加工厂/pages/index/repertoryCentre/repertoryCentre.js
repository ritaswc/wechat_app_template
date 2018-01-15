Page({
  data: {
    array2: ['莱卡', '纯棉'],
    array3: ['男款', '女款','男女同款'],
    array4: ['白色', '黑色'],
    all:true,
    warn:false,
    stockout:false,
    objectArray2: [
      {
        id: 0,
        name: '莱卡'
      },
      {
        id: 1,
        name: '纯棉'
      }
    ],
    objectArray3: [
      {
        id: 0,
        name: '男款'
      },
      {
        id: 1,
        name: '女款'
      },
      {
        id: 2,
        name: '男女同款'
      }
    ],
    objectArray4: [
      {
        id: 0,
        name: '白色'
      },
      {
        id: 1,
        name: '黑色'
      }
    ],
    index2: 0,
    index3: 0,
    index4: 0,
  },
  bindPickerChange2: function(e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      index2: e.detail.value
    })
  },
  bindPickerChange3: function(e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      index3: e.detail.value
    })
  },
  bindPickerChange4: function(e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      index4: e.detail.value
    })
  },
  /*顶部选项卡*/
  clickAll:function(){
    this.setData({
      all:true,
      warn:false,
      stockout:false
    })
  },
  clickWarn:function(){
    this.setData({
      all:false,
      warn:true,
      stockout:false
    })
  },
  clickStockout:function(){
    this.setData({
      all:false,
      warn:false,
      stockout:true
    })
  }
})