import express from 'express';

import search from '../controller/search';

const router = express.Router();


router.get('/course', search.getSearchData);

export default router;
