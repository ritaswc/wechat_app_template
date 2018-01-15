Page({
  data: {
    inputDisabled:false      //input 是否可输入
  },
  

  setInputStatus:function(options){
    console.log(0,options.from)
      let inputDisabled = false;
      if (options.from){
         if (options.from == 'inspect'){
              inputDisabled = true
         } else {
              inputDisabled = false;
         }
         this.setData({
            inputDisabled:inputDisabled
         })
      }
      console.log(11,inputDisabled)
  },
  onLoad: function (options) {
    this.setInputStatus(options);
  },
  

    
})
