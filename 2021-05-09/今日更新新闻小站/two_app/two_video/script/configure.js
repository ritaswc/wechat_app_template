/**
 * Created by Wonderchief on 2017/2/22.
 */
var Configure={
    __project__name__:"AR Plastic Tutor "
    ,__menu__:[
        {name:"用户管理",page:"html/userManage.html"}
        ,{name:"客户管理",page:"html/customerManage.html"}
        ,{name:"设备管理",page:"html/deviceManage.html"}
        // ,{name:"商户数据统计",page:"stats.html"}
        // ,{name:"销售额统计",page:"index.html"}
        // ,{name:"问题反馈",page:"index.html"}
        ,{name:"登出",page:"html/login.html"}
        ,{name:"你的身份为：管理员",page:"index.html"}
    ]
};
if(sessionStorage.appid==2){
    Configure.__menu__=[
        {name:"设备管理",page:"html/deviceManage.html"}

        ,{name:"登出",page:"html/login.html"}
        ,{name:"你的身份为：技术员",page:"index.html"}
    ]
}
if(sessionStorage.appid==3){
    Configure.__menu__=[
        {name:"客户管理",page:"html/customerManage.html"}
        ,{name:"设备管理",page:"html/deviceManage.html"}
        ,{name:"登出",page:"html/login.html"}
        ,{name:"你的身份为：业务员",page:"index.html"}
    ]
}

$("#__project__name__").text(Configure.__project__name__);
var $_memu = $("#__menu__");
$.each(Configure.__menu__,function (i,o) {
    var $_li = $("<li><a></a></li>");
    if(window.location.pathname.indexOf(o.page)>0);
        $_li.children("li").first().attr('class','active');
    $_li.children("a").first().attr('href',location.origin+"/arpt-official/"+o.page).text(o.name);
    $_memu.append($_li);
});
$("#__now__").text(moment().format('MMMM Do YYYY, h:mm:ss a'));
setInterval(function () {
    $("#__now__").text(moment().format('MMMM Do YYYY, h:mm:ss a'));
},1000);
