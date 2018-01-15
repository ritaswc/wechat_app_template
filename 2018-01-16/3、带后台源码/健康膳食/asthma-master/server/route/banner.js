import express from 'express';

import banner from '../controller/banner';

const router = express.Router();


router.get('/', banner.getBanners);


export default router;
