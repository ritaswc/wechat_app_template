/**
 * Created by Administrator on 2017/3/4.
 */
var express = require('express');
var theater=require('../models/theater')
var router = express.Router();
var responseData;
var cityTheater=theater.cityTheater;
var indexTheater=theater.indexTheater;
var seatdata=theater.seatdata;
router.use( function(req, res, next) {
    responseData = {
        code: 0,
        message: ''
    }
    next();
} );
router.get('/cityTheater',function (req,res) {
var city=req.query.city;
    responseData.data=cityTheater;
    res.json(responseData);


});
router.get('/indexTheater',function (req,res) {
    var city=req.query.city;
    var id =req.query.id;
    responseData.data=indexTheater;
    res.json(responseData);


});
router.get('/seatdata',function (req,res) {
    responseData.data=seatdata;
    res.json(responseData);


});
module.exports=router;
