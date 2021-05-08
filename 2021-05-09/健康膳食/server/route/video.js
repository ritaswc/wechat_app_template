import express from 'express';

import video from '../controller/video';

const router = express.Router();


router.get('/qulityvideo', video.getQulityVideos);

export default router;

