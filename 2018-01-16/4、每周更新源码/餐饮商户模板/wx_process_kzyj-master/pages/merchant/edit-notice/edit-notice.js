//获取应用实例
var app = getApp()
Page({
  data: {
    allCanSee:true,
    attentionCanSee:true,
    vipCanSee:true,
  },
  
  onLoad: function () {
   
  },
  choiceAllCanSee:function(){
      if (this.data.allCanSee) {
        this.setData({
            allCanSee:false,
            attentionCanSee:false,
            vipCanSee:false
         });
      } else {
          this.setData({
              allCanSee:true,
              attentionCanSee:true,
              vipCanSee:true
          });
      }
  },
  choiceAttentionCanSee:function(){
    if (this.data.allCanSee) {
        this.setData({
            allCanSee:false,
            attentionCanSee:false,
            vipCanSee:true
         });
      } else {
          if (this.data.vipCanSee){
             this.setData({
                allCanSee:true,
                attentionCanSee:true,
                vipCanSee:true
             });
          } else {
             this.setData({
                allCanSee:false,
                attentionCanSee:!this.data.attentionCanSee
             });
          }
          
      }
  },
  choiceVipCanSee:function(){
    if (this.data.allCanSee) {
        this.setData({
            allCanSee:false,
            attentionCanSee:false,
            vipCanSee:true
         });
      } else {
          if (this.data.attentionCanSee){
             this.setData({
                allCanSee:true,
                attentionCanSee:true,
                vipCanSee:true
             });
          } else {
             this.setData({
                allCanSee:false,
                vipCanSee:!this.data.vipCanSee
             });
          }
          
      }
  }

})
