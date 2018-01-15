import mongoose from 'mongoose';

const { Schema } = mongoose;

const VideoSchema = new Schema({
    status: Number, //是否发布1-已发布；0-未发布
    createDate: Date,
    modifyDate: Date,
    category: String, //分类
    specialObj: String, //专科
    _category: String, //id
    _specialObj: String,
    teacher: String, //讲师
    section: String, //科室
    unit: String, //单位
    name: String, //标题
    content: String, //简介
    videoUrl: String, //视频地址
    videoName: String, //视频名字
    publishDate: Date, //视频发布时间
    img: String, //视频缩略图
    recommend: Date, //推荐时间
})


export default mongoose.model('Video', VideoSchema);
