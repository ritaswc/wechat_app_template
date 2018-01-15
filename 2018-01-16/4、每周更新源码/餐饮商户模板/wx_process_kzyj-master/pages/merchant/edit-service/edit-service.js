//获取应用实例
var app = getApp()

const tagArr = [{
  text:'满立减',
  selected:false
},{
  text:'哈哈',
  selected:true
},{
  text:'呵呵好',
  selected:false
},{
  text:'hello',
  selected:true
},{
  text:'word',
  selected:false
},{
  text:'好人',
  selected:true
},{
  text:'换人',
  selected:false
},{
  text:'不卖',
  selected:true
},{
  text:'奢侈啊',
  selected:true
}];

Page({
  data: {
      tagArr:tagArr,
      showTags:false,
      tempFilePaths:''
  },
  
  onLoad: function () {
   
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
  chooseTag:function(){
      this.setData({
        showTags:true
      })
  },
  selectTag:function(e){
      const index = e.currentTarget.id;
      let tagArr =  this.data.tagArr;
      console.log(0,this.data.tagArr[index])
      tagArr[index].selected = !this.data.tagArr[index].selected;
      console.log(1,tagArr[index])
      this.setData({
        tagArr:tagArr
      })

      console.log(2,this.data.tagArr[index])
  },
  confirmTag:function(){
      this.setData({
        showTags:false
      })
  },
  

})
