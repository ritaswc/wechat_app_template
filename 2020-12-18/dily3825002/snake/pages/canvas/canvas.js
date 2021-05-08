var snakeModule = require( 'snake.js' )
var playerModule = require( 'player.js' )
var gameModule = require( 'game.js' )
Page( {


  data: {
    // text:"这是一个页面"
    directionIconLeft: 80,
    directionIconTop: 80,
    canvasAnimationData: {},
    rockerAnimationData: {}

  },

  onReady: function() {

    //摇杆的的 原始坐标
    this.stickLoacl = []
    this.stickLoacl[ 'x' ] = 80
    this.stickLoacl[ 'y' ] = 80
    // 摇杆 移动的坐标
    this.curStickPosition = []
    this.curStickPosition[ 'x' ] = 80
    this.curStickPosition[ 'y' ] = 80

    //手机 系统信息
    this.curSystemInfo = null


    // 初始化 game 对象
    var curGame = gameModule.newGame()

    var curPlayer = playerModule.newPlayer()
    curGame.start( curPlayer )
    var snakeObj = curGame.getCurSnake()

    // rpx 跟 px 的比例
    this.pxScale = 1
    var that = this
    wx.getSystemInfo( {
      success: function( res ) {
        that.curSystemInfo = res
        // rpx 跟 px 的比例
        that.pxScale = 750 / res.windowWidth

        // 获取  蛇的初始位置
        var startPostion = snakeObj.getStartPostion()
        // 设置屏幕的 位置
        var head = snakeObj.getSnakeHead()
        that.setCanvasAnimation( head )
        //游戏 主循环
        // curGame.runing( that.curStickPosition, that.stickLoacl, that.curSystemInfo )
        that.ballPositioninterval = setInterval( function() {
          curGame.runing( that.curStickPosition, that.stickLoacl, that.curSystemInfo )

          // 移动 屏幕
          var head = snakeObj.getSnakeHead()
          that.setCanvasAnimation( head )

          if(curGame.getCurPlayerLifeFlag() == false){
            clearInterval( that.ballPositioninterval )
            console.log('-------------game over--------------------------')
          }

        }, 17 )

      }
    })
    // 画布 动画
    this.canvasAnimation = wx.createAnimation( {
      duration: 0,
      timingFunction: 'ease',
    })

    // 摇杆 动画
    this.rockerAnimation = wx.createAnimation( {
      duration: 0,
      timingFunction: 'ease',
    })
  },


  // 触摸开始事件
  curTouchStartEvent: function( event ) {
    // console.log( '------------touchStartEvent', event )
    var clientx = event[ "touches" ][ 0 ][ "clientX" ]
    var clienty = event[ "touches" ][ 0 ][ "clientY" ]

    this.rockerAnimation.left( clientx / this.pxScale ).top( clienty / this.pxScale ).step()

    this.setData( {
      rockerAnimationData: this.rockerAnimation.export()
    })
    // this.setData( {
    //   directionIconLeft: clientx,
    //   directionIconTop: clienty
    // })
    this.curStickPosition = {
      x: clientx,
      y: clienty
    }

  },

  // touchmove 事件
  controlDirection: function( event ) {
    // console.log( '------------touchmove', event )

    var clientx = event[ "touches" ][ 0 ][ "clientX" ]
    var clienty = event[ "touches" ][ 0 ][ "clientY" ]
    // this.setData( {
    //   directionIconLeft: clientx,
    //   directionIconTop: clienty
    // })

    this.rockerAnimation.left( clientx / this.pxScale ).top( clienty / this.pxScale ).step()

    this.setData( {
      rockerAnimationData: this.rockerAnimation.export()
    })
    this.curStickPosition = {
      x: clientx,
      y: clienty
    }

  },

  // 触摸结束事件
  touchEndEvent: function( event ) {
    this.rockerAnimation.left( this.stickLoacl[ 'x' ] / this.pxScale ).top( this.stickLoacl[ 'y' ] / this.pxScale ).step()

    this.setData( {
      rockerAnimationData: this.rockerAnimation.export()
    })
    // this.setData( {
    //   directionIconLeft: this.stickLoacl[ 'x' ],
    //   directionIconTop: this.stickLoacl[ 'y' ]
    // })
  },



  // 设置画布的 动画 位移
  setCanvasAnimation: function( head ) {
    // head = this.ballList[ 0 ]
    // 设置 动画 位移
    this.canvasAnimation.left( -( head.x - this.curSystemInfo.windowWidth / 2 ) ).top( -( head.y - this.curSystemInfo.windowHeight / 2 ) ).step()
    this.setData( {
      canvasAnimationData: this.canvasAnimation.export()
    })
  },

  // 设置屏幕的 位置
  setScreenPistition: function( head ) {

    // 设置屏幕的 位置
    this.setData( {
      canvasLeft: -( head.x - this.curSystemInfo.windowWidth / 2 ) * this.pxScale,
      canvasTop: -( head.y - this.curSystemInfo.windowHeight / 2 ) * this.pxScale
    })
  },


  // 退出函数
  onUnload: function() {
    clearInterval( this.ballPositioninterval )
  },
})
