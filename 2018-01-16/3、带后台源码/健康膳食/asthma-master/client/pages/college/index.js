var app = getApp()
const {
    getAllSpecializedObj,
    searchCourse,
    recommendedCourse
} = require('../../utils/api');

Page({
    data: {
        focus: true,
        showMoreCollegeGroup: true,
        winWidth: 0,
        winHeight: 0,
        specializedObjs: [],
        searchInputValue: ''
    },
    onLoad: function() {
        var that = this
        wx.pro.request({
            url: getAllSpecializedObj
        }).then((data) => {
            const specializedObjs = data.specializedObjs.map((item) => {
                return {
                    id: item._id,
                    name: item.name,
                    active: false
                };
            });

            that.setData({
                specializedObjs
            });

        }).catch((err) => {
            console.log(err);
        });
        wx.pro.getSystemInfo().then((res) => {
            that.setData({
                winWidth: res.windowWidth,
                winHeight: res.windowHeight
            });
        }).catch((err) => {
            console.log(err);
        })
    },
    toggleActive(e) {
        const that = this;
        const typeid = e.target.dataset.typeid;
        wx.navigateTo({
            url: `../searchlist/index?typeid=${typeid}`
        });
    },

    bindKeyInput(e) {
        const that = this;
        that.setData({
            searchInputValue: e.detail.value
        });
    },

    searchBySpecializedObj(e) {
        const that = this;
        const specializedObj = e.target.dataset
    },
    submitSearch() {
        const that = this;
        const name = that.data.searchInputValue;

        if (!name) {
            return false;
        }

        wx.navigateTo({
            url: `../searchlist/index?name=${name}`
        });
    },

    toggleShowMore() {
        const that = this;
        that.setData({
            showMoreCollegeGroup: !that.data.showMoreCollegeGroup
        })
    }
})
