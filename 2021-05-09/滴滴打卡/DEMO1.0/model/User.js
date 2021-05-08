const AV=require('../lib/av-weapp-min');

class User extends AV.Object{

    get UserId(){return this.get('UserId');}
    set UserId(value){this.set('UserId',value);}

    get UserNickname(){
        return this.get('UserNickname');
    }
    set UserNickname(value){
        this.set('UserNickname',value);
    }
    get UserLv(){
        return this.get('UserLv');
    }
    set UserLv(value){
        this.set('UserLv',value)
    }
}

AV.Object.register(User,'User');
module.exports=User;