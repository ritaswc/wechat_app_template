import co from 'co';

import Course from '../model/course';

const LIMIT = 5;


/**
 *  获取推荐课程
 * */
export function getRecommendedCourse(req, res) {
    co(function*() {
        const recommendedCourses = yield Course.find({
            recommend: {
                $ne: null
            }
        }).sort({
            recommend: -1
        }).exec();
        res.json({
            recommendedCourses
        });
    }).catch((err) => {
        console.log(err);
    })
}


export function getCourse(req, res) {
    co(function*() {
        const {
            id
        } = req.params;
        const course = yield Course.findOne({
            _id: id
        }).populate('courseware').exec();
        res.json({
            course
        });
    }).catch((err) => {
        console.log(err);
    })
}


export function getCourses(req, res) {
    co(function*() {
        let {
            page
        } = req.query;
        if (isNaN(parseInt(page)) || !page) {
            page = 1;
        }

        const skip = (page - 1) * LIMIT;
        const courses = yield Course.find({}).sort({
            'createDate': -1
        }).skip(skip).limit(LIMIT).exec();
        const count = yield Course.find({}).count().exec();

        res.json({
            courses,
            count,
            page
        });
    }).catch((err) => {
        console.log(err);
    })
}



export default {
    getRecommendedCourse,
    getCourse,
    getCourses
}
