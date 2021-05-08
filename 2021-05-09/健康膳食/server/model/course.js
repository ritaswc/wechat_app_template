import mongoose from 'mongoose';
const Schema = mongoose.Schema;

const { ObjectId } = Schema.Types;

const CourseSchema = new Schema({
    status: Number, //是否发布1-已发布；0-未发布
    createDate: Date,
    modifyDate: Date,
    category: String, //分类
    specialObj: String, //学组
    _category: String, //id
    _specialObj: String,
    section: String, //科室
    unit: String, //单位
    name: String, //课程标题
    content: String, //简介
    recommend: Date, //推荐时间
    publishDate: Date, //课程发布时间
    img: String, //课程缩略图
    courseware: [{
        type: ObjectId,
        ref: "Video"
    }], //课件id
    count: Number, //学习人数
    score_num: Number, //评分人数
    score: Number, //评分(总分)
    averageScore: Number, //平均分
    decideScore:[{
        userID:String, // 用户id
        score:Number // 评分
    }],

    itemvideo: [{
        vid: String,
        name: String
    }],
})



export default mongoose.model('Course', CourseSchema);
