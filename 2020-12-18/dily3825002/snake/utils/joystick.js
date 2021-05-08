var TouchType = {  
    DEFAULT: "DEFAULT",  
    FOLLOW: "FOLLOW"  
};  
  
var DirectionType = {  
    FOUR: "FOUR",  
    EIGHT: "EIGHT",  
    ALL: "ALL"  
};  
  
var Joystick = cc.Node.extend({  
    _stick: null,       //控杆  
    _stickBG: null,     //控杆背景  
    _listener: null,    //监听器  
    _radius: 0,         //半径  
    _angle: null,       //角度  
    _radian: null,      //弧度  
    _target: null,      //操控的目标  
    _speed: 0,          //实际速度  
    _speed1: 1,         //一段速度  
    _speed2: 2,         //二段速度  
    _touchType: null,   //触摸类型  
    _directionType: null,   //方向类型  
    _opacity: 0,        //透明度  
    callback: null,     //回调函数  
    ctor: function(stickBG, stick, radius, touchType, directionType, target)  
    {  
        this._super();  
        this._target = target;  
        this._touchType = touchType;  
        this._directionType = directionType;  
  
        //创建摇杆精灵  
        this._createStickSprite(stickBG, stick, radius);  
  
        //初始化触摸事件  
        this._initTouchEvent();  
    },  
  
    _createStickSprite: function(stickBG, stick, radius)  
    {  
        this._radius = radius;  
  
        if(this._touchType == TouchType.FOLLOW)  
            this.setVisible(false);  
  
        //摇杆背景精灵  
        this._stickBG = new cc.Sprite(stickBG);  
        this._stickBG.setPosition(cc.p(radius, radius));  
        this.addChild(this._stickBG);  
  
        //摇杆精灵  
        this._stick = new cc.Sprite(stick);  
        this._stick.setPosition(cc.p(radius, radius));  
        this.addChild(this._stick);  
  
        //根据半径设置缩放比例  
        var scale = radius / (this._stickBG.getContentSize().width / 2);  
        this._stickBG.setScale(scale);  
        this._stick.setScale(scale);  
  
        //设置大小  
        this.setContentSize(this._stickBG.getBoundingBox());  
  
        //设置锚点  
        this.setAnchorPoint(cc.p(0.5, 0.5));  
    },  
  
    _initTouchEvent: function()  
    {  
        this._listener = cc.EventListener.create({  
            event: cc.EventListener.TOUCH_ONE_BY_ONE,  
            swallowTouches: false,  
            onTouchBegan: this.onTouchBegan,  
            onTouchMoved: this.onTouchMoved,  
            onTouchEnded: this.onTouchEnded  
        });  
  
        //如果存在相同的对象，将被移除  
        this.setUserObject(this._listener);  
  
        //添加触摸监听  
        cc.eventManager.addListener(this._listener, this._stickBG);  
    },  
  
    //计算角度并返回  
    _getAngle: function(point)  
    {  
        var pos = this._stickBG.getPosition();  
        this._angle = Math.atan2(point.y-pos.y, point.x-pos.x) * (180/cc.PI);  
        return this._angle;  
    },  
  
    //计算弧度并返回  
    _getRadian: function(point)  
    {  
        this._radian = cc.PI / 180 * this._getAngle(point);  
        return this._radian;  
    },  
  
    //计算两点间的距离并返回  
    _getDistance: function(pos1, pos2)  
    {  
        return Math.sqrt(Math.pow(pos1.x - pos2.x, 2) +  
        Math.pow(pos1.y - pos2.y, 2));  
    },  
  
    onTouchBegan: function(touch, event)  
    {  
        //触摸监听目标  
        var target = event.getCurrentTarget();  
  
        //如果触摸类型为FOLLOW，则摇控杆的位置为触摸位置,触摸开始时候现形  
        if(target.getParent()._touchType == TouchType.FOLLOW)  
        {  
            target.getParent().setPosition(touch.getLocation());  
            target.getParent().setVisible(true);  
            target.getParent().scheduleUpdate();  
            return true;  
        }  
        else  
        {  
            //把触摸点坐标转换为相对与目标的模型坐标  
            var touchPos = target.convertToNodeSpace(touch.getLocation());  
  
            //点与圆心的距离  
            var distance = target.getParent()._getDistance(touchPos, target);  
  
            //圆的半径  
            var radius = target.getBoundingBox().width / 2;  
  
            //如果点与圆心距离小于圆的半径,返回true  
            if(radius > distance)  
            {  
                target.getParent()._stick.setPosition(touchPos);  
                target.getParent().scheduleUpdate();  
                return true;  
            }  
        }  
        return false;  
    },  
  
    onTouchMoved: function(touch, event)  
    {  
        //触摸监听目标  
        var target = event.getCurrentTarget();  
  
        //把触摸点坐标转换为相对与目标的模型坐标  
        var touchPos = target.convertToNodeSpace(touch.getLocation());  
  
        //点与圆心的距离  
        var distance = target.getParent()._getDistance(touchPos, target);  
  
        //圆的半径  
        var radius = target.getBoundingBox().width / 2;  
  
        //如果点与圆心距离小于圆的半径,控杆跟随触摸点  
        if(radius > distance)  
        {  
            target.getParent()._stick.setPosition(touchPos);  
        }  
        else  
        {  
            var x = target.getPositionX() + Math.cos(target.getParent()._getRadian(touchPos)) * target.getParent()._radius;  
            var y = target.getPositionY() + Math.sin(target.getParent()._getRadian(touchPos)) * target.getParent()._radius;  
            target.getParent()._stick.setPosition(cc.p(x, y));  
        }  
  
        //更新角度  
        target.getParent()._getAngle(touchPos);  
  
        //设置实际速度  
        target.getParent()._setSpeed(touchPos);  
  
        //更新回调  
        target.getParent()._updateCallback();  
    },  
  
    onTouchEnded: function(touch, event)  
    {  
        //触摸监听目标  
        var target = event.getCurrentTarget();  
  
        //如果触摸类型为FOLLOW，离开触摸后隐藏  
        if(target.getParent()._touchType == TouchType.FOLLOW)  
            target.getParent().setVisible(false);  
  
        //摇杆恢复位置  
        target.getParent()._stick.setPosition(target.getPosition());  
  
        target.getParent().unscheduleUpdate();  
    },  
  
    //设置实际速度  
    _setSpeed: function(point)  
    {  
        //触摸点和遥控杆中心的距离  
        var distance = this._getDistance(point, this._stickBG.getPosition());  
  
        //如果半径  
        if(distance < this._radius)  
        {  
            this._speed = this._speed1;  
        }  
        else  
        {  
            this._speed = this._speed2;  
        }  
    },  
  
    //更新回调  
    _updateCallback: function()  
    {  
        if(this.callback && typeof(this.callback) === "function")  
        {  
            this.callback();  
        }  
    },  
  
    //更新移动目标  
    update: function(dt)  
    {  
        switch (this._directionType)  
        {  
            case DirectionType.FOUR:  
                this._fourDirectionsMove();  
                break;  
            case DirectionType.EIGHT:  
                this._eightDirectionsMove();  
                break;  
            case DirectionType.ALL:  
                this._allDirectionsMove();  
                break;  
            default :  
                break;  
        }  
    },  
  
    //四个方向移动(上下左右)  
    _fourDirectionsMove: function()  
    {  
        if(this._angle > 45 && this._angle < 135)  
        {  
            this._target.y += this._speed;  
        }  
        else if(this._angle > -135 && this._angle < -45)  
        {  
            this._target.y -= this._speed;  
        }  
        else if(this._angle < -135 && this._angle > -180 || this._angle > 135 && this._angle < 180)  
        {  
            this._target.x -= this._speed;  
        }  
        else if(this._angle < 0 && this._angle > -45 || this._angle > 0 && this._angle < 45)  
        {  
            this._target.x += this._speed;  
        }  
    },  
  
    //八个方向移动(上下左右、左上、右上、左下、右下)  
    _eightDirectionsMove: function()  
    {  
        if(this._angle > 67.5 && this._angle < 112.5)  
        {  
            this._target.y += this._speed;  
        }  
        else if(this._angle > -112.5 && this._angle < -67.5)  
        {  
            this._target.y -= this._speed;  
        }  
        else if(this._angle < -157.5 && this._angle > -180 || this._angle > 157.5 && this._angle < 180)  
        {  
            this._target.x -= this._speed;  
        }  
        else if(this._angle < 0 && this._angle > -22.5 || this._angle > 0 && this._angle < 22.5)  
        {  
            this._target.x += this._speed;  
        }  
        else if(this._angle > 112.5 && this._angle < 157.5)  
        {  
            this._target.x -= this._speed / 1.414;  
            this._target.y += this._speed / 1.414;  
        }  
        else if(this._angle > 22.5 && this._angle < 67.5)  
        {  
            this._target.x += this._speed / 1.414;  
            this._target.y += this._speed / 1.414;  
        }  
        else if(this._angle > -157.5 && this._angle < -112.5)  
        {  
            this._target.x -= this._speed / 1.414;  
            this._target.y -= this._speed / 1.414;  
        }  
        else if(this._angle > -67.5 && this._angle < -22.5)  
        {  
            this._target.x += this._speed / 1.414;  
            this._target.y -= this._speed / 1.414;  
        }  
    },  
  
    //全方向移动  
    _allDirectionsMove: function()  
    {  
        this._target.x += Math.cos(this._angle * (Math.PI/180)) * this._speed;  
        this._target.y += Math.sin(this._angle * (Math.PI/180)) * this._speed;  
    },  
  
    //设置透明度  
    setOpacity: function(opacity)  
    {  
        this._opacity = opacity;  
        this._stick.setOpacity(opacity);  
        this._stickBG.setOpacity(opacity);  
    },  
  
    //设置一段速度  
    setSpeedwithLevel1: function(speed)  
    {  
        this._speed1 = speed;  
    },  
  
    //设置二段速度  
    setSpeedwithLevel2: function(speed)  
    {  
        if(this._speed1 < speed)  
        {  
            this._speed2 = speed;  
        }  
        else  
        {  
            this._speed2 = this._speed2;  
        }  
    },  
  
    //设置遥控杆开关  
    setEnable: function(enable)  
    {  
        if(this._listener != null)  
        {  
            if(enable)  
            {  
                cc.eventManager.addListener(this._listener, this._stickBG);  
            }  
            else  
            {  
                cc.eventManager.removeListener(this._listener);  
            }  
        }  
    },  
  
    //获取角度  
    getAngle: function()  
    {  
        return this._angle;  
    },  
  
    onExit: function()  
    {  
        this._super();  
        //移除触摸监听  
        if(this._listener != null)  
        {  
            cc.eventManager.removeListener(this._listener);  
        }  
    }  
}); 