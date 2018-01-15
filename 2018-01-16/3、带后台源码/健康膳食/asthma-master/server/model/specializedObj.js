import mongoose from 'mongoose';

const { Schema } = mongoose;

const SpecializedObjSchema = new Schema({
    name : String, //分类名字
    status: Number, //是否发布1-已发布；0-未发布
    type: Number, //类型：1-行业资讯；2-视频课件
    createDate: Date,
    modifyDate: Date,
});


export default mongoose.model('SpecializedObj', SpecializedObjSchema);
