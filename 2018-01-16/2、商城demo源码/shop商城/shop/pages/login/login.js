import * as utils from '../../utils/util.js';
import S_request from '../../utils/requestService.js';
let app = getApp();

Page({
    data : {
        modal : {
            hidden : true,
            msg : ''
        },
        toast : {
            hidden : true,
            msg : ''
        }
    },
    formSubmit : function(e){
        console.log(e);
        let email = e.detail.value.email,
            pwd = e.detail.value.pwd;

        if(!email){
            this.setData({
                "modal.hidden" : false,
                "modal.msg" : "请填写手机号"
            });
            return ;

        }else if(!pwd){
            this.setData({
                "modal.hidden" : false,
                "modal.msg" : "请填写密码"
            });
            return ;
        }
        pwd = utils.md5(pwd);
        S_request.login(email, pwd, (res) => {
            var status = res.data.status;
            if(status == 1){
                this.setData({
                    "modal.hidden" : false,
                    "modal.msg" : res.data.message
                });
                return ;
            }
            wx.setStorageSync('user_info', res.data.data);
            this.setData({
                "toast.hidden" : false,
                "toast.msg" : res.data.message
            });
            setTimeout(() =>{
                wx.navigateBack();
            },1500);
        })

    },
    modalChange : function(e){
        this.setData({
            "modal.hidden" : true,
            "modal.msg" : ""

        })
    }
})