Page({
  data: {
    pointInfo: [],
    comment:[],
    point_id:[],
    avg_score:[]
  },
  onLoad: function (options) {
    var vm = this;
    vm.setData({
      point_id: options.id
    })
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/parking/point-info',
      data: {
        point_id: options.id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res.data.data)
        vm.setData({
          pointInfo: res.data.data
        })
        vm.setData({
          avg_score: parseInt(res.data.data.avg_score)
        })
      }
    });
    wx.request({
      url: 'https://wx.viparker.com/valetparking/api/web/index.php/comment/comment-list',
      data: {
        point_id:options.id
      },
      header: {
        "Content-Type": "application/x-www-form-urlencoded"
      },
      method: "POST",
      success: function (res) {
        console.log(res)
        vm.setData({
          comment: res.data.data
        })
      }
    });
  },
  bindSuccess: function (e) {
    var id = e.currentTarget.dataset.id;
    wx.showModal({
      confirmColor: '#00a598',
      title: '友情提示',
      content: '您确定预约泊车服务?',
      success: function (res) {
        if (res.confirm) {
          wx.navigateTo({
            url: './order/order?id=' + id
          })
        }
      }
    })
  }
})