/**
 * Created by Wonderchief on 2017/1/12.
 */
// <!--=====end 弹出框=====-->
$("body").append('<div class="alert alert-danger animated fadeInDown navbar-fixed-top text-center hidden" style="top:70px;z-index: 50000000" role="alert" id="alert">'+
    '<a href="#" class="alert-link" id="error"></a>'+
    '</div>')
$("body").append('<div class="alert alert-success animated fadeInDown navbar-fixed-top text-center hidden" style="top:70px;z-index: 50000000" role="alert" id="alertsuccess">'+
    '<a href="#" class="alert-link" id="success"></a>'+
    '</div>')
function error(message){
    var alerts=$("#alert")
    if(alerts.hasClass("hidden")){
        alerts.removeClass("hidden");
        setTimeout((function () {
            $("#alert").addClass("hidden");
            $("#error").html("");
        }),2000)
    }
    $("#error").html(message);
}
function success(message) {
    var alerts=$("#alertsuccess")
    if(alerts.hasClass("hidden")){
        alerts.removeClass("hidden");
        setTimeout((function () {
            $("#alertsuccess").addClass("hidden");
            $("#success").html("");
        }),1500)
    }
    $("#success").html(message);
}


///////////////////////////////////
var APPID = "";
function param(data) {
    var reqpack = {
        appid: APPID
        , core: {}
        , data: null
    };
    //进行装包操作,把本地的信息塞到core里,比如XXXid这些或者token

    if (sessionStorage.memberid != null) {
        reqpack.core.memberid = sessionStorage.memberid;
    }
    if (sessionStorage.token != null) {
        reqpack.token = sessionStorage.token;
    }
    reqpack.data = data;
    return reqpack
}

function checklogin(){
    if(sessionStorage.memberid&&sessionStorage.token&&sessionStorage.appid){
       if(sessionStorage.appid==1){
           APPID="webadmin"
       }else if(sessionStorage.appid==2){
           APPID="webtech"
       }
       else if(sessionStorage.appid==3){
           APPID="websale"
       }
        return true
    }else{
        return false
    }
}



//对jq中的ajax请求的封装，处理一些常用库
function req(url, data, fn) {

    var reqpack = {
        appid: APPID
        , core: {}
        , data: null
        , token: null
    };
    //进行装包操作,把本地的信息塞到core里,比如XXXid这些或者token
    if (sessionStorage.memberid != null) {
        reqpack.core.memberid = sessionStorage.memberid;
    }
    if (sessionStorage.token != null) {
        reqpack.token = sessionStorage.token;
    }
    reqpack.data = data;
    //消息处理程序
    var msgHandler=function (msg) {
        switch (msg.type)
        {
            case 0://空消息
                break;
            case 1://系统默认消息
                console.log(msg.data);
                break;
            case 2://系统异常消息
                error(msg.data)
                break;
            case 3://控制客户端重定向
                location.href=msg.data;
                break;
            case 4://控制客户端弹出消息框

                error(msg.data);

                break;
            case 5://控制localstorage
                $.each(msg.data,function (i,obj) {
                    localStorage.setItem(i,obj);
                });
                break;
            case 9://控制客户端eval执行js脚本
                eval(msg.data);
                break;
        }
    };
    //
    $.post(url, reqpack, function (pack) {
        console.log(pack);
        //进行解包操作,处理status
        switch(pack.status){
            case 0://正常状态
                //执行msg处理器
                if(pack.msg instanceof Array)
                {
                    for(var i in pack.msg)
                    {
                        msgHandler(pack.msg[i]);
                    }
                }
                else
                    msgHandler(pack.msg);
                break;
            case -1://服务器异常状态
                break;
            case 1://服务器繁忙状态
                break;
            case 2://没有权限访问
                error(pack.msg.data)
                setTimeout(function () {
                    location.reload();
                },2000)
                break;
            case 3://找不到对应的业务
                break;
        }
        fn(pack.result);
    }, 'JSON');

}

//获取QueryString的数组

function getQueryString(){

    var result = location.search.match(new RegExp("[\?\&][^\?\&]+=[^\?\&]+","g"));

    if(result == null){

        return "";

    }
    for(var i = 0; i < result.length; i++){

        result[i] = result[i].substring(1);

    }

    return result;

}

//根据QueryString参数名称获取值

function getQueryStringByName(name){

    var result = location.search.match(new RegExp("[\?\&]" + name+ "=([^\&]+)","i"));

    if(result == null || result.length < 1){

        return "";

    }

    return result[1];

}

//根据QueryString参数索引获取值

function getQueryStringByIndex(index){

    if(index == null){

        return "";

    }

    var queryStringList = getQueryString();

    if (index >= queryStringList.length){

        return "";

    }

    var result = queryStringList[index];

    var startIndex = result.indexOf("=") + 1;

    result = result.substring(startIndex);

    return result;

}