

const BusStationListMapper = require("../mapper/BusStationListMapper.js");

class BusStationListService {

    constructor() {

    }

    queryListByCondition(condition) {
        return BusStationListMapper.list(condition);
    }
}

module.exports = new BusStationListService();
