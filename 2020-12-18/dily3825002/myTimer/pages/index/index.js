var int
Page({
  data: {
         hour:"00",
         min:"00",
         sec:"00",
         mill:"00",
         status_star:false,
         status_pause:true,
         status_reset:true,
         txt_start : "开始计时",
         txt_pause : "暂停计时",
         txt_stop : "重新计时"
  },
  status_pause: function(){
    console.log(3333333313)
  },
  onShow:function(){
    console.log(333333);
  },
  time_s:function(){
      var that = this;
    console.log(1111111);
       that.setData({
          status_star:true,
          status_pause:false,
          status_reset:false
       })
     
       var hour = this.data.hour;
       var minute = this.data.min;
       var sec = this.data.sec;
       var mill = this.data.mill;
       int = setInterval(function(){
        //逻辑
        mill++
        if(mill==100){
          mill="00";
          sec++;
          //设置分钟
           if(sec >0 && sec <10){
             that.setData({
               sec : "0"+sec
             })
           }else{
               that.setData({
               sec : sec
             })
           }
            if(sec == 60){
              sec  = "00";
              mill = "00";
              minute ++;
              if(minute>0 && minute<10){
                that.setData({
                minute : "0"+minute
                })
              }else{
                that.setData({
                minute : minute
                })
              }
             if(minute == 60){
               minute = "00";
               sec = "00"
               mill = "00"
               hour ++
               if(hour >0 && hour <10){
                 that.setData({
                   hour : "0"+hour
                 })
               }else{
                 that.setData({
                   hour : hour
                 })
               }
             }
            }

        }else{
          //设置显示毫秒
          if(mill >0 && mill < 10){
            that.setData({
              mill : "0"+mill
            })
          }else{
            that.setData({
              mill : mill
            })
          }
        }

     },10)

  },
  status_pause: function(){
     this.setData({
         status_star:false,
         status_pause:true,
         status_reset:true
     })
     clearInterval(int)
  },
  status_stop: function(){
     clearInterval(int)
    this.setData({
         hour:"00",
         min:"00",
         sec:"00",
         mill:"00",
          status_star:false,
         status_pause:true,
         status_reset:true
     })
    
  }
  
})
