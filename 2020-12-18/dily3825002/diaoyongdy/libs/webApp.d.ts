declare module  wx{

    /**
     * 发起的是https请求。一个微信小程序，同时只能有5个网络请求连接
     */
    export function request(obj:{
        /**开发者服务器接口地址*/
        url:string;
        /**请求的参数 */
        data?:any;
        /**设置请求的header , header中不能设置Referer*/
        header?:any;
        /**默认为GET，有效值：OPTIONS,GET,HEAD,POST,PUT,DELETE,TRACE,CONNECT */
        method?:string;
        /**收到开发者服务成功返回的回调函数，res = {data:"开发者服务器返回的内容"} */
        success?:(res:{})=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
    }):void;
    /**
     *  将本地资源上传到开发者服务器。如页面通过 wx.chooseImage 等接口获取到一个本地资源的临时文件路径后，可通过此接口将本地资源上传到指定服务器。客户端发起一个HTTPS POST请求，其中 Content-Type 为 multipart/form-data
     */
    export function uploadFile(obj:{
        /**开发者服务器url*/
        url:string;
        /**要上传文件资源的路径 */
        filePath:string;
        /**文件对应的key , 开发者在服务器端通过这个key可以获取到文件二进制内容 */
        name:string;
        /**HTTP 请求 Header */
        header?:any;
        /**HTTP 请求中其他额外的form data */
        fromData?:any;
        /**接口调用成功的回调函数 */
        success?:(res:{})=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
    }):void;
    /**
     * ​ 下载文件资源到本地。客户端直接发起一个HTTP GET请求，把下载到的资源根据 type 进行处理，并返回文件的本地临时路径。
     */
    export function downloadFile(obj:{
        /**下载资源的 url */
        url:string;
        /**下载资源的类型，用于客户端识别处理，有效值：image/audio/video */
        type?:string;
        /**HTTP 请求 Header */
        header?:any;
        /**下载成功后以 tempFilePath 的形式传给页面，res={tempFilePath:"文件的临时路径"} */
        success?:(res:{})=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
    }):void;

    /**
     * ​ 创建一个 WebSocket 连接；一个微信小程序同时只能有一个WebSocket连接，如果当前已存在一个WebSocket连接，会自动关闭该连接，并重新创建一个WebSocket连接。
     */
    export function connectSocket(obj:{
        /**开发者服务器接口地址，必须是HTTPS协议，且域名必须是后台配置的合法域名 */
        url:string;
        /**请求的数据 */
        data?:any;
        /**HTTP Header */
        header?:any;
        /**	默认是GET，有效值为： OPTIONS, GET, HEAD, POST, PUT, DELETE, TRACE, CONNECT */
        method?:string;
        /** 接口调用成功的回调函数*/
        success?:(res:{})=>void;
        /** 接口调用失败的回调函数*/
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;

    }):void;
    /**
     * 监听WebSocket连接打开事件
     */
    export function onSocketOpen(cb:(res?)=>void):void;
    /**
     * 监听WebSocket错误
     */
    export function onSocketError(cb:(res?)=>void):void;
    /**
     * 通过WebSocket连接发送数据，需要先wx.connectSocket，并在wx.onSocketOpen回调之后才能发送。
     */
    export function sendSocketMessage(obj:{
        /** 需要发送的内容*/
        data:string
    }):void;
    /**
     * 监听WebSocket接受到服务器的消息事件
     */
    export function onSocketMessage(cb:(res:{
        /**服务器返回的消息 */
        data?:string
    })=>void):void;
    /**
     * ​ 关闭WebSocket连接
     */
    export function closeSocket(cb:(res:{})=>void):void;
    /**
     * 监听WebSocket关闭
     */
    export function onSocketClose():void;
    /**
     * ​ 从本地相册选择图片或使用相机拍照
     */
    export function chooseImage(obj:{
        /**成功则返回图片的本地文件路径列表tempFilePaths */
        success:(res:{})=>void;
        /**最多可以选择的图片张数，默认9 */
        count?:number;
        /**"original"原图，"compressed"压缩图，默认二者都有 */
        sizeType?:string[];
        /**"album"从相册选图，"camera"使用相机，默认二者都由 */
        sourceType?:string[];
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**	接口调用结束的回调函数（调用成功、失败都会执行）*/
        complete?:()=>void;
    }):void;
    /**
     * ​ 预览图片
     */
     export function previewImage(obj:{
         /**需要预览的图片链接列表 */
        urls:string[];
        /**当前显示图片的链接，不填则默认为urls的第一张 */
        current?:string;
        /**接口调用成功的回调函数 */
        success?:(res:{})=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
    }):void;
    /**
     * ​ 开始录音。当主动调用wx.stopRecord，或者录音超过1分钟时自动结束录音，返回录音文件的临时文件路径
     */
    export function startRecord(obj:{
        /**录音成功后调用，返回录音文件的临时文件路径，res={tempFilePath:"录音文件的临时路径"} */
        success?:(res:{tempFilePath?:string})=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void
        /**接口调用结束的回调函数（调用成功、失败都会执行） */;
        complete?:()=>void;
    }):void;
    /**
     * ​主动调用停止录音。
     */
    export function stopRecord():void;
    /**
     * ​ ​ 开始播放语音，同时只允许一个语音文件正在播放，如果前一个语音文件还没播放完，将中断前一个语音播放。
     */
    export function playVoice(obj:{
        /**需要播放的语音文件的文件路径 */
        filePath:string;
        /**接口调用成功的回调函数 */
        success?:(res:{})=>void;
        /** 接口调用失败的回调函数*/
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
    }):void;
    /**
     * 暂停正在播放的语音。再次调用wx.playVoice播放同一个文件时，会从暂停处开始播放。如果想从头开始播放，需要先调用wx.stopVoice
     */
    export function pauseVoice():void;
    /**
     *结束播放语音
     */
    export function stopVoice():void;
    /**
     * 获取音乐播放状态
     */
    export function getBackgroundAudioPlayerState(cb:(data:{
        /**选定音频的长度（单位：s），只有在当前有音乐播放时返回 */
        duration:number;
        /**选定音频的播放位置（单位：s），只有在当前有音乐播放时返回 */
        currentPosition:number;
        /**播放状态（2：没有音乐在播放，1：播放中，0：暂停中） */
        status:number;
        /**音频的下载进度（整数，80 代表 80%），只有在当前有音乐播放时返回 */
        downloadPercent:number;
        /**歌曲数据链接，只有在当前有音乐播放时返回 */
        dataUrl:string;
    }
    )=>void):void;
    /**
     * 播放音乐，同时只能有一首音乐正在播放。
     */
    export function playBackgroundAudio(obj:{
        /**	音乐链接 */
        dataUrl:string;
        /**	音乐标题 */
        title?:string;
        /**	封面URL */
        coverImgUrl?:string;
    }):void;
    /**
     * 暂停播放音乐
     */
    export function pauseBackgroundAudio():void;
    /**
     * 控制音乐播放进度
     */
    export function seekBackgroundAudio(obj:{
        /**音乐位置，单位：秒 */
        position:number;
    }):void;

    /**
     * 停止播放音乐
     */
    export function stopBackgroundAudio():void;
    /**
     * 监听音乐播放
     */
    export function onBackgroundAudioPlay(cb:()=>void):void;
    /**
     * 监听音乐暂停
     */
    export function onBackgroundAudioPause(cb:()=>void):void;
    /**
     * 监听音乐停止
     */
    export function onBackgroundAudioStop(cb:()=>void):void;
    /**
     * ​ ​ ​ 保存文件到本地
     */
    export function saveFile(obj:{
        /**需要保存的文件的临时路径 */
        tempFilePath:string;
        /**返回文件的保存路径*/
        success?:(res:{
            /**文件的保存路径 */
            savedFilePath:string;
        })=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
    }):void;
     /**
     * ​ ​ ​ 拍摄视频或从手机相册中选视频，返回视频的临时文件路径。
     */
    export function chooseVideo(obj:{
        /**"album"从相册选视频，"camera"使用相机拍摄，默认为：['album', 'camera'] */
        sourceType?:string;
        /**拍摄视频最长拍摄时间，单位秒。最长支持60秒 */
        maxDuration?:number;
        /**前置或者后置摄像头，默认为前后都有，即：['front', 'back']*/
        camera?:string[];
        /**接口调用成功，返回视频文件的临时文件路径 */
        success?:(res:{
            /**选定视频的临时文件路径 */
            tempFilePath:string;
            /**选定视频的时间长度 */
            duration:number;
            /**选定视频的数据量大小 */
            size:number;
            /**返回选定视频的长 */
            height:number;
            /**返回选定视频的宽 */
            width:number;
        })=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
    }):void;
    /**
     * 将数据存储在本地缓存中指定的key中，会覆盖掉原来该key对应的内容，这是一个异步接口。
     */
     export function setStorage(obj:{
         /**本地缓存中的指定的key */
         key:string;
          /**需要存储的内容 */
         data:any;
     }):void;
     /**
      * 将DATA存储在本地缓存中指定的KEY中，会覆盖掉原来该KEY对应的内容，这是一个同步接口。
      *@param key 本地缓存中的指定的key
      *@param data 需要存储的内容
      */
     export function setStorageSync( key:string,data:any):void;
     /**
     * 从本地缓存中异步获取指定key对应的内容。
     */
     export function getStorage(obj:{
         /**本地缓存中的指定的key */
         key:string;
         /**接口调用的回调函数 */
         success:(res:{
             /**key对应的内容 */
             data:any
            })=>void;
     }):void;
     /**
      *  从本地缓存中同步获取指定key对应的内容。
      * @param key 本地缓存中的指定的key
      */
    export function getStorageSync(key:string):any;
    /**
      *  ​ 清理本地数据缓存
      */
     export function clearStorage():void;
     /**
      *  ​ 同步清理本地数据缓存
      */
     export function clearStorageSync():void;
     /**
      *  ​ ​ 获取当前的地理位置、速度
      */
     export function getLocation(obj:{
         /**成功获取地理位置的回调 */
        success:(res:{
            /**纬度，浮点数，范围为-90~90，负数表示南纬 */
            latitude:number;
            /**经度，浮点数，范围为-180~180，负数表示西经 */
            longitude:number;
            /**速度，浮点数，单位m/s */
            speed:number;
            /**位置的精确度 */
            accuracy:number;
        })=>void;
         /**默认为"wgs84"返回gps坐标，"gcj02"返回可用于wx.openLocation的坐标 */
        type?:string;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /** 接口调用结束的回调函数（调用成功、失败都会执行）*/
        complete?:()=>void;
     }):void;
     /**
      * 使用微信内置地图查看位置
      */
      export function openLocation(obj:{
          /**纬度，范围为-90~90，负数表示南纬 */
        latitude:number;
        /**经度，范围为-180~180，负数表示西经 */
        longitude:number;
        /**缩放比例，范围1~28，默认为28 */
        scale?:number;
        /**在查看位置界面底部显示的超链接,可点击跳转 */
        infoUrl?:string;
        /**位置名 */
        name?:string;
        /**地址的详细说明 */
        address?:string;
        /**接口调用成功的回调函数 */
        success?:(res:{})=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
     });
      /**
      * 获取网络类型
      */
      export function getNetworkType(obj:{
          /**返回网络类型networkType */
        success:(res:{
            /**返回网络类型2g，3g，4g，wifi */
            networkType:string
        })=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行）*/
        complete?:()=>void;
     }):void;
     /**
      * 获取系统信息
      */
      export function getSystemInfo(obj:{
        success:(res:{
            /**手机型号 */
            model:any;
            /**设备像素比 */
            pixelRatio:any;
            /**窗口宽度 */
            windowWidth:any;
            /**窗口高度 */
            windowHeight:any;
            /**微信设置的语言 */
            language:any;
            /**微信版本号 */
            version:any;
        })=>void;
     }):void;
     /**
      * 监听重力感应数据，频率：50次/秒
      */
      export function onAccelerometerChange(cb:(res:{
          /**X 轴 */
          x:number;
          /**Y 轴 */
          y:number;
          /**Z 轴 */
          z:number;
      })=>void):void;
     /**
      * 监听罗盘数据，频率：50次/秒
      */
      export function onCompassChange(cb:(res:{
          /*面对的方向度数 */
          direction:number;
      })=>void):void;

       /**
      * 动态设置当前页面的标题
      */
      export function setNavigationBarTitle(obj:{
          /**页面标题 */
        title?:string;
        /** 接口调用成功的回调函数*/
        success?:(res:{})=>void;
        /** 接口调用失败的回调函数*/
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
     }):void;

     /**
      * 在当前页面显示导航条加载动画
      */
      export function showNavigationBarLoading():void;
      /**
      * 隐藏导航条加载动画
      */
      export function hideNavigationBarLoading():void;
       /**
      * 保留当前页面，跳转到应用内的某个页面，使用wx.navigateBack可以返回到原页面。
      * 注意：为了不让用户在使用小程序时造成困扰，我们规定页面路径只能有五层，请尽量避免多层级的交互方式
      */
      export function navigateTo(obj:{
          /**需要跳转的应用内页面的路径 */
        url:string;
        /**接口调用成功的回调函数 */
        success?:(res:{})=>void;
        /**接口调用失败的回调函数 */
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
     }):void;
        /**
      * 关闭当前页面，跳转到应用内的某个页面。
      */
     export function redirectTo(obj:{
        /**需要跳转的应用内页面的路径 */
        url:string;
        /**接口调用成功的回调函数*/
        success?:(res:{})=>void;
        /**接口调用失败的回调函数*/
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行） */
        complete?:()=>void;
     }):void;
        /**
      * 关闭当前页面，回退前一页面
      */
     export function navigateBack():void;

     export interface animation{
         /**透明度，参数范围 0~1 */
         opacity(value:any):animation;
         /**颜色值 */
         backgroundColor(color:any):animation;
         /**长度值，如果传入 number 则默认使用 px，可传入其他自定义单位的长度值 */
         width(length:any):animation;
         /**长度值，如果传入 number 则默认使用 px，可传入其他自定义单位的长度值 */
         height(length:any):animation;
         /**长度值，如果传入 number 则默认使用 px，可传入其他自定义单位的长度值 */
         top(length:any):animation;
         /**长度值，如果传入 number 则默认使用 px，可传入其他自定义单位的长度值 */
         left(length:any):animation;
         /**长度值，如果传入 number 则默认使用 px，可传入其他自定义单位的长度值 */
         bottom(length:any):animation;
         /**长度值，如果传入 number 则默认使用 px，可传入其他自定义单位的长度值 */
         right(length:any):animation;

         /**deg的范围-180~180，从原点顺时针旋转一个deg角度 */
         rotate(deg:any):animation;
         /**deg的范围-180~180，从原点顺时针旋转一个deg角度 */
         rotateX(deg:any):animation;
         /**deg的范围-180~180，从原点顺时针旋转一个deg角度 */
         rotateY(deg:any):animation;
         /**deg的范围-180~180，从原点顺时针旋转一个deg角度 */
         rotateZ(deg:any):animation;
         /**deg的范围-180~180，从原点顺时针旋转一个deg角度 */
         rotate3d(x:any,y:any,z:any,deg:any):animation;

         /**一个参数时，表示在X轴、Y轴同时缩放sx倍数；两个参数时表示在X轴缩放sx倍数，在Y轴缩放sy倍数*/
         scale(sx:any,sy?:any):animation;
         /**在X轴缩放sx倍数*/
         scaleX(sx:any):animation;
         /**在Y轴缩放sy倍数*/
         scaleY(sy:any):animation;
         /**在Z轴缩放sy倍数*/
         scaleZ(sz:any):animation;
         /**在X轴缩放sx倍数，在Y轴缩放sy倍数，在Z轴缩放sz倍数 */
         scale3d(sx:any,sy:any,sz:any):animation;

         /**一个参数时，表示在X轴偏移tx，单位px；两个参数时，表示在X轴偏移tx，在Y轴偏移ty，单位px。 */
         translate(tx:any,ty?:any):animation;
         /**在X轴偏移tx，单位px */
         translateX(tx:any):animation;
         /**在Y轴偏移ty，单位px */
         translateY(ty:any):animation;
         /**在Y轴偏移tz，单位px */
         translateZ(tz:any):animation;
         /**在X轴偏移tx，在Y轴偏移ty，在Z轴偏移tz，单位px */
         translate3d(tx:any,ty:any,tz:any):animation;

         /**参数范围-180~180；一个参数时，Y轴坐标不变，X轴坐标延顺时针倾斜ax度；两个参数时，分别在X轴倾斜ax度，在Y轴倾斜ay度 */
         skew(ax:any,ay?:any):animation;
         /**参数范围-180~180；Y轴坐标不变，X轴坐标延顺时针倾斜ax度 */
         skewX(ax:any):animation;
         /**参数范围-180~180；X轴坐标不变，Y轴坐标延顺时针倾斜ay度 */
         skewY(ay:any):animation;
         matrix(a:any,b:any,c:any,d:any,tx:any,ty:any):animation;
         matrix3D(a1, b1, c1, d1, a2, b2, c2, d2, a3, b3, c3, d3, a4, b4, c4, d4):animation;
         step(obj:{
               /**动画持续时间，单位ms，默认值 400 */
            duration?:number;
             /** 定义动画的效果，默认值"linear"，有效值："linear","ease","ease-in","ease-in-out","ease-out","step-start","step-end"*/
            timingFunction?:string;
             /**动画持续时间，单位 ms，默认值 0 */
            delay?:number;
              /**设置transform-origin，默认为"50% 50%" */
            transformOrigin?:string;
        }):void;
        /**注意: export 方法每次调用后会清掉之前的动画操作 */
         export():any;
     }
        /**
         *  创建一个动画实例animation。调用实例的方法来描述动画。最后通过动画实例的export方法导出动画数据传递给组件的animation属性。
        *  注意: export 方法每次调用后会清掉之前的动画操作
      */
     export function createAnimation(obj:{
         /**动画持续时间，单位ms，默认值 400 */
        duration?:number;
        /** 定义动画的效果，默认值"linear"，有效值："linear","ease","ease-in","ease-in-out","ease-out","step-start","step-end"*/
        timingFunction?:string;
        /**动画持续时间，单位 ms，默认值 0 */
        delay?:number;
        /**设置transform-origin，默认为"50% 50%" */
        transformOrigin?:string;

     }):animation;

      export interface context{
        /**
         * 获取当前context上存储的绘图动作
         */
          getActions():any;
          /**
           * 清空当前的存储绘图动作
           */
          clearActions():void;
          /**
           * 对横纵坐标进行缩放
           * @param scaleWidth 横坐标缩放的倍数(1=100%，0.5=50%，2=200%，依次类推)
           * @param scaleHeight 纵坐标缩放的倍数(1=100%，0.5=50%，2=200%，依次类推)
           */
          scale(scaleWidth:number,scaleHeight:number):void;
          /**
           * 对坐标轴进行顺时针旋转
           * @param value 旋转角度，以弧度计(degrees * Math.PI/180；degrees范围为0~360)
           */
          rotate(value:number):void;
          /**
           * 对坐标原点进行缩放
           * @param x 水平坐标平移量
           * @param y 竖直坐标平移量
           */
          translate(x:number,y:number):void;
          /**
           * 保存当前坐标轴的缩放、旋转、平移信息
           */
          save():void;
          /**
           * 恢复之前保存过的坐标轴的缩放、旋转、平移信息
           */
          restore():void;
          /**
           * 清除画布上在该矩形区域内的内容
           * @param x 矩形区域左上角的x坐标
           * @param y 矩形区域左上角的y坐标
           * @param width 矩形区域的宽度
           * @param height 矩形区域的高度
           */
          clearRect(x:number,y:number,width:number,height:number):void;
          /**
           * 在画布上绘制被填充的文本。
           * @param text 在画布上输出的文本
           * @param x 绘制文本的左上角x坐标位置
           * @param y 绘制文本的左上角y坐标位置
           */
          fillText(text:string,x:number,y:number):void;
          /**
           * 绘制图像，图像保持原始尺寸。
           * @param imageResource 所要绘制的图片资源(通过chooseImage得到一个文件路径或者一个项目目录内的图片);
           * @param x 图像左上角的x坐标
           * @param y 图像左上角的y坐标
           */
          drawImage(imageResource:string,x:number,y:number):void;
          /**
           * 对当前路径进行填充
           */
          fill():void;
            /**
           * 对当前路径进行描边
           */
          stroke():void;
          /**开始一个路径 */
          beginPath():void;
          /**关闭一个路径。 */
          closePath():void;
           /**把路径移动到画布中的指定点，不创建线条。。
            * @param x 目标位置的x坐标
            * @param y 目标位置的y坐标
           */
          moveTo(x:number,y:number):void;
          /**在当前位置添加一个新点，然后在画布中创建从该点到最后指定点的路径。
            * @param x 目标位置的x坐标
            * @param y 目标位置的y坐标
          */
          lineTo(x:number,y:number):void;
          /**添加一个矩形路径到当前路径。
            * @param x 矩形路径左上角的x坐标
            * @param y 矩形路径左上角的y坐标
            * @param width 矩形路径的宽度
            * @param height 矩形路径的高度
           */
          rect(x:number,y:number,width:number,height:number):void;
          /** 添加一个弧形路径到当前路径，顺时针绘制。
           * @param x 矩形路径左上角的x坐标
           * @param y 矩形路径左上角的y坐标
           * @param radius 矩形路径的宽度
           * @param startAngle 起始弧度
           * @param sweepAngle 从起始弧度开始，扫过的弧度
          */
          arc(x:number,y:number,radius:number,startAngle:number,sweepAngle:number):void;
          /**创建二次贝塞尔曲线路径。
           *  @param cpx 贝塞尔控制点的x坐标
           *  @param cpy 贝塞尔控制点的y坐标
           *  @param x 结束点的x坐标
           *  @param y 结束点的y坐标
          */
          quadraticCurveTo(cpx:number,cpy:number,x:number,y:number):void;
          /**创建三次方贝塞尔曲线路径。
           *  @param cp1x 第一个贝塞尔控制点的 x 坐标
           *  @param cp1y 第一个贝塞尔控制点的 y 坐标
           *  @param cp2x 第二个贝塞尔控制点的 x 坐标
           *  @param cp2y 第二个贝塞尔控制点的 y 坐标
           *  @param x 结束点的x坐标
           *  @param y 结束点的y坐标
          */
          bezierCurveTo(cp1x:number,cp1y:number,cp2x:number,cp2y:number,x:number,y:number):void;
          /**设置纯色填充。
           * @param color 设置为填充样式的颜色('rgb(255, 0, 0)'或'rgba(255, 0, 0, 0.6)'或'#ff0000'格式的颜色字符串)
          */
          setFillStyle(color:string):void;
          /**设置纯色描边
           * @param color 设置为填充样式的颜色('rgb(255, 0, 0)'或'rgba(255, 0, 0, 0.6)'或'#ff0000'格式的颜色字符串)
          */
          setStrokeStyle(color:string):void;
          /**设置阴影样式。
           *  @param offsetX 阴影相对于形状在水平方向的偏移
           *  @param offsetY 阴影相对于形状在竖直方向的偏移
           *  @param blur 阴影的模糊级别，数值越大越模糊(0~100)
           *  @param color 阴影的颜色('rgb(255, 0, 0)'或'rgba(255, 0, 0, 0.6)'或'#ff0000'格式的颜色字符串)
          */
          setShadow(offsetX:number,offsetY:number,blur:number,color:any):void;
          /**设置字体的字号。
           * @param fontSize 字体的字号
          */
          setFontSize(fontSize:number):void;
          /**设置线条的宽度。
           * @param lineWidth 线条的宽度
          */
          setLineWidth(lineWidth:number):void;
          /**设置线条的结束端点样式。
           * @param lineCap 线条的结束端点样式('butt'、'round'、'square')
          */
          setLineCap(lineCap:string):void;
          /**设置两条线相交时，所创建的拐角类型。
           * @param lineJoin 两条线相交时，所创建的拐角类型('bevel'、'round'、'miter')
          */
          setLineJoin(lineJoin:string):void;
          /**设置最大斜接长度，斜接长度指的是在两条线交汇处内角和外角之间的距离。 当setLineJoin为'miter'时才有效。超过最大倾斜长度的，连接处将以lineJoin为bevel来显示
           * @param miterLimit 最大斜接长度
          */
          setMiterLimit(miterLimit:number):void;
      }
      /**
       * 创建并返回绘图上下文context对象。
​         context只是一个记录方法调用的容器，用于生成记录绘制行为的actions数组。context跟<canvas/>不存在对应关系，一个context生成画布的绘制动作数组可以应用于多个<canvas/>。
       */
      export function createContext():context;

      export function drawCanvas(obj:{
          /**画布标识，传入<canvas/>的cavas-id */
          canvasId:string;
          /**绘图动作数组，由wx.createContext创建的context，调用getActions方法导出绘图动作数组。 */
          actions:[any];
      }):void;
      /**​ 收起键盘 */
      export function hideKeyboard():void;
      /** 停止当前页面下拉刷新*/
      export function stopPullDownRefresh():void;
      /**
       * 调用接口获取登录凭证(code)进而换取用户登录态信息，包括用户的唯一标识(openid) 及本次登录的 会话密钥(session_key)。用户数据的加解密通讯需要依赖会话密钥完成。
       */
      export function login(obj:{
          /**接口调用成功的回调函数 */
        success?:(res:{
            /**调用结果 */
            errMsg:string;
            /**用户允许登录后，回调内容会带上code（有效期五分钟），开发者需要将code发送到开发者服务器后台，使用code换取session_key api，将code换成openid和session_key */
            code:string;
        })=>void;
        /**接口调用失败的回调函数*/
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行）*/
        complete?:()=>void;
     }):void;
     /**
      * 获取用户信息，需要先调用wx.login接口
      */
     export function getUserInfo(obj:{
         success:(res:{
             /**用户信息对象 */
             userInfo:any;
             /**原始数据，用于计算签名。 */
             rawData:string;
             /**使用rawData+sessionkey签名得到的哈希值，用于校验用户信息。 */
             signature:string;
             /**用sessionKey加密后做base64encode的数据 */
             encryptData:string;
         })=>void;
     }):void;
     /**
      * 发起微信支付
      */
     export function requestPayment(obj:{
         /**时间戳从1970年1月1日00:00:00至今的秒数,即当前的时间 */
         timeStamp:number;
         /**随机字符串，长度为32个字符以下。 */
         nonceStr:string;
         /**统一下单接口返回的prepay_id参数值，提交格式如：prepay_id=* */
         package:string;
         /**签名算法，暂支持MD5 */
         signType:string;
         /**签名,具体签名方案参见微信公众号支付帮助文档(https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=4_3&t=1474537654457); */
         paySign:string;
        /**接口调用成功的回调函数 */
        success?:(res:{})=>void;
         /**接口调用失败的回调函数*/
        fail?:()=>void;
        /**接口调用结束的回调函数（调用成功、失败都会执行）*/
        complete?:()=>void;
     }):void;
}
/**函数用来注册一个小程序。接受一个object参数，其指定小程序的生命周期函数等。 */
declare function App(obj:{
    /**生命周期函数--监听小程序初始化(当小程序初始化完成时，会触发onLaunch（全局只触发一次）） */
    onLaunch?:()=>void;
    /**生命周期函数--监听小程序显示（当小程序启动，或从后台进入前台显示，会触发onShow） */
    onShow?:()=>void;
    /**生命周期函数--监听小程序隐藏（	当小程序从前台进入后台，会触发onHide） */
    onHide?:()=>void;
}):void;
declare function Page(obj:{
    /**页面的初始数据 */
    data?:{};
    /**生命周期函数--监听页面加载 */
    onLoad?:()=>void;
    /**生命周期函数--监听页面渲染完成 */
    onReady?:()=>void;
    /**生命周期函数--监听页面显示 */
    onShow?:()=>void;
    /**生命周期函数--监听页面隐藏 */
    onHide?:()=>void;
    /**生命周期函数--监听页面卸载 */
    onUnload?:()=>void;
}):void;
/*全局的getApp()函数，可以获取到小程序实例 */
declare function getApp():{
    /**用户获取当前页面的实例。 */
    getCurrentPage:()=>void
}


