var app = getApp();

//index.js
Page({
  data: {
    productData: [],
    page: 1,
    // 滑动
    imgUrl: [
       '../../images/im.jpg',
        '../../images/im.jpg',
         '../../images/im.jpg',
           '../../images/im.jpg',
             '../../images/im.jpg',
               '../../images/im.jpg',
    ],
        img: [
      {
        'pg':'../../images/ii.jpg',
        'ming':'KEISF雪忆式',

        'zhuan':'美容院'
      }  ,
            {
        'pg':'../../images/im.jpg',
        'ming':'mrs.wens',

        'zhuan':'美容院'
      }  ,
            {
        'pg':'../../images/io.jpg',
        'ming':'mrs.wens',

        'zhuan':'美容院'
      }  ,
            {
        'pg':'../../images/iu.jpg',
        'ming':'mrs.wens',
        'zhuan':'美容院'
      }  ,

    ],
  'kbs':[
  {
 'imgs' : '../../images/shop.png',
'text':'挂件',
   } ,
    {
      'imgs' : '../../images/xs.png',
     'text':'手腕'
   } ,
       {
      'imgs' : '../../images/xs.png',
     'text':'饰品'
   }  ,   {
      'imgs' : '../../images/xs.png',
     'text':'蛋面裸石'
   } , {
      'imgs' : '../../images/xs.png',
     'text':'杂项'
   },
   ],
   "shopList": [
            {
                "shopAddr":"飞马牌服饰",
                "shopName":"PUMA Kids银泰西湖店",
                "shopLogo":"../../images/sp_slt01.png",
                "type":1, //
               "yuan":"￥100",
               "lun":"99",
                //标识该门店类型 1-热门店 2-购买过 3-关注店 4-附近店
            },   
          {
                "shopAddr":"飞马牌服饰",
                "shopName":"PUMA Kids银泰西湖店",
                "shopLogo":"../../images/sp_slt01.png",
                "type":1, //
               "yuan":"￥100",
               "lun":"99",
                //标识该门店类型 1-热门店 2-购买过 3-关注店 4-附近店
            }, 
                      {
                "shopAddr":"飞马牌服饰",
                "shopName":"PUMA Kids银泰西湖店",
                "shopLogo":"../../images/sp_slt01.png",
                "type":1, //
               "yuan":"￥100",
               "lun":"99",
                //标识该门店类型 1-热门店 2-购买过 3-关注店 4-附近店
            },  
        ],
  },
  dele: function (e) {
  console.log(e.currentTarget.dataset.text)
  var title =e.currentTarget.dataset.text
  console.log("index" + title);
   wx.navigateTo({
    url: '../list/list?title='+title
  });
}, 
qiang:function(e){
    console.log(e.currentTarget.dataset.title)
    wx.navigateTo({
      url: '../listdetail/listdetail?title='+e.currentTarget.dataset.title,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
 dangtian:function(e){
    console.log(e.currentTarget.dataset.title)
    wx.navigateTo({
      url: '../ritual/ritual?title='+e.currentTarget.dataset.title,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
 inpu: function (e) {
   console.log(e.currentTarget.dataset.title)
   wx.navigateTo({
     url: '../search/search?title=' + e.currentTarget.dataset.title,
     success: function (res) {
       // success
     },
     fail: function () {
       // fail
     },
     complete: function () {
       // complete
     }
   })
 },
   jj:function(e){
    console.log(e.currentTarget.dataset.ming)
    wx.navigateTo({
      url: '../meirongyuan/mei?ming='+e.currentTarget.dataset.ming,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
   listdetail:function(e){
    console.log(e.currentTarget.dataset.title)
    wx.navigateTo({
      url: '../listdetail/listdetail?title='+e.currentTarget.dataset.title,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },
  category:function(e){
    console.log(e.currentTarget.dataset.title)
    wx.navigateTo({
      url: '../panic/panic?title='+e.currentTarget.dataset.title,
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
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
//  商品连接数据 
  initProductData: function (data){
    for(var i=0; i<data.length; i++){
      console.log(data[i]);
      var item = data[i];
      item.Price = item.Price/100;
      // item.Price = 100;
      item.ImgUrl = app.d.hostImg + item.ImgUrl;
      
    }
  },
  onLoad: function (options) {
    var that = this;
    wx.request({
      url: app.d.hostUrl + '/ztb/productZBT/GetTJProductList',
      method:'post',
      data: {
        pageindex: that.data.page,
        pagesize:10,
      },
      header: {
        'Content-Type':  'application/x-www-form-urlencoded'
      },
      success: function (res) {
        console.log(res)
        //--init data        
        var data = res.data.data;
        console.log(data);
        that.initProductData(data);
        that.setData({
          productData:data,
        });
        //endInitData
      },
    })
    // 定位
      // var that = this;  
    /* 获取定位地理位置 */  
    // 新建bmap对象   
    var BMap = new bmap.BMapWX({   
        ak: that.data.ak,
    });   
        console.log(BMap)    
    var fail = function(data) {   
        console.log(data);  
    };   
    var success = function(data) {   
        //返回数据内，已经包含经纬度  
        console.log(data);  
        //使用wxMarkerData获取数据  
        //  = data.wxMarkerData;  
wxMarkerData=data.originalData.result.addressComponent.city
        //把所有数据放在初始化data内  
        console.log(wxMarkerData)
        that.setData({   
            // markers: wxMarkerData,
            // latitude: wxMarkerData[0].latitude,  
            // longitude: wxMarkerData[0].longitude,  
            address: wxMarkerData 
        });  
    }   
    // 发起regeocoding检索请求   
    BMap.regeocoding({   
        fail: fail,   
        success: success  
    });      

  },
  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {
    return {
      title: '微信小程序联盟',
      desc: '最具人气的小程序开发联盟!',
      path: '/page/user?id=123'
    }
  },


});