
const BusCityMapper = require("../mapper/BusCityMapper.js");

class BusCityService {

    constructor() {

    }

    queryListByCondition(condition) {
        return BusCityMapper.list(condition);
    }
}

module.exports = new BusCityService();
