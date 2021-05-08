// pages/objecTive/objecTive.js
let method = require('../../utils/util');
let all = require('../../utils/AELACTION').all;
let alljob = require('../../utils/AELPOSITION').all;
let upDataExperiesce = require('../../utils/wxrequire').upDataExperiesce;
Page({
  data:{
    begin_time:'请选择开始时间',
    end_time:'请选择结束时间',
    company_name:'',
    hangye:['酒店业','餐饮业','其他行业'],
    hangyelist:{
      id:1,
      val:'请选择所属行业'
    },
    zhiwei:{
      id:'',
      job:'请选择职位'
    },
    city:{
      "current_location":"请选择所在城市"
    },
    salaryArray:['不限','1000以下','1001-2000','2001-3000','3001-5000','4500-6000','6001-8000','8001-10000','10000以上'],
    salarylist:{
      id:'',
      val:'请选择职位薪资'
    },
    duty:'请输入岗位职责',
    Deeds:'请输入工作业绩',
    showLoading:false,
    disabled:true,
    tip:'信息不能为空',
    showTip:false,
    endTime:''
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    let work_id = options.id //工作经验的id;
    wx.setStorage({ //缓存工作经验id
        key:"work_id",
        data:work_id
    })
    let time = new Date();
    let endTime =  time.getFullYear()+'-'+time.getMonth() + 1 ;
    this.setData({
        endTime:endTime
    })
    let hangye = ['酒店业','餐饮业','其他行业'];
    let salaryArray = ['不限','1000以下','1001-2000','2001-3000','3001-5000','4500-6000','6001-8000','8001-10000','10000以上'];
    let all_work_exps = wx.getStorageSync('work_exps') || []  //所有的工作经验,已经在上个页面加载

    if( work_id ){  //如果有id 进入工作经验详情
        let now_work = all_work_exps.filter(function(item,index,arr){
            if( item.id == work_id ){
              return arr[index]
            }
        });
        let now = now_work[0]; //拿到当前的工作经验
        
        let begin_time = now.begin_year+'-'+now.begin_month; //开始时间
        let end_time = now.end_year == '至今'? now.end_year :now.end_year+now.end_month; //结束时间
        let company_industry = hangye[parseInt( now.company_industry )-1]; //所属行业
        let company_name_cn = now.company_name_cn; //企业名称
        let position_id = method.selectJob(alljob,now.position_id)   //职位
        let location = now.location;    //所在城市
        let salary = now.salary;  //职位薪资
        let job_responsibilities_cn = now.job_responsibilities_cn;  //岗位职责
        let job_performance_cn = now.job_performance_cn  //工作业绩

        // console.log(alljob, position_id )

        this.setData({
          begin_time:begin_time,
          end_time:end_time,
          'hangyelist.val':company_industry,
          company_name:company_name_cn,
          zhiwei:position_id,
          'salarylist.val':salaryArray[salary]
        })
        if( location != 'null' ){
          let city = method.selectAction(all, location );
          this.setData({
            city:city
          })
        }
        if( job_responsibilities_cn ){
          this.setData({
            duty:job_responsibilities_cn
          })
        }
        if( job_performance_cn ){
          this.setData({
            Deeds:job_performance_cn
          })
        }

        wx.setStorage({
            key:"now",
            data:now
        })
    }else{  //没有id  进入添加工作经验


    }
    
    

  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
    let status =  wx.getStorageSync('status_ss');
    let duty = wx.getStorageSync('duty'); //岗位职责
    let Deeds = wx.getStorageSync('Deeds'); //工作业绩

    let cityId = wx.getStorageSync('cityId');  //所在城市
    let current_location = method.selectAction(all, cityId ) //所在城市
    
    let experienceId = wx.getStorageSync('experienceId'); //选择职位
    let experience = method.selectJob(alljob,experienceId);
    // console.log( current_location )
    if( status && status == 'duty' ){
      if( duty != '' ){
          this.setData({
            duty:duty
          })
      }
    }else if( status && status == 'Deeds' ){
      if( Deeds != '' ){
          this.setData({
            Deeds:Deeds
          })
      }
    }
    if( experienceId ){
        this.setData({
          zhiwei:experience
        })
    }
    if( cityId ){
        this.setData({
          city:current_location
        })
    }

  },
  onHide:function(){
    // 页面隐藏
    
  },
  onUnload:function(){
    // 页面关闭
    wx.removeStorage({ key: 'experienceId',})
    wx.removeStorage({ key: 'duty',})
    wx.removeStorage({ key: 'Deeds',})
    wx.removeStorage({ key: 'status_ss',})
    wx.removeStorage({ key: 'work_id',}) //清除选择的职位id 避免冲突
    wx.removeStorage({ key: 'cityId',}) //清除选择的城市id 避免和基本信息里面的cityid冲突
  },
  begin_time:function(e){ //开始时间
    let val = e.detail.value;
    this.setData({
      begin_time:val,
      disabled:false
    })
  },
  end_time:function(e){ //结束时间
    let val = e.detail.value;
    this.setData({
      end_time:val,
      disabled:false
    })
  },
  selectHangye:function(e){ //所属行业
    let val = e.detail.value;
    let hangye=['酒店业','餐饮业','其他行业']
    this.setData({
      'hangyelist.id':val,
      'hangyelist.val':hangye[val],
      disabled:false
    })
  },
  changedisable:function(){ //企业名称
    this.setData({
      disabled:false
    })
  },
  selectSalary:function(e){ //职位薪资
    let val = e.detail.value;
    let salaryArray = ['不限','1000以下','1001-2000','2001-3000','3001-5000','4500-6000','6001-8000','8001-10000','10000以上'];
    this.setData({
      'salarylist.id':val,
      'salarylist.val':salaryArray[val],
      disabled:false
    })
  },
  sel_work_city:function(){  //这里用到了和基本信息中的现居地一样的方法,
    wx.navigateTo({
      url: '../workplace/workplace?status=basicInf'
    })
    this.setData({
      disabled:false
    })
  },
  select_zhiwei:function(){ //选择职位
    wx.navigateTo({
      url: '../worktype/worktype?status=experience'
    })
    this.setData({
      disabled:false
    })
  },
  select_zhize:function(e){
    this.setData({
      disabled:false
    })
  },
  
  formSubmit:function(e){  //工作经验提交;
  let data = e.detail.value;
  let _that = this;
  /*
  begin_year:'',
  begin_month:'',
  end_year:'',
  end_month:'',
  company_industry:'',行业
  company_name_cn:''名称
  position_id:'',职位
  location:'',城市
  salary:'',薪资
  job_responsibilities_cn:'' 职责
  job_performance_cn:'' 业绩
  */ 
      let now = wx.getStorageSync('now');
      let begin_year = data.begin_time.substring(0,4);
      let begin_month = data.begin_time.substring(5,7);
      let end_year = data.end_time.substring(0,4) ;
      let end_month = data.end_time.substring(5,7);
      let position_id =  wx.getStorageSync('experienceId');//职位
      let job_responsibilities_cn = wx.getStorageSync('duty');//岗位职责
      let job_performance_cn = wx.getStorageSync('Deeds');//工作业绩
      let location = wx.getStorageSync('cityId');//所在城市

      // console.log( end_year )
      let formList =Object.assign({}, data,position_id,job_responsibilities_cn,job_performance_cn,location);
      let work_id = wx.getStorageSync('work_id');
      
      // console.log( begin_month )
      let user_ticket = wx.getStorageSync('user_ticket');
      let changeWork,submitNewWork,look = true;
      changeWork = {  //用户修改过后的工作经验
        'id':work_id,
        'user_ticket':user_ticket,
        'begin_year':begin_year || now.begin_year,
        'begin_month':begin_month || now.begin_month,
        'end_year':end_year || now.end_year,
        'end_month':end_month || now.end_month || 0,
        'company_industry': data.company_industry == ''?now.company_industry:parseInt( data.company_industry )+1,
        'company_name_cn':data.company_name_cn,
        'position_id':position_id || now.position_id,
        'location':location || now.location,
        'salary':data.salary || now.salary,
        'job_responsibilities_cn':job_responsibilities_cn || now.job_responsibilities_cn,
        'job_performance_cn' :job_performance_cn || now.job_performance_cn
      }
      submitNewWork = { //新建的工作经验
        'user_ticket':user_ticket,
        'begin_year':begin_year,
        'begin_month':begin_month,
        'end_year':end_year,
        'end_month':end_month,
        'company_industry':parseInt( data.company_industry )+1,
        'company_name_cn':data.company_name_cn,
        'position_id':position_id,
        'location':location,
        'salary':data.salary,
        'job_responsibilities_cn':job_responsibilities_cn,
        'job_performance_cn':job_performance_cn
      }
      if( work_id ) {  //有ID 为修改工作经验
        // console.log( changeWork )
        if( changeWork.begin_year >changeWork.end_year  ){
            this.setData({
                tip:'开始时间不能大于结束时间',
                showTip:true
            });
            look = false;
        }
        if(changeWork.begin_year == changeWork.end_year && changeWork.begin_month >=changeWork.end_month ){
            this.setData({
                tip:'开始时间不能大于结束时间',
                showTip:true
            });
            look = false;
        }
        if( look ){
          this.setData({
              showTip:false
          });

          wx.showToast({
            title: '加载中...',
            icon: 'loading',
            duration: 20000000
          })

          upDataExperiesce( '/resume/set_work_exp',changeWork,function(res){  //修改工作经验
              let status = res.data.status;
              // console.log( res )
              if( status == 1 ){ //=======后端返回状态成功
                  wx.setStorage({  //添加修改工作经验状态,在显示工作经验的时候确定是否从新加载
                      key:"require_two",
                      data:'require_two'
                  })
                  wx.showToast({
                    title: '成功',
                    icon: 'success',
                    duration: 1000
                  })
                  wx.setStorageSync( "experience_text",'完整');
                  setTimeout(function(){
                    wx.hideToast();
                    wx.navigateBack({
                      delta: 1
                    })
                  },1000)
              }else{   //=======后端返回状态失败,异常处理
                wx.hideToast();
                let errdata = res.data.errMsg;
                wx.showModal({
                  title: '失败',
                  content: errdata,
                  showCancel:false,
                  success: function(res) {
                    if (res.confirm) {
                      _that.setData({
                        showLoading:false
                      })
                    }
                  }
                })
              }
          },function(res){
              console.log( res,'上传工作经验失败' )
              wx.showModal({
                title: '失败',
                content: '修改工作经验失败',
                showCancel:false,
              })
          })
        }

      }else{  //没有ID 为添加工作经验
        // console.log( submitNewWork )
        wx.showToast({
            title: '上传中...',
            icon: 'loading',
            duration: 20000000
        })
        if( submitNewWork.begin_year == '' || submitNewWork.end_year == '' || submitNewWork.company_industry == '' || submitNewWork.company_name_cn == '' || submitNewWork.position_id == ''){
            this.setData({
                tip:'带红色*为必填项',
                showTip:true
            });
             wx.hideToast();
            look = false;
        }
        if( submitNewWork.begin_year >submitNewWork.end_year  ){
            this.setData({
                tip:'开始时间不能大于结束时间',
                showTip:true
            });
             wx.hideToast();
            look = false;
        }
        if(submitNewWork.begin_year == submitNewWork.end_year && submitNewWork.begin_month >=submitNewWork.end_month ){
            this.setData({
                tip:'开始时间不能大于结束时间',
                showTip:true
            });
             wx.hideToast();
            look = false;
        }
        if( look ){
          this.setData({
              showTip:false
          });
          // console.log( submitNewWork )
          wx.showToast({
            title: '加载中...',
            icon: 'loading',
            duration: 20000000
          })
          upDataExperiesce( '/resume/set_work_exp',submitNewWork,function(res){  //上传新建的工作经验
              let status = res.data.status;
              if( status == 1 ){
                  wx.setStorage({
                      key:"require_two",
                      data:'require_two'
                  })
                  wx.showToast({
                    title: '成功',
                    icon: 'success',
                    duration: 1000
                  })
                  setTimeout(function(){
                    wx.hideToast();
                    wx.navigateBack({
                      delta: 1
                    })
                  },1000)
              }
          },function(res){

              console.log( res,'上传工作经验失败' )
              wx.showModal({
                title: '失败',
                content: '上传工作经验失败',
                showCancel:false,
              })
          })
        }
      }
  }
})