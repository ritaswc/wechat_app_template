//examtest.js
var app = getApp();
var WxParse = require('../../wxParse/wxParse.js');
Page({
  data: {
		singleScore: 0,
		totalScore: 100,
		score: 0,    
    curPage: 1, //0为题号页，1为答题页
    questionsData: {},
    progressData: {},
    questionsStatus: {},
    questionContent: '',
    analysisContent: '',
		curNum: '-',
		totalNum: '-', 
    finishedNum: 0,
    questionCid: 0,
    cType: 0, 
    cTypeContent:['未知', '单选', '多选'],
    choices: [],
    myChoices: [],
    myChoicesLetter: [],
    answers: [],
    lists: [],
    submitStatus: 0,
    isFavorite: false,
    isSubmitted: false,
		accuracy: 0,
		correctNum: 0,
		examPage: true	
  },
  onLoad: function (params) {
    this.getProgress();
  },
  getProgress: function() {
    var that = this;
    wx.request({
      url: app.url.host + app.url.progressesUrl,
      method: 'GET',
      data: {
        cid: wx.getStorageSync('SubjectId'),
        limit: 1
      },
      header: {
        Authorization: wx.getStorageSync('Authorization'),
        // 'content-type': 'application/x-www-form-urlencoded'
      },
      success: function(res){
        if(res.statusCode == '200') {
          var progressData = res.data;
          if (progressData) {
            //返回的是对象数组
            console.log('获取进度成功：');
            console.log(progressData[0]);
            that.progressHandler(progressData[0]);
          } else {
            var toastDelay = 1500;
            wx.showToast({
              title: '没有题目',
              duration: toastDelay
            });
            setTimeout(function(){
              wx.navigateBack({
                delta: 1, // 回退前 delta(默认为1) 页面
              })
            },toastDelay);
          }
        } else {
          app.unauthorized(res.statusCode);
        } //statusCode-else结束
      }
    });		
  },
  
  progressHandler: function(progressData){
    this.setData({
      progressData: progressData
    });
    this.questionsGet(progressData);
  },

  questionsGet: function(progressData){
    var that = this;
    if (progressData) {
      //判断上次考试是否已完成
      if (progressData.Status != 1){
        // this.setData({
        //   progressData: progressData
        // });
        var getData = {
          id: ''
        }; 
        var questions = progressData.Questions.split(',');
        var questionsStatus = this.data.questionsStatus;
        for(var i = 0; i < questions.length; i++){
          //id作为键，改变cardList状态
          questionsStatus[''+ questions[i].split(':')[0]] = questions[i].split(':')[1];
          getData.id += (questions[i].split(':')[0] + ',');
        }
        //去掉末尾逗号
        getData.id = getData.id.substring(0, getData.id.length-1);
        this.setData({
          progressData: progressData,
          questionsStatus: questionsStatus
        });
        //根据进度，获取指定id题目集合
        wx.request({
          url: app.url.host + app.url.questionsUrl,
          method: 'GET',
          data: getData,
          header: {
            Authorization: wx.getStorageSync('Authorization')
            // 'content-type': 'application/x-www-form-urlencoded'
          },
          success: function(res){
            if(res.statusCode == '200') {
              var questionList = res.data;
              if(questionList){
                that.setData({
                  questionsData: questionList
                });                
                that.questionsHandler(that.data.questionsData);
                //修改当前题号
                that.changeCurNum(that.data.questionsStatus);
              }
            } else {
              app.unauthorized(res.statusCode);
            } //statusCode-else结束
          }
        });
      }else{
        //上次status=1，已完成考试，新POST一个
        this.getRandomQuestion();	
      }
    }else{
      //进度为null，为第一次做题，获取随机题目
      this.getRandomQuestion();
    }
  },

  getRandomQuestion: function(){
    var that = this;
    //获取问题列表
    wx.request({
      url: app.url.host + app.url.questionsRandomUrl,
      method: 'GET',
      data: {
        cid: wx.getStorageSync('SubjectId'),
        limit: 2,
        field: 'id'
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          var questionsData = res.data;
					if(questionsData){
            that.setData({questionsData: questionsData})
						that.submitProgress(that.data.submitStatus);
						that.questionsHandler(questionsData);
						//修改当前题号
						that.changeCurNum(that.data.questionsStatus);
					}          
        }
      }
    });
  },

  questionsHandler: function(questionsData) {
    this.setData({
      totalNum: questionsData.length,
      submitStatus: 1,
      singleScore: this.data.totalScore / questionsData.length
    });
    this.statusHandler(questionsData);
  },

  statusHandler: function(questionsData){
    this.addCardList(questionsData); 
  },
  addCardList: function(questionsData){
    var lists = [];
    for (var i = 0; i < questionsData.length; i++) {
      lists.push({
        questionId: questionsData[i].Id,
        number: i+1,
        status: this.data.questionsStatus[''+questionsData[i].Id]
      });
    }
    this.setData({lists:lists});
  },
  changeCurNum: function(questionsStatus){
    this.setData({curNum:1});
    var i = 1;
    console.log(questionsStatus)
    for(var key in questionsStatus){
      //单个题目：0未完成
      if (questionsStatus[key] == 0) {
        this.setData({curNum:i});
        break; 
      }
      i++;
    }
    this.jumpQuestion(this.data.curNum, this.data.questionsData)
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
      questionCid: questionsData[index].Cid,
      questionCid: questionsData[index].Cid,
      submitStatus: 1,
			examPage: true
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
          var answerData = res.data;
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
            that.getAnswersHistory();
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
  getAnswersHistory: function(){
    var that = this;
    //根据Qid获取当前题目历史
    wx.request({
      url: app.url.host + app.url.answerHistoryUrl,
      method: 'GET',
      data: {
        qid : that.data.questionId
      },
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
          if(res.statusCode == '200') {
            var historyData = res.data;
            console.log('获取本题答题历史成功');
            console.log(historyData);
            that.showPreChoices(historyData);
          } else {
            app.unauthorized(res.statusCode);
          } //statusCode-else结束
      }
    });
  },  
  showPreChoices: function(historyData){
    //通过questionsStatus控制重复
    if (this.data.questionsStatus[this.data.questionId] == 0) return;
    //处理该题历史信息
    var choicesHistory = {};
    var tempHistory = historyData.Aid.split(',');
    for(var i = 0; i < tempHistory.length;i++) {
      choicesHistory[tempHistory[i].split(':')[0]] = tempHistory[i].split(':')[1];
    }
    var choices = this.data.choices;
    for(var i=0; i<choices.length; i++) {
      for (var key in choicesHistory) {
        if (choices[i].choiceId == key) {
          choices[i].isChosen = true;
        }
      }      
    }
    console.log('我的选择', choices)
    this.setData({choices: choices});
  },
  questionSwitch: function(e){ //list题目跳转
    var list = e.currentTarget.dataset.list;
    this.jumpQuestion(list.number, this.data.questionsData);
  },
  preQuestion: function(e){
    if(this.data.curNum > 1) {
      this.setData({
        curNum: this.data.curNum - 1
      });
      this.submitAnswer();
      this.jumpQuestion(this.data.curNum, this.data.questionsData);
    }
  },
  nextQuestion: function(e){
    if(this.data.curNum < this.data.totalNum) {
      this.setData({
        curNum: this.data.curNum + 1
      });
      this.submitAnswer();
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
    if(this.data.isSubmitted) return;
    var that = this;
    var userChosenNum = 0;
    var correctUserNum = 0;
    var answersNum = this.data.answers.length;    
    var submitData = {
      Nid: 0,
      Cid: parseInt(wx.getStorageSync('SubjectId')),
      Qid: this.data.questionId,
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
    wx.request({
      url: app.url.host + app.url.answerHistoryUrl,        
      method: 'POST',
      data: jsonData,
      header: {
        Authorization: wx.getStorageSync('Authorization')
      },
      success: function(res){
        if(res.statusCode == '200') {
          console.log('提交至答题历史成功');
        } //statusCode-else结束
      } //success结束
    });
    //提交progress
    that.submitProgress(this.data.submitStatus);

  },
  getScore: function(questionsStatus){
    var totalScore = this.data.totalScore;
    //利用questionsStatus判断
    for(var key in questionsStatus){
      if (questionsStatus[key] != 1) {
        totalScore -= this.data.singleScore;
      }
    }
    totalScore = Math.floor(totalScore);
    return totalScore;
  },  
  getFinishedNum: function(questionsStatus){
    var finishedNum = this.data.finishedNum;
    //利用questionsStatus判断
    for(var key in questionsStatus){
      if (questionsStatus[key] != 0) {
        finishedNum++;
      }
    }
    this.setData({finishedNum:finishedNum});
  },  
  submitExam: function(){
    var that = this;
    wx.showModal({
      title:'注意',
      content:'确定交卷',
      confirmColor: '#00a5cf',
      success:function(res) {
        if (res.confirm) {
          that.setData({submitStatus: 2});    
          that.submitAnswer();
        }
      }
    });
  },  
  submitProgress: function(submitStatus) {
    //0没有进度，1没有完成，2已完成
    var that = this;
    var ajaxType = 'POST';
    var questionsStatus = this.data.questionsStatus;
    if (submitStatus === 0) {
      for (var i = 0; i <  this.data.questionsData.length; i++) {
        //初始化status变量
        questionsStatus['' + this.data.questionsData[i].Id] = 0;
      }
    } 
    this.setData({questionsStatus:questionsStatus});   

    //提交progress
    var postdata = {
      Nid: 0,
      Cid: parseInt(wx.getStorageSync('SubjectId')),
      Ctype: 2, //考试进度
      Status: -1, //考试状态 1是完成考试 0是未开始考，-1是未完考试
      Questions: this.data.questionsStatus,
    };
    if(submitStatus !== 0){
      console.log(this.data.progressData)
      //并不是第一次提交，需要添加Id和Version，PUT方式更新进度
      postdata.Id = this.data.progressData.Id; //progressData的ID
      postdata.Version = this.data.progressData.Version; //progressData的Version
      postdata.TotalNumber = this.data.questionsData.length;
      ajaxType = 'PUT';
      if (submitStatus === 2) {
        //提交试卷，加上分数等数据
        postdata.Status = 1;		
        this.getFinishedNum(this.data.questionsStatus)		
        postdata.FinishNumber = this.data.finishedNum;		
        postdata.Score = this.getScore(this.data.questionsStatus); 
      }      
    }
    console.log('准备发送/更新进度信息');
    console.log(app.myJson(postdata),submitStatus);
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
          if (that.data.submitStatus === 2) {
						that.resultHandler();
					}
        } else {
          app.unauthorized(res.statusCode);
        } //statusCode-else结束
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
  resultHandler: function(){
    var that = this;
    var correctNum= this.data.correctNum;
    var questions = this.data.progressData.Questions.split(',');
    for(var i=0; i<questions.length; i++) {
      //遍历正确数目
      if(questions[i].split(':')[1] == 1){
        correctNum ++;
      };      
    }
    this.setData({
      isSubmitted: true,
      examPage: false,
      score: this.data.progressData.Score,
      accuracy: Math.floor(100 * correctNum / this.data.totalNum)    
    });
  },  
  pageSwitch: function() {
    this.setData({examPage: !this.data.examPage});
  }
})

