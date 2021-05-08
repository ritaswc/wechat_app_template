Page({
  data: {
    array:["天海新世纪号","歌诗达邮轮•大西洋号","歌诗达邮轮•幸运号","歌诗达邮轮•维多利亚号","歌诗达邮轮•赛琳娜号","海洋量子号","海洋水手号","蓝宝石公主号"],
    index:0
  },
  bindPickerChange: function(e) {
    console.log('picker发送选择改变，携带值为', e.detail.value)
    this.setData({
      index: e.detail.value
    })
  }
})