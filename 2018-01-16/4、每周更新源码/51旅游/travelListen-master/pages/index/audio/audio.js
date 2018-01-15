var _url = 'https://www.yuanweilh.com.cn/vtg/scenic/api.do';
var _data = 'LanguageId=2439&Mobile=15820097670&method=scenicDownload&';

var ajax = require('../../../utils/util.ajax.js');

var olddistance = 0;  //这个是上一次两个手指的距离  
var newdistance;      //本次两手指之间的距离，两个一减咱们就知道了滑动了多少，以及放大还是缩小（正负嘛）  
var oldscale = 1;     //这个是上一次动作留下的比例  
var diffdistance;     //这个是新的比例，新的比例一定是建立在旧的比例上面的，给人一种连续的假象  
var baseHeight;       //上一次触摸完之后的高  
var baseWidth;        //上一次触摸完之后的宽

Page({
    data:{
        icon:'',
        audioSrc:'',
        scaleWidth: '',
        scaleHeight: '',
    },
    onLoad:function (query) {
        var that = this;
        // console.log(query.language);  
        ajax.post(_url,'LanguageId='+query.language+'&Mobile=15820097670&method=scenicDownload&',function (res) {  
            that.setData({
                icon:res.data.data.icon,
                audioSrc:res.data.data.voice
            });
        });

        var res = wx.getSystemInfoSync();  //获取系统信息的同步方法
        baseWidth = res.windowWidth;       
        baseHeight = res.windowHeight;  
        //那就给前面的图片进行赋值，高，宽以及路劲   
        this.setData({   
            scaleHeight: baseHeight,      
            scaleWidth: baseWidth   
        });   
    },  
    //两手指进行拖动了  
    movetap:function(event){  
        var e = event;  
        if(e.touches.length == 2) {  
            var xMove = e.touches[1].clientX - e.touches[0].clientX;  
            var yMove = e.touches[1].clientY - e.touches[0].clientY;  
            var distance = Math.sqrt(xMove * xMove + yMove * yMove);//两手指之间的距离   
            if (olddistance == 0) {  
                olddistance = distance; //要是第一次就给他弄上值，什么都不操作  
                console.log(olddistance);   
            } else {  
                newdistance = distance; //第二次就可以计算它们的差值了  
                diffdistance = newdistance - olddistance;  
                olddistance = newdistance; //计算之后更新  
                console.log(diffdistance);  
                var newScale = oldscale + 0.005 * diffdistance;  //比例
                newScale = newScale < 1 ? 1 : newScale;   
                console.log(newScale);  
                //刷新.wxml  
                this.setData({   
                    scaleHeight: newScale * baseHeight,   
                    scaleWidth: newScale * baseWidth   
                })       
                oldscale = newScale;  
                //更新比例  
            }  
        }  
    },  
    endtap:function(event){  
        console.log(event);//抬起手指，保存下数据  
        if(event.touches.length == 2) {  
            olddistance = 0;  
        }  
    },  
})