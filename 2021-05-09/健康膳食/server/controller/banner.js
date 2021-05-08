import co from 'co';

import Wxbanner from '../model/wxbanner';


export function getBanners(req, res) {
    co(function*() {
        const banners = yield Wxbanner.find({status: 1}).limit(3).exec();
        res.json({banners});
    }).catch((err) => {
      console.log(err);
    })
}




export default {
  getBanners
}
