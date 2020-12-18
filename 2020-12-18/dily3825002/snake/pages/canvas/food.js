// 食物对象
var colorModule = require( 'color.js' )
var canvasModule = require( 'canvasDraw.js' )

function foodListOBject() {
    // new object
    var foodListeObj = new Object();
    function FoodProject( x, y, color, radius ) {
        this.x = x;
        this.y = y;
        this.color = color;
        this.radius = radius;
        this.isShow = true
    }

    // 食物半径
    foodListeObj.foodRadius = 4
    // 初始 食物 的数量
    foodListeObj.initFoodCount = 50
    // 食物 对象 list
    foodListeObj.foodBallList = []
    // 食物的的 狩猎范围基数
    foodListeObj.rangeBase = 10


    // 返回  bodaylist
    foodListeObj.getFoodList = function() {
        return this.foodBallList
    }

    // 返回  bodaylist 长度
    foodListeObj.getFoodListLength = function() {
        return this.foodBallList.length
    }

    // 能否吃到食物
    function canEatFood( earRange, food ) {
        if( food.isShow == true && ( food.x > earRange.xRange.min && food.x < earRange.xRange.max ) && ( food.y > earRange.yRange.min && food.y < earRange.yRange.max ) ) {
            return true
        }
        return false
    }

    //计算蛇头的的 狩猎范围
    function getHeadRange( head, rangeBase ) {
        var xRange = { min: Math.max( head.x - rangeBase, 0 ), max: head.x + rangeBase }
        var yRange = { min: Math.max( head.y - rangeBase, 0 ), max: head.y + rangeBase }
        return {
            xRange: xRange,
            yRange: yRange
        }
    }

    // 判断 蛇是否吃到球
    foodListeObj.eatFood = function( snakeObj ) {
        //计算蛇头的的 狩猎范围
        var head = snakeObj.getSnakeHead()
        var step = snakeObj.getSnakeStep()
        var earRange = getHeadRange( head, step * this.rangeBase )
        for( var i = 0;i < this.foodBallList.length;i++ ) {
            if( canEatFood( earRange, this.foodBallList[ i ] ) ) {
                // 更换 新的坐标
                var foodPostion = {
                    x: Math.ceil( Math.random() * 1000 + 200 ),
                    y: Math.ceil( Math.random() * 1000 + 200 )
                }
                this.foodBallList[ i ].x = foodPostion.x
                this.foodBallList[ i ].y = foodPostion.y
                // this.foodBallList[ i ].isShow = false
                // 添加 新的的 身体
                snakeObj.add( snakeObj.getBoadyListLength() )
            }
        }
    }

    //  绘制 食物
    foodListeObj.drawFood = function( context, curScreenRange ) {
        var foodList = this.foodBallList
        var thisFoodBallListLength = this.foodBallList.length

        // 绘制 食物
        for( var i = thisFoodBallListLength - 1;i >= 0;i-- ) {
            if( foodList[ i ].isShow ) {
                canvasModule.drawing( foodList[ i ].x, foodList[ i ].y, foodList[ i ].color, foodList[ i ].radius, context, curScreenRange )
            }
        }
    }

    // 添加 新的食物（修改 被吃掉的食物的坐标）
    foodListeObj.add = function() {
        for( var i = 0;i < this.foodBallList.length;i++ ) {
            if( this.foodBallList.isShow == false ) {
                var foodPostion = {
                    x: Math.ceil( Math.random() * 1000 + 200 ),
                    y: Math.ceil( Math.random() * 1000 + 200 )
                }
                this.foodBallList[ i ].x = foodPostion.x
                this.foodBallList[ i ].y = foodPostion.y
            }
        }
    }

    // 蛇 死亡 变成 foodfood
    foodListeObj.snakeToFood = function( snake ) {

        var curBodayList = snake.getBoadyList()
        var curBodayListLength = curBodayList.length

        for( var i = 0;i < curBodayListLength;i++ ) {
            var offset = Math.ceil( Math.random() * ( 5 + 1 ) ) - 2
            if( curBodayList[ i ].isShow ) {
                var newBall = new FoodProject( curBodayList[ i ].x + offset, curBodayList[ i ].y + offset, curBodayList[ i ].color, this.foodRadius )
                this.foodBallList.push( newBall )
            }
        }
    }

    // start 
    foodListeObj.start = function() {
        //初始化 颜色 对象
        var curColorObj = colorModule.colorValues()
        // 食物列表  this.initFoodCount
        for( var i = 0;i < this.initFoodCount;i++ ) {
            //随机位置
            var foodPostion = {
                x: Math.ceil( Math.random() * 1000 + 200 ),
                y: Math.ceil( Math.random() * 1000 + 200 )
            }
            var newBall = new FoodProject( foodPostion.x, foodPostion.y, curColorObj.getColorValue( i ), this.foodRadius )
            this.foodBallList[ i ] = newBall
        }
    }

    return foodListeObj
}

module.exports = {
    foodListOBject: foodListOBject
}