// snake object
var colorModule = require( 'color.js' )
var canvasModule = require( 'canvasDraw.js' )

function BallProject( x, y, color, radius, toX, toY, isShow ) {
    this.x = x;
    this.toX = x;
    this.y = y;
    this.toY = y;
    this.color = color;
    this.radius = radius;
    this.moveAngle = 0
    this.isShow = isShow
}

function newSnake() {
    // new object
    var snakeObj = new Object();

    // 蛇 身体半径
    snakeObj.ballRadius = 8
    // true  为 活着， false为死亡
    snakeObj.lifeFlag = true
    // 身体对象list
    snakeObj.ballList = []
    // 身体 真实长度
    snakeObj.realBoadyLength = 10
    // 每步距离
    snakeObj.step = 2
    // 初始 蛇 身体的长度
    snakeObj.initBoadyLenght = 10
    // 随机 初始点位置
    snakeObj.startPostion = {
        x: 0,
        y: 0
    }

    // 计算 直径跟 步距的的 比例关系
    this.stepCount = 0


    // 返回 startPostion
    snakeObj.getStartPostion = function() {
        return this.startPostion
    }
    // 返回  bodaylist
    snakeObj.getBoadyList = function() {
        return this.ballList
    }

    // 返回  bodaylist 长度
    snakeObj.getBoadyListLength = function() {
        return this.ballList.length
    }

    // 获取  bodaylist 真实 长度
    snakeObj.getRealBoadyListLength = function() {
        return this.realBoadyLength
    }

    // 返回 head
    snakeObj.getSnakeHead = function() {
        if( this.ballList.length > 0 ) {
            return this.ballList[ 0 ]
        }
        return {
            x: 0,
            y: 0
        }
    }

    // 返回 step
    snakeObj.getSnakeStep = function() {
        return this.step
    }

    // 计算 直径跟 步距的的 比例关系
    function calculateStepTORadius() {
        // 丢弃小数部分,保留整数部分
        var stepCount = parseInt( snakeObj.ballRadius * 2 / 1.5 / snakeObj.step )
        return stepCount
    }

    //初始化 颜色 对象
    var curColorObj = colorModule.colorValues()
    var windowTHis = this

    // 返回  是否死亡
    snakeObj.getCurSnakeLifeFlag = function() {
        return this.lifeFlag
    }

    // 是否 死亡
    snakeObj.isDead = function( otherSnake ) {
        // Robot 传两个参数, 默认 为 1， robot 为 随机
        var scale = arguments[ 1 ] ? arguments[ 1 ] : 1;

        var curHead = this.getSnakeHead()
        var curSnakeLength = this.getBoadyListLength()
        var otherSnakeList = otherSnake.getBoadyList()
        var otherSnakeLength = otherSnake.getBoadyListLength()

        //
        var step = this.step
        for( var i = 0;i < otherSnakeLength;i++ ) {
            if( otherSnakeList[ i ].isShow ) {
                if( isCrash( curHead, otherSnakeList[ i ], scale ) ) {
                    if( i == 0 ) {
                        if( curSnakeLength < otherSnakeLength ) {
                            this.lifeFlag = false
                            return true
                        }
                    } else {
                        this.lifeFlag = false
                        return true
                    }
                }
                // else if( scale != 1 ) {
                //     // 机器人 人 探路 
                //     if( isCrash( curHead, otherSnakeList[ i ], scale ) ) {
                //         return true
                //     }
                // }
            }
        }
        return false


        //根据 范围判断  是否 相撞
        function isCrash( head, otherSnakePoint, scale ) {
            var headRange = getRange( head, scale )
            var otherRange = getRange( otherSnakePoint, scale )

            if( headRange.xRange.max < otherRange.xRange.min ) {
                return false
            }

            if( headRange.xRange.min > otherRange.xRange.max ) {
                return false
            }

            if( headRange.yRange.max < otherRange.yRange.min ) {
                return false
            }

            if( headRange.yRange.min > otherRange.yRange.max ) {
                return false
            }

            return true
        }

        //获取 判断 范围
        function getRange( obj, scale ) {

            return {
                xRange: {
                    min: obj.x - step * scale,
                    max: obj.x + step * scale
                },
                yRange: {
                    min: obj.y - step * scale,
                    max: obj.y + step * scale,
                }
            }
        }

    }

    // 添加 新的的 身体
    snakeObj.add = function addNewBoday( curBoadyIndex ) {
        // 添加 不显示的 球
        for( var stepIndex = 0;stepIndex < this.stepCount;stepIndex++ ) {
            var positionIndex = curBoadyIndex + stepIndex
            if( curBoadyIndex == 0 ) {
                var startX = this.startPostion.x
                var startY = this.startPostion.y + positionIndex * this.step
            }
            else {
                var startX = this.ballList[ curBoadyIndex - 1 ].x
                var startY = this.ballList[ curBoadyIndex - 1 ].y + this.step
            }
            if( stepIndex == 0 ) {
                var newBall = new BallProject( startX, startY, curColorObj.getColorValue( positionIndex ), this.ballRadius, startX, startY, true )
            }
            // 添加 不显示的 球˝
            else {
                var newBall = new BallProject( startX, startY, '#1aad19', this.ballRadius, startX, startY, false )
            }
            this.ballList[ positionIndex ] = newBall

        }
    }

    // 判断 x toX y toY 是否相等
    function isFromSameAsTo( ballObj ) {
        if( ( ballObj.x == ballObj.toX ) && ( ballObj.y == ballObj.toY ) ) {
            return true
        }
        return false
    }

    // 计算 摇杆 的角度
    function getAngle( pointA, pointB ) {
        return Math.atan2( pointA.y - pointB.y, pointA.x - pointB.x ) * ( 180 / Math.PI );
    }

    //全方向移动  距离
    function allDirectionsMove( curAngle ) {
        return {
            x: Math.cos( curAngle * ( Math.PI / 180 ) ) * snakeObj.step,
            y: Math.sin( curAngle * ( Math.PI / 180 ) ) * snakeObj.step
        }
    }

    // 修改球列表 坐标
    snakeObj.moveAllDirection = function changeBallsPosition( curStickPosition, stickLoacl ) {
        var curRealBoadyLength = 0
        // 当前 ball list 长度
        var thisBallListLength = this.ballList.length
        for( var i = thisBallListLength - 1;i >= 0;i-- ) {
            if( this.ballList[ i ].isShow ) {
                curRealBoadyLength++
            }
            // 判断 xy 跟 tox toy是不是一样的
            if( i !== 0 ) {
                if( isFromSameAsTo( this.ballList[ i ] ) ) {
                    this.ballList[ i ].toX = this.ballList[ i - 1 ].x
                    this.ballList[ i ].toY = this.ballList[ i - 1 ].y
                }
            }

            //移动 蛇头
            if( i != 0 ) {
                // 身体
                //计算 角度
                this.ballList[ i ].x = this.ballList[ i ].toX
                this.ballList[ i ].y = this.ballList[ i ].toY
            }
            else {
                // 蛇头
                var curAngel = getAngle( curStickPosition, stickLoacl )

                this.ballList[ i ].moveAngle = curAngel
                //全方向移动  距离
                var curCalculateBallPostion = allDirectionsMove( this.ballList[ i ].moveAngle )

                if( curCalculateBallPostion != null ) {
                    this.ballList[ i ].x += curCalculateBallPostion[ 'x' ]
                    this.ballList[ i ].y += curCalculateBallPostion[ 'y' ]
                }
            }
        }

        this.realBoadyLength = curRealBoadyLength
    }

    // 绘制 身体
    snakeObj.drawSnake = function( context, curScreenRange ) {
        if( this.lifeFlag ) {
            var ballList = this.ballList

            var thisBallListLength = this.ballList.length

            // 循环 绘制 身体 
            for( var i = thisBallListLength - 1;i >= 0;i-- ) {
                if( ballList[ i ].isShow ) {
                    canvasModule.drawing( ballList[ i ].x, ballList[ i ].y, ballList[ i ].color, ballList[ i ].radius, context, curScreenRange )
                }
            }
        }
    }

    // start
    snakeObj.start = function() {
        var initLength = arguments[ 0 ] ? arguments[ 0 ] : this.initBoadyLenght;

        // 随机 初始点位置
        this.startPostion = {
            x: Math.ceil( Math.random() * ( 1000 - 100 + 1 ) ) + 100,
            y: Math.ceil( Math.random() * ( 1500 - 100 + 1 ) ) + 100
        }
        // 计算 直径跟 步距的的 比例关系
        this.stepCount = calculateStepTORadius()
        // 循环 初始化 身体
        for( var i = 0;i < initLength;i++ ) {
            // 添加 不显示的 球
            this.add( i * this.stepCount )
        }

    }

    // running
    snakeObj.running = function( curStickPosition, stickLoacl ) {
        this.moveAllDirection( curStickPosition, stickLoacl )
    }

    // 返回 对象
    return snakeObj;
}

module.exports = {
    newSnake: newSnake
}
