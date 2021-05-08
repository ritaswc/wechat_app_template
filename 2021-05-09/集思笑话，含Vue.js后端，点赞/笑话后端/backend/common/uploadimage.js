var multer = require('multer')
var mkdirp = require('mkdirp')

mkdirp('uploads')
var storage = multer.diskStorage({
  destination: function (req, file, cb) {
    cb(null, 'uploads/')
  },
  filename: function (req, file, cb) {
    var refilename = file.fieldname + '-' + Date.now() +'-'+ file.originalname
    req.refilename = refilename
    cb(null, refilename)
  }
})

var upload = multer({ storage: storage }).single('upload')

//for ckeditor 
module.exports = function (app){
  app.post('/uploader/uploadimage', function (req, res, next) {
    upload(req, res, function (err) {
      if (err) {
        return res.end('failed')
      }
      console.log(req.refilename)
      // app.images is mongodb collection
      // save the url info to images collection
      var ct = new Date()
      app.images.create({url:req.refilename,createdate:ct})    

      // return for ctrl+v , clipboard
      if (req.query['responseType'] == 'json'){
            return res.json({"fileName":"image.png","uploaded":1,"url":"/"+req.refilename})
      } else {
        // return for upload file by form submit   
        res.setHeader('Content-Type', 'text/html'); 
			  res.end('<script type="text/javascript">window.parent.CKEDITOR.tools.callFunction("0", "/'+req.refilename+'", "");</script>')
      }
     })	
  });
}

