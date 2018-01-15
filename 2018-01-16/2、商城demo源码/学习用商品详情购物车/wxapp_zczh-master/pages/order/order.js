var app = getApp();
Page({
  data:{  
        good:[],//商品
        goodUrl:'/m.php?g=api&m=wxshop&a=goods&goods_id=',//商品
        formData:'',
        index:0,
        num:1,
        buy:{},
        orderUrl:'m.php?g=api&m=wxshop&a=send_order',//提交订单
        //提示框参数
        toast1Hidden:true,  
        modalHidden: true,  
        modalHidden2: true,  
        notice_str: '',
        serverSrc:app.globalData.serverUrl,//服务器地址
        order:[],
        address:{},//默认地址         
  },
  onShow: function () {
    var that = this
    if(app.globalData.order_k){
        //发送请求获取订单
      wx.request({
        url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=my_order&limit=10&',
        data: {
          sess_id:wx.getStorageSync(
                 'sess_id'
                ),
           type:"no_handle"  
        },
        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
        // header: {}, // 设置请求的 header
        success: function(res){
          // 遍历所有订单，通过order_id获取当前订单
          console.log(res.data.data)
          var _data=res.data.data;
          for(var i=0;i<_data.length;i++){
            console.log(_data[i])
            if(_data[i].order_id==app.globalData.order_id){
                app.globalData.choose_address={
                  address:_data[i].address,
                  phone:_data[i].phone,
                  receiver: _data[i].user,
                  num:_data[i].goods_num*1,
                }
            }
          }
          that.setData({
            num:app.globalData.choose_address.num,
            address:app.globalData.choose_address//选择的地址代替更改默认地址
          })
          app.globalData.order_k=false;//改为默认
        },
        fail: function() {
          // fail
        },
        complete: function() {
          // complete
        }
      })
    }else{
      that.setData({
        address:app.globalData.choose_address//选择的地址代替更改默认地址
      })
    }
    console.log(app.globalData.choose_address)
    //获取商品信息
    console.log(app.globalData.requestId+"---"+app.globalData.choose_address)
    wx.request({
      url: app.globalData.serverUrl+that.data.goodUrl+app.globalData.requestId, //详情页数据
      header: {
          'content-type': 'application/json'
      },
      success: function(res) {
        console.log(res.data)
        that.setData({
          good:res.data.data
        })
        console.log(that.data.good)   
      }
    })
    //获取订单信息
    wx.request({
      url: app.globalData.serverUrl+'m.php?g=api&m=wxshop&a=my_order&limit=10', //购物车
      header: {
          'content-type': 'application/json'
      },
      data:{
        sess_id:wx.getStorageSync(
                 'sess_id'
                )
      },
      success: function(res) {
        console.log(res.data)
      }
    })
    //调用应用实例的方法获取全局数据
    app.getUserInfo(function(userInfo){
      //更新数据
      that.setData({
        userInfo:userInfo
      })
    })
  },
  // 数量增加
  add:function(){
     var nums=this.data.num
     nums+=1
     this.setData({
     num:nums
     })   
    // let num=this.data.num
    // num+=1 
  },
  sub:function(){
     var nums=this.data.num
     nums-=1;
     if(nums==0){
      this.setData({
        toast1Hidden:false, 
        notice_str:'数量不能小于1', 
      })  
      nums=1
     }
     this.setData({
     num:nums
     })   
    // let num=this.data.num
    // num+=1 
  },
  toast1Change:function(e){  
    this.setData({toast1Hidden:true});  
  },  
  //弹出确认框  
  modalTap: function(e) {  
    this.setData({  
      modalHidden: false  
    })  
  },  
  confirm_one: function(e) {  
    console.log(e);
    var that=this
    //确认---->提交订单，发送到服务器
    wx.request({  
        url:app.globalData.serverUrl+that.data.orderUrl+'&sess_id='+wx.getStorageSync('sess_id'),
        data:{
          phone:that.data.formData.number,
          user:that.data.formData.name,
          address:that.data.formData.address,
          goods_id:app.globalData.requestId,
          num:that.data.num,
          extra:that.data.formData.txt
        }, 
        method: 'POST', 
        header: {  
            'Content-Type': "application/x-www-form-urlencoded" 
        },  
        success: function(res) {  
            console.log("提交订单成功")
            console.log(res.data)
            
        }  
      })    
    this.setData({  
      modalHidden: true,  
      toast1Hidden:false,  
      notice_str: '提交成功!'  
    });  
  },  
  cancel_one: function(e) {  
    console.log(e);  
    this.setData({  
      modalHidden: true,  
      toast1Hidden:false,  
      notice_str: '取消成功'  
    });  
  },
  //跳转个人中心
  goPer:function(){
    wx.navigateTo({
      url: '../personal/personal',
      success: function(res){
        // success
      }
    })
  },
  //去选择地址
  goAdr:function(){
    wx.navigateTo({
      url: '../address/address',
      success: function(res){
        // success
      },
      fail: function() {
        // fail
      },
      complete: function() {
        // complete
      }
    })
  },   
  //弹出提示框  
  // modalTap2: function(e) {  
  //   this.setData({  
  //     modalHidden2: false  
  //   })  
  // },  
  // modalChange2: function(e) {  
  //   this.setData({  
  //     modalHidden2: true  
  //   })  
  // },  
   //提交订单
  formSubmit:function(e){
    var formData = e.detail.value
    var that=this
    that.setData({ 
      formData:formData
    })
    console.log(typeof formData.number)
    console.log(that.data.num+"----"+formData)
    // app.requestId
    if(formData.number=="" || formData.name=="" || formData.address==""){//电话、姓名、地址有一个为空
        that.setData({
          toast1Hidden:false, 
          notice_str:'请填写完整表单信息', 
        })  
    }else{//电话、姓名、地址都不为空
      //弹出提示框
      that.modalTap(); 
    }  
  },
  // formReset: function() {  
  //   console.log('form发生了reset事件');  
  //   this.modalTap2();  
  // }, 
  // bindPickerChange: function(e) {  
  //   console.log('picker发送选择改变，携带值为', e.detail.value)  
  //   this.setData({  
  //     index: e.detail.value  
  //   })  
  // },  
  // onLoad:function(options){  
  //   // 页面初始化 options为页面跳转所带来的参数  
  // },
  back:function(){
    wx.navigateBack({
      delta: 2
    })
  },
  //分享
  onShareAppMessage: function () {
    return {
      title: '商城',
      desc: '订单',
      path: 'pages/order/order'
    }
  }
})