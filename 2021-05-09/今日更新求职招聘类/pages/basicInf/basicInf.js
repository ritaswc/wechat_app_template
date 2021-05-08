// pages/basicInf/basicInf.js
let requireMassage = require('../../utils/wxrequire').requireMassage;
let method = require('../../utils/util');
let all = require('../../utils/AELACTION').all;
const url = require('../../utils/requireurl.js').url;
var appInstance = getApp()
Page({
  data:{
    name:'',
    birthday:'',
    graduate:'',
    sexArray:['女','男'],
    sex:{},
    email:'',
    educationArray:["不限", "初中", "高中", "中技", "中专", "大专", "本科", "硕士", "博士"],
    education:{},
    current_location:{},
    work_year:'',
    mobile:'',
    showLoading:false,
    disabled:true
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
    })
  
    let user_ticket = wx.getStorageSync('user_ticket');
    let _that = this;
    requireMassage('/resume/get_base',user_ticket,function(res){
      let status = res.data.status;
      let data = res.data.data;
      // console.log( data )
      if( status == 1 ){
        let name = data.true_name_cn; //姓名
        let work_year
         data.work_year == 0 ? work_year = '': work_year = data.work_year; //工作年限
        let birthday = data.birthday || ''; //出生日期
        let email = data.email;  //email
        let gender = method.selectSex( data.gender ); //性别 2->男, 1->女, 0->'' ;
        let graduation_time = data.graduation_time || '';  //毕业时间
        let mobile;
         data.mobile == 0 ? mobile = '':mobile = data.mobile;  // 手机号码
        let education =method.secectDegree( data.degree );  //最高学历
        let cityID = data.current_location,current_location;//现居地
        if( cityID ){
             current_location = method.selectAction(all, cityID ) 
        }else{
              current_location = ''
        }
        
        
        // console.log(data )

        wx.setStorage({ //缓存性别
          key:"gender",
          data:data.gender
        })
        wx.setStorage({//缓存学历
          key:"degree",
          data:data.degree
        })
        wx.setStorage({//缓存地点
          key:"current_location",
          data:cityID
        })
        _that.setData({
          name:name,
          sex:gender,
          current_location:current_location,
          email:email,
          graduate:graduation_time,
          work_year:work_year,
          birthday:birthday,
          mobile:mobile,
          education:education
        })
        wx.hideToast();
      }else{
        console.log( '服务器返回状态错误' )
      }

    },function(){
      console.log("接口调用失败")
    })
  },
  onReady:function(){
    // 页面渲染完成

  },
  onShow:function(){
    // 页面显示
   let cityId = wx.getStorageSync('cityId');  //此id是有在workplace页面选择的城市id,若没有修改现居地,则为空.
   let current_location = method.selectAction(all, cityId ) //现居地
  //  console.log( current_location )
    if( cityId ){
      this.setData({
        current_location:current_location
      })
    }
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
    wx.removeStorage({  //
      key: 'cityId',
      success: function(res) {
        // console.log(res.data)
      } 
    })
  },
  changedisabled:function(){
    this.setData({
        disabled:false
    })
  },
  dataOfBirth:function(e){ //出身日期
    let birthday = e.detail.value;
    this.setData({
        birthday:birthday,
        disabled:false
    })
  },
  dataOfGraduate:function(e){
    this.setData({
      graduate:e.detail.value,
      disabled:false
    })
  },
  selectsex:function(e){ //选择性别
    let val = e.detail.value;
    let sexArray = ['女','男'];
    this.setData({
      'sex.val':sexArray[val],
      'sex.id':val+1,
      disabled:false
    })
  },
  selectEducation:function(e){  //选择学历
    let val = e.detail.value;
    let educationArray = ["不限", "初中", "高中", "中技", "中专", "大专", "本科", "硕士", "博士"];
    this.setData({
      'education.val':educationArray[val],
      'education.id':val,
      disabled:false
    })
  },
  selectLocation:function(){ //现居地
     this.setData({
        disabled:false
    })
    wx.navigateTo({
      url: '../workplace/workplace?status=basicInf',
    })
  },
  formSubmit:function(e){//====================================基本信息提交
    this.setData({
      showLoading:true
    });
    let gender = { //性别
      'gender': parseInt( e.detail.value.gender )+1 || wx.getStorageSync('gender') 
    };
    let degree = { //学历
      'degree':e.detail.value.degree || wx.getStorageSync('degree')
    };
    let formList = e.detail.value;
    let _that = this;
    let user_ticket = wx.getStorageSync('user_ticket');
    let current_location = {  //新的现居地 若修改就取修改后的cityId,没有修改就取当前的current_location;
        'current_location':wx.getStorageSync('cityId') || wx.getStorageSync('current_location') 
    };

    let SubmitData = Object.assign({},formList,gender,degree,{'user_ticket':user_ticket},current_location);
    // console.log( SubmitData )

    wx.request({
      url: url+'/resume/set_base',
      header: {
          'content-type': 'application/x-www-form-urlencoded'
      },
      data: SubmitData,
      method: 'POST',
      success: function(res){
        let status = res.data.status;
        // console.log( res )
        if( status == 1 ){ //==============保存基本信息正确
          wx.showToast({
            title: '成功',
            icon: 'success',
            duration: 1000
          })
          wx.setStorageSync( "info_text",'完整');
          setTimeout(function(){
              wx.navigateBack({
                delta: 1
              })
          },1000)
          _that.setData({
            showLoading:false
          });
        }else{         //==============保存基本信息错误
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
        
      },
      fail: function(res) {
        console.log( res )
      }
    })
  }
})