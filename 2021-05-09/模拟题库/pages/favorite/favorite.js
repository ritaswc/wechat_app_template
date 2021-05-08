//favorite.js
var app = getApp();
var WxParse = require('../../wxParse/wxParse.js');
Page({
  data: {
    chapterId: 0,
    curPage: 1, //0为题号页，1为答题页
    questionsData: {},
    questionContent: '',
    analysisContent: '',
    showAnalysis: true,
		curNum: '-',
		totalNum: '-', 
    questionCid: 0,
    cType: 0, 
    cTypeContent:['未知', '单选', '多选'],
    choices: [],
    myChoices: [],
    myChoicesLetter: [],
    answers: [],
    lists: [],
    isFavorite: false,
  },
  onLoad: function (params) {
    console.log(params)
    this.setData({chapterId: parseInt(params.cid)});
    this.questionsGet();
  },
  questionsGet: function(){
    var that = this;
    //获取问题列表
    wx.request({
      url: app.url.host + app.url.favoritesUrl,
      method: 'GET',
      data: {
        
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          var questionsData = res.data;
          that.questionsHandler(questionsData);
        } else if(res.statusCode == '400' || res.statusCode == '401') {
          app.unauthorized(res.statusCode);
        } else {
          var toastDelay = 1500;
          wx.showToast({
            title: '没有收藏题目',
            duration: toastDelay
          });
          setTimeout(function(){
            wx.navigateBack({
              delta: 1, // 回退前 delta(默认为1) 页面
            })
          },toastDelay);
        }
      }
    });
  },
  questionsHandler: function(questionsData) {
    this.setData({
      questionsData: questionsData,
      totalNum: questionsData.length
    });
    this.addCardList(questionsData); 

    this.setData({curNum:1});
    this.jumpQuestion(this.data.curNum, this.data.questionsData)
  },
  addCardList: function(questionsData){
    var lists = [];
    for (var i = 0; i < questionsData.length; i++) {
      lists.push({
        questionId: questionsData[i].Id,
        number: i+1,
      });
    }
    this.setData({lists:lists});
  },
  jumpQuestion(curNum, questionsData) {
    var index = curNum - 1; //数组下标从0开始
    this.setData({
      curPage: 1,
      curNum: curNum,
      cType: questionsData[index].Ctype,
      // questionContent: questionsData[index].Title,
      // analysisContent: questionsData[index].Content,
      questionId: questionsData[index].Id,
      myChoices: [],
      myChoicesLetter: [],
      questionCid: questionsData[index].Cid,
      showAnalysis: true,
      // isSubmitted: false,
    });
    WxParse.wxParse('questionContent', 'html', questionsData[index].Title, this);
    WxParse.wxParse('analysisContent', 'html', questionsData[index].Content, this);      
    this.getChoices(this.data.questionId);
    this.getFavoriteMark(this.data.questionId);
  },
  getChoices: function(questionId){
    var that = this;
    this.setData({
      choices: [],
      answers: []
    });
    //请求该题号对应的答案
    wx.request({
      url: app.url.host + app.url.answersUrl,
      method: 'GET',
      data:{
        qid: questionId
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          var answerData = res.data, choiceLetter = ['A','B','C','D','E'];
          if(answerData){
            //填充答案
            var choicesArr = [];
            var answersArr = [];
            for (var i = 0; i < answerData.length; i++) {
              //记录正确的答案选项
              if(answerData[i].IsRight == 1){
                answersArr.push(app.toLetter(i));
              }
              choicesArr.push({
                content: app.toLetter(i) + '. ' + answerData[i].Content,
                isRight: answerData[i].IsRight,
                choiceId: answerData[i].Id,
                isChosen: false,
              });
            }
            that.setData({
              choices: choicesArr,
              answers: answersArr
            });
          }else{
            wx.showToast({
              title: '没有选项',
              duration: 1500
            });
          }
        } else {
          app.unauthorized(res.statusCode);
        } //statusCode-else结束
      }
    });
  },
  questionSwitch: function(e){
    var list = e.currentTarget.dataset.list;
    this.jumpQuestion(list.number, this.data.questionsData);
  },
  preQuestion: function(e){
    if(this.data.curNum > 1) {
      this.setData({
        curNum: this.data.curNum - 1
      });
      this.jumpQuestion(this.data.curNum, this.data.questionsData);
    }
  },
  nextQuestion: function(e){
    if(this.data.curNum < this.data.totalNum) {
      this.setData({
        curNum: this.data.curNum + 1
      });
      this.jumpQuestion(this.data.curNum, this.data.questionsData);
    } 
  },
  analysis: function(e) {
    this.setData({
      showAnalysis: !this.data.showAnalysis
    });
  },
  choiceChange: function(e) {
    this.setData({
      myChoices: e.detail.value,
      myChoicesLetter: []
    });
    var choiceLetter = ['A','B','C','D','E'];
    var choices = this.data.choices;
    var myChoices = this.data.myChoices;
    if(myChoices.length > 1) {
      myChoices.sort(function(a,b){
        return parseInt(a) - parseInt(b);
      });
    }
    var myChoicesLetter = [];
    //根据选项ID判断哪些答案被选择
    for(var i=0; i<choices.length; i++) {
      choices[i].isChosen = false;
      for(var j=0; j<myChoices.length; j++) {
        if(myChoices[j] == choices[i].choiceId) {
          myChoicesLetter.push(app.toLetter(i));
          choices[i].isChosen = true;
        }
      }
    }

    console.log('判断哪些答案被选择',myChoices,choices);
    this.setData({
      choices: choices,
      myChoices: myChoices, //已排序
      myChoicesLetter: myChoicesLetter
    });

  },
  submitAnswer: function(e) {
    var that = this;
    var submitData = {
      Nid: this.data.chapterId,
      Cid: parseInt(wx.getStorageSync('SubjectId')),
      Qid: this.data.questionsData[this.data.curNum-1].Id,
      Aid: {},
      IsRight: 1,
      Ctype: this.data.cType
    };
    var choices = this.data.choices,
        myChoices = this.data.myChoices;
    console.log('点击提交时',myChoices,choices);


    
    //遍历选项
    var correctNum = 0;
    var answersNum = this.data.answers.length;
    for(var i=0; i<choices.length; i++) {
				if(choices[i].isChosen){
					//记录Id和答案IsRight
					submitData.Aid[choices[i].choiceId] = parseInt(choices[i].isRight);
				}
				if(submitData.Ctype == 1 || submitData.Ctype == 2){
					if(choices[i].isRight == -1){
						if(choices[i].isChosen){
							submitData.IsRight = -1;	
						}
					}else{
						if(choices[i].isChosen){
							correctNum++;
						}
					}		
				}
    }
    if(correctNum !== answersNum){
      submitData.IsRight = -1;
    }
    console.log('遍历选项后submitData：', submitData);

    if(JSON.stringify(submitData.Aid) !== '{}'){
      //修改status
      var questionsStatus = that.data.questionsStatus;
      var lists = that.data.lists;
      questionsStatus[''+submitData.Qid] = submitData.IsRight;
      lists[this.data.curNum - 1].status = submitData.IsRight;
      this.setData({
        questionsStatus: questionsStatus,
        lists: lists
      });

      // 提交至答题历史
      var jsonData = app.myJson(submitData);
      console.log('myJson：',jsonData);
      wx.request({
        url: app.url.host + app.url.answerHistoryUrl,        
        method: 'POST',
        data: jsonData,
        header: {
          Authorization: wx.getStorageSync('Authorization')
        },
        success: function(res){
          if(res.statusCode == '200') {
            that.setData({
              isSubmitted: true, //修改已提交状态
              showAnalysis: true //显示解析和答案
            });
          } else {
            app.unauthorized(res.statusCode);
          } //statusCode-else结束
        } //success结束
      });
      //提交progress
      that.submitProgress(false);
    }else{
      wx.showToast({
        title: '请先进行选择',
        //icon: 'success',
        duration: 1500
      })
    }
  }, //submitAnswer()结束
  submitProgress: function(firstSubmit) {
    var that = this;
    var ajaxType = 'POST';
    //提交progress
    var postdata = {
      Nid: 0 || this.data.chapterId,
      Cid: 0 || parseInt(wx.getStorageSync('SubjectId')),
      Ctype: 1, //章节进度
      Status: 0, //考试状态 1是完成考试 0是未开始考，-1是未完考试
      Questions: this.data.questionsStatus,
    };
    if(!firstSubmit){
      //并不是第一次提交，需要添加Id和Version
      postdata.Id = this.data.progressData.Id; //progressData的ID
      postdata.Status = this.judgeExamStatus(this.data.questionsStatus);
      postdata.Version = this.data.progressData.Version; //progressData的Version
      ajaxType = 'PUT';
    }
    console.log('准备发送进度信息');
    console.log(app.myJson(postdata));
    wx.request({
      url: app.url.host + app.url.progressUrl,
      method: ajaxType,
      data: app.myJson(postdata),
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          var newProgressData = res.data;
          console.log('发送进度信息成功');
          console.log(newProgressData);
          that.setData({progressData: newProgressData});
        } else {
          app.unauthorized(res.statusCode);
        } //statusCode-else结束
      },
      error: function(data){
        console.log('发送进度信息失败');
      }
    });
  },
  judgeExamStatus: function(questionsStatus){
    //考试状态 1是完成考试 0是未开始考，-1是未完考试
    var status = 1;
    for (var key in questionsStatus) {
      if (questionsStatus[key] == 0) {
        status = -1;
        break;
      }
    }
    return status;
  },
  getFavoriteMark: function(questionId){
    var that = this;
    //请求该题号对应的收藏状态
    wx.request({
      url: app.url.host + app.url.favoriteUrl,
      method: 'GET',
      data: {
        id: that.data.questionId,
        cid: wx.getStorageSync('SubjectId')
      },
      header:{
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          var favoriteData = res.data;
          if(favoriteData){
            that.setData({isFavorite: favoriteData.IsQuestionMark});
          }
        } else {
          app.unauthorized(res.statusCode);
        } //statusCode-else结束
      }
    });
  },
  favorite: function(e){
    var that = this;
    var ajaxType = 'POST';
    var postFavData = {
      id: this.data.questionId,
    };
    if (this.data.isFavorite) {
      ajaxType = 'DELETE'; //已收藏
    } else {
      postFavData.cid = this.data.questionCid; //未收藏
    }
    //收藏功能
    wx.request({
      url: app.url.host + app.url.favoriteUrl,
      method: ajaxType,
      data: postFavData,
      header:{
        Authorization: wx.getStorageSync('Authorization'),
        'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res) {
        console.log(res)
        if(res.statusCode === 200) {
          that.setData({isFavorite: !that.data.isFavorite});
        }
        wx.showToast({
          title:res.data.Message
        });
      }
    });
  },
})

