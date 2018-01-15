Page({
  data: {
    pointList:[],
    prizeList:[],
    pointColors:['#A4F6F9','#FF99FE'],
    prizeDefaultColor:'#fff',
    prizeSelectColor:'#BA52ED',
    prizeIndex:0,
    isRunning:false,
    prizeImgs:[
      '../../imgs/HOU.png',
      '../../imgs/BOS.png',
      '../../imgs/CHI.png',
      '../../imgs/CLE.png',
      '../../imgs/GSW.png',
      '../../imgs/LAC.png',
      '../../imgs/LAL.png',
      '../../imgs/OKC.png',
      '../../imgs/SAS.png'
    ]
  },
  onLoad: function () {
    let self = this,
        leftPoint = 7.5,
        topPoint = 7.5,
        pointList = []
     for(let i = 0;i < 24;i++){
      if (i === 0) {
        topPoint = 15
        leftPoint = 15
      } else if (i < 6) {
        topPoint = 7.5
        leftPoint = leftPoint + 102.5
      } else if (i == 6) {
        topPoint = 15
        leftPoint = 620
      } else if (i < 12) {
        topPoint = topPoint + 94
        leftPoint = 620
      } else if (i == 12) {
        topPoint = 565
        leftPoint = 620
      } else if (i < 18) {
        topPoint = 570
        leftPoint = leftPoint - 102.5
      } else if (i == 18) {
        topPoint = 565
        leftPoint = 15
      } else if (i < 24) {
        topPoint = topPoint - 94
        leftPoint = 7.5
      } else {
        return
      }
      pointList.push({topPoint:topPoint,leftPoint:leftPoint})
     } 

     this.setData({
       pointList : pointList
     })


     setInterval(function(){
       let color1 = self.data.pointColors[0],
           color2 = self.data.pointColors[1]
       if(color1 === '#A4F6F9'){
         self.setData({
           color1 : '#FF99FE',
           color2 : '#A4F6F9'
         })
       }else{
         self.setData({
           color2 : '#FF99FE',
           color1 : '#A4F6F9'
         })
       }
     },800)
     const prizeList = []
     let topPrize = 25,
         leftPrize = 25
    for(let i = 0;i < 8;i++){
      if(i === 0){
        topPrize = 25
        leftPrize = 25
      }else if(i < 3){
        topPrize = topPrize
        leftPrize = leftPrize + 166.666 + 15
      }else if(i < 5){
        leftPrize = leftPrize
        topPrize = topPrize + 150 + 15
      }else if(i < 7){
        leftPrize = leftPrize - 166.666 -15
        topPrize = topPrize
      }else if (i < 8 ){
        leftPrize = leftPrize
        topPrize = topPrize - 150 -15
      }
      let prizeImg = self.data.prizeImgs[i]
      prizeList.push({topPrize:topPrize,leftPrize:leftPrize,prizeImg:prizeImg})
    }
    self.setData({
      prizeList : prizeList
    })
  },
  startGame(){
    if(this.data.isRunning === true) return 
    this.setData({
      isRunning:true
    })
    const self = this
    let prizeIndex = 0
    let i = 0
    const timer = setInterval(function(){
      prizeIndex++
      i += 30
      if(i >= 1000){
        clearInterval(timer)

        wx.showModal({
          title:'恭喜你',
          content:`获得了第${self.data.peizeIndex + 1}个奖品`,
          showCancel:false,
          success:function(res){
            if(res.confirm){
              self.setData({
                isRunning:false
              })
            }
          }
        })
        prizeIndex = prizeIndex % 8 
        self.setData({
          prizeIndex:prizeIndex
        })
      }
    },(200 + i))
  }
})
