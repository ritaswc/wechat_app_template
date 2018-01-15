//index.js
var app = getApp()
Page({
  data: {
    chapters: []
  },
  onLoad: function () {
      this.subjectGet();
  },
  showTab: function(e) {
    this.setData({
      subjectTab: true      
    });
  },
  subjectGet: function(){
    var that = this;
    //获取科目列表
    wx.request({
      url: app.url.host + app.url.nodesUrl,
      method: 'GET',
      data: {
        cid: wx.getStorageSync('SubjectId')
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          var data = res.data, chaptersArr = [];
          if(data){
            for (var i = 0; i < data.length; i++) {
              chaptersArr.push({
                title: '第' + (i + 1) +'章 ' + data[i].Title,
                isSelected: i? false: true,
                chapterId: data[i].Id,
                historyNum: '--',
                totalNum: '--',
              });
            }
            that.setData({chapters: chaptersArr});
            that.progressGet(0);
          } else {
            wx.showToast({
              title: '没有章节',
              duration: 1500
            });
          }    
        } else {
          app.unauthorized(res.statusCode);
        }
      }
    });
  },
  subjectSelect: function(e) {
    var data = e.currentTarget.dataset.subject;
    this.setData({
      subjectTitle: data.title,
      subjectTab: false      
    });
    wx.setStorageSync("SubjectId", data.subjectId);
  },
  progressGet: function(index) {
    var chapter = this.data.chapters[index];
    this.historyNumGet(chapter, index);
    this.totalNumGet(chapter, index);
  },
  totalNumGet: function(chapter, index){
    console.log(this)
    var that = this;
    //获取题目列表
    wx.request({
      url: app.url.host + app.url.questionsUrl,
      type: 'GET',
      data: {
        nid: chapter.chapterId
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
            var totalData = res.data;
            if(totalData){
              //修改总题目数
              var chapters = that.data.chapters;
              chapters[index].totalNum = totalData.length;
              that.setData({chapters: chapters});
            }else{
              console.log('获取 '+ chapter.title +' 题目总数失败');
            }
        } else {
          app.unauthorized(res.statusCode);
        }
      }
    });	
  },
  historyNumGet: function(chapter, index){
    var that = this;
    //获取题目历史
    wx.request({
      url: app.url.host + app.url.progressesUrl,
      type: 'GET',
      data: {
        nid: chapter.chapterId,
        limit: 1,
        ctype: 1
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
            var progressData = res.data;
            if(progressData){
              var historyNum = 0;
              var questions = progressData[0].Questions.split(',');
              for(var i=0; i<questions.length; i++) {
                //id作为键，改变cardList状态
                if(questions[i].split(':')[1] != 0) historyNum ++;
              }

              //修改已完成题目数
              var chapters = that.data.chapters;
              chapters[index].historyNum = historyNum;
              that.setData({chapters: chapters});
            }else{
              console.log('获取 '+ chapter.title +' 进度失败');
            }
        } else {
          app.unauthorized(res.statusCode);
        }

      }
    });
  },
  listToggle: function(e) {
    var chapter = e.currentTarget.dataset.chapter;
    console.log(chapter);
    var chapterId = chapter.chapterId;
    var chapters = this.data.chapters;
    for(var i = 0; i < chapters.length; i++) {
      if(chapters[i].chapterId == chapterId) {
        chapters[i].isSelected = !chapters[i].isSelected;
        if(!Number.isInteger(chapters[i].historyNum) &&  !Number.isInteger(chapters[i].totalNum)) {
          this.progressGet(i);
        }
      } 
      
    }
    this.setData({chapters: chapters});
  }
})

