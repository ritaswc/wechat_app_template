Page({
  data: {
    rstInfo: null,   //全国彩的开奖信息
    len: 30,     //显示的条数
    timeArray: null,    //开奖前7天的信息
    index: 0,
    hisId: null,    ///指定的ID
    tag: null,     //标识
    isHidden: false,   //加载图标
  },
  onLoad: function (options) {
    // 生命周期函数--监听页面加载
    //console.log("传递的参数:"+options.id)
    var that = this;
    var hisID = options.id;

    // console.log("标识:"+FLAG);
    //指定的彩种 
    wx.request({
      url: 'https://api.168cpt.com/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=' + options.id + '&count=' + that.data.len ,                 //调用的地址
      header: {
        'content-type': 'application/json'
      },
      success: function (res) {
        //数据的处理
        var highDatas = res.data.data[options.id];
        console.log(highDatas);
        for(var i = 0; i < highDatas.length; i++){
          //开奖结果的处理
          var highCodes = highDatas[i].nums.split(',');
          highDatas[i]['codes'] = highCodes;

          //时间的处理(星期)
          var timeStr = highDatas[i].expect_time.substr(0,8);     
          var pattern = /(\d{4})(\d{2})(\d{2})/;
          var formateDate = timeStr.replace(pattern, '$1/$2/$3');
          var weekIndex = new Date(formateDate).getDay();
          var weekArray = new Array("周日","周一","周二","周三","周四","周五","周六");
          highDatas[i]['week'] = weekArray[weekIndex];
          console.log(formateDate);

          //时间的处理(日期)
          var formateDate2 = timeStr.replace(pattern, '$1-$2-$3');
          highDatas[i]['date'] = formateDate2;
        }

                //获得七天的日期
        var myDate = new Date();
        myDate.setDate(myDate.getDate()-6);     //转换成时间戳
        var dateArray = new Array();
        var tempArray = new Array()
        var tempDate;
        var flag = 1;
        for(var i = 0; i < 7; i++){
          var month = myDate.getMonth()+1;
          if(myDate.getMonth()+1 < 9){
              month = "0"+ month;
          }
          var day = myDate.getDate();
          if(day < 9){
            day = "0" +day;
          }
          tempDate = myDate.getFullYear()+'-'+month+'-'+day;
           tempArray.push(tempDate);
           myDate.setDate(myDate.getDate() + flag);
        }
        for(var i = 6; i >= 0; i--){
          dateArray.push(tempArray[i]);
        }

        wx.setNavigationBarTitle({
          title: options.name,
          success: function(res) {
            // success
          }
        })
       

        that.setData({
          rstInfo: res.data.data,
          timeArray: dateArray,
          hisId: hisID,
          tag: options.tag,
          isHidden: true
        });
      }
    })
  },
  //  点击时间组件确定事件  
  // bindPickerChange: function (e) {  
  //   //进行url请求

  //   wx.request({
  //         url: 'https://api.168cpt.com/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=' + options.id + '&count=' + that.data.len + '&issue_date=' +e.detail.value ,
  
  //     success: function(res){
  //       // success
        
  //     },
      
  //   })


  //   this.setData({  
  //     index: e.detail.value  
  //   })  
  // } 


    //  点击时间组件确定事件  
  bindPickerChange: function (e) {
    //进行url请求
    var that = this;
    console.log("时间：" + that.data.timeArray[e.detail.value]);
    var time = that.data.timeArray[e.detail.value];
    console.log("id" + that.data.hisId);
    // console.log("时间："+time.replace(/-/g,""));
    var strTime = time.replace(/-/g, "");
    wx.request({
      url: 'https://api.168cpt.com/index.php/Home/index/test/?command=lottery/get_lottery_last_result&lottery_ids=' + that.data.hisId + '&count=' + that.data.len + "&issue_date=" + strTime,

      success: function (res) {

        // console.log("数据"+res);
        console.log(res.data.data);

        //数据的处理
        var highDatas = res.data.data[that.data.hisId];
        console.log(highDatas);
        for (var i = 0; i < highDatas.length; i++) {
          //开奖结果的处理
          var highCodes = highDatas[i].nums.split(',');
          highDatas[i]['codes'] = highCodes;

          //时间的处理(星期)
          var timeStr = highDatas[i].expect_time.substr(0, 8);
          var pattern = /(\d{4})(\d{2})(\d{2})/;
          var formateDate = timeStr.replace(pattern, '$1/$2/$3');
          var weekIndex = new Date(formateDate).getDay();
          var weekArray = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
          highDatas[i]['week'] = weekArray[weekIndex];
          console.log(formateDate);

          //时间的处理(日期)
          var formateDate2 = timeStr.replace(pattern, '$1-$2-$3');
          highDatas[i]['date'] = formateDate2;
        }

        that.setData({
          index: e.detail.value,
          rstInfo: res.data.data,
        })



      },
      fail: function () {
        // fail
      },
      complete: function () {
        // complete
      }
    })

  }
})