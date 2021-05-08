module.exports =  function(){
    let user_info = this.core.getStorageSync(this.const.USER_INFO);
    if (user_info) {
        return user_info;
    } else {
        return '';
    }
}