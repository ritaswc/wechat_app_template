import co from 'co';

import Video from '../model/video';


export function getQulityVideos(req, res) {
    co(function*() {
        const videos = yield Video.find({recommend: {$ne: null}}).limit(10).exec();
        res.json({videos});
    }).catch((err) => {
      console.log(err);
    })
}



export default {
  getQulityVideos
}
