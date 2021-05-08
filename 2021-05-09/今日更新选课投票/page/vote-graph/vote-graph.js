// page/vote-graph/vote-graph.js

// 路径是wxCharts文件相对于本文件的相对路径
var wxCharts = require('../../util/wxcharts.js');

var app = getApp();
var pieChart = null;
Page({
    data: {
       
    },
    touchHandler: function (e) {
        console.log(pieChart.getCurrentDataIndex(e));
    },        
    onLoad: function (e) {
       
        var windowWidth = 320;
        try {
            var res = wx.getSystemInfoSync();
            windowWidth = res.windowWidth;
        } catch (e) {
            console.error('getSystemInfoSync failed!');
        }
        var series = JSON.parse(e.evaluation);
        console.log(e.evaluation)
        pieChart = new wxCharts({
            animation: true,
            canvasId: 'ringCanvas',
            type: 'ring',
            series: series,
            width: windowWidth,
            height: 300,
            dataLabel: true,
        });
    }
});