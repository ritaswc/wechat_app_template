Page({
    data: {
    imgMsg: [
  {imgUrl:'http://ojhq3mgil.bkt.clouddn.com/442/20160822/201608221341146251.jpg',
  picDisc:'创意卡扣式手机支架托架办公室桌面可爱老人苹果小米通用手机座'},
  {imgUrl:'http://ojhq3mgil.bkt.clouddn.com/447/20160908/201609081145371577.jpg',
  picDisc:'彩色杯子w'},
  {imgUrl:'http://ojhq3mgil.bkt.clouddn.com/453/20160930/201609301548075198.jpg',
  picDisc:'女款黑色前胸字母绣花连帽卫衣'},
  {imgUrl:'http://ojhq3mgil.bkt.clouddn.com/500/20161021/201610211626005082.jpg',
  picDisc:'七夕情人节金箔玫瑰礼品'}
    ],
    indicatorDots: true,
    autoplay: true,
    interval: 5000,
    duration: 1000,
    isShow:false,
    buynum:1
  },
  isShow:function  () {
    this.setData({
        isShow : true
      })
  },
  isClose:function  () {
    this.setData({
        isShow : false
      })
  },
  changeNum:function  (e) {
    var that = this;
    if (e.target.dataset.alphaBeta == 0) {
        if (this.data.buynum <= 1) {
            buynum:1
        }else{
            this.setData({
                buynum:this.data.buynum - 1
            })
        };
    }else{
        this.setData({
            buynum:this.data.buynum + 1
        })
    };
  }

})