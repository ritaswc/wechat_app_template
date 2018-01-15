var seatmap = require('../../../utils/seatmap');
var limt = 0
function formatNumber(n) {
  n = n.toString()
  return n[1] ? n : '0' + n
}
Page({
  data: {
    shop: {},
    map: [],
    willChange: false,
    hasSelected: false,
  },
  onLoad: function (options) {
    var that = this
    wx.request({
      url: 'http://localhost:8888/data/seatdata?data=' + options.shop,
      data: {},
      method: 'GET',
      success: function (res) {
        var shop = res.data.data
        console.log(res.data)
        that.setData({
          shop: shop,
          map: shop.seat
        })
      }
    })
  },
  onReady: function () {
    var columnArr = []
    var map = this.data.map
    for (var i = 1; i <= map.length; i++) {
      columnArr.push(i)
    }
    this.setData({
      columnArr: columnArr
    })
  },
  onShow: function () {
  },
  scrollstart: function (ev) {
    this.sX = ev.changedTouches[0].clientX
    this.sY = ev.changedTouches[0].clientY
    this.setData({
      willChange: true
    })
    console.log(ev)
  },
  scrollmove: function (ev) {
    var mX = ev.changedTouches[0].clientX
    var mY = ev.changedTouches[0].clientY
    var deltaX = (mX - this.sX) / 2
    var deltaY = (mY - this.sY) / 2
    this.setData({
      deltaX: deltaX,
      deltaY: deltaY
    })
  },
  scrollend: function (ev) {
    var eX = ev.changedTouches[0].clientX
    var eY = ev.changedTouches[0].clientY
    console.log(ev)
    this.setData({
      willChange: false
    })
  },
  selectSeat: function (ev) {
    var ver = ev.currentTarget.dataset.ver
    var hor = ev.currentTarget.dataset.hor
    var map = this.data.map
    var seats = []
    var cStr = ''
    limt++
    if (limt <= 4) {
      map[ver][hor] = 2
      for (var i = 0; i < map.length; i++) {
        for (var j = 0; j < map[i].length; j++) {
          if (map[i][j] === 2) {
            cStr = formatNumber(i + 1) + '排' + (j + 1) + '座'
            seats.push(cStr)
          }
        }
      }
      this.setData({
        map: map,
        seats: seats
      })
    }
    else {
     wx.showToast({
  title: '最多选4个座位',
  icon: 'success',
  duration: 2000
})
    }
  },
  cancelSeat: function (ev) {
    var ver = ev.currentTarget.dataset.ver
    var hor = ev.currentTarget.dataset.hor
    var cStr = ''
    var seats = []
    console.log(ev)
    var map = this.data.map
    map[ver][hor] = 1
    for (var i = 0; i < map.length; i++) {
      for (var j = 0; j < map[i].length; j++) {
        if (map[i][j] === 2) {
          cStr = formatNumber(i + 1) + '排' + (j + 1) + '座'
          seats.push(cStr)
        }
      }
    }
    this.setData({
      map: map,
      seats: seats
    })

  }
})