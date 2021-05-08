const  https = require('https');
const  fs = require('fs');
const  express = require('express');
const  cookieParser = require("cookie-parser");
const  bodyParser = require("body-parser");

const  options = {
	key: fs.readFileSync('./privatekey.pem'),
	cert: fs.readFileSync('./certificate.pem')
};
const  BusCityController = require("./src/api/BusCityController.js");
const  BusStationController = require("./src/api/BusStationListController.js");
const  app = express();

BusCityController(app);
BusStationController(app);

https.createServer(options, app).listen(3011, () => {
	console.log('https server listening on port 3011');
});

