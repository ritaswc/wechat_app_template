//index.js
//获取应用实例
var app = getApp();
Page({
  data: {
    delay: 3000,
    ads: [{
      url: "../../image/ad1.gif"
    },{
      url: "../../image/ad1.gif"
    }],
    sliders: [
      {
        title: "题库练习",
        intro: "海量题库，章节练习",
        url: "./chapterlist/chapterlist",
        image: "../image/book.png"
      },
      {
        title: "模拟考试",
        intro: "历年真题，实战考试",
        url: "./examtest/examtest",
        image: "../image/book.png"
      },
      {
        title: "资料查看",
        intro: "精选资料，图文并茂",
        url: "./material/material",
        image: "../image/book.png"
      },
    ],
    subjects: [],
    subjectName: '',
    subjectTab: true
  },
  onLoad: function () {
    this.subjectsGet();
  },
  subjectsGet: function(){
   var that = this;
    wx.request({
      url: app.url.host + app.url.categoriesUrl,
      method: 'GET',
      data: {
        ctype:1,
        pid:-1
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          //测试专用
          wx.navigateTo({url: "./examtest/examtest?cid=1"});
          //测试专用
          var data = res.data, subjectsArr = [];
          if(wx.getStorageSync('SubjectId')) {
            that.setData({subjectTab: false});
            var subjectId = wx.getStorageSync('SubjectId'); 
          }
          var find = false;          
					for (var i = 0; i < data.length; i++) {
            subjectsArr.push({title: data[i].Title, selected:false, subjectId: data[i].Id});
            if(subjectId && subjectId == data[i].Id) {
              //找到与id相等的data
              subjectsArr[i].isSelected = true;
              that.setData({subjectName: data[i].Title});
              find = true;
            }            
					}
          that.setData({subjects: subjectsArr});
          if (!find) that.setData({subjectTab: true});
        } else {
          app.unauthorized(res.statusCode, './login/login');
        }
      }
    });
  },
  showTab: function(e) {
    this.setData({
      subjectTab: true      
    });
  },
  hideTab: function(e) {
    if(!this.data.subjectName.length) {
      wx.showToast({
        title:'请先选择科目'
      });
      return;
    }
    this.setData({
      subjectTab: false      
    });
  },
  subjectSelect: function(e) {
    var subject = e.currentTarget.dataset.subject;
    var subjects = this.data.subjects;
    wx.setStorageSync("SubjectId", subject.subjectId);

    for(var i=0; i<subjects.length; i++) {
        if(subjects[i].subjectId == subject.subjectId) {
          //找到与id相等的data
          subjects[i].isSelected = true;
        }      
        break;
    }
    this.setData({
      subjectName: subject.title,
      subjectTab: false,
      subjects: subjects    
    });
  },
  ping: function(){
    app.ping();
  }
})
