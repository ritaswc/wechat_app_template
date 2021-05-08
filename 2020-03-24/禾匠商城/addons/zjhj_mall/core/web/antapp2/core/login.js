module.exports = function (args) {
    var app = this;
    let currentPage = app.page.currentPage;
    let currentPageOptions = app.page.currentPageOptions;
    if (currentPage && currentPage.route === 'pages/index/index') {
        if (app.platform === 'my') {
            return ;
        }
    }
    this.request({
        url:this.api.share.index,
        success:function(res){
            if(res.code == 0){
                app.page.setPhone();
                app.trigger.run(app.trigger.events.login);
            }
        }
    });
};
