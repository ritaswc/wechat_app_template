import co from 'co';

import Course from '../model/course';
import SpecializedObj from '../model/specializedObj';


export function getSearchData(req, res) {
    co(function*() {
        // const { name, specializedObjs } = req.query;
        const { name, typeid } = req.query;
        // const specialObjQuery = JSON.parse(specializedObjs).map((item) => { return {specialObj: item}});

        let courses = [];

        if (name) {
            courses = yield Course.find({name}).exec();
        }

        if (typeid) {
            const { name } = yield SpecializedObj.findOne({_id: typeid}).exec();
            courses = yield Course.find({specialObj:name}).exec();
        }

        // const options = specialObjQuery.length === 0 ? {name} : {name, $or: specialObjQuery}
        // const courses = yield Course.find(options).exec();

        res.json(courses);
    }).catch((err) => {
      console.log(err);
    })
}





export default {
  getSearchData
}
