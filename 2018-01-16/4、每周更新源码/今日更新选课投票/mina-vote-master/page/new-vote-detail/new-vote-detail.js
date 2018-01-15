
var app = getApp()

Page({
    data: {
        index: 0,
        date: '2016-09-01',
        time: '12:01',
        options: [],
        option: "",
        courseInfos: null,
        courseNames: null,
        courseIndex: 0,
        courseNameInput: null,
    },

    onShow: function() {
        app.getCourseInfos(this.updateCourseInfos)
    },

    // 更新课程信息
    updateCourseInfos: function(courseInfos) {
        var courseNames = new Array();
        for(var courseInfo in courseInfos) {
            courseNames.push(courseInfos[courseInfo].courseName)
        }
        this.setData({
            courseInfos: courseInfos,
            courseNames: courseNames
        })
    },
    // 课程选择
    bindCoursePickerChange: function(e) {
        this.setData({
            courseIndex: e.detail.value
        })
    },

    bindDateChange: function(e) {
        this.setData({
            date: e.detail.value
        })
    },
    bindTimeChange: function(e) {
        this.setData({
            time: e.detail.value
        })
    },

    bindKeyInput: function(e) {
        this.setData({
            option: e.detail.value
        })
    },

    // 添加评价
    addOption: function(e) {
        var newOptions = this.data.options;
        newOptions.push(this.data.option);
        console.log(newOptions[0]);
        this.setData({
            options: newOptions,
            option: ""
        })
    },
    // 添加投票
    newVote: function(e) {
        var courseObject = new Object()
        var courseNo = "xxx"
        var courseName = this.data.courseNameInput
        var courseType = "艺术课"
        var courseSocre = 9.5
        courseObject.courseNo =  courseNo
        courseObject.courseName =  courseName
        courseObject.courseType =  courseType
        courseObject.courseSocre =  courseSocre
        
        var evaluation = new Array();
        for(var option in this.data.options) {
            var optionObject = new Object()
            optionObject.name =  this.data.options[option]
            optionObject.data = 1
            evaluation.push(optionObject)
        }
        courseObject.evaluation = evaluation
        console.log(courseObject)
        var newCourseInfos = this.data.courseInfos
        newCourseInfos.push(courseObject)
        this.setData({
            courseInfos: newCourseInfos
        })

        wx.setStorage({
          key: 'courseInfos',
          data: newCourseInfos,
          success: function(res){
            // success
            console.log("投票添加成功")
            wx.showToast({
                title: '添加成功（可在首页查看）',
                icon: 'success',
                duration: 2000
            })
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    },
    // 添加投票
    bindCourseNameInput: function(e) {
        this.setData({
            courseNameInput: e.detail.value
        })
        console.log(this.data.courseNameInput)
    }
});