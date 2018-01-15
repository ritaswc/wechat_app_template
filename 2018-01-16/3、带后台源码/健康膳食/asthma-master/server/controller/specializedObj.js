import co from 'co';

import SpecializedObj from '../model/specializedObj';



export function getAll(req, res) {
    co(function* () {
        const specializedObjs = yield SpecializedObj.find({}).exec();
        res.json({specializedObjs});
    }).catch((err) => {
      console.log(err);
    })
}



export default {
  getAll
}
