import express from 'express';

import specializedObj from '../controller/specializedObj';

const router = express.Router();

router.get('/all', specializedObj.getAll);




export default router;

