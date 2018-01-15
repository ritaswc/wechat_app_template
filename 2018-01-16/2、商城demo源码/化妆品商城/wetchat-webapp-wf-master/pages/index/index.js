//获取应用实例
var app = getApp()
import {rootUrl} from '../../utils/params'
Page({
    data: { 
      imgUrls: [  
            {  
                link:`/pages/proInfo/index?id=85`,  
                url:`${rootUrl}/images/upload/index_ppt_001.jpg` 
            },{  
                link:'/pages/proInfo/index?id=93',  
                url:`${rootUrl}/images/upload/index_ppt_003.jpg`  
            }   
        ],
      proLst:[
          {
                link:`/pages/proInfo/index?id=93`,  
                url:`${rootUrl}/images/upload/index_list_ban_093.jpg` ,
                text:'洁面乳+天才水+玻尿酸精华',
                title:'年终特惠价￥158.00'
          },{
              link:`/pages/proInfo/index?id=86`,  
                url:`${rootUrl}/images/upload/index_list_ban_086.jpg` ,
                text:'白竹炭净颜洗面奶男女通用100ML',
                title:'销售价￥58.00'
          },{
              link:`/pages/proInfo/index?id=88`,  
                url:`${rootUrl}/images/upload/index_list_ban_088.jpg` ,
                text:'玻尿酸精华液25ML',
                title:'销售价￥109.00'
          },{
              link:`/pages/proInfo/index?id=87`,  
                url:`${rootUrl}/images/upload/index_list_ban_087.jpg` ,
                text:'青春密码天才水爽肤水125ML',
                title:'销售价￥109.00'
          },{
              link:`/pages/proInfo/index?id=85`,  
                url:`${rootUrl}/images/upload/index_list_ban_085.jpg` ,
                text:'蓝莓水盈深润蚕丝面膜25ML*6片装',
                title:'销售价￥49.90'
          },{
              link:`/pages/proInfo/index?id=84`,  
                url:`${rootUrl}/images/upload/index_list_ban_084.jpg` ,
                text:'玫瑰提亮净颜蚕丝面膜25ML*6片装',
                title:'销售价￥49.90'
          },{
              link:`/pages/proInfo/index?id=82`,  
                url:`${rootUrl}/images/upload/index_list_ban_082.jpg` ,
                text:'水灵灵密集保湿蚕丝面膜30ML*6片装',
                title:'销售价￥59.90'
          },{
              link:`/pages/proInfo/index?id=81`,  
                url:`${rootUrl}/images/upload/index_list_ban_081.jpg` ,
                text:'鲜嫩嫩舒缓修复蚕丝面膜30ML*6片装',
                title:'销售价￥59.90'
          },{
              link:`/pages/proInfo/index?id=95`,  
                url:`${rootUrl}/images/upload/index_list_ban_095.jpg` ,
                text:'天杞园特殊膳食 科学 安全 有效 不反弹',
                title:'销售价￥198.00'
          }
      ],  
        indicatorDots: true,
        vertical: false,
        autoplay: true,
        interval: 3000,
        duration: 1000,
        // loadingHidden: false,  // loading
        userInfo:'',
        userDate:''
    },
    onShareAppMessage() {
        return {
        title: '佰露集微商城',
        desc: '一个专卖良心商品的小网站',
        path: '/pages/index'
        }
    },
    onLoad(){
        var that = this
            //调用应用实例的方法获取全局数据
        app.getUserInfo((userInfo)=>{
            // 更新数据
            this.setData({
                userInfo: userInfo
            })
        })
        //sliderList
        // wx.request({
        //     url: `${rootUrl}/api/dbCart_action.php?act=cartCount`,
        //     method: 'POST',
        //     data: {},
        //     header: {
        //         'Accept': 'application/json'
        //     },
        //     success: (res)=>{
        //         this.setData({
        //             userDate: res,
        //             loadingHidden:true
        //         })
        //     }
        // })

    }
})
