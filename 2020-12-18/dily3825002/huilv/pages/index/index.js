//index.js

var logic = require("../../logic/logic.js")
//获取应用实例
var app = getApp()
Page({
    data: {
        hv: [],
        tempNum:"0",
        arr:[]
    },
    //事件处理函数
    clickNumFun: function(e) {
        // wx.navigateTo({ url: "../country/country" })
        var id = e.currentTarget.id;
        switch (id) {
            case "C": 
                this.data.tempNum = "0";
            break;
            case "d": 
                this.data.tempNum = this.data.tempNum.substring(0, this.data.tempNum.length - 1);
                if (this.data.tempNum.length == 0) {
                    this.data.tempNum = "0";
                }
            break;
            default:
                this.data.tempNum += id;

        }
        if (parseInt(this.data.tempNum) == 0) {
            this.data.tempNum = "0";
            this.onChange(app.cid, "100");
            return;
        }
        if (this.data.tempNum.length > 1 && this.data.tempNum[0] == "0") {
            this.data.tempNum = this.data.tempNum.substring(1);
        }
        console.log(this.data.tempNum);


        this.onChange(app.cid, this.data.tempNum);
    },

    clickCountryItemFun: function(e) {
        app.cid = e.currentTarget.id;
        this.data.tempNum = "0";

        this.onChange(app.cid, "100");
    },

    //事件处理函数
    clickCountryFun: function(e) {
        app.cid = e.currentTarget.id;

        wx.navigateTo({ url: "../country/country" })

    },
    onChange:function (id, value) {
        this.data.hv[id].m = value;

        if (!this.data.arr) {
            return;
        }

        var basePer = value / this.data.arr[id][2];

        var hvs = [];
        for (var i = 0; i < this.data.arr.length; i++) {
            this.data.hv[i].m = ((this.data.arr[i])[2] * basePer).toFixed(2);

            if (i == id) {
                this.data.hv[id].class = "input2";
            }
            else {
                this.data.hv[id].class = "input1";
            }
        }

        this.setData( {hv: this.data.hv} );
    },

    onLoad: function() {
        var self = this;
        logic.getList(function(arr){
            var cs = {}
            for( var i=0;i<arr.length;i++)
            {
                cs[arr[i].name] = arr[i].code
            }
            app.couns = cs
            //console.log(cs)
            logic.getExchange( function(arr){
                self.data.arr = arr;

                var hvs = [];
                //console.log( arr )
                for( var i=0;i<arr.length;i++){
                    //var obj = { name:arr[i][0], code:app.couns[arr[i][0]], input:arr[i][2], l:(arr[i][1]/arr[i][2]),m:(arr[i][1]/arr[i][2])*100 }
                    var obj = { name:arr[i][0], code:app.couns[arr[i][0]], l:(arr[i][1]/arr[i][2]),m:(arr[i][1]/arr[i][2])*100 }
                    obj.m = obj.m.toFixed(2)
                    hvs.push( obj )
                }
                //console.log( hvs )
                self.setData({ hv: hvs })
                self.onChange(0, "100");
            } )
        })
        app.cid = 0;
    },
    onShow: function(){
        if(app.change!=null)
        {
            var objs = this.data.hv;
            //console.log( objs )
            objs[ app.cid ] = app.change;
            app.change = null
            //console.log( objs )
            this.setData( {hv:objs} );
        }
    }
})