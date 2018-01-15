
var qcloud = require('../../vendor/qcloud-weapp-client-sdk/index');
var config = require('../../config');
var showBusy = text => wx.showToast({
    title: text,
    icon: 'loading',
    duration: 10000
});
var showSuccess = text => wx.showToast({
    title: text,
    icon: 'success'
});
var showModel = (title, content) => {
    wx.hideToast();

    wx.showModal({
        title,
        content: JSON.stringify(content),
        showCancel: false
    });
};

var cfg_js=require('../../utils/cfg.js');
var util_js=require('../../utils/util.js');
var formContent={
      name:'',
      tel:'',
      adds:'',
      location:''
};

Page({

    data: {
        loginUrl: config.service.loginUrl,
        requestUrl: config.service.addOrderUrl,
        tunnelUrl: config.service.tunnelUrl,
        tunnelStatus: 'closed',
        tunnelStatusText: {
            closed: '已关闭',
            connecting: '正在连接...',
            connected: '已连接'
        },


        bannerImg:cfg_js.uri.bannerImg,
        senManImg:cfg_js.uri.senManImg,
        phoneImg:cfg_js.uri.phoneImg,
        addsImg:cfg_js.uri.addsImg,
        val:''
    },
    doLogin() {
        //showBusy('正在登录');
        qcloud.login({
            success(result) {
                //showSuccess('登录成功');
                //console.log('登录成功', result);
            },

            fail(error) {
               // showModel('登录失败', error);
               // console.log('登录失败', error);
            }
        });
    },
    onLoad:function(){
        util_js.getLoaction(function(res){
            formContent['location']=res;
        },function(res){
            formContent['location']=0;
        })
         wx.clearStorageSync()
         this.doLogin();
    },
    
  nameChange:function(e){
    formContent['name']=e.detail.value;
    this.setData({
      name_err:'#e5e5e5',
      err_msg:''
    });
  },
  telChange:function(e){
    formContent['tel']=e.detail.value;
    this.setData({
       tel_err:'#e5e5e5',
       err_msg:'',
       ipt_tel:'#000000'
    });
  },
  addsChange:function(e){
    formContent['adds']=e.detail.value;
    this.setData({
        adds_err:'#e5e5e5',
        err_msg:''
    });

  },
  

  doRequest() {
        var that = this; 
        if(!that.inputCheck()){
            return false;
        }
        showBusy('正在发送');
        qcloud.request({
            // 要请求的地址
            method: 'POST',
            header: {'content-type': 'application/x-www-form-urlencoded'},
            url: this.data.requestUrl,
            data:{
                user_name: formContent.name,
                phone:formContent.tel,
                address:formContent.adds,
                location:formContent.location.latitude+','+formContent.location.longitude
            },
            // 请求之前是否登陆，如果该项指定为 true，会在请求之前进行登录
            login: false,
            
            success(result) {
                //showModel('下单失败');
                showSuccess('下单成功！当前正在派件中');
                that.setData({val:''});
                formContent={
                    name:'',
                    tel:'',
                    adds:'',
                    location:''
                };
                console.log('request success', result);
            },
            fail(error) {
               showModel('下单失败', error);
            }
        });
  },

  //表单验证
  inputCheck:function(){
      var that=this;
      if(formContent.name==""){
          this.setData({
            name_err:'red',
            err_msg:'请输入姓名'
          });
          return false;
      }else if(formContent.tel==""){
           this.setData({
             tel_err:'red',
             err_msg:'请输入手机号'
           });
           return false;
      }else if(formContent.tel!=""&&!(/^1[34578]\d{9}$/.test(formContent.tel))){
          this.setData({
             ipt_tel:'red',
             tel_err:'red',
             err_msg:'请输入正确的11位手机号码'
           });
           return false;
      }else if(formContent.adds==""){
           this.setData({
             adds_err:'red',
             err_msg:'请输入地址'
          });
           return false;
      }else{
          return true;
      }
  },
    onShareAppMessage: function () {
        return {
        title: '风速快递',
        desc: '快递',
        path: '/page/index'
        }
    }



    
});
