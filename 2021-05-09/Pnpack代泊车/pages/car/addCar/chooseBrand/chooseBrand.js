
Page({
  data: {
    successdata: [],
    brands: [],
    brandList: [],
    wordindex: [],
    toView: 'a',
    allResult: true,
    searchResult: false,
  },
  onLoad: function (options) {
    var that = this;
    // 页面初始化 options为页面跳转所带来的参数
    var cData = that.data.successdata;
    // console.log(that.data.list+"llll") 
    //   cData[0].wordindex = "#";//先修改json值  
    that.setData({ //再set值  
      wordindex: cData
    })
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/cars/all-brand-list',
      header: {//请求头
        "Content-Type": "application/x-www-form-urlencoded"
      },
      data: {
        band: "band"
      },
      method: "POST",//get为默认方法/POST
      success: function (res) {
        for (var i = 0; i < res.data.data.length; i++) {
          that.setData({
            brands: res.data.data[i].brands
          })


        };

        that.setData({

          successdata: res.data.data



        })


      }
    })


  },
  choiceWordindex: function (event) {
    var wordindex = event.currentTarget.dataset.wordindex;
    if (wordindex == '#') {
      this.setData({
        toView: 'a',
      })
    } else {
      this.setData({
        toView: wordindex,
      })
    }
    console.log(this.data.toView);
  },

  edit: function (e) {
    var that = this;

    // 取得下标
    var name = e.currentTarget.dataset.name;
    var logo = e.currentTarget.dataset.logo;
    var id = e.currentTarget.dataset.id;
    wx.navigateBack();
    var pages = getCurrentPages();
    var currPage = pages[pages.length - 1];   //当前页面
    var prevPage = pages[pages.length - 2];  //上一个页面
    var prPage = pages[pages.length - 3]; 
    prevPage.setData({
      logo: logo,
      brand: name,
      band_id: id,
    })
    prPage.setData({
      logo: logo,

    })



  },
  onBindInput: function (e) {

    console.log(e.detail.value + ".....");


  },
  onBindFocus: function (e) {

    this.setData({
      allResult: false,
      searchResult: true
    })

  },


})