Page({
  data: {
    selectedTab: 0,
    emergencyTitle: '应急，事不容迟。',
    studayTitle: '学习，未雨绸缪。',
    emergencyList: [

    ],
    studayList: [

    ],
  },
  bindSelectedTab(e) {
    this.setData({
      selectedTab: Number(e.target.dataset.tab),
    });
  },
  tap(e) {
    if (e.target.dataset.title === '意识丧失' && e.target.dataset.type === '应急'){
      wx.navigateTo({
        url: '../isBreath/isBreath',
      });
    }
    if (e.target.dataset.title === '烧烫伤' && e.target.dataset.type === '应急'){
      wx.navigateTo({
        url: '../burning/burning',
      });
    }
    if (e.target.dataset.title === '意识丧失' && e.target.dataset.type === '学习'){
      wx.navigateTo({
        url: '../unconsciousnessStudy/unconsciousnessStudy',
      });
    }

  },
  change(e) {
    this.setData({
      selectedTab: Number(e.detail.current),
    });
  },
});
