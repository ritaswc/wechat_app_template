import express from 'express';
import mongoose from 'mongoose';
import Promise from 'bluebird';

import expressConfig from './config/express';
import routes from './route/';
import config from './config/';

mongoose.Promise = Promise;

const app = express();
const port = 1988;

expressConfig(app);
routes(app);

app.get('/', (req, res) => {
    res.send('hello, world!!!');
});

connect().on('error', console.log).on('open', listen).on('disconnected', connect)

function connect() {
    return mongoose.connect(config.db, config.options
        ? config.options
        : null).connection;
}

function listen() {
    app.listen(port, () => {
        console.log(`server started on port ${port}`);
    });
}
