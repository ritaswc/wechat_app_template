import resource from '../../../lib/resource';

const app = getApp();

Page({
  data: {
    loading : true
  },
  onLoad() {
    this.setData({
      list: [{
        text: '头像',
        tip: '',
        img: true,
        info: app.globalData.userInfo.avatarUrl
      }, {
        text: '昵称',
        tip: '',
        url: 'username-edit/username-edit',
        info: app.globalData.userInfo.nickName
      }, {
        text: '绑定手机号',
        tip: '',
        url: 'tel-bind/tel-bind',
        info: app.globalData.userInfo.mobile || '尚未绑定'
      }]
    });
  },
  navigateTo(e) {
    const url = e.currentTarget.dataset.url;
    const that = this;
    if (url === undefined) {
      wx.chooseImage({
        count: 1, // 默认9
        sizeType: ['original', 'compressed'], // 可以指定是原图还是压缩图，默认二者都有
        sourceType: ['album', 'camera'], // 可以指定来源是相册还是相机，默认二者都有
        success(res) {
          // 返回选定照片的本地文件路径列表，tempFilePath可以作为img标签的src属性显示图片
          const tempFilePaths = res.tempFilePaths;
          console.log(tempFilePaths[0]);
          wx.uploadFile({
            url: resource.getUrl('/wx/upload'), // 仅为示例，非真实的接口地址
            filePath: tempFilePaths[0],
            name: 'file',
            formData: {
              user: 'test'
            },
            success(res) {
              if(res.statusCode != 200) {
                 resource.showTips(that, '图片上传失败');
                 console.log(res);
                 return;
              }
              var icon = res.data;
              resource.updateUserInfo({icon:icon}).then(res=>{
                  if(res.statusCode == 200) {
                     app.globalData.userInfo.avatarUrl = res.data.data.icon;
                     resource.showTips(that, '修改成功');
                     that.onLoad();
                  } else {
                     resource.showTips(that, '修改失败');
                  }
              });
            },
            fail(res) {
              console.log(res);
              resource.showTips(that, '图片上传失败');
            }
          });
        }
      });
    } else {
      wx.navigateTo({
        url
      });
    }
  }
});
