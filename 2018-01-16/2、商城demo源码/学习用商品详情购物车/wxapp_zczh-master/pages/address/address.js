var app = getApp();
Page({
    data:{
        address:[],
       
    },
    // onShow:function(){
    //     // console.log(this.data.address)
    //     // console.log(app.globalData.address)
    //     if(!app.globalData.address.address){
    //         app.globalData.address.address=this.data.address
    //     }
    //     console.log(app.globalData.address)
    //     this.setData({
    //         address:app.globalData.address.address
    //     })
        
    // },
    onShow:function(){
        var that=this;
        //发送请求获取地址信息
        // console.log(wx.getStorageSync(
        //          'sess_id'
        //         ))
        wx.request({
          url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=get_address',
          data: {
               sess_id:wx.getStorageSync(
                 'sess_id'
                )
          },
          method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
          // header: {}, // 设置请求的 header
          success: function(res){
            // success
            // console.log(res.data.data)
            that.setData({
                address:res.data.data.reverse()
            })
          },
          fail: function() {
            // fail
            console.log("请求失败")
            
          },
          complete: function() {
            // complete
          }
        })
    },
    //编辑
    toEdit:function(e){
        wx.navigateTo({
            url: '../editAddress/editAddress',
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
        var id=e.currentTarget.id;
        app.globalData.edit_type=id;
        var that=this;
        var old_address=that.data.address;
        // console.log(e.currentTarget.id)
        for(var i=0;i<old_address.length;i++){
            if(old_address[i].id==e.currentTarget.id){
                app.globalData.old_address=old_address[i]//待修改
            }
                
        };
        console.log(app.globalData.edit_type)
        // app.globalData.address.address=this.data.address;
         console.log(id)
        
    },
    //单选框切换
    radioChange:function(e){
        var that=this;
        var address_data=that.data.address;
        // console.log(e.currentTarget.id)
        for(var i=0;i<address_data.length;i++){
            if(address_data[i].id==e.currentTarget.id){
                app.globalData.choose_address=address_data[i]
            }
        };
        console.log(app.globalData.choose_address)
        // console.log(this.data.address)
        wx.navigateBack({
            delta: 1
        })
    },
    //删除
    del:function(e){
        var that=this;
        var id=e.currentTarget.id;
        wx.request({
          url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=del_address',
          data: {
              id:id,
              sess_id:wx.getStorageSync(
                     'sess_id'
                )
            },
          method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
          // header: {}, // 设置请求的 header
          success: function(res){
            // 删除成功提示
            wx.showToast({
                title: '删除成功',
                icon: 'success',
                duration: 2000
            });           
            console.log("删除了一个地址")
                    wx.request({
                        url: 'https://www.520hhy.cn/m.php?g=api&m=wxshop&a=get_address',
                        data: {
                            sess_id:wx.getStorageSync(
                                'sess_id'
                                )
                        },
                        method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
                        // header: {}, // 设置请求的 header
                        success: function(res){
                            // success
                            // console.log(res.data.data)
                            that.setData({
                                address:res.data.data.reverse()
                            })
                        },
                        fail: function() {
                            // fail
                            console.log("请求失败")
                            
                        },
                        complete: function() {
                            // complete
                        }
                    })
          },
          fail: function() {
            // fail
          },
          complete: function() {
            // complete
          }
        })
    }   
})