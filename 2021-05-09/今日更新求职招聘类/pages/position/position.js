// pages/position/position.js
let WxParse = require('../../wxParse/wxParse.js');
let upDataExperiesce = require('../../utils/wxrequire').upDataExperiesce;
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    current:0,
    position:{},
    biaoqian:[],
    otherList:[],
    c_user:[],
    c_list:[],
    xingji:'',
    is_applied:false,
    showloding:false,
    is_favorited:'image/_06.png',
    has_favorited:''
  },
  onLoad:function(options){
    wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
    })
    let job_id = options.job_id;//职位详情id "745972" 
    let c_userid = options.c_userid//企业详情id
    let _that = this;
    let user_ticket = wx.getStorageSync('user_ticket');
    wx.request({ //职位详情
      url: url+'/job/detail',
      data: {'job_id':job_id,'user_ticket':user_ticket},
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      method: 'POST',
      success: function(res){
        let status = res.data.status;
       
        if( status == 1 ){
          let data = res.data.data;
          let decription = data.decription;
          // console.log( data )

          WxParse.wxParse('article', 'html', decription, _that,);
          let otherList = data.list.map(function(item){
            return Object.assign({},item,{update_time:item.update_time.substring(0,10)})
          });

          wx.setNavigationBarTitle({
            title: data.job_name
          })
          _that.setData({
            position : data,
            otherList:otherList,
            decription:decription,
            has_favorited:data.is_favorited
          })
          let is_applied = data.is_applied;
          let is_favorited = data.is_favorited;
          if( is_applied == 1 ){ //职位是否申请
            _that.setData({
                is_applied:true
            })
          }
          if( is_favorited ==1 ){ //职位是否收藏
              _that.setData({
                is_favorited:'image/_09.png'
            })
          }

          wx.hideToast();
        }else{
          console.log(res)
        }

      },
      fail: function() {
        console.log("请求职位详情失败")
      }
    });


    wx.request({//企业详情
      url: url+'/job/company_detail',
      header: {
        'content-type': 'application/x-www-form-urlencoded'
      },
      data: {'company_id':c_userid},
      method: 'POST',
      success: function(res){
        let status = res.data.status;
        
        if( status == 1 ){
          let data = res.data.data;
          let description = data.description;
          // console.log( data )
          WxParse.wxParse('description', 'html', description, _that);

          let biaoqian = data.label;
          let c_list = data.c_list;
          let xingji = data.star_level,xingxing;
          // console.log( data )
          _that.setData({
              biaoqian : biaoqian,
              c_user:data
          })
          switch (xingji){
              case '五星' :
                xingxing=5;
                break;
              case '准五星':
                xingxing =5;
                break;
              case '四星':
                xingxing=4;
                break;
              case '三星':
                xingxing=3;
                break;
              case '二星':
                xingxing=2;
                break;
              case '一星':
                xingxing=1;
              break;
          }
          _that.setData({
              xingji:xingxing,
              c_list:c_list
          })

        }else{
          console.log("企业详情接口挂了")
        }
      },
      fail: function() {
        console.log("请求企业详情失败")
      }
    })



  },
  onReady:function(){
    // 页面渲染完成
  },
  onShow:function(){
    // 页面显示
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  switchSlider:function(e){
    let current = e.currentTarget.dataset.index;
    this.setData({
      current:current
    })
  },
  changeSlider:function(e){
    let current = e.detail.current;
    this.setData({
        current:current
    })
  },
  goZhihiWeiList:function(e){
      let c_userid = e.currentTarget.dataset.c_userid;
      let job_id = e.currentTarget.dataset.job_id;
      wx.redirectTo({
        url: '../position/position?job_id='+job_id+'&c_userid='+c_userid,
        fail:function(){
          console.log("go公司详情页失败")
        }
      })
  },
  call:function(e){ //给企业打电话
    let phone_num = e.currentTarget.dataset.number;
    wx.makePhoneCall({
      phoneNumber: phone_num,
      fail:function(){
        console.log("调用打电话接口失败")
      }
    })
  },
  site:function(e){//查看企业地图
     let latitude = e.currentTarget.dataset.latitude;
     let longitude = e.currentTarget.dataset.longitude;
      wx.navigateTo({
        url: '../map/map?latitude='+latitude+'&longitude='+longitude
      })
  },
  positionCollect:function(e){ //=============================职位收藏按钮
    let job_id = e.currentTarget.dataset.job_id;
    let _that = this;
    let user_ticket = wx.getStorageSync('user_ticket');
    let is_applied = e.currentTarget.dataset.is_applied; //0没有收藏; 1收藏
    let data={
      'user_ticket':user_ticket,
      'job_id':job_id
    }
    wx.showToast({
      title: '加载中...',
      icon: 'loading',
      duration: 20000000
    })
    if( is_applied == 0 ){ //============去收藏
        
        upDataExperiesce('/user/add_favorite_job',data,function(res){
            // console.log( res )
            let status = res.data.status;
            if( status == 1 ){
                _that.setData({
                    is_favorited:'image/_09.png',
                    has_favorited:1
                })
                
                wx.showToast({
                  title: '收藏成功',
                  icon: 'success',
                  duration: 1000
                })
            }else{
                wx.hideToast();
                let err = res.data.errMsg;
                wx.showModal({
                  title: '失败',
                  showCancel:false,
                  content: err,
                  success: function(res) {
                  }
                })
            }
        },function(res){
            console.log( res ,'接口调用失败' )
        })
    }else if( is_applied == 1 ){ //========取消收藏
        
        upDataExperiesce('/user/delete_favorite_job',data,function(res){
            // console.log( res )
            let status = res.data.status;
            if( status == 1 ){
                wx.showToast({
                  title: '取消收藏',
                  icon: 'success',
                  duration: 1000
                })
                _that.setData({
                    is_favorited:'image/_06.png',
                    has_favorited:0
                })
            }else{
                wx.hideToast();
                let err = res.data.errMsg;
                wx.showModal({
                  title: '失败',
                  showCancel:false,
                  content: err,
                  success: function(res) {
                  }
                })
            }
        },function(res){
            console.log( res ,'接口调用失败' )
        })
    }
  },
  positionApply:function(e){ //=================================立即申请按钮
    this.setData({
      showloding:true
    })
    let job_id = e.currentTarget.dataset.job_id;
    let user_ticket = wx.getStorageSync('user_ticket');
    let _that = this;
    let client_id ;
     wx.getSystemInfo({
        success: function(res) {
          if( res.platform == 'ios' ){
              client_id = 2;
          }else{
              client_id = 1;
          }
        }
      })
    let data={
      'user_ticket':user_ticket,
      'job_id':job_id,
      'client_id':client_id
    }
    // console.log( data );

    upDataExperiesce('/user/apply',data,function(res){
      // console.log( res );
      let status = res.data.status;
      if( status == 1 ){
        wx.showToast({
          title: '申请成功',
          icon: 'success',
          duration: 1000
        })
        _that.setData({
          showloding:false,
          is_applied:true
        })
      }else{
        let errdata = res.data.errMsg;
        // console.log( errdata )
        wx.showModal({
          title: '失败',
          content: errdata,
          showCancel:false,
          success: function(res) {
            if (res.confirm) {
              _that.setData({
                showloding:false
              })
            }
          }
        })
      }
    },function(res){
      console.log( res,'接口调用失败' )
    })
  }
})