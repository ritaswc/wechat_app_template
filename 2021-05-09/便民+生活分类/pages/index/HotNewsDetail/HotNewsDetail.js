var WxParse = require('../../../wxParse/wxParse.js');

var htmlContentStr = `<html lang="en"><head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <meta http-equiv="pragma" content="no-cache">
	  <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0"> 

    <link rel="stylesheet" href="http://g.jointem.com/applgov/plugins/PhotoSwipe-master/dist/photoswipe.css">
    <link rel="stylesheet" href="http://g.jointem.com/applgov/plugins/PhotoSwipe-master/dist/default-skin/default-skin.css">
    <link rel="stylesheet" href="http://g.jointem.com/applgov/common/css/common.css">
    <link rel="stylesheet" href="http://g.jointem.com/applgov/common/css/content.css">

    <script src="http://g.jointem.com/applgov/common/js/config.js"></script>
    <script data-main="http://g.jointem.com/applgov/popnews/js/popnews" src="http://g.jointem.com/applgov/common/js/require.js?d=New Date()" defer="" async=""></script>
    <title></title>
<script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="popnews" src="http://g.jointem.com/applgov/popnews/js/popnews.js"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="jquery" src="http://g.jointem.com/applgov/common/js/jquery.js?v=1.0.0"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="Utils" src="http://g.jointem.com/applgov/common/js/Utils.js?v=1.0.0"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="eventListener" src="http://g.jointem.com/applgov/common/js/newEventListener.js?v=1.0.0"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="dialog" src="http://g.jointem.com/applgov/common/js/dialog.js?v=1.0.0"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="zh_CN" src="http://g.jointem.com/applgov/common/js/zh_CN.js?v=1.0.0"></script><script type="text/javascript" charset="utf-8" async="" data-requirecontext="_" data-requiremodule="imgload" src="http://g.jointem.com/applgov/plugins/stackgrid/js/imgload.adem.js?v=1.0.0"></script></head>
<body>
<div id="_pagemodes" class="_pagemodes _pagemode _pagemode_active"><div class="openApp"><img src="../common/img/hgp.png"></div>
<div id="article" class="article"><p style="text-align:center"><span style="font-family: 宋体, SimSun; font-size: 16px;"><img src="http://www.jointem.com//ZYB_BE/ueditor/20170506/1494060040270025102.jpg" title="1494060040270025102.jpg" alt="要闻开头图.jpg"></span></p><p><span style="font-family: 宋体, SimSun; font-size: 16px;"><br></span></p><p style="text-align: center;"><span style="font-family: 宋体, SimSun; font-size: 24px;">国务院重奖真抓实干 24组典型获“真金白银”</span></p><p style="text-align: center;"><span style="font-family: 宋体, SimSun; font-size: 12px;"><br></span></p><p style="text-align: center;"><span style="font-family: 宋体, SimSun; font-size: 12px;">2017-05-06 &nbsp;来源：人民日报海外版</span><span style="font-family: 宋体, SimSun; font-size: 16px;"><br></span></p><p><span style="font-family: 宋体, SimSun; font-size: 12px;"><br></span></p><p style="text-align:center"><span style="font-family: 宋体, SimSun; font-size: 16px;"><img src="http://www.jointem.com//ZYB_BE/ueditor/20170506/1494060079471029028.jpg" title="1494060079471029028.jpg" alt="2017050605074553566.jpg"></span></p><p style="margin-top: 0px; margin-bottom: 28px; font-stretch: normal; font-size: 16px; line-height: 1.75em; font-family: Arial, 宋体; color: rgb(51, 51, 51); white-space: normal; widows: 1; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, SimSun; font-size: 14px;">月6日，江苏省连云港市赣榆区纪委督查组调查了解渔船燃油补贴等发放情况，加大精准扶贫监督执纪问责力度。司 伟 董自军摄影报道（人民视觉）</span></p><p style="margin-top: 0px; margin-bottom: 28px; font-stretch: normal; font-size: 16px; line-height: 1.75em; font-family: Arial, 宋体; color: rgb(51, 51, 51); white-space: normal; widows: 1; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, SimSun; font-size: 16px;">　　本报北京5月5日电　（记者赵展慧）国务院新闻办公室今天举行国务院政策例行吹风会， 介绍国务院对2016年落实重大政策措施成效明显地方予以表扬激励的情况，共通报表扬24组有典型性和示范性的地方，覆盖31个省（区、市）和新疆生产建设兵团，并相应部署实施了24条激励措施。</span></p><p style="margin-top: 0px; margin-bottom: 28px; font-stretch: normal; font-size: 16px; line-height: 1.75em; font-family: Arial, 宋体; color: rgb(51, 51, 51); white-space: normal; widows: 1; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, SimSun; font-size: 16px;">　　这24组受到表扬激励的地方是结合2016年国务院大督查、专项督查和部门日常督查情况评选出来的。其中超额或提前完成年度重点量化任务的地方有4组，如浙江、福建、江西等省超额完成化解钢铁、煤炭过剩产能目标任务量，安徽、湖北等省完成扶贫开发年度计划、减贫成效显著；大力推动重大战略和改革举措落地生效的地方有4组，比如北京市西城区、天津市滨海新区等20个市、县（市、区）推动工商注册制度便利化及时到位、事中事后监管等政策措施社会反映好；在保持经济稳定增长方面积极主动作为、工作成效明显的地方有5组，如浙江、海南、重庆等省全社会固定资产投资保持稳定增长，中央预算内投资项目开工率、完成率及地方投资到位率高；积极为转型升级、创新发展营造良好环境的地方有6组，如北京、上海、浙江推动双创政策落地勇于探索；围绕保障和改善民生真抓实干、取得突出成效的地方有5组，如福建省三明市等试点城市公立医院综合改革成效明显。</span></p><p style="margin-top: 0px; margin-bottom: 28px; font-stretch: normal; font-size: 16px; line-height: 1.75em; font-family: Arial, 宋体; color: rgb(51, 51, 51); white-space: normal; widows: 1; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, SimSun; font-size: 16px;">　　对于受表扬地方，国务院相应部署实施了24条含金量很高的激励措施，由国家发展改革委、财政部、国土资源部等18个部门承担表扬激励工作。其中，直接给予资金、土地指标奖励的有5条；在资金分配、项目布局等方面给予倾斜的有10条；在改革试点、功能区升级、融资发债等方面予以优先支持的有8条。</span></p><p style="margin-top: 0px; margin-bottom: 28px; font-stretch: normal; font-size: 16px; line-height: 1.75em; font-family: Arial, 宋体; color: rgb(51, 51, 51); white-space: normal; widows: 1; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, SimSun; font-size: 16px;">　　在直接给予资金奖励的激励措施中，国家发展改革委在中央预算内投资既有专项中统筹安排约20亿元，主要用于奖励支持补短板、惠民生项目建设，用于激励对全社会固定资产投资保持稳定增长，中央预算内投资项目开工率、完成率及地方投资到位率高的省（市）。据国家发展改革委秘书长李朴民介绍，目前已经综合比选出了5个奖励省份，正在印发有关投资计划。</span></p><p style="margin-top: 0px; margin-bottom: 28px; font-stretch: normal; font-size: 16px; line-height: 1.75em; font-family: Arial, 宋体; color: rgb(51, 51, 51); white-space: normal; widows: 1; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, SimSun; font-size: 16px;">　　除了资金奖励外，一些落实国家重大政策措施成效明显的地区还得到了土地指标奖励。国土资源部今年计划奖励指标共计9.1万亩，用于奖励土地节约利用水平高的地区。</span></p><p style="margin-top: 0px; margin-bottom: 28px; font-stretch: normal; font-size: 16px; line-height: 1.75em; font-family: Arial, 宋体; color: rgb(51, 51, 51); white-space: normal; widows: 1; background-color: rgb(255, 255, 255);"><span style="font-family: 宋体, SimSun; font-size: 16px;">　　国务院新闻办公室新闻发言人袭艳春介绍，此次国务院对2016年真抓实干、成效明显地方给予比较系统的表扬激励，是近年来的第一次，标志着国务院层面初步建立起了以督查结果运用为基础的正向激励长效机制。</span></p><p style="text-align:center"><a href="http://m.jointem.com/mobilJointem/zyb.html" target="_self" title="好公仆下载"><span style="font-family: 宋体, SimSun; font-size: 16px;"><img src="http://www.jointem.com//ZYB_BE/ueditor/20170506/1494060109163092634.gif" title="1494060109163092634.gif" alt="要闻结尾图.gif"></span></a></p></div></div>

</body></html>`;

Page({
  data:{
    
  },

  onLoad:function(options){
    // 生命周期函数--监听页面加载

	WxParse.wxParse('htmlContentStr', 'html', htmlContentStr, this, 5);

		// var that = this

    // wx.request({
    //   url: 'http://g.jointem.com/applgov/popnews/popnews.html?id=1760',
    //   data: {},
    //   method: 'GET', // OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT
    //   // header: {}, // 设置请求的 header
    //   success: function(res){
    //     // success
    //     console.log(res.data)
    //     htmlContentStr = res.data
    //     WxParse.wxParse('htmlContentStr', 'html', htmlContentStr, that, 5);


    //   },
    //   fail: function(res) {
    //     // fail
    //   },
    //   complete: function(res) {
    //     // complete
    //   }
    // })

  },
  onReady:function(){
    // 生命周期函数--监听页面初次渲染完成
    
  },
  onShow:function(){
    // 生命周期函数--监听页面显示
    
  },
  onHide:function(){
    // 生命周期函数--监听页面隐藏
    
  },
  onUnload:function(){
    // 生命周期函数--监听页面卸载
    
  },
  onPullDownRefresh: function() {
    // 页面相关事件处理函数--监听用户下拉动作
    
  },
  onReachBottom: function() {
    // 页面上拉触底事件的处理函数
    
  },
  onShareAppMessage: function() {
    // 用户点击右上角分享
    return {
      title: 'title', // 分享标题
      desc: 'desc', // 分享描述
      path: 'path' // 分享路径
    }
  }
})