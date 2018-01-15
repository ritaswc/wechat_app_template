//index.js
//获取应用实例
var app = getApp()
Page( {
    data: {
        today: {},
        forecast: [],
        imgUrls: [
            "http://103.27.4.65/files/banner/20160815/147123112168.jpg",
            "http://103.27.4.65/files/banner/20160815/14712455440.jpg"
        ],
        indicatorDots: true, //是否显示面板指示点
        autoplay: true, //是否自动切换
        interval: 3000, //自动切换时间间隔,3s
        duration: 1000, //	滑动动画时长1s
    },
    onLoad: function() {
        wx.getStorage({
            key: 'city',
            success: function(res) {
                console.log(res.data)
            } 
        })
        var city=wx.getStorageSync('city');
        console.error(city);
        if(city)
        {
            
        }
        var that = this;

        console.log( '=========onLoad========' )
        wx.request( {
            url: 'http://wthrcdn.etouch.cn/weather_mini',
            data: {
                city: '厦门',
            },
            header: {
                'Content-Type': 'application/json',
            },
            success: function( res ) {
                if( res.data.status != 1000 ) {
                    console.error("服务器访问失败！");
                    return;
                }
                var day={date: res.data.data.forecast[ 0 ].date,
                        type1: res.data.data.forecast[ 0 ].type,
                        hightemp: res.data.data.forecast[ 0 ].high,
                        lowtemp: res.data.data.forecast[ 0 ].low,
                        image:weatherImage(res.data.data.forecast[ 0 ].type),}
                var temp = [];
                //处理数组数据
                for( var i = 1;i < res.data.data.forecast.length;i++ ) {
                    temp.push( {
                        date: res.data.data.forecast[ i ].date,
                        type1: res.data.data.forecast[ i ].type,
                        hightemp: res.data.data.forecast[ i ].high,
                        lowtemp: res.data.data.forecast[ i ].low,
                        image:weatherImage(res.data.data.forecast[ i ].type),
                    })
                }
                console.log( temp );//输出数据
                that.setData(
                    {
                        today: day,
                        forecast: temp,
                    }
                )
            }
        })
    }
});

/**
 * 选择天气图片
 */
function weatherImage(type1)
{
    if(type1=='阵雨')
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/zhongyu.png";
    }
    else if(type1=='中雨')
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/zhongyu.png";
    }
    else if(type1=='大雨')
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/dayu.png";
    }
    else if(type1=='晴')
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/qing.png";
    }
    else if(type1=='阴')
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/yin.png";
    }
    else if(type1=='雷阵雨')
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/leizhenyu.png";
    }
    else if(type1=='多云')
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/duoyun.png";
    }
    else
    {
        return "http://7xnmrr.com1.z0.glb.clouddn.com/xiaoyu.png";
    }
}