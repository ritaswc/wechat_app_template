var snakeModule = require( 'snake.js' )
var uuidModule = require( 'uuid.js' )

function Robot() {
    var robotObj = new Object();
    robotObj.curSnake = null
    robotObj.curTarget = 0
    // uuid
    robotObj.uuid = null

    // 类的 类型
    robotObj.objTYpe = 'Robot'

    //躲避的概率基数
    robotObj.robotIQ = 1

    //躲避路线
    robotObj.escapePoint = null

    // 设置 name
    robotObj.setName = function( name ) {
        this.name = name
    }

    // 当前  用户 生存状态
    robotObj.getCurPlayerLifeFlag = function() {
        return this.curSnake.getCurSnakeLifeFlag()
    }

    // 返回 当前 用户的 snake
    robotObj.getCurSnake = function() {
        return this.curSnake
    }

    // 是否 死亡
    robotObj.isDead = function( otherPlayer ) {
        //自动 躲避
        var curIsDeat = this.curSnake.isDead( otherPlayer.curSnake, this.curSnake.ballRadius * 2 )
        if( curIsDeat ) {
            if( this.getCurPlayerLifeFlag() ) {
                this.escapePoint = this.curSnake.getBoadyList[ Math.ceil( this.curSnakegetBoadyListLength / 2 ) ]
                return false
            }
            else {
                return true
            }
        }
        return this.curSnake.isDead( otherPlayer.curSnake)

    }

    // 绘图 函数
    robotObj.draw = function( context, curScreenRange ) {
        this.curSnake.drawSnake( context, curScreenRange )
    }

    //获取 判断 范围
    function getRange( obj, scale ) {
        return {
            xRange: {
                min: obj.x - obj.radius * scale,
                max: obj.x + obj.radius * scale
            },
            yRange: {
                min: obj.y - obj.radius * scale,
                max: obj.y + obj.radius * scale
            }
        }
    }

    // 开始游戏
    robotObj.start = function( target ) {
        // uuid
        this.uuid = uuidModule.uuid()
        // 随机 生成 目标 index
        this.curTarget = target
        this.curSnake = snakeModule.newSnake()

        // 初始化的 长度
        var initLength = Math.ceil( Math.random() * ( 10 - 5 ) ) + 5
        this.curSnake.start( initLength )

        // robot的IQIQ
        this.robotIQ = this.curTarget
    }

    // 玩游戏
    robotObj.running = function( targetFood ) {
        if( this.getCurPlayerLifeFlag() ) {
            var curSnakeHead = this.curSnake.getSnakeHead()
            this.curSnake.running( targetFood, curSnakeHead )

        }
    }

    // 返回 对象
    return robotObj;
}

module.exports = {
    newRobot: Robot
}