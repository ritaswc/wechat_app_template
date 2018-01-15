const api = 'https://mobile-interface.veryeast.cn';
function myRequire(user_ticket,type_,_that){
    wx.request({ //===========================================投递请求
        url: api+'/user/Delivery',
        header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
        data: {
            'user_ticket':user_ticket,
            'type':type_
        },
        method: 'POST',
        success: function(res){
            let status = res.data.status;
            let list = res.data.data;
            let delivers,look;
            // console.log(list ) 
            switch(type_){ //配置请求的类型
                case 1:
                    if( status == 1 ){
                        _that.setData({
                            deliver_success:list,
                            nodeliver:false,
                        })
                        if( list.length == 0 ){
                            _that.setData({
                                nodeliver:true,
                            })
                        }
                        wx.hideToast()
                        wx.stopPullDownRefresh()
                    }else{
                        wx.hideToast();
                        wx.stopPullDownRefresh()
                        let err = res.data.errMsg;
                        wx.showModal({
                        title: '失败',
                        showCancel:false,
                        content: err,
                        success: function(res) {
                            }
                        })
                    }
                break;
                case 2:
                    if( status == 1 ){
                        _that.setData({
                            have_look:list,
                            nolook:false,
                        })
                        if( list.length == 0 ){
                            _that.setData({
                                nolook:true,
                            })
                        }
                        wx.hideToast()
                        wx.stopPullDownRefresh()
                    }else{
                        wx.hideToast();
                        wx.stopPullDownRefresh()
                        let err = res.data.errMsg;
                        wx.showModal({
                        title: '失败',
                        showCancel:false,
                        content: err,
                        success: function(res) {
                            }
                        })
                    }
                break;
                case 3:
                    if( status == 1 ){
                        _that.setData({
                            interview:list,
                            nointerview:false,
                        })
                        if( list.length == 0 ){
                            _that.setData({
                                nointerview:true,
                            })
                        }
                        wx.hideToast()
                        wx.stopPullDownRefresh()
                    }else{
                        wx.hideToast();
                        wx.stopPullDownRefresh()
                        let err = res.data.errMsg;
                        wx.showModal({
                        title: '失败',
                        showCancel:false,
                        content: err,
                        success: function(res) {
                            }
                        })
                    }
                break;
                case 4:
                    if( status == 1 ){
                        _that.setData({
                            inappropriate:list,
                            noinappropriate:false,
                        })
                        if( list.length == 0 ){
                            _that.setData({
                                noinappropriate:true,
                            })
                        }
                        wx.hideToast()
                        wx.stopPullDownRefresh()
                    }else{
                        wx.hideToast();
                        wx.stopPullDownRefresh()
                        let err = res.data.errMsg;
                        wx.showModal({
                        title: '失败',
                        showCancel:false,
                        content: err,
                        success: function(res) {
                            }
                        })
                    }
                break;
            }
            
        },
        fail: function(res) {
           console.log(res ,'请求失败' )
        }
    })
}
function markClick(user_ticket,job_id,success,fail){
    wx.request({
      url: api+'/user/clickresume',
      header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
      data: {
          'user_ticket':user_ticket,
          'job_id':job_id
      },
      method: 'POST',
      success: function(res){
          success( res )
      },
      fail: function() {
        fail( res )
      }
    })
}
function requireMassage(url,user_ticket,success,fail){
    wx.request({
      url: 'https://mobile-interface.veryeast.cn'+url,
      header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
      data: {
          'user_ticket':user_ticket
      },
      method: 'POST',
      success: function(res){
          success( res )
      },
      fail: function(res) {
        fail( res )
      }
    })
}
function upDataExperiesce(url,data,success,fail){
    wx.request({
      url: 'https://mobile-interface.veryeast.cn'+url,
      header: {
            'content-type': 'application/x-www-form-urlencoded'
        },
      data: data,
      method: 'POST',
      success: function(res){
          success( res )
      },
      fail: function(res) {
        fail( res )
      }
    })
}
function downLoadExperience(_that){
    let user_ticket = wx.getStorageSync('user_ticket');
    requireMassage('/resume/get_work_exps',user_ticket,function(res){
      let status = res.data.status;
      let data = res.data.data;
      // console.log( data )
      data.map(function(item){
          return Object.assign(item,{end_year:item.end_year == '0'? '至今':item.end_year },{end_month:item.end_year == '0'?'':'-'+parseInt(item.end_month)})
      })
      if( status == 1 ){
        wx.setStorage({//缓存工作经验
          key:"work_exps",
          data:data
        })
        _that.setData({
          list:data
        })
        wx.hideToast();
      }else{
        console.log( res )
      }
    },function(){
      console.log("接口调用失败")
    })
}



module.exports = {
    myRequire:myRequire,
    markClick:markClick,
    requireMassage:requireMassage,
    upDataExperiesce:upDataExperiesce,
    downLoadExperience:downLoadExperience
}