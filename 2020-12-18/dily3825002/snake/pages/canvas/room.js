var palyerModule = require( 'player.js' )
var robotModule = require( 'robot.js' )
var foodModule = require( 'food.js' )

function Room() {
    var roomObj = new Object();

    //当前 用户
    roomObj.curRealPalyer = null

    // 真人 用户的 列表
    roomObj.realPalyerList = []
    //默认 机器人 数量
    roomObj.robotPalyerCount = 5
    // 机器人 列表
    roomObj.robotPalyerList = []

    // 食物列表对象
    roomObj.foodListObj = null
    roomObj.foodList = null

    //所有的 蛇 身体的坐标
    roomObj.allSnakesBoadyPosition = []
    //所有的 蛇 头的坐标
    roomObj.allSnakesHeadPosition = []

    // 指定 当前 用户
    roomObj.setCurRealPalyer = function( curPlayer ) {
        this.curRealPalyer = curPlayer
        this.realPalyerList.push( curPlayer )
    }

    // 返回 当前 用户的 snake
    roomObj.getCurSnake = function() {
        return this.curRealPalyer.getCurSnake()
    }

    // 返回 现在 屏幕 的范围
    roomObj.getCurScreenRange = function( curSystemInfo ) {
        return this.curRealPalyer.getCurScreenRange( curSystemInfo )
    }

    //返回 真人  palyerList 的长度
    roomObj.getRealPalyerListLength = function() {
        return this.realPalyerList.length
    }

    //返回 机器人  palyerList 的长度
    roomObj.getRobotPalyerListLength = function() {
        return this.robotPalyerList.length
    }

    // 添加机器人
    roomObj.addRobot = function( id ) {
        var curRobot = robotModule.newRobot( id )
        this.robotPalyerList.push( curRobot )
    }

    // 当前  用户 生存状态
    roomObj.getCurPlayerLifeFlag = function() {
        return this.curRealPalyer.getCurPlayerLifeFlag()
    }

    // 判断 是否吃到 食物
    roomObj.eadFood = function() {
        // 真人
        for( var i = 0;i < this.realPalyerList.length;i++ ) {
            this.foodListObj.eatFood( this.realPalyerList[ i ].curSnake )
        }

        //  机器人
        for( var i = 0;i < this.robotPalyerList.length;i++ ) {
            this.foodListObj.eatFood( this.robotPalyerList[ i ].curSnake )
        }
    }

    // 绘图 函数
    roomObj.draw = function( curSystemInfo ) {
        //  获取 现在 屏幕 的范围
        var curScreenRange = this.getCurScreenRange( curSystemInfo )

        var context = wx.createContext()

        // 绘制  食物
        this.foodListObj.drawFood( context, curScreenRange )

        // 绘制 蛇
        // 绘制 真人
        for( var i = 0;i < this.realPalyerList.length;i++ ) {
            this.realPalyerList[ i ].draw( context, curScreenRange )
        }

        // 绘制 机器人
        for( var i = 0;i < this.robotPalyerList.length;i++ ) {
            this.robotPalyerList[ i ].draw( context, curScreenRange )
        }

        wx.drawCanvas( {
            canvasId: 'canvas',
            actions: context.getActions()
        })
    }


    //TODO   是否 死亡 联鸡
    roomObj.isSnakeDead = function() {
        var realPalyerList = this.realPalyerList
        var realPalyerListLength = realPalyerList.length
        var robotPalyerList = this.robotPalyerList
        var robotPalyerListLength = robotPalyerList.length

        // 要删除的的 真实用户的 元素
        var realPlayerDeleteList = []
        //  要删除的的 机器人 的 元素
        var robotDeleteList = []

        // 真 用户
        for( var i = 0;i < realPalyerListLength;i++ ) {
            //真人 比 真人
            for( var x = 0;x < realPalyerListLength;x++ ) {
                // 自己 不跟 自己比较
                if( realPalyerList[ i ].uuid != realPalyerList[ x ].uuid ) {
                    if( realPalyerList[ i ].isDead( realPalyerList[ x ] ) ) {
                        // 死亡  变成 食物
                        this.foodListObj.snakeToFood( realPalyerList[ i ].getCurSnake() )
                        realPlayerDeleteList.push( i )
                    }
                }
            }

            //真人 比 机器人
            for( var x = 0;x < robotPalyerListLength;x++ ) {
                if( realPalyerList[ i ].isDead( robotPalyerList[ x ] ) ) {
                    // 死亡  变成 食物
                    this.foodListObj.snakeToFood( realPalyerList[ i ].getCurSnake() )
                    realPlayerDeleteList.push( i )
                }
            }
        }

        //  删除 真人 用户
        var realPlayerDeleteListLength = realPlayerDeleteList.length
        for( var i = 0;i < realPlayerDeleteListLength;i++ ) {
            realPalyerList.splice( realPlayerDeleteList[ i ], 1 )
        }

        //重新 计算 真人 用户列表 长度
        var realPalyerListLength = realPalyerList.length

        // 机器人
        for( var i = 0;i < robotPalyerListLength;i++ ) {
            //机器人 比 真人
            for( var x = 0;x < realPalyerListLength;x++ ) {
                if( robotPalyerList[ i ].isDead( realPalyerList[ x ] ) ) {
                    // 死亡  变成 食物
                    this.foodListObj.snakeToFood( robotPalyerList[ i ].getCurSnake() )
                    robotDeleteList.push( i )
                }
            }

            //机器人 比 机器人
            for( var x = 0;x < robotPalyerListLength;x++ ) {
                // 自己 不跟 自己比较
                if( robotPalyerList[ i ].uuid != robotPalyerList[ x ].uuid ) {
                    if( robotPalyerList[ i ].isDead( robotPalyerList[ x ] ) ) {
                        // 死亡  变成 食物
                        this.foodListObj.snakeToFood( robotPalyerList[ i ].getCurSnake() )
                        robotDeleteList.push( i )
                    }

                }
            }
        }

        //  删除 机器人
        var robotDeleteListLength = robotDeleteList.length
        for( var i = 0;i < robotDeleteListLength;i++ ) {
            robotPalyerList.splice( robotDeleteList[ i ], 1 )
        }
    }


    // 蛇 移动
    roomObj.moveSnake = function( curStickPosition, stickLoacl ) {
        var foodList = roomObj.foodList
        var foodListLength = foodList.length
        // 真人 蛇 移动
        for( var i = 0;i < this.realPalyerList.length;i++ ) {
            this.realPalyerList[ i ].running( curStickPosition, stickLoacl )
        }

        //  机器人 
        for( var i = 0;i < this.robotPalyerList.length;i++ ) {
            // console.log('-------------this.robotPalyerList[ i ].escapePoint-------------', this.robotPalyerList[ i ].escapePoint)
            if( this.robotPalyerList[ i ].escapePoint == null ) {
                var targetFood = getTargetFood( this.robotPalyerList[ i ].curTarget )
            } else {
                var targetFood = this.robotPalyerList[ i ].escapePoint

                this.robotPalyerList[ i ].escapePoint = null
            }
            this.robotPalyerList[ i ].running( targetFood )
        }

        // 获取 index
        function getTargetFood( index ) {
            while( true ) {
                index = index % foodListLength
                if( foodList[ index ].isShow ) {
                    return foodList[ index ]
                }
                else {
                    index = Math.ceil( Math.random() * 1000 )
                }
            }
        }
    }

    // 运行
    roomObj.running = function( curStickPosition, stickLoacl, curSystemInfo ) {
        //是否死亡
        this.isSnakeDead()
        // 蛇 移动
        this.moveSnake( curStickPosition, stickLoacl )

        // 判断 是否吃到 食物
        this.eadFood()

        // 绘图
        this.draw( curSystemInfo )
    }

    // 开始
    roomObj.start = function( curPlayer ) {
        // 将 当前用户 加入 房间
        this.setCurRealPalyer( curPlayer )

        //
        // 食物列表对象
        this.foodListObj = foodModule.foodListOBject()
        this.foodListObj.start()
        this.foodList = this.foodListObj.foodBallList

        // 默认 先 添加  机器人

        for( var i = 0;i < this.robotPalyerCount;i++ ) {
            this.addRobot()
        }

        // 真人
        for( var i = 0;i < this.realPalyerList.length;i++ ) {
            this.realPalyerList[ i ].start()
        }

        //  机器人
        for( var i = 0;i < this.robotPalyerList.length;i++ ) {
            this.robotPalyerList[ i ].start(i)
        }
    }

    // 返回 对象
    return roomObj
}

module.exports = {
    newRoom: Room
}