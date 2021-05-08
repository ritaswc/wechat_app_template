const BusStationListService = require('../service/BusStationListService.js');
const ResultModel = require('../vo/ResultModel.js');
module.exports = function(app) {

    app.get('/fr/station/list', (req, res) => {

        let condition = req.query;

        let rm = new ResultModel();

        BusStationListService.queryListByCondition(condition).then((result) => {
            rm.setData(result);
            rm.setSuccseeCode();
            res.send(rm);
            res.end();
        })
    });
};