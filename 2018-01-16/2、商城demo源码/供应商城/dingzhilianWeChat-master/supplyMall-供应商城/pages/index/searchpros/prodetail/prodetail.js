var util = require('../../../../utils/util.js')
var WxParse = require('../../../../wxParse/wxParse.js');
Page({
  onShareAppMessage: function () {//分享
    var that = this
    return {
      title:'找货就上定制链商城',
      path: '/pages/index/searchpros/prodetail/prodetail?styleId='+that.data.styleId
    }
  },
  data: {
    ctx: util.ctx,
    weixinCtx: util.weixinCtx,
    detailsClass: 'activedetail',
    detailsShow:true,
    attentionsClass:'',
    attentionsShow:false,
    imgUrls: [],
    indicatorDots: true,
    autoplay: false,
    interval: 3000,
    duration: 500
  },
  onLoad: function (option) {
    var that = this
    var styleId = option.styleId
    
    console.log(styleId)
    util.requestSupply("getStyleDetail", "?styleId=" + styleId,
      function (res) {
        var result = res.result;
        //转换html代码
        WxParse.wxParse('show_details', 'html', result.show_details, that,5)
        WxParse.wxParse('attentions', 'html', result.attentions, that,5)

        var seconds = result.seconds, secondNames = '';
        for (var s = 0; s < seconds.length; s++) {
          secondNames += seconds[s].name + '  '
        }
        console.log(result);
        that.setData({
          styleId:styleId,
          style: result.style,
          imgUrls: result.thumb.split(","),
          price: result.price_of_foreign,
          fabric: result.fabric,
          secondNames: secondNames,
          colors: result.seconds[0].colors,
          store: result.store,
          province: result.province,
          city: result.city,
          phone:result.contact,
          qq:result.qq,
          showDetails:result.show_details,
          attentions:result.attentions
        });
      }, function (res) {
        console.log(res);
      });
  },
  changeIndicatorDots: function (e) {
    this.setData({
      indicatorDots: !this.data.indicatorDots
    })
  },
  changeAutoplay: function (e) {
    this.setData({
      autoplay: !this.data.autoplay
    })
  },
  intervalChange: function (e) {
    this.setData({
      interval: e.detail.value
    })
  },
  durationChange: function (e) {
    this.setData({
      duration: e.detail.value
    })
  },
  showDetails:function(){
    var that = this
    that.setData({
      detailsClass: 'activedetail',
      attentionsClass:'',
      detailsShow:true,
      attentionsShow:false
    })
  },
  showAttentions:function(){
    var that = this
    that.setData({
      detailsClass:'',
      attentionsClass: 'activedetail',
      detailsShow:false,
      attentionsShow:true
    })
  }
})
