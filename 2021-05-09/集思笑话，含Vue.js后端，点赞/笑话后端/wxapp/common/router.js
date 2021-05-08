var express = require('express')
var router = express.Router()

// API is backend interface

// define the home page route
router.get('/login', function (req, res) {
  res.send('Birds home page')
})
// define the about route
router.get('/register', function (req, res) {
  res.send('About birds')
})

module.exports = router
