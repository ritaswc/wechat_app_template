Page({
  data: {
    nationRstInfo: null,          //全国彩的开奖信息
    isHidden: false,              //加载条的显隐式
    openCodes: null,              //开奖结果的数组
    codeLen: null,                //长度  
    weeks: null,                  //时间的处理(星期)
    dates: null,                  //时间的处理(日期)
    hisId: null,
    tag: null,
},
  onLoad: function (options) {
    // 生命周期函数--监听页面加载
    var that = this;
    var hisID = options.id;
    // console.log('开奖信息'+options.tag);
    //指定的彩种
    wx.request({
      url: 'https://api.168cpt.com/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=' + options.id + '&count=50',                 //调用的地址
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        var hisDatas = res.data.data[options.id];      //获得数据
        var codesArr = new Array();               //开奖结果数组
        var weeksArr = new Array();               //星期的数组
        var dateArr = new Array();                //日期的数组
        //将开奖结果拼接成字符串
        for (var i = 0; i < hisDatas.length; i++) {
          //长度
          var codes = hisDatas[i].nums.split(",");
          codesArr[i] = codes;
          // 时间的处理(星期)
          var dateStr = hisDatas[i].expect_time.substr(0, 8);
          var pattern = /(\d{4})(\d{2})(\d{2})/;
          var formatDate = dateStr.replace(pattern, '$1/$2/$3');
          //var day = new Date(formatDate).getDay();
          var weekArrays = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
          var day = weekArrays[new Date(formatDate).getDay()]; 
          weeksArr[i] = day;
          //时间的处理(日期)
          var pattern2 = /(\d{4})(\d{2})(\d{2})/;
          var formatDate2 = dateStr.replace(pattern2, '$1-$2-$3');
          dateArr[i] = formatDate2;
        }
        // console.log(codesArr);
        wx.setNavigationBarTitle({
          title: options.name,
          success: function(res) {
            // success
          }
        })
        that.setData({
          nationRstInfo: res.data.data,
          openCodes: codesArr,
          codeLen: options.length,
          weeks: weeksArr,
          dates: dateArr,
          isHidden: true,
          hisId: hisID,
          tag: options.tag
        });
      }
    })
  },
})