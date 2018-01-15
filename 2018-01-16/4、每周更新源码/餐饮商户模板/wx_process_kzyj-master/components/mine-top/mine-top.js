const aboutMe = () => {
    wx.navigateTo({
        url: '../../common/about-us/about-us',
        success: function(res){
        // success
        },
        fail: function() {
        // fail
        },
        complete: function() {
        // complete
        }
    })
}


//export default 
module.exports = {
    aboutMe
}
