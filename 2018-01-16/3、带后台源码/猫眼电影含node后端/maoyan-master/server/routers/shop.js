/**
 * Created by Administrator on 2017/3/16.
 */
var express = require('express');
var shop=require('../models/shop');

var router = express.Router();
var responseData;


router.use( function(req, res, next) {

    responseData = {
        code: 0,
        message: ''
    }
    next();
} );
router.get('/',function (req,res) {
    responseData.data=shop
    res.json(
        responseData
    )
    
})


module.exports = router;