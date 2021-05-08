var snakeModule = require( 'snake.js' )
var uuidModule = require( 'uuid.js' )

function Player() {
    var playerObj = new Object();
    playerObj.name = ' '
    playerObj.curSnake = null
    // uuid
    playerObj.uuid = null

    // 类的 类型
    playerObj.objTYpe = 'Player'

    // 返回 当前 用户的 snake
    playerObj.getCurSnake = function() {
        return this.curSnake
    }

    // 返回 现在 屏幕 的范围
    playerObj.getCurScreenRange = function( curSystemInfo ) {
        var head = this.curSnake.getSnakeHead()
        return {
            xRange: {
                min: Math.max( head.x - curSystemInfo.windowWidth / 2, 0 ),
                max: head.x + curSystemInfo.windowWidth / 2
            },
            yRange: {
                min: Math.max( head.y - curSystemInfo.windowHeight / 2, 0 ),
                max: head.y + curSystemInfo.windowHeight / 2
            }
        }
    }

    // 当前  用户 生存状态
    playerObj.getCurPlayerLifeFlag = function( ) {
        return this.curSnake.getCurSnakeLifeFlag()
    }

    // 是否 死亡
    playerObj.isDead = function( otherPlayer ) {
        //TODO
        return this.curSnake.isDead( otherPlayer.curSnake )
    }

    // 设置 name
    playerObj.setName = function( name ) {
        this.name = name
    }

     // 绘图 函数
    playerObj.draw = function( context, curScreenRange  ) {
        this.curSnake.drawSnake(context, curScreenRange )
    }

    // 开始游戏
    playerObj.start = function() {
        this.uuid = uuidModule.uuid()
        this.curSnake = snakeModule.newSnake()
        this.curSnake.start()
    }

    // 玩游戏
    playerObj.running = function( curStickPosition, stickLoacl ) {
        // 未死亡
        if( this.getCurPlayerLifeFlag()) {
            this.curSnake.running( curStickPosition, stickLoacl )
        }

    }

    // 返回 对象
    return playerObj;
}

module.exports = {
    newPlayer: Player
}