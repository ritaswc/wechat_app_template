//获取应用实例
var app = getApp()
Page({
    data:{
        //首页展示图片
       header_src:"../../images/tus.jpg",
       //会员页面分类
       selected:true,
       selected1:false,
       //五个模块
    cate_src: [
      {
        mode: 'scaleToFill',
        text: '全部订单',
        picture: '../../images/i_dd.png',
      },
      {
        mode: 'scaleToFill',
        text: '待付款',
        picture: '../../images/i_fk.png',
      },
      {
        mode: 'scaleToFill',
        text: '待发货',
        picture: '../../images/i_fh.png',
      },
      {
        mode: 'scaleToFill',
        text: '待收货',
        picture: '../../images/i_sh.png',
      },
      {
        mode: 'scaleToFill',
        text: '待退换',
        picture: '../../images/i_th.png',
      },
    ],
     cate_src1: [
      {
        mode: 'scaleToFill',
        text: '我的排行榜',
        picture: '../../images/ph_i.png',
        url:"../../pages/model/model",
      },
      {
        mode: 'scaleToFill',
        text: '我的钱包',
        picture: '../../images/qb_i.png',
        url:"../../pages/money/money",
      },
      {
        mode: 'scaleToFill',
        text: '我的优惠券',
        picture: '../../images/qb.png',
        url:"../../pages/personal_coupon/personal_coupon",
      },
      {
        mode: 'scaleToFill',
        text: '充值',
        picture: '../../images/cz_i.png',
      },
      {
        mode: 'scaleToFill',
        text: '我的购物车',
        picture: '../../images/c_i.png',
      },
      {
        mode: 'scaleToFill',
        text: '我的收藏',
        picture: '../../images/sc_i.png',
      },
      {
        mode: 'scaleToFill',
        text: '修改资料',
        picture: '../../images/xg_i.png',
        url:"../../pages/personal_edit_profile/personal_edit_profile",
      },
      {
        mode: 'scaleToFill',
        text: '地址管理',
        picture: '../../images/dz_i.png',
        url:'../../pages/personal_address/personal_address',
      },
      {
        mode: 'scaleToFill',
        text: '会员等级特权',
        picture: '../../images/fj_i.png',
      },
      {
        mode: 'scaleToFill',
        text: '抽奖活动',
        picture: '../../images/cj_i.png',
      },
      
    ],
    },
   selected:function(e){
        this.setData({
            selected1:false,
            selected:true
        })
    },
    selected1:function(e){
        this.setData({
            selected:false,
            selected1:true
        })
    },
})