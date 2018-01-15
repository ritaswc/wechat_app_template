import courseRoute from './course';
import bannerRoute from './banner';
import videoRoute from './video';
import specializedObjRoute from './specializedObj';
import searchRoute from './search';

import { getSecretInfo } from '../config/middleware';

export default (app) => {
    app.use('/course', courseRoute);
    app.use('/banner', bannerRoute);
    app.use('/video', videoRoute);
    app.use('/specializedObj', specializedObjRoute);
    app.use('/search', searchRoute);
    app.use('/weixin', getSecretInfo, (req, res) => {
        res.json(req.wxData);
    })
}
