// pages/new_task/new_task.js
const AV=require('../../lib/av-weapp-min');
const Todo=require('../../model/Todo');
Page({
  data:{
    date:'点我设置截至日期',
    date_f:'0',
    date_l:'0',
    date_t:'0',
    T_name:'',
    T_con:'',
    DeadLine:'',
    NowTime:'',
  },
  onLoad:function(options){
    // 页面初始化 options为页面跳转所带来的参数
  },
  bindDateChange:function(e){
    var d=new Date();
    var day=d.getDate()
    var month=d.getMonth()+1;
    var year=d.getFullYear();
    var Tnow=year+"-"+month+"-"+day;
    var Tset=e.detail.value;
    var iDays=DateDiff(Tset,Tnow).toString().split("");
    if(iDays.length==2){
    this.setData({
      date:Tset,
      date_f:0,
      date_l:iDays[0],
      date_t:iDays[1],
      DeadLine:Tset,
      NowTime:Tnow,
    })}else{
      this.setData({
      date:Tset,
      date_f:iDays[0],
      date_l:iDays[1],
      date_t:iDays[2],
      DeadLine:Tset,
      NowTime:Tnow,
    })
    }

     function  DateDiff(sDate1,  sDate2){    //sDate1和sDate2是2002-12-18格式  
       var  aDate,  oDate1,  oDate2,  iDays  
       aDate  =  sDate1.split("-")  
       oDate1  =  new  Date(aDate[1]  +  '-'  +  aDate[2]  +  '-'  +  aDate[0])    //转换为12-18-2002格式  
       aDate  =  sDate2.split("-")  
       oDate2  =  new  Date(aDate[1]  +  '-'  +  aDate[2]  +  '-'  +  aDate[0])  
       if((oDate1-oDate2)<0){
         wx.showModal({
           title:'',
           content:'  日期不要小于今天'+  Tnow,
           duration:3000
         })
         return null;
       }else{
       iDays  =  parseInt(Math.abs(oDate1  -  oDate2)  /  1000  /  60  /  60  /24)    //把相差的毫秒数转换为天数  
       return  iDays}  
}
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
  add_ok:function(){
    wx.redirectTo({
      url: '../task_list/task_list'
    })

  },
 updateTName:function({detail:{value}}){
   if(!value)return;
   this.setData({T_name:value});
 },
 updateTCon:function({detail:{value}}){
   if(!value)return;
   this.setData({T_con:value});
 },
})