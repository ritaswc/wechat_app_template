var index = require("../../data/index-list.js")
var util = require("../../utils/util.js")
var crypt = require("../../utils/crypt.js")


var app = getApp();
Page({
  data: {
    selected: 1,
    showRecomment: {},
    emoij: false,
    commentText: "",
    selectedImgs: [],
    currentMoreComment: null,
    articles: [], // 我的发帖
    payArticles:[], // 我的付费贴
  },
  onLoad: function (options) {
    // 页面初始化 options为页面跳转所带来的参数
    let uid = options.uid;
    this.setData({ "uid": uid })
    this.init(uid);
  },
  onReady: function () {
    // 页面渲染完成
  },
  onShow: function () {
    // 页面显示
  },
  onHide: function () {
    // 页面隐藏
  },
  onUnload: function () {
    // 页面关闭
  },

  init: function (uid) {
    var that = this;
    that.setData({"loading":true})
    app.getInit(function (result) {
      that.setData({ "user": result.obj._LookUser, "minisns": result.obj._Minisns, "tmpFile": result.obj.tmpFile })
      if (user.Id != that.data.uid) {
        wx.setNavigationBarTitle({"tilte":"TA的发帖"})
      }
    })
    
    that.getArticles(uid);

  },

  /**
   * 重置数据
   */
  resetData: function() {
    this.setData({"showRecomment":{}, "emoij":false, "commentText":"", "selectedImgs":[]
    , "currentMoreComment":null })
  },

  /**
 * 切换列表
 */
  showList: function (e) {
    var that = this;
    let id = e.currentTarget.dataset.id;
    if (id == 1) {
      that.setData({ "selected": 1 })
      that.getArticles();
    } else {
      that.setData({ "selected": 2 })

      that.getChargeArt();
    }
  },

  /**
   * 获取我的帖子
   */
  getArticles: function (uid) {
    var that = this;
    var tmpFile = that.data.tmpFile;
    var minisId = that.data.minisns.Id;
    var unionid = that.data.user.unionid;
    var verifyModel = util.primaryLoginArgs(unionid);
    wx.uploadFile({
      url: 'https://snsapi.vzan.com/minisnsapp/myarticles',
      filePath: tmpFile,
      name: 'file',
      // header: {}, // 设置请求的 header
      formData: {
        "userid": uid, "deviceType": verifyModel.deviceType, "timestamp": verifyModel.timestamp,
        "uid": unionid, "versionCode": verifyModel.versionCode, "sign": verifyModel.sign, "fid": minisId
      }, // HTTP 请求中其他额外的 form data
      success: function (res) {
        var result = JSON.parse(res.data);
        let list = []
        for (let i = 0; i < result.objArray.length; i++) {
          let article = result.objArray[i]
          if (article.Address) {
            let address = JSON.parse(article.Address);
            article.Address = address;
          }
          let articleComments = article.articleComments;
          if (articleComments) {
            articleComments = articleComments.reverse()
            article.articleComments = articleComments;
          }
          list.push(article)
        }
        console.log("获取我的发帖列表", list);
        that.setData({ articles: list })
      },
      complete: function() {
        that.setData({"loading":false})
      }
    })


  },

  /**
   * 获取我的付费贴
   */
  getChargeArt: function () {
    let that = this
    that.resetData()
    
  },

  /**
 * 操作帖子
 */
  openArrow: function (e) {
    var that = this;
    let artId = e.currentTarget.dataset.artId;
    // 获取权限
    var tmpFile = that.data.tmpFile;
    var minisId = that.data.minisns.Id;
    var unionid = that.data.user.unionid;
    var verifyModel = util.primaryLoginArgs(unionid);
    wx.uploadFile({
      url: 'https://snsapi.vzan.com//minisnsapp/checkpermissionbyuser',
      filePath: tmpFile,
      name: 'file',
      // header: {}, // 设置请求的 header
      formData: {
        "deviceType": verifyModel.deviceType, "timestamp": verifyModel.timestamp,
        "uid": unionid, "versionCode": verifyModel.versionCode, "sign": verifyModel.sign,
        artId: artId
      }, // HTTP 请求中其他额外的 form data
      success: function (res) {
        let result = JSON.parse(res.data);
        console.log("获取帖子权限", result);
        if (result.result == true) {
          let actionList = []
          if (result.obj.BlackOne) { // 举报
            actionList.push("举报")
          }
          actionList.push("取消")
          wx.showActionSheet({
            itemList: actionList,
            success: function (res) {
              if (!res.cancel) {
                let idx = res.tapIndex;
                if (actionList[idx] == "举报") {
                  console.log("点击举报")
                }
              }
            }
          })
        }
      }
    })
  },
  /**
   * 初始化评论信息
   */
  initRecomment: function () {
    var that = this;
    that.setData({
      "emoij": false,
      "commentText": "",
      "selectedImgs": []
    })
  },

  /**
 * 评论帖子
 */
  showReComment: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;  // 帖子ID
    let showRecomment = that.data.showRecomment;
    if (showRecomment != null) { // 关闭评论
      that.setData({ "showRecomment": null })
      that.initRecomment();
    } else { // 打开评论
      that.setData({ "showRecomment": { "id": id } })
      that.initRecomment();
    }
  },
  /**
 * 回复用户评论
 */
  commentUser: function (e) {
    var that = this;
    let showRecomment = that.data.showRecomment;
    if (showRecomment != null) { // 关闭评论
      that.setData({ "showRecomment": null })
      that.initRecomment();
    } else {
      let artId = e.currentTarget.dataset.artid;
      let uid = e.currentTarget.dataset.uid;
      let name = e.currentTarget.dataset.name;
      let id = e.currentTarget.dataset.id;
      that.setData({
        "showRecomment": {
          "id": artId,
          "toUserId": uid,
          "commontId": id,
          "toUserName": name
        }
      })
    }
  },
  /**
   * 删除图片
   */
  removeImg: function (event) {
    var that = this;
    var id = event.currentTarget.dataset.id;
    var imgs = that.data.selectedImgs;
    for (var i = 0; i < imgs.length; i++) {
      if (imgs[i].id == id) {
        imgs.splice(i, 1)
        break;
      }
    }
    that.setData({ selectedImgs: imgs })
  },
  /**
   * 打开或关闭Emoij选框
   */
  selectEmoij: function (e) {
    var emoij = this.data.emoij;
    this.setData({ "emoij": !emoij })
  },
  /**
 * 选择图片
 */
  selectImg: function (e) {
    var that = this;
    var id = e.currentTarget.dataset.id;
    var minisId = wx.getStorageSync('minisns').Id;
    var unionid = wx.getStorageSync('user').unionid;
    var verifyModel = util.primaryLoginArgs(unionid);
    wx.chooseImage({
      count: 9, // 最多可以选择的图片张数，默认9
      sizeType: ['original', 'compressed'], // original 原图，compressed 压缩图，默认二者都有
      sourceType: ['album', 'camera'], // album 从相册选图，camera 使用相机，默认二者都有
      success: function (res) {
        var tmp = res.tempFilePaths;

        for (var i = 0; i < tmp.length; i++) {
          // 上传图片s
          wx.uploadFile({
            url: 'https://snsapi.vzan.com/minisnsapp/uploadfilebytype',
            filePath: tmp[i],
            name: 'file',
            // header: {}, // 设置请求的 header
            formData: {
              "fid": minisId, "uploadType": "img", "deviceType": verifyModel.deviceType, "timestamp": verifyModel.timestamp,
              "uid": unionid, "versionCode": verifyModel.versionCode, "sign": verifyModel.sign
            }, // HTTP 请求中其他额外的 form data
            success: function (res) {
              var result = JSON.parse(res.data);
              console.log("上传图片成功", result);
              // 刷新页面
              var rtmp = that.data.selectedImgs;
              rtmp = rtmp.concat({ id: result.obj.id, src: result.obj.url });
              that.setData({ selectedImgs: rtmp });
            }
          })
        }
      }
    })
  },
  /**
   * 取消评论
   */
  commentCancle: function (e) {
    console.log("取消评论")
    this.setData({ showRecomment: null, emoij: false, commentText: "", selectedImgs: [] })
  },
  /**
   * 提交帖子评论 | 回复
   */
  commentSubmit: function (e) {
    var that = this;
    let content = e.detail.value.content;
    that.setData({ "commentText": content })
    var showRecomment = that.data.showRecomment;
    if (showRecomment.commontId) { // 回复用户
      that.replyComment();
    } else {
      that.replyPost(); // 回复帖子
    }

    console.log("提交评论 -- END");
  },
  /**
    * 回复帖子
    * @Param id 帖子ID
 */
  replyPost: function () {
    var that = this;
    var minisId = wx.getStorageSync('minisns').Id;
    var unionid = wx.getStorageSync('user').unionid;
    var verifyModel = util.primaryLoginArgs(unionid);
    var imgs = "";
    for (var i = 0; i < that.data.selectedImgs.length; i++) {
      if (i = 0) { imgs = that.data.selectedImgs[i].id; }
      else { imgs = imgs + "," + that.data.selectedImgs[i].id; }
    }
    var content = that.data.commentText;
    let id = that.data.showRecomment.id;// 帖子ID
    wx.uploadFile({
      url: 'https://snsapi.vzan.com/minisnsapp/commentartbyid',
      filePath: wx.getStorageSync('tmpFile'),
      name: 'file',
      // header: {}, // 设置请求的 header
      formData: {
        "deviceType": verifyModel.deviceType, "timestamp": verifyModel.timestamp,
        "uid": unionid, "versionCode": verifyModel.versionCode, "sign": verifyModel.sign,
        "artId": id, "comment": content, "images": imgs
      }, // HTTP 请求中其他额外的 form data
      success: function (res) {
        var result = JSON.parse(res.data);
        if (result.result == true) { // 发帖成功
          wx.request({
            url: "https://snsapi.vzan.com/minisnsapp/getcmt-" + id,
            data: { fid: minisId, pageIndex: 1 },
            method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
            // header: {}, // 设置请求的 header
            success: function (res) {
              var arts = that.data.articles;
              for (var i = 0; i < arts.length; i++) {
                var tmp = arts[i];
                if (tmp.Id == id) {
                  tmp.articleComments = that.generateComments(res.data.CommentList);
                  arts[i] = tmp;
                  // 更新数据
                  that.setData({ articles: arts })
                  break;
                }
              }
            }
          })
        }
      },
      complete: function () {
        // 清空评论数据
        that.setData({ showRecomment: null, emoij: false, commentText: "", selectedImgs: [] })
      }
    })
  },
  /**
   * 回复用户评论
   * @param id 帖子ID
    */
  replyComment: function () {
    var that = this;
    var minisId = that.data.minisns.Id;
    var unionid = that.data.user.unionid;
    var verifyModel = util.primaryLoginArgs(unionid);
    var imgs = "";
    for (var i = 0; i < that.data.selectedImgs.length; i++) {
      if (i = 0) {
        imgs = that.data.selectedImgs[i].id;
      } else {
        imgs = imgs + "," + that.data.selectedImgs[i].id;
      }
    }
    var content = that.data.commentText;
    var showRecomment = that.data.showRecomment;
    wx.uploadFile({
      url: 'https://snsapi.vzan.com/minisnsapp/replyartcommentbyid',
      filePath: wx.getStorageSync('tmpFile'),
      name: 'file',
      // header: {}, // 设置请求的 header
      formData: {
        "deviceType": verifyModel.deviceType, "timestamp": verifyModel.timestamp,
        "uid": unionid, "versionCode": verifyModel.versionCode, "sign": verifyModel.sign,
        "artId": showRecomment.id, "toUserId": showRecomment.toUserId, "commontId": showRecomment.commontId, "comment": content, "images": imgs
      }, // HTTP 请求中其他额外的 form data
      success: function (res) {
        var result = JSON.parse(res.data);
        if (result.result == true) { // 发帖成功
          wx.request({
            url: "https://snsapi.vzan.com/minisnsapp/getcmt-" + showRecomment.id,
            data: { fid: minisId, pageIndex: 1 },
            method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
            // header: {}, // 设置请求的 header
            success: function (res) {
              var arts = that.data.articles;
              for (var i = 0; i < arts.length; i++) {
                var tmp = arts[i];
                if (tmp.Id == showRecomment.id) {
                  tmp.articleComments = that.generateComments(res.data.CommentList);
                  arts[i] = tmp;
                  // 更新数据
                  that.setData({ articles: arts })
                  break;
                }
              }
            }
          })
        }
      },
      complete: function () {
        // 清空评论数据
        that.setData({
          showRecomment: null,
          emoij: false, commentText: "", selectedImgs: []
        })
      }
    })

  },

  // 播放声音
  playAudio: function (event) {
    let vid = event.currentTarget.dataset.vId;
    let vSrc = event.currentTarget.dataset.vSrc;
    util.playVoice(vid, vSrc)

  },
  /**
   * 展示大图
   */
  showBigImg: function (e) { // 展示大图
    var src = e.currentTarget.dataset.src;
    wx.previewImage({
      current: src, // 当前显示图片的链接，不填则默认为 urls 的第一张
      urls: [src],
    })
    return false;
  },


  /**
   * 整合评论信息
   */
  generateComments: function (commentList) {
    var comment = {};
    console.log("获取帖子评论列表", commentList)
    for (var i = 0; i < commentList.length; i++) {
      var tmp = commentList[i];
      // 回复者
      for (var j = 0; j < tmp.Comments.length; j++) {
        var rTmp = tmp.Comments[j];
        rTmp.DUser = { "Id": tmp.User.Id, "Headimgurl": tmp.User.Headimgurl, "NickName": tmp.User.Nickname };
        rTmp.ComUser = rTmp.User;
        comment[rTmp.Id] = rTmp;
      }
      if (typeof comment[tmp.Id] == "undefined") {
        tmp.ComUser = tmp.User;
        comment[tmp.Id] = tmp;
      }
    }
    var list = [];
    for (var key in comment) {
      list.push(comment[key])
    }
    console.log("转换后的评论列表", list);
    return list.reverse();
  },
  /**
 * 保存选择的表情
 */
  emoijSelected: function (e) {
    var code = e.currentTarget.dataset.code;
    var tmp = this.data.commentText;
    tmp = tmp + code;
    this.setData({ commentText: tmp });
  },
  /**
 * 保存评论的内容
 */
  saveTextValue: function (e) {
    var content = e.detail.value;
    this.setData({ commentText: content });
  },
  /**
   * 点赞
   */
  praise: function (e) {
    var that = this;
    var minisId = wx.getStorageSync('minisns').Id;
    var unionid = wx.getStorageSync('user').unionid;
    var verifyModel = util.primaryLoginArgs(unionid);
    var id = e.currentTarget.dataset.id; // 帖子ID
    var verifyModel = util.primaryLoginArgs(unionid);
    wx.uploadFile({
      url: 'https://snsapi.vzan.com/minisnsapp/articlepraise',
      filePath: wx.getStorageSync('tmpFile'),
      name: 'file',
      // header: {}, // 设置请求的 header
      formData: {
        "deviceType": verifyModel.deviceType, "timestamp": verifyModel.timestamp,
        "uid": unionid, "versionCode": verifyModel.versionCode, "sign": verifyModel.sign, "artId": id
      }, // HTTP 请求中其他额外的 form data
      success: function (res) {
        var tmp = that.data.articles;
        var result = JSON.parse(res.data);
        console.log("点赞成功", result)
        // 修改状态
        if (result.result == true) {
          for (var i = 0; i < tmp.length; i++) {
            if (tmp[i].Id == id) {
              tmp[i].IsPraise = true;
              tmp[i].Praise = tmp[i].Praise + 1;
            }
          }
          that.setData({ articles: tmp })
        } else {
          wx.showModal({ title: "提示", content: result.msg, showCancel: false, confirmText: "取消" })
        }
      }
    })
  },

  /**
 * 更多评论信息
 */
  moreComment: function (e) {
    this.setData({ currentMoreComment: e.currentTarget.dataset.id })
  },

  /**
   * 跳转到用户详情页
   */
  navicateToUser: function (e) {
    let uid = e.currentTarget.dataset.id;
    let that = this;
    if (uid == that.data.user.Id) {
      // 回退
      wx.navigateBack({"delta":1})
    } else {
      wx.redirectTo({
        url: '/pages/user/user?uid=' + uid,
        success: function (res) {
          console.log("从用户发帖列表，跳转到用户信息页")
        }
      })
    }

  }



})