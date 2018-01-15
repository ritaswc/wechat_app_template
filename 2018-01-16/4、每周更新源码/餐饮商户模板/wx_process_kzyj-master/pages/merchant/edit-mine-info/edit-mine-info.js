Page({
  data: {
    motto: 'Hello World',
    userInfo: {},
    positionInfo:[{name:'常用位置',content:'XXXXXXXXXXXXXXXXX'}],
    showTagMask:true,
    showAddressMask:false,
    locationAddress:'',
    addressInputValue:'请选择地址',
    tempFilePaths:''
  },
  
  onLoad: function () {
  },
  addressInput:function(e){
    console.log(11,e.detail.value)
    this.setData({
      addressInputValue:e.detail.value
    })
  },
  // 有地址
  choiceAddress:function(){
    // this.setData({
    //   showAddressMask:true
    // });

      var that = this
      wx.chooseLocation({
        success: function (res) {
          console.log(res)
          that.setData({
            // hasLocation: true,
            // location: formatLocation(res.longitude, res.latitude),
            showAddressMask:true,
            locationAddress: res.address,
            addressInputValue:res.address
          })

        }
      })
  },
  //选择照片
  choicePic:function(){
    const _this = this;
    wx.chooseImage({
      count: 1, // 默认9
      sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
      sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
      success: function (res) {
        // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
        var tempFilePaths = res.tempFilePaths
        console.log(11,tempFilePaths)
        _this.setData({
          tempFilePaths:tempFilePaths
        })
      }
    })
  },
  // 确定选择的地址
  confiromAddress:function(){
    const _this = this;
    this.setData({
      showAddressMask:false
    });
  },
  // 选择标签
  choiceTag:function(){
    this.setData({
      showTagMask:true
    });
  },
  // 确认选择的标签
  confirmTag:function(){
    this.setData({
      showTagMask:false
    });
  }
})
