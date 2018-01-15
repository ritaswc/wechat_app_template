//index.js
//获取应用实例
var app = getApp()
var wxSortPickerView = require('../../wxSortPickerView/wxSortPickerView.js');
Page({
  data: {
    //页面设置
    winWidth: 0,
    winHeight: 0,
    //tab切换  
    currentTab: 0,
    scrollTop: 0,
    scrollHeight: 0,

    nationInfo: null,                       //全国彩的开奖信息 
    highInfo: null,                         //高频彩的开奖信息
    areaInfo: null,                         //地方彩的开奖信息 
    isHidden: false,                        //加载
    nationIds: ['4', '5', '17'],            //全国彩的彩种信息
    nationNames: ['福彩3D', '排列三', '排列五'],
    nationCode: null,                       //全国彩的开奖结果
    nationCodeLen: null,                    //全国彩结果的长度
    nationNamesArr: null,                   //全国彩采种名
    nationDay: null,                        //全国彩星期
    nationDate: null,                       //全国彩日期
    nationTag: null,


    highIds: ['1', '3', '6', '7', '9', '10', '11', '13', '14', '15', '20', '21', '23', '24', '27', '42', '43'],          //高频彩采种Id
    highNames: [{ '1': '重庆时时彩' },
    { '3': '天津时时彩' },
    { '6': '江西11选5' },
    { '7': '新疆时时彩' },
    { '9': '广东11选5' },
    { '10': '山东11选5' },
    { '11': '天津快乐十分' },
    { '13': '湖南快乐十分' },
    { '14': '上海时时乐' },
    { '15': '北京快乐8' },
    { '20': '安徽11选5' },
    { '21': '重庆快乐十分' },
    { '23': '江苏快3' },
    { '24': '安徽快3' },
    { '27': '北京PK拾' },
    { '42': '北京快三' },
    { '43': '广西快三' }],                        //高频彩彩种名


    areaIds: ['1', '3', '6', '7', '9', '10', '11', '12', '13', '14', '15', '20', '21', '22', '23', '24', '27', '42', '43'], //地方彩采种Id
    areaNames: [{ '1': '重庆时时彩' },
    { '3': '天津时时彩' },
    { '6': '江西11选5' },
    { '7': '新疆时时彩' },
    { '9': '广东11选5' },
    { '10': '山东11选5' },
    { '11': '天津快乐十分' },
    { '12': '广东快乐十分' },
    { '13': '湖南快乐十分' },
    { '14': '上海时时乐' },
    { '15': '北京快乐8' },
    { '20': '安徽11选5' },
    { '21': '重庆快乐十分' },
    { '22': '上海11选5' },
    { '23': '江苏快3' },
    { '24': '安徽快3' },
    { '27': '北京PK拾' },
    { '42': '北京快三' },
    { '43': '广西快三' }],                        //地方彩彩种名 

    //全国彩的标签
    nationTags: [{ 4: '每日 21:15 开奖' },
    { 5: '每日 20:30开奖' },
    { 17: '每日 20:30开奖' }],

    //高频彩的标签
    highTag: [{ 1: '10:00-22:00 10分钟1期，22:00-2:00 5分钟1期' },
    { 3: '每日08:58-22:58 10分钟1期' },
    { 6: '每日9:00-23:00 10分钟一期' },
    { 7: '每日9:00-22:00 10分钟1期' },
    { 9: '每日9:00-23:00 10分钟一期' },
    { 10: '每日9:00-23:00 10分钟一期' },
    { 11: '每日08:55-22:55 10分钟1期' },
    { 13: '每日09:00-23:30 10分钟1期' },
    { 14: '每日10:30-22:00 30分钟1期' },
    { 15: '每日09:05-23:55 5分钟1期' },
    { 20: '每日9:00-23:00 10分钟一期' },
    { 21: '每日10:00-02:00 10分钟1期' },
    { 23: '每日08:40-22:10 10分钟1期' },
    { 24: '每日08:40-22:00 10分钟1期' },
    { 27: '每日09:05-23:55 5分钟1期' },
    { 42: '每日08:40-22:10 10分钟1期' },
    { 43: '每日09:30-20:30 10分钟1期' },
    ],

    //地方彩
    areaTag: [{ 1: '10:00-22:00 10分钟1期，22:00-2:00 5分钟1期' },
    { 3: '每日08:58-22:58 10分钟1期' },
    { 6: '每日9:00-23:00 10分钟一期' },
    { 7: '每日9:00-22:00 10分钟1期' },
    { 9: '每日9:00-23:00 10分钟一期' },
    { 10: '每日9:00-23:00 10分钟一期' },
    { 11: '每日08:55-22:55 10分钟1期' },
    { 12: '每日09:00-23:00 10分钟1期' },
    { 13: '每日09:00-23:30 10分钟1期' },
    { 14: '每日10:30-22:00 30分钟1期' },
    { 15: '每日09:05-23:55 5分钟1期' },
    { 20: '每日9:00-23:00 10分钟一期' },
    { 21: '每日10:00-02:00 10分钟1期' },
    { 22: '每日9:00-23:00 10分钟一期' },
    { 23: '每日08:40-22:10 10分钟1期' },
    { 24: '每日08:40-22:00 10分钟1期' },
    { 27: '每日09:05-23:55 5分钟1期' },
    { 42: '每日08:40-22:10 10分钟1期' },
    { 43: '每日09:30-20:30 10分钟1期' },
    ],
  },

  upper: function (e) {
    console.log('下拉了...');
    console.log(e)
  },
  lower: function (e) {
    console.log(e)
  },
  scroll: function (e) {
    console.log(e)
  },


  onLoad: function () {
    console.log('onLoad');
    var that = this;

      wx.request({
        url: 'https://api.168cpt.com/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=1,3,6,7,9,10,11,13,14,15,20,21,23,24,27,42,43&flag=result', //调用高频彩的接口
        header: {
          'content-type': 'application/json'
        },
        success: function (res) {
          //console.log(res.data.data);
          //拼接数组
          var highInfos = res.data.data;
          for (var i = 0; i < that.data.highIds.length; i++) {
            //拼接地方
            var area = that.data.highNames[i][highInfos[that.data.highIds[i]][0].lottery_id];
            highInfos[that.data.highIds[i]][0]['area'] = area;
            var province = area.substr(0, 2);
            //console.log("省会"+province);
            if (province == '黑龙') {
              province += '江';
            } else if (province == '内蒙') {
              province += '古';
            }
            highInfos[that.data.highIds[i]][0]['province'] = province;
            // console.log(area);
            //拼接开奖号码
            var nums = highInfos[that.data.highIds[i]][0].nums.split(',');
            highInfos[that.data.highIds[i]][0]['codes'] = nums;
            //console.log(nums);

            //处理时间(日期)
            var dateStr = highInfos[that.data.highIds[i]][0].expect_time;
            // console.log("时间:"+timeStr);
            var parnter = /(\d{2})(\d{2})/;
            var formatStr = dateStr.substr(4, 4);
            var formatedDate2 = formatStr.replace(parnter, '$1-$2');
            highInfos[that.data.highIds[i]][0]['date'] = formatedDate2;

            //处理时间(周几)
            var parnter2 = /(\d{4})(\d{2})(\d{2})/;
            var formatStr2 = dateStr.substr(0, 8);
            var formatedDate = formatStr2.replace(parnter2, '$1/$2/$3');
            var day = new Date(formatedDate).getDay();
            var weekArray = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六")
            var week = weekArray[new Date(formatedDate).getDay()]
            highInfos[that.data.highIds[i]][0]['week'] = week;

            //拼接长度
            var len = nums.length;
            highInfos[that.data.highIds[i]][0]['len'] = len;

            //全国彩的标签
           var highTag = that.data.highTag[i][highInfos[that.data.highIds[i]][0].lottery_id];
           highInfos[that.data.highIds[i]][0]['tag'] = highTag;
            //数据的添加

          }
          wxSortPickerView.init(res.data.data, that.data.highIds, that);

          that.setData({
            highInfo: res.data.data,
            isHidden: true,
          });
        }
      })
   
    //获得系统信息
    wx.getSystemInfo({
      success: function (res) {
        that.setData({
          winWidth: res.windowWidth,
          winHeight: res.windowHeight,
        });
      }
    });
  },
  //滑动切换tab  
  bindChange: function (e) {
    var that = this;
    // console.log('tab' + e.detail.current);
    if (e.detail.current == 1) {
       //全国彩
    wx.request({
      url: 'https://api.168cpt.com/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=4,5,17', //调用全国彩的接口
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        //将开奖结果拼接成数组      
        var currentCode = new Array();          //拼接开奖结果
        var currentDay = new Array();           //拼接星期几
        var currentDate = new Array();          //对日期的处理
        var codeLen = new Array();
        for (var i = 0; i < that.data.nationIds.length; i++) {
          currentCode[that.data.nationIds[i]] = res.data.data[that.data.nationIds[i]][0].nums.split(",");

          //处理星期几
          var dateString = res.data.data[that.data.nationIds[i]][0].expect_time.substr(0, 8);
          var pattern = /(\d{4})(\d{2})(\d{2})/;      //正则表达式的规则
          var formatedDate = dateString.replace(pattern, '$1/$2/$3');
          var weekArray = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
          var day = weekArray[new Date(formatedDate).getDay()]

          //处理日期
          var dateStr = res.data.data[that.data.nationIds[i]][0].expect_time.substr(4, 4);
          //console.log(dateStr);
          var pattern2 = /(\d{2})(\d{2})/;
          var formatedDate2 = dateStr.replace(pattern2, '$1-$2');

          //全国彩的开奖结果的长度
          var codeLength = currentCode[that.data.nationIds[i]].length;
          codeLen[that.data.nationIds[i]] = codeLength;

          currentDay[that.data.nationIds[i]] = day;
          currentDate[that.data.nationIds[i]] = formatedDate2;

        }
        //将全国彩名字与采种的id拼接成数组
        var currentName = new Array();
        var nationTAG = new Array()
        for (var i = 0; i < that.data.nationNames.length; i++) {
          currentName[that.data.nationIds[i]] = that.data.nationNames[i];
          //全国彩对应的标签名
          nationTAG[that.data.nationIds[i]] = that.data.nationTags[i][that.data.nationIds[i]];
        }

        that.setData({
          nationInfo: res.data.data,
          nationCode: currentCode,
          nationNamesArr: currentName,
          nationDay: currentDay,
          nationDate: currentDate,
          nationCodeLen: codeLen,
          nationTag:nationTAG,
          isHidden: true,
        });

        var dateString = '20170215';
        var pattern = /(\d{4})(\d{2})(\d{2})/;
        var formatedDate = dateString.replace(pattern, '$1/$2/$3');
        var day = new Date(formatedDate).getDay();
      }
    })
    } else if (e.detail.current == 2) {
      //地方彩
      wx.request({
        url: 'https://api.168cpt.com/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=1,3,6,7,9,10,11,12,13,14,15,20,21,22,23,24,27,42,43',   //调用地方彩的接口
        header: {
          'content-type': 'application/json'
        },
        success: function (res) {
          //数据的处理
          var areaInfos = res.data.data;            //地方彩的信息

          for (var i = 0; i < that.data.areaIds.length; i++) {

            //拼接地方
            var area = that.data.areaNames[i][areaInfos[that.data.areaIds[i]][0].lottery_id];
            areaInfos[that.data.areaIds[i]][0]['area'] = area;
            var province = area.substr(0, 2);
            if (province == '黑龙') {
              province += '江';
            } else if (province == '内蒙') {
              province += '古';
            }
            areaInfos[that.data.areaIds[i]][0]['province'] = province;
            //拼接开奖号码
            var nums = areaInfos[that.data.areaIds[i]][0].nums.split(',');
            areaInfos[that.data.areaIds[i]][0]['codes'] = nums;
            //console.log(nums);

            //处理时间(日期)
            var dateStr = areaInfos[that.data.areaIds[i]][0].expect_time;
            var parnter = /(\d{2})(\d{2})/;
            var formatStr = dateStr.substr(4, 4);
            var formatedDate2 = formatStr.replace(parnter, '$1-$2');
            areaInfos[that.data.areaIds[i]][0]['date'] = formatedDate2;

            //处理时间(周几)

            var parnter2 = /(\d{4})(\d{2})(\d{2})/;
            var formatStr2 = dateStr.substr(0, 8);
            var formatedDate = formatStr2.replace(parnter2, '$1/$2/$3');
            var day = new Date(formatedDate).getDay();
            var weekArray = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六")
            var week = weekArray[new Date(formatedDate).getDay()]
            areaInfos[that.data.areaIds[i]][0]['week'] = week;

            //拼接长度
            var len = nums.length;
            areaInfos[that.data.areaIds[i]][0]['len'] = len;

            //地方彩Tag
            var areaTag = that.data.areaTag[i][areaInfos[that.data.areaIds[i]][0].lottery_id];
           areaInfos[that.data.areaIds[i]][0]['tag'] = areaTag;

     

          }

          wxSortPickerView.init(res.data.data, that.data.areaIds, that);

                    console.log(areaInfos);
          that.setData({
            areaInfo: areaInfos,
            isHidden: true
          });
        },
      })
    }

    that.setData({ currentTab: e.detail.current });
  },

  //   onReachBottom(){
  // console.log('--------下拉刷新-------')
  //   },


  onPullDownRefresh() {
    // 　　console.log('--------下拉刷新-------')
    wx.showNavigationBarLoading();

    wx.request({
      url: 'http://kaijiang.com:8003/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=4,5,17', //调用全国彩的接口
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        //将开奖结果拼接成数组      
        var currentCode = new Array();          //拼接开奖结果
        var currentDay = new Array();           //拼接星期几
        var currentDate = new Array();          //对日期的处理
        var codeLen = new Array();
        for (var i = 0; i < that.data.nationIds.length; i++) {
          currentCode[that.data.nationIds[i]] = res.data.data[that.data.nationIds[i]][0].nums.split(",");

          //处理星期几
          var dateString = res.data.data[that.data.nationIds[i]][0].expect_time.substr(0, 8);
          //console.log(dateString);
          var pattern = /(\d{4})(\d{2})(\d{2})/;      //正则表达式的规则
          var formatedDate = dateString.replace(pattern, '$1/$2/$3');
          // var day = new Date(formatedDate).getDay();
          var weekArray = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六")
          var day = weekArray[new Date(formatedDate).getDay()]

          //处理日期
          var dateStr = res.data.data[that.data.nationIds[i]][0].expect_time.substr(4, 4);
          //console.log(dateStr);
          var pattern2 = /(\d{2})(\d{2})/;
          var formatedDate2 = dateStr.replace(pattern2, '$1-$2');

          //全国彩的开奖结果的长度
          var codeLength = currentCode[that.data.nationIds[i]].length;

          codeLen[that.data.nationIds[i]] = codeLength;

          currentDay[that.data.nationIds[i]] = day;
          currentDate[that.data.nationIds[i]] = formatedDate2;
        }
        // console.log(res.data.data);

        //全国彩的开奖结果的长度
        // console.log("时间："+currentDay);

        //将全国彩名字与采种的id拼接成数组
        var currentName = new Array();
        for (var i = 0; i < that.data.nationNames.length; i++) {
          currentName[that.data.nationIds[i]] = that.data.nationNames[i];
        }

        //console.log(currentName);
        that.setData({
          nationInfo: res.data.data,
          nationCode: currentCode,
          nationNamesArr: currentName,
          nationDay: currentDay,
          nationDate: currentDate,
          nationCodeLen: codeLen,
          isHidden: true,
        });

        var dateString = '20170215';
        var pattern = /(\d{4})(\d{2})(\d{2})/;
        var formatedDate = dateString.replace(pattern, '$1/$2/$3');
        var day = new Date(formatedDate).getDay();
        //console.log(day);
      },
      complete: function () {
        wx.hideNavigationBarLoading();    //完成加载
        wx.stopPullDownRefresh();      //停止下拉刷新
      }

    })

  },
  //点击tab切换 
  swichNav: function (e) {
    var that = this;
    if (this.data.currentTab === e.target.dataset.current) {
      return false;
    } else {
      that.setData({
        currentTab: e.target.dataset.current
      })
    }
  },

  wxSortPickerViewItemTap: function (e) {
    console.log(e.target.dataset.text);
  }
})
