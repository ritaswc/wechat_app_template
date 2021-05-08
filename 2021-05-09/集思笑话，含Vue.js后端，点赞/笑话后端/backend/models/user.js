var Account = require('./account')
var login = require('./login')

module.exports = function (app) {

/*
 * passport-local-mongoose
 */

var passport = require('passport');
var LocalStrategy = require('passport-local').Strategy;

// Configure passport middleware
app.use(passport.initialize());
app.use(passport.session());

// Configure passport-local to use account model for authentication
passport.use(new LocalStrategy(Account.authenticate()));

passport.serializeUser(Account.serializeUser());
passport.deserializeUser(Account.deserializeUser());

app.use('/',login)

}
