import mongoose from 'mongoose';

const Banner = mongoose.model('banners', {
    status: Number, //是否发布1-已发布；0-未发布
    createDate: Date,
    modifyDate: Date,
    url: String, //图片链接地址
    img: String, //图片地址
    publishDate: Date, //发布时间
    title: String, //图片标题
});

export default Banner;
