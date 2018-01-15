Page({

  data: {
    brand: "",
    vehicle: "",
    logo: "1",
    message: {},
    carList: [],
    car_id: 1,
    delBtnWidth: 220//删除按钮宽度单位（rpx）
  },

  onLoad: function (options) {
    var vm = this;
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/cars/my-car-list',
      header: {//请求头
        "Content-Type": "application/x-www-form-urlencoded"
      },
      data: {
        openid: wx.getStorageSync('openid')
      },
      method: "POST",//get为默认方法/POST
      success: function (res) {
        console.log(res)
        vm.setData({
          list: res.data.message
        })
      }
    })
    var objectId = options.objectId;
    this.initEleWidth();

    // this.tempData();

  },
  bindSuccess: function () {
    wx.redirectTo({
      url: './addCar/addCar'
    })
  },
  touchS: function (e) {

    if (e.touches.length == 1) {
      this.setData({

        //设置触摸起始点水平方向位置

        startX: e.touches[0].clientX,
       
        

      });

    }

  },

  touchM: function (e) {

    if (e.touches.length == 1) {

      //手指移动时水平方向位置

      var moveX = e.touches[0].clientX;

      //手指起始点位置与移动期间的差值

      var disX = this.data.startX - moveX;

      var delBtnWidth = this.data.delBtnWidth;

      var txtStyle = "";

      if (disX == 0 || disX < 0) {
        //如果移动距离小于等于0，文本层位置不变

        txtStyle = "left:0px";

      } else

        if (disX > 0) {
          //移动距离大于0，文本层left值等于手指移动距离

          txtStyle = "left:-" + disX + "px";

          if (disX >= delBtnWidth) {

            //控制手指移动距离最大值为删除按钮的宽度

            txtStyle = "left:-" + delBtnWidth + "px";

          }

        }

      //获取手指触摸的是哪一项

      var index = e.target.dataset.index;

      var list = this.data.list;

      list[index].txtStyle = txtStyle;

      //更新列表的状态

      this.setData({

        list: list

      });

    }

  },
  touchE: function (e) {

    if (e.changedTouches.length == 1) {

      //手指移动结束后水平位置

      var endX = e.changedTouches[0].clientX;

      //触摸开始与结束，手指移动的距离

      var disX = this.data.startX - endX;

      var delBtnWidth = this.data.delBtnWidth;

      //如果距离小于删除按钮的1/2，不显示删除按钮

      var txtStyle = disX > delBtnWidth / 2 ? "left:-" + delBtnWidth + "px" : "left:0px";

      //获取手指触摸的是哪一项

      var index = e.target.dataset.index;

      var list = this.data.list;

      list[index].txtStyle = txtStyle;

      //更新列表的状态

      this.setData({

        list: list

      });

    }

  },

  //获取元素自适应后的实际宽度

  getEleWidth: function (w) {

    var real = 0;

    try {
      var res = wx.getSystemInfoSync().windowWidth;

      var scale = (750 / 2) / (w / 2);
      //以宽度750px设计稿做宽度的自适应
      real = Math.floor(res / scale);

      return real;

    } catch (e) {

      return false;

    }

  },

  initEleWidth: function () {

    var delBtnWidth = this.getEleWidth(this.data.delBtnWidth);

    this.setData({

      delBtnWidth: delBtnWidth

    });

  },

  //点击删除按钮事件

  delItem: function (e) {

    //获取列表中要删除项的下标

    var index = e.target.dataset.index;

    console.log(e.currentTarget.dataset.car_id);

    var list = this.data.list;

    //移除列表中下标为index的项

    list.splice(index, 1);

    //更新列表的状态
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/cars/delete-my-car',
      header: {//请求头
        "Content-Type": "application/x-www-form-urlencoded"
      },
      data: {
        openid: wx.getStorageSync('openid'),
        car_id: e.currentTarget.dataset.car_id,
      },
      method: "POST",//get为默认方法/POST
      success: function (res) {
      wx.showToast({
				title: "删除成功",
				icon: 'success',
				duration: 1500
			});
      }
    })
    this.setData({

      list: list

    });

  },
  editItem: function (e) {
    console.log(e.currentTarget.dataset.car_id);

    wx.redirectTo({
      url: './editCar/editCar?car_name=' + e.currentTarget.dataset.car_name+ '&brand_name=' + e.currentTarget.dataset.brand_name + '&brand_id=' + e.currentTarget.dataset.brand_id + '&car_id=' + e.currentTarget.dataset.car_id 
    });
  }
})