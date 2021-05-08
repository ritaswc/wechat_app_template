import request from '../../../../lib/request';

const app = getApp();

Page({
  data: {},
  onLoad() {
    this.setData({
      username: app.globalData.userInfo.nickName
    });
  },
  clearText() {
    this.setData({
      username: '',
      display: 'display-none',
      disabled: true,
      class: 'disabled'
    });
  },
  changeStatus(e) {
    if (e.detail.value.length > 0) {
      this.setData({
        display: '',
        disabled: false,
        class: '',
        username: e.detail.value
      });
    } else {
      this.setData({
        display: 'display-none',
        disabled: true,
        class: 'disabled'
      });
    }
  },
  updataInfo(e) {
    const data = {
      nickname: e.currentTarget.dataset.nickname
    };
    request({ path: '/users/user', data, method: 'put' }).then((res) => {
      if (res) {
        app.globalData.userInfo.nickName = e.currentTarget.dataset.nickname;
        wx.navigateTo({
          url: '../user-info'
        });
      }
    });
  }
});
