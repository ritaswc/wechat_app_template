var app =getApp()
Page({
  data:{
    "text":"hello"
  },
    change:function(e){
        var type = [
            "wechat","call_center"
        ];
        
        var id = e.currentTarget.id , data = {};
        for(var i=0,len=type.length;i<len;i++){
            data[type[i]+"Show"] = false;
        }
        data[id + 'Show'] = !this.data[id + 'Show'];
        this.setData(data);
        
    }
})