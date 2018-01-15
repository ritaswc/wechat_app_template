var WxAutoImage = require('../../js/wxAutoImageCal.js');
var loading = require('../../template/common.js');
//index.js
//获取应用实例

var app = getApp()
Page({
  data: {
    hiddening:true,
    userInfo: {},
    imgUrl:["../../image/1.jpg",
    "../../image/2.jpg",
    "../../image/3.jpg"],
    indicatorDots:true,
    autoplay:true,
    interval:'2000',
    duration:'1000',
    circular:true,
    headImg:"../../image/headline.png",
    goodclassify:["生鲜果蔬","粮油干货","地方特产","名茶名酒","进口食品"],
    goodclassifyimg:["../../image/dining-table-header.jpg",
                      "../../image/grain-dry-cargo-header.jpg",
                      "../../image/local-specialty-header.jpg",
                      "../../image/tea—tobacco-header.jpg",
                      "../../image/imported-food-header.jpg"],
    friut:[
      {"name":"菜心 2kg","pic":"../../image/dining-1.jpg","price":"￥8.9"},
      {"name":"羊肉卷","pic":"../../image/dining-18.jpg","price":"￥21.98"},
      {"name":"安迪山苹果","pic":"../../image/dining-3.jpg","price":"￥8.9"}],
    grain:[
      {"name":"有机石板米","pic":"../../image/grain-1.jpg","price":"￥188.0"},
      {"name":"长寿花金胚玉米油","pic":"../../image/grain-16.jpg","price":"￥208.0"},
      {"name":"龙稻稻花香大米","pic":"../../image/grain-23.jpg","price":"￥96.0"}],
    local:[
      {"name":"振豫臻品腐竹","pic":"../../image/local-1.jpg","price":"￥82.0"},
      {"name":"原味丹堤腰果","pic":"../../image/local-2.jpg","price":"￥398.0"},
      {"name":"精选陕北红枣","pic":"../../image/local-3.jpg","price":"￥83.0"}],
    teawine:[
      {"name":"韩国清河清酒","pic":"../../image/tea-2.jpg","price":"￥82.0"},
      {"name":"特级明前茶","pic":"../../image/tea-3.jpg","price":"￥398.0"},
      {"name":"欢沁桃红葡萄酒","pic":"../../image/tea-4.jpg","price":"￥83.0"},
      {"name":"普洱迷你小沱茶","pic":"../../image/tea-5.jpg","price":"￥82.0"},
      {"name":"忆江南龙井","pic":"../../image/tea-6.jpg","price":"￥82.0"},
      {"name":"欢沁桃红葡萄酒","pic":"../../image/tea-7.jpg","price":"￥82.0"}],
    imported:[
      {"name":"泰国金枕头榴莲","pic":"../../image/imported-1.jpg","price":"￥82.0"},
      {"name":"爱伦蒂全脂纯牛奶","pic":"../../image/imported-2.jpg","price":"￥398.0"},
      {"name":"澳洲混合桉树蜂蜜","pic":"../../image/imported-3.jpg","price":"￥83.0"},
      {"name":"马来西亚白咖啡","pic":"../../image/imported-4.jpg","price":"￥82.0"},
      {"name":"越南白心火龙果 ","pic":"../../image/imported-6.jpg","price":"￥82.0"},
      {"name":"西班牙特级橄榄油","pic":"../../image/imported-39.jpg","price":"￥82.0"}]
  },
  onShareAppMessage:function(){
        return{
          title:"小程序商城",
          desc:"全球最火商城",
          path:"/page/user?id=123"
        }
  },
  cusImageLoad: function(e){
        var that = this;
        that.setData(WxAutoImage.wxAutoImageCal(e));
  },
  tapName: function(event) {
    console.log(event)
  },
  onLoad:function(){
       var that=this;
       this.setData({
           hiddening:false
       })
  },
  onReady:function(){
       var that=this;
       setTimeout(function(){
            that.setData({
              hiddening:true
            })
         console.log('close')                             
        },2000)
  }    
})
