// pages/searchend/searchend.js
const more = require('../../utils/MORE_ACTION.js');
const select = require('../../utils/util.js');
const url = require('../../utils/requireurl.js').url;
Page({
  data:{
    workYear:'不限',
    treatment_s:'不限',
    yearId:0,
    treatmentId:0,
    showYear:false,
    showTreatment:false,
    showMore:false,
    moreId:'0100',
    listId:'0101',
    contant:'',
    year:[
      {
        "year":"不限",
        "id":"0"
      },
      {
        "year":"1年以上",
        "id":"1"
      },
      {
        "year":"2年以上",
        "id":"2"
      },
      {
        "year":"3年以上",
        "id":"3"
      },
      {
        "year":"4年以上",
        "id":"4"
      },
      {
        "year":"5年以上",
  
        "id":"5"
      },
      {
        "year":"10年以上",
        "id":"6"
      }
    ],
    treatment: [
      {
        "treatment":"2000以下",
        "id":"0"
      },{
        "treatment":"2000-3000",
        "id":"1"
      },{
        "treatment":"3000-4000",
        "id":"2"
      },{
        "treatment":"4000-5000",
        "id":"3"
      },{
        "treatment":"5000-6000",
        "id":"4"
      },{
        "treatment":"6000-8000",
        "id":"5"
      },{
        "treatment":"8000-10000",
        "id":"6"
      }
    ],
    moreChild:[ ],
    moreChildList:[ ],
    searchList:[]
  },
  onLoad:function(options){
     wx.showToast({
        title: '加载中...',
        icon: 'loading',
        duration: 20000000
    })
    // 页面初始化 options为页面跳转所带来的参数;
    let keyword = options.user_import;
    let area = options.city_id;
    let position = options.post_id;
    let _that = this;
    let data = {
      'scope':4,
      'size':15,
      'page':1,
      'keyword':keyword,
      'area':area,
      'position':position
    }
    wx.setStorage({
      key:"searchData",
      data:data
    });
    wx.request({  //由页面搜索页面带来的条件进行筛选
      url: url+'/job/search',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
      },
      data: data,
      method: 'POST',
      success: function(res){
      //  console.log( res )
        let status = res.data.status;
        let searchList = res.data.data.list || [];
        let totallList = res.data.data.count; //总的职位个数
        let totalPage = Math.ceil ( totallList/15 );//总的页数


        let city_arr = wx.getStorageSync('has_select') || []; //用户选择的城市
        let post_arr = wx.getStorageSync('has_post') || [];   //用户选择的职位
        let user_import = wx.getStorageSync('user_import');   //用户输入的内容
        let history = wx.getStorageSync('search_history') || [];  //搜索的历史记录 这几个异步获取的storage是用来存储搜索记录的.
        let now_search_history,search_history,search_id;


        wx.setStorage({  //搜索的总页数 用来下拉加载
          key:"searchTotalPage",
          data:totalPage
        });

        searchList.map(function(item){
            Object.assign(item,{ update_time: item.update_time.substring(0,10) },{ salary :item.salary == '0-0' ? '面议':item.salary })
        })
        // console.log( searchList,totallList, totalPage);
        if( status  == 1 ){
          if(searchList == ''){ //搜索结果为空
              wx.hideToast()
              wx.showModal({
                content: '啊哦!你搜索的职位为空,请重新搜索',
                success: function(res) {
                  if (res.confirm) {
                    wx.navigateBack({ //返回上一页
                      delta: 1
                    })
                  }
                },
                fail:function(){
                  console.log('showModal调用失败')
                }
              })
          }else{//不为空,部署数据
               _that.setData({
                  searchList : searchList
              });
              wx.setStorageSync("search_page", 1);
              wx.setStorageSync("searchList", searchList);
              wx.hideToast();
          }
          search_id = history.length+1;
          now_search_history = [{//当前的这一条记录
            "search_id":search_id,
            "num":totallList,
            "user_import":user_import,
            "city_arr":city_arr,
            "post_arr":post_arr
          }];

          search_history = history.concat(now_search_history).reverse();
          wx.setStorage({ //更新历史记录
            key: 'search_history',
            data: search_history,
            fail: function() {
              console.log( "search_history存入历史记录失败" )
            }
          });
          wx.setNavigationBarTitle({
            title: totallList+'个职位'
          });

          
        }else{
          console.log("接口挂了")
        }
      },
      fail: function() {
        console.log("请求失败")
      }
    })

    // console.log( typeof area )
    let list = more.list // moreChild分类
    wx.setStorage({
        key: 'moreChild',
        data:select._type(list)
    })
    this.setData({
        moreChild:select._type(list)
    })
  },
  onReady:function(){
    // 页面渲染完成
    let all =  more.all; //所有分类
    let id = "0100";
    this.setData({
        moreChildList:select.postlist(all,id)
    })
  },
  onShow:function(){
    // 页面显示
     wx.hideNavigationBarLoading();
  },
  onHide:function(){
    // 页面隐藏
  },
  onUnload:function(){
    // 页面关闭
  },
  workYear:function(){// 工作年限
    this.setData({
        showYear:!this.data.showYear,
        showTreatment:false,
        showMore:false
    })
  },
  selectYear:function(e){  //==========================按工作年限进行筛选
    wx.showNavigationBarLoading();
    let id = e.currentTarget.dataset.id;
    let workYear = e.currentTarget.dataset.year;
    let searchData =  wx.getStorageSync('searchData');
    let work_year = {'work_year':id}
    let page ={
      'page':1
    }
    let newSearchData = Object.assign({},searchData,work_year,page); //新的筛选条件is  OK!
   
    let _that = this;
    // console.log( newSearchData )
    wx.setStorage({  //存储新的筛选条件,
        key:"searchData",
        data:newSearchData
    });
    this.setData({
        yearId:id,
        workYear:workYear,
        showYear:!this.data.showYear,
    });

    wx.request({
      url: url+'/job/search',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
      },
      data: newSearchData,
      method: 'POST',
      success: function(res){
        
        let status = res.data.status;
        let searchList = res.data.data.list || [];
        // console.log( searchList )
        searchList.map(function(item){
          return Object.assign(item,{update_time:item.update_time.substring(0,10)},{ salary :item.salary == '0-0' ? '面议':item.salary })
        })
        
        let totallList = res.data.data.count; //总的职位个数
        let totalPage = Math.ceil ( totallList/15 );//总的页数
        wx.setStorage({  //搜索的总页数 用来下拉加载
          key:"searchTotalPage",
          data:totalPage
        });
        if( status ==  1 ){
            if(searchList == '' ){ //工作年限条件筛选为空
                wx.hideToast()
                wx.showModal({
                  content: '啊哦!你筛选的职位为空,请重新筛选',
                  success: function(res) {
                    if (res.confirm) {
                       //不做处理
                    }
                  },
                  fail:function(){
                    console.log('showModal调用失败')
                  }
                })

            }else{
                _that.setData({
                  searchList : searchList
              });

              wx.setStorageSync("search_page", 1);
              wx.setStorageSync("searchList", searchList);
              wx.hideNavigationBarLoading();
            }

            wx.setNavigationBarTitle({
              title: totallList+'个职位'
            });
        }else{
          console.log( "工作年限接口挂了" )
        }
      },
      fail: function() {
        console.log('工作年限请求失败')
      }
    })




  },
  //薪资待遇
  treatment:function(){
    this.setData({
        showTreatment:!this.data.showTreatment,
        showYear:false,
        showMore:false
    })
  },
  selectTreatment:function(e){ //==========================按薪资待遇进行筛选
  wx.showNavigationBarLoading();
    let id = parseInt( e.currentTarget.dataset.id );
    let treatment = e.currentTarget.dataset.treatment;
    this.setData({
        treatmentId:id,
        treatment_s:treatment,
        showTreatment:!this.data.showTreatment,
    })
    let page ={
      'page':1
    }
    let _that = this;
    let searchData =  wx.getStorageSync('searchData');
    let salary = {'salary':id+1}
    let newSearchData = Object.assign({},searchData,salary,page); //新的筛选条件is  OK!

    wx.setStorage({  //存储新的筛选条件,
        key:"searchData",
        data:newSearchData
    });
    wx.request({
      url: url+'/job/search',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
      },
      data: newSearchData,
      method: 'POST',
      success: function(res){
        let status = res.data.status;
        let searchList = res.data.data.list || [];
        searchList.map(function(item){
          return Object.assign(item,{update_time:item.update_time.substring(0,10)},{ salary :item.salary == '0-0' ? '面议':item.salary })
        })
        
        let totallList = res.data.data.count; //总的职位个数
        let totalPage = Math.ceil ( totallList/15 );//总的页数
        wx.setStorage({  //搜索的总页数 用来下拉加载
          key:"searchTotalPage",
          data:totalPage
        });
        if( status ==  1 ){
            if(searchList == '' ){ //工作年限条件筛选为空
                wx.hideToast()
                wx.showModal({
                  content: '啊哦!你筛选的职位为空,请重新筛选',
                  success: function(res) {
                    if (res.confirm) {
                       //不做处理
                    }
                  },
                  fail:function(){
                    console.log('showModal调用失败')
                  }
                })

            }else{
                _that.setData({
                    searchList : searchList
                });
              wx.setStorageSync("search_page", 1);
              wx.setStorageSync("searchList", searchList);
              wx.hideNavigationBarLoading();
            }

            wx.setNavigationBarTitle({
              title: totallList+'个职位'
            });
        }else{
          console.log( "薪资待遇接口挂了" )
        }
      },
      fail: function() {
        console.log('薪资待遇请求失败')
      }
    })



  },
  //更多条件
  more:function(){
      this.setData({
        showMore:!this.data.showMore,
        showYear:false,
        showTreatment:false
    })
    
  },
  //分出子类
  sec_child:function(e){
    let all =  more.all //所有分类
    let id = e.currentTarget.dataset.id;
    // console.log( all )
    let moreChildList = select.postlist(all,id);
    var listId = id.substring(0,2)+'01';
    this.setData({
      moreChildList:moreChildList,  //分出的子类
      moreId:id,  //控制选择左边栏
      listId:listId  //控制选择图片
    })
  },
  //选择子类 
  sec_child_list:function(e){
    wx.showNavigationBarLoading()
    //let list = more.list // moreChild分类

    // console.log( list )

    let id = e.currentTarget.dataset.id;
    let val = e.currentTarget.dataset.val;
    let faterId = id.substring(0,2)+'00';
    let moreChild =  wx.getStorageSync('moreChild');
    let newData = select.changetext(moreChild,faterId,val)
    wx.setStorage({
      key:"moreChild",
      data:newData,
    })
   let page ={
      'page':1
    }
    let searchData =  wx.getStorageSync('searchData');
    let styleID = id.substring(0,2);
    let endID = id.substring(2);
    let newSearchData,_that = this;

     switch (styleID){  //配置接口请求ID
        case '01' :
          let update_time_id;
          if( endID == '01' ){
              update_time_id = -1
          }else if( endID == '02' ){
              update_time_id = 0
          }else if( endID == '03' ){
              update_time_id = 3
          }else if( endID == '04' ){
              update_time_id = 5
          }else if( endID == '05' ){
              update_time_id = 7
          }else if( endID == '06' ){
              update_time_id = 14
          }else if( endID == '07' ){
              update_time_id = 30
          }else if( endID == '08' ){
              update_time_id = 60
          }
          let update_time = {  //更新时间
            'update_time':update_time_id
          }
           newSearchData = Object.assign({},searchData,update_time,page);
           wx.setStorage({  //存储新的筛选条件,
              key:"searchData",
              data:newSearchData
          });
          break;
        case '02':
          let education_id = id.substring(3)-1;
          let education = {  //学历要求
            'education':education_id
          }
          newSearchData = Object.assign({},searchData,education,page);
           wx.setStorage({  //存储新的筛选条件,
              key:"searchData",
              data:newSearchData
          });
          break;
        case '03':
          let room_board_id = id.substring(3)-1;
          let room_board={ //食宿情况
            'room_board':room_board_id
          }
          newSearchData = Object.assign({},searchData,room_board,page);
           wx.setStorage({  //存储新的筛选条件,
              key:"searchData",
              data:newSearchData
          });
          break;
        case '04':
          let work_mode_id = id.substring(3)-1;
          let work_mode = {  //职位性质
            'work_mode':work_mode_id
          }
          newSearchData = Object.assign({},searchData,work_mode,page);
           wx.setStorage({  //存储新的筛选条件,
              key:"searchData",
              data:newSearchData
          });
          break;
    }
    wx.request({
      url: url+'/job/search',
      header: {
        'content-type': 'application/x-www-form-urlencoded',
      },
      data: newSearchData,
      method: 'POST',
      success: function(res){
        let status = res.data.status;
        let searchList = res.data.data.list || [];
        searchList.map(function(item){
           Object.assign(item,{update_time:item.update_time.substring(0,10)},{ salary :item.salary == '0-0' ? '面议':item.salary })
        });
        
        let totallList = res.data.data.count; //总的职位个数
        let totalPage = Math.ceil ( totallList/15 );//总的页数
        wx.setStorage({  //搜索的总页数 用来下拉加载
          key:"searchTotalPage",
          data:totalPage
        });
        if( status ==  1 ){
            if(searchList == '' ){ //工作年限条件筛选为空
                wx.hideToast()
                wx.showModal({
                  content: '啊哦!你筛选的职位为空,请重新筛选',
                  success: function(res) {
                    if (res.confirm) {
                       //不做处理
                    }
                  }
                })

            }else{
                _that.setData({
                    searchList : searchList
                });

                wx.setStorageSync("search_page", 1);
                wx.setStorageSync("searchList", searchList);
                wx.hideNavigationBarLoading();
            }

            wx.setNavigationBarTitle({
              title: totallList+'个职位'
            });
        }else{
          console.log( "薪资待遇接口挂了" )
        }
      },
      fail: function() {
        console.log('薪资待遇请求失败')
      }
    })

     this.setData({
        listId:id,
        moreChild: newData,
        showMore:!this.data.showMore
    })

  },
  onReachBottom:function(){ //下拉 继续加载
    wx.showNavigationBarLoading();
    let _that =this;
    try {
      let totalPage =  wx.getStorageSync('searchTotalPage');
      let searchData =  wx.getStorageSync('searchData');
      let search_page = parseInt(wx.getStorageSync('search_page') ) +1;
      let page = {
        'page':search_page
      };
      // let nowPage = search_page+1;
      let newSearchData = Object.assign({},searchData,page);
        wx.setStorage({  //存储新的筛选条件,
          key:"searchData",
          data:newSearchData
      });
      // console.log(search_page,totalPage  )
      if( search_page <= totalPage ){
            wx.request({
              url: url+'/job/search',
              header: {
                'content-type': 'application/x-www-form-urlencoded',
              },
              data: newSearchData,
              method: 'POST',
              success: function(res){
                // console.log( res )
                let status = res.data.status;
                let now_searchList = res.data.data.list || [];
                let totallList = res.data.data.count; //总的职位个数
                let totalPage = Math.ceil ( totallList/15 ),searchList;//总的页数
                wx.setStorage({  //搜索的总页数 用来下拉加载
                  key:"searchTotalPage",
                  data:totalPage
                });
                if( status ==  1 ){
                  let List = wx.getStorageSync('searchList');
                    now_searchList.map(function(item){
                        Object.assign(item,{ salary :item.salary == '0-0' ? '面议':item.salary });
                    })
                    // console.log( now_searchList );
                    searchList = List.concat(now_searchList);
                    
                      _that.setData({
                        searchList : searchList
                    });

                     wx.setStorageSync("searchList", searchList);
                     wx.setStorageSync("search_page", search_page);
                      wx.hideNavigationBarLoading();
                   
                }else{
                  console.log( "接口挂了" )
                }
              },
              fail: function() {
                console.log('请求失败')
              }
        })
      }else{ //已加载完所有page;
        wx.showModal({
          title: '提示',
          content: '已经加载到最后一页'
        })
        wx.hideNavigationBarLoading();
      }

    } catch (e){
      console.log( "获取totalPage || searchData 失败" )
    }
  },
  goZhihiWeiList:function(e){  //公司详情页
      let c_userid = e.currentTarget.dataset.company_id;
      let job_id = e.currentTarget.dataset.job_id;
      wx.navigateTo({
        url: '../position/position?job_id='+job_id+'&c_userid='+c_userid,
        fail:function(){
          console.log("go公司详情页失败")
        }
      })
  }
})