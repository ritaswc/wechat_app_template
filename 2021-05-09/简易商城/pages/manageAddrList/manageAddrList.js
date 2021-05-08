// pages/manageAddress/manageAddress.js
Page({
  data:{
    userAddrList:"",
    curIdx:''
  },
  defAddr:function(e){
    var that = this;
    var addr_id= e.currentTarget.dataset.id;
    var def_addr = e.currentTarget.dataset.def;
    var idx= e.currentTarget.dataset.index;
    console.log(addr_id)
    if(parseInt(def_addr) == 0){ 
      wx.getStorage({
        key:"session",
        success: function(res){
          var session=res.data.session;
          var url = 'https://shop.llzg.cn/weapp/showaddr.php?act=default&session_id='+session+'&address_id='+addr_id;
          wx.request({
            url: url,
            data:{},
            method: 'POST',
            header: {'content-type': 'application/x-www-form-urlencoded'},
            success: function(res) {
              console.log(res)
              if(parseInt(res.data) == 0){
                that.setData({
                  curIdx:idx
                })
              }
            }
          })
        },
        fail: function(res) {
          console.log("用户登录未登录，获取地址失败!")
        }
      })
    }  
  },
  add_addr:function(){
    wx.navigateTo({
      url:'../manageAddr/manageAddr?add_address=true'
    })
  },
  edit_addr:function(e){
    console.log(e.target.dataset.id)
    wx.navigateTo({
      url:'../manageAddr/manageAddr?address_id='+e.target.dataset.id
    })
  },
  onLoad:function(){
    var that = this;
    wx.setNavigationBarTitle({
      title: "地址管理"
    });
  },
  onShow:function(){
    var that = this;
    //获取session
    wx.getStorage({
      key:"session",
      success: function(res){
        var session=res.data.session;
        var url = 'https://shop.llzg.cn/weapp/showaddr.php?act=show&'+"session_id="+session;
        //验证session
        wx.request({
          url: url,
          data:{},
          method: 'POST',
          header: {'content-type': 'application/x-www-form-urlencoded'},
          success: function(res) {
            if(res.data.status == 0){
              console.log("用户没有保存收货地址");
            }else if(res.data.status == 1){
              var addr_arr = res.data.data;
              for(var i=0;i<addr_arr.length;i++){
                if(parseInt(addr_arr[i].def_addr) == 1){
                  that.setData({
                    curIdx:i
                  })
                }
              }
              that.setData({
                userAddrList:res.data.data
              });
            }else{
              console.log("服务器故障!!")
            }
          }
        })
      },
      fail: function(res) {
        console.log("用户登录未登录，获取地址失败!")
      }
    })
  }
})