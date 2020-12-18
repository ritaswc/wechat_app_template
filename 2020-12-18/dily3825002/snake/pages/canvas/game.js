var snakeModule = require( 'player.js' )
var roomModule = require( 'room.js' )
function Game() {
    var gameObj = new Object();

    // 房间 列表
    gameObj.roomList = []

    // 当前 房间
    gameObj.curRoom = null

    // 指定 游戏 当前房间  //  TODO 联网  。。。。
    gameObj.setCurRoom = function() {
        this.curRoom = this.addNewRoom()
    }

    // 获取 room list  TODO 联机
    function getRoomListFromServer() {
        this.roomList = []
    }

    // 返回 roomList 长度
    gameObj.getRoomListLength = function() {
        return this.roomList.length
    }

    // TODO 联机
    gameObj.intoRoom = function( player, roomId ) {

    }

    // 创建 房间
    gameObj.addNewRoom = function() {
        var curRoom = roomModule.newRoom()
        this.roomList.push( curRoom )
        return curRoom
    }

    // 返回 当前 用户的 snake
    gameObj.getCurSnake = function() {
        return this.curRoom.getCurSnake()
    }

    // 当前  用户 生存状态
    gameObj.getCurPlayerLifeFlag = function( ) {
        return this.curRoom.getCurPlayerLifeFlag()
    }

    // 开始 游戏
    gameObj.start = function( curPlayer ) {
        // 指定 游戏 当前房间  //  TODO 先 直接 指定房间
        this.setCurRoom()

        // room start
        this.curRoom.start(curPlayer)
    }

    // 进行 游戏
    gameObj.runing = function( curStickPosition, stickLoacl, curSystemInfo) {
        // 蛇 移动
        this.curRoom.running(curStickPosition, stickLoacl, curSystemInfo)
    }

    // 返回 对象
    return gameObj
}

module.exports = {
    newGame: Game
}