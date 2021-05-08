/* settings for ssl */
var settings = require('../settings.js');
var fs = require('fs');

var options;

if ( settings.development ){
  options = {
    key: fs.readFileSync('./ssl/dev/server.key'),
    cert: fs.readFileSync('./ssl/dev/server.crt'),
    ca: fs.readFileSync('./ssl/dev/ca.crt'),
    passphrase : 'huiyou24',
    requestCert: true,
    rejectUnauthorized: false
  };
} else {
  options = {
    key: fs.readFileSync('./ssl/pro/server.key'),
    cert: fs.readFileSync('./ssl/pro/server.crt'),
    ca: fs.readFileSync('./ssl/pro/ca.crt'),
    passphrase: 'Aa1234567890',
    requestCert: true,
    rejectUnauthorized: false
  };
}

module.exports = options;