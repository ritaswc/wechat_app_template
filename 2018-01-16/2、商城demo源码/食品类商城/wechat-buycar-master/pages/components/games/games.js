//index.js
//获取应用实例
var app = getApp()
Page({
  data: {
    startx: 0,
    starty: 0,
    endx:0,
    endy:0,
    direction:'',
    numbers:[[2,2,2,2],[0,2,8,0],[0,4,0,0],[0,0,0,0]],
  },
  tapStart: function(event){
    this.setData({
      startx: event.touches[0].pageX,
      starty: event.touches[0].pageY
      })
  },
  tapMove: function(event){
    this.setData({
      endx: event.touches[0].pageX,
      endy: event.touches[0].pageY
      })
  },
  tapEnd: function(event){
    var heng = (this.data.endx) ? (this.data.endx - this.data.startx) : 0;
    var shu = (this.data.endy) ? (this.data.endy - this.data.starty) : 0;
    console.log(heng, shu);
    if(Math.abs(heng) > 5 || Math.abs(shu) > 5){
      var direction = (Math.abs(heng) > Math.abs(shu)) ? this.computeDir(1, heng):this.computeDir(0, shu);
      this.setData({
        startx:0,
        starty:0,
        endx:0,
        endy:0
      })
      this.mergeAll(direction) && this.randInsert();
    }
  },
  computeDir: function(heng, num){
    if(heng) return (num > 0) ? 'right' : 'left';
    return (num > 0) ? 'bottom' : 'top';
  },

  mergeAll: function(dir){
    switch(dir){
      case 'left':
        return this.mergeleft();
        break;
      case 'right':
        return this.mergeright();
        break;
      case 'top':
        return this.mergetop();
        break;
      case 'bottom':
        return this.mergebottom();
        break;
      default:
    }
    
  },

  //左划
  mergeleft: function(){
    var change = false;
    var arr = this.data.numbers;
    
    for(var i = 0; i < 4; i++){
      //merge first
      for(var j = 0; j < 3; j++){
        if(arr[i][j] == 0) continue;
        for(var k = 1; k < 4-j; k++){
          if(arr[i][j] != 0 && arr[i][j+k] != 0){
            if(arr[i][j] != arr[i][j+k]) break;   //不相同则直接跳过
            arr[i][j] = arr[i][j] *2;
            arr[i][j+k] = 0;
            change = true;
            break;
          }
        }
      }
      //movemove
      for(var j = 0; j < 3; j++){
        if(arr[i][j] == 0){
          for(var k = 1; k < 4-j; k++){
            if(arr[i][j+k] != 0){
              arr[i][j] = arr[i][j+k];
              arr[i][j+k] = 0;
              change = true;
              break;
            }
          }
        }
      }
    }
    this.setData({
          numbers:arr
          })
    return change
  },
  //右滑
  mergeright: function(){
    var change = false
    var arr = this.data.numbers;
    
    for(var i = 0; i < 4; i++){
      //merge first
      for(var j = 3; j > 0; j--){
        if(arr[i][j] == 0) continue;
        for(var k = 1; k <= j; k++){
          if(arr[i][j] != 0 && arr[i][j-k] != 0){
            if(arr[i][j] != arr[i][j-k]) break;
            arr[i][j] = arr[i][j] *2;
            arr[i][j-k] = 0;
            change = true;
            break;
          }
        }
      }
      //movemove
      for(var j = 3; j > 0; j--){
        if(arr[i][j] == 0){
          for(var k = 1; k <= j; k++){
            if(arr[i][j-k] != 0){
              arr[i][j] = arr[i][j-k];
              arr[i][j-k] = 0;
              change = true;
              break;
            }
          }
        }
      }
    }
    this.setData({
          numbers:arr
          })
    return change
  },
  //下划
  mergebottom: function(){
    var change = false
    var arr = this.data.numbers;
    
    for(var i = 0; i < 4; i++){
      //merge first
      for(var j = 3; j > 0; j--){
        if(arr[j][i] == 0) continue;
        for(var k = 1; k <= j; k++){
          if(arr[j][i] != 0 && arr[j-k][i] != 0){
            if(arr[j][i] != arr[j-k][i]) break;
            arr[j][i] = arr[j][i] *2;
            arr[j-k][i] = 0;
            change = true
            break;
          }
        }
      }
      //movemove
      for(var j = 3; j > 0; j--){
        if(arr[j][i] == 0){
          for(var k = 1; k <= j; k++){
            if(arr[j-k][i] != 0){
              arr[j][i] = arr[j-k][i];
              arr[j-k][i] = 0;
              change = true
              break;
            }
          }
        }
      }
    }
    this.setData({
          numbers:arr
          })
    return change
  },
  //上滑
  mergetop: function(){
    var change = false
    var arr = this.data.numbers;
    
    for(var i = 0; i < 4; i++){
      //merge first
      for(var j = 0; j < 3; j++){
        if(arr[j][i] == 0) continue;
        for(var k = 1; k < 4-j; k++){
          if(arr[j][i] != 0 && arr[j+k][i] != 0){
            if(arr[j][i] != arr[j+k][i]) break;
            arr[j][i] = arr[j][i] *2;
            arr[j+k][i] = 0;
            change = true
            break;
          }
        }
      }
      //movemove
      for(var j = 0; j < 3; j++){
        if(arr[j][i] == 0){
          for(var k = 1; k < 4-j; k++){
            if(arr[j+k][i] != 0){
              arr[j][i] = arr[j+k][i];
              arr[j+k][i] = 0;
              change = true
              break;
            }
          }
        }
      }
    }
    this.setData({
          numbers:arr
          })
    return change
  },
  randInsert: function(){
    var arr = this.data.numbers
    //随机2或4
    num = Math.random() < 0.8 ? 2 : 4
    //计算随机位置
    var zeros = [];
    for(var i = 0; i < 4; i++){
      for(var j = 0; j < 4; j++){
        if(arr[i][j] == 0){
            zeros.push([i, j]);
        }
      }
    }
    var position = zeros[Math.floor(Math.random() * zeros.length)];
    arr[position[0]][position[1]] = num
    this.setData({
      numbers:arr
      })
  }
})
