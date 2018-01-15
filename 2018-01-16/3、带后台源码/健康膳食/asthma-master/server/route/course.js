import express from 'express';

import course from '../controller/course';

const router = express.Router();


router.get('/recommendedcourse', course.getRecommendedCourse);
router.get('/coursedetail/:id', course.getCourse);
router.get('/list', course.getCourses);


export default router;


