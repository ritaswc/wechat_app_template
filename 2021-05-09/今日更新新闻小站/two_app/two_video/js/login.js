/**
 * Created by Aber on 17/3/17.
 */
$(document).ready(function(){
    sessionStorage.removeItem("token")
    sessionStorage.removeItem("memberid")
    sessionStorage.removeItem("appid")
    $("#login").click(function(){
        var action = $("#lg-form").attr('action');
        var form_data = {
            username: $("#username").val(),
            password: $("#password").val()
        };
        // 9cfb86c00dde11e79d60e7e74ace2be0
        // 913446
        if(form_data.username&&form_data.password){
            req("/sys_member/login",form_data ,function (d) {
                if(d.status){
                    $("#lg-form").slideUp('slow', function(){
                        $("#message").html('<p class="success">登陆成功!</p><p>Redirecting....</p>');
                    });
                    setTimeout(function () {
                        sessionStorage.setItem("token",d.data.token)
                        sessionStorage.setItem("memberid",d.data.memberid)
                        sessionStorage.setItem("appid",d.data.role)
                        sessionStorage.setItem("data",JSON.stringify(d.data))
                        location.href="../index.html"
                    },1500)
                }
                else
                    $("#message").html('<p class="error">ERROR:密码或者用户名错误</p>');
            })
        }else{
            error("用户名或密码不能为空")
        }

    });
});