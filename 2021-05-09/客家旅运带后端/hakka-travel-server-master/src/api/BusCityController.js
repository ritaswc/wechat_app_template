

var BusCityService = require('../service/BusCityService.js');
var ResultModel = require('../vo/ResultModel.js');
module.exports = function(app) {

    // get
    app.get('/fr/city/get/:id', (req, res) => {


    });

    app.get('/fr/city/list', (req, res) => {

        console.log('invoke /fr/city/list---------------');

        let condition = req.query;
        console.log(condition);
        let rm = new ResultModel();

        BusCityService.queryListByCondition(condition).then((result) => {

            console.log('result-------------');
            console.log(result);
            rm.setData(result);
            rm.setSuccseeCode();
            res.send(rm);
            res.end();
        })

    });

};