import express from 'express';
import compression from 'compression';
import morgan from 'morgan';
import cors from 'cors';
import bodyParser from 'body-parser';
import {join} from 'path';


export default(app) => {

    app.use(cors());
    app.use(express.static(join(__dirname, '../public')));
    app.use(compression());
    app.use(morgan('dev'));
    app.use(bodyParser.json());
    app.use(bodyParser.urlencoded({extended: true}));
}
