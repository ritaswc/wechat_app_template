const method = require('../../utils/methond.js');
var app = getApp()
Page({
  data:{
    action:'',
    position_type:'',
    search_history:[]
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
    
  },
  onReady:function(){
    // 页面渲染完成
    
  },
  onShow:function(){
    // 页面显示
    let _search_history;
    let has_select_temp = wx.getStorageSync('has_select') || [];     //选择的工作地点
    let has_post_tep = wx.getStorageSync('has_post') || [];          //选择的职位
    let search_history = wx.getStorageSync('search_history') || [];  //搜索的历史记录   
    
    _search_history = search_history.map(function(item){
      let city = item.city_arr.map(function(it){
          return it.city
      }).join()
      let post = item.post_arr.map(function(it){
          return it.post
      }).join()
      return {
        search_id:item.search_id,
        company_num:item.num,
        position_name:[item.user_import, city, post].join('+')
      }
    })
   
      this.setData({
        action:method.has_(has_select_temp,'city'),
        position_type:method.has_(has_post_tep,'post'),
        search_history:_search_history
      })
      wx.setStorage({
          key:"user_import",
          data:''
        })
  },
  onHide:function(){
    // 页面隐藏
    
  },
  onUnload:function(){
    // 页面关闭
    
  },
  user_import:(e)=>{
    let value = e.detail.value;
    wx.setStorage({
      key:"user_import",
      data:value
    })
  },
  search:(e)=>{
    let id = app.data.searchId;  // 全局数据
    try {
      let city_arr = wx.getStorageSync('has_select') || []; //用户选择的城市
      let post_arr = wx.getStorageSync('has_post') || [];   //用户选择的职位
      let user_import = wx.getStorageSync('user_import');   //用户输入的内容
      let city_id,post_id;
      if (city_arr =='' && post_arr == '' && user_import == '' ) {
          wx.showModal({
            showCancel:false,
            content: '请选择地点或职位名'
        })
      }else{

        city_id = city_arr.map(function(item){  //对应的职位id
            return item.id
        });
        
        post_id = post_arr.map(function(item){ //选择的城市id
            return item.id
        }); //用户选择的职位id
        wx.navigateTo({
          url:"../searchend/searchend?user_import="+user_import+"&city_id="+city_id+"&post_id="+post_id,
        })
      }
    } catch (e) {
      console.log(e);
    }
  },
  clear_his: function(){  //清空历史记录
      this.setData({
        search_history:''
      })
      wx.removeStorage({
        key: 'search_history',
        fail: function(res) {
          console.log('清除历史记录失败')
        } 
      })
  },
  history_search:function(e){
    let search_id = e.currentTarget.dataset.search_id;
    try {
        let  search_history = wx.getStorageSync('search_history')
        if (search_history) {
            
            let now_his=[];
            search_history.map(function(item){
                if( item.search_id == search_id ){
                  now_his.push(item)
                }
            })
            let city_arr = now_his[0].city_arr;
            let user_import = now_his[0].user_import;
            let post_arr = now_his[0].post_arr;
            let city_id,post_id;
            city_id = city_arr.map(function(item){
                return item.id
            }).join()
            post_id = post_arr.map(function(item){
                return item.id
            }).join()
            // console.log( user_import,city_id,post_id )
            wx.navigateTo({
                url:"../searchend/searchend?user_import="+user_import+"&city_id="+city_id+"&post_id="+post_id,
            })

        }
      } catch (e) {
      
    }
  }
})