
function query(text) {   //拼音进行排序
    var str = text.trim();   //去除空格
    if (str == "") return;   //如果为空返回
    var arrRslt = makePy(str);  //制作成拼音
    return arrRslt;
}
//数据的初始化
function init(array, ids, that, callback) {
    var temData = that.data.wxSortPickerData;
    if (typeof temData == 'undefined') {
        temData = {};
    }
    that.wxSortPickerViewUpper = wxSortPickerViewUpper;
    that.wxSortPickerViewLower = wxSortPickerViewLower;
    that.wxSortPickerViewScroll = wxSortPickerViewScroll;
    that.wxSortPickerViewTemTagTap = wxSortPickerViewTemTagTap;
    setViewWH(that);
    buildTextData(that, array, ids);
}
function buildTextData(that, arr, ids) {
    var tempData = [{ tag: "安徽", textArray: [] },
    { tag: "北京", textArray: [] },
    { tag: "重庆", textArray: [] },
    { tag: "福建", textArray: [] },
    { tag: "甘肃", textArray: [] },
    { tag: "广东", textArray: [] },
    { tag: "广西", textArray: [] },
    { tag: "贵州", textArray: [] },
    { tag: "河北", textArray: [] },
    { tag: "河南", textArray: [] },
    { tag: "黑龙江", textArray: [] },
    { tag: "湖北", textArray: [] },
    { tag: "湖南", textArray: [] },
    { tag: "吉林", textArray: [] },
    { tag: "江苏", textArray: [] },
    { tag: "江西", textArray: [] },
    { tag: "辽宁", textArray: [] },
    { tag: "内蒙", textArray: [] },
    { tag: "山东", textArray: [] },
    { tag: "山西", textArray: [] },
    { tag: "陕西", textArray: [] },
    { tag: "上海", textArray: [] },
    { tag: "四川", textArray: [] },
    { tag: "天津", textArray: [] },
    { tag: "新疆", textArray: [] },
    { tag: "云南", textArray: [] },
    { tag: "浙江", textArray: [] },
    { tag: "#", textArray: [] },];
    var textData = new Array();
    var temABC = ["安徽", "北京", '重庆', '福建', '甘肃', '广东', '广西', '贵州', '河北', '河南', '黑龙', '湖北', '湖南', '吉林', '江苏',
        '江西', '辽宁', '内蒙', '山东', '山西', '陕西', '上海', '四川', '天津', '新疆', '云南', '浙江', 'PC'];
    for (var i = 0; i < ids.length; i++) {     //这里进行数据的判断的。
        //大致思路：获得标签，将彩票与标签进行对比，如果匹配成功，则将它归为一类
        var text = arr[ids[i]][0]['area'];
        //确定放置位置
        var firstChar = text.substr(0, 2).toString();   //截取字符串
        //var reg = query(firstChar[0])[0];       //将首字母进行排序  
        var temIndex = temABC.indexOf(firstChar);  //位置的确定
        tempData[temIndex].textArray.push(arr[ids[i]]);  //将数据push到指定的位置      
    }
    // console.log('数组'+tempData);


    //长度问题()    
    for(var i = 0; i < tempData.length; i++){
        //进行数据的判断，如果没有长度，将pop函数出来
        if(tempData[i].textArray.length != 0){
            textData.push(tempData[i]);
        }
        
    }
    var temData = that.data.wxSortPickerData;
    if (typeof temData == 'undefined') {
        temData = {};
    }
    temData.textData = textData;
    that.setData({
        wxSortPickerData: temData
    })
}
function wxSortPickerViewUpper(e) {
    console.dir(e);
}
function wxSortPickerViewLower(e) {
    console.dir(e);
}
function wxSortPickerViewScroll(e) {
    console.log(e.detail.scrollTop);
}

function setViewWH(that) {
    wx.getSystemInfo({
        success: function (res) {
            // console.dir(res);
            var windowWidth = res.windowWidth;
            var windowHeight = res.windowHeight;
            var temData = that.data.wxSortPickerData;
            if (typeof temData == 'undefined') {
                temData = {};
            }
            var view = {};
            view.scrollHeight = windowHeight;
            temData.view = view;
            that.setData({
                wxSortPickerData: temData
            })
        }
    })
}
function wxSortPickerViewTemTagTap(e) {
    var that = this;
    var temData = that.data.wxSortPickerData;
    temData.wxSortPickerViewtoView = e.target.dataset.tag;
    that.setData({
        wxSortPickerData: temData
    })

}
module.exports = {
    init: init,
    query: query
}