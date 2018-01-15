const app = getApp();

const star = '../../images/star_gan.png';
const starActive = '../../images/star_gan_active.png';

Page({
    data: {
        winWidth: 0,
        winHeight: 0,
        src: '',
        content: '',
        unit: '',
        teacher: '',
        currentTab: 0,
        commentDialogStatus: false,
        starImages: []
    },
    onLoad: function(options) {

        const {
            videoUrl,
            content,
            unit,
            teacher,
            name
        } = options;
        wx.setNavigationBarTitle({
            title:name
        });
        this.setImages();
        this.setData({
            src: videoUrl,
            content,
            unit,
            teacher
        });

        wx.pro.getSystemInfo().then((res) => {
            this.setData({
                winWidth: res.windowWidth,
                winHeight: res.windowHeight
            });
        }).catch((err) => {
            console.log(err);
        })

    },
    bindChange(e) {
        const that = this;
        that.setData({
            currentTab: e.detail.current
        });
    },
    switchNav(e) {
        const that = this;
        if (this.data.currentTab === e.target.dataset.current) {
            return false;
        } else {
            that.setData({
                currentTab: e.target.dataset.current
            })
        }
    },
    showCommentDialog() {
        const that = this;
        that.setData({commentDialogStatus: true});
    },
    hideCommentDialog(e) {
        const that = this;
        if (e.target.id === 'dialog-bg') {
            that.setData({commentDialogStatus: false});
        }
    },
    setImages() {
        const starImages = [];
        for (let i = 0; i < 5; i++) {
            starImages.push(star);
        }
        this.setData({
            starImages
        });
    },
    changeStar(e) {
        const starImages = [];
        const index = e.target.dataset.gindex;
        const that = this;
        for (let i = 0; i < 5; i++) {
            if (i <= index) {
                starImages.push(starActive);
            } else {
                starImages.push(star);
            }
        }
        that.setData({
            starImages
        });
    },
    submitComment() {
        const that = this;
        const rating = that.data.starImages.filter((image) => image === starActive).length;
        console.log(rating);
    }
})
