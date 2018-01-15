/**
 * Created by Aber on 17/3/14.
 */
var nodemailer = require('nodemailer');
var email={}
email.sendEmail=function(to,subject,text,html) {
    var transporter = nodemailer.createTransport({
        service: 'qq',
        auth: {
            user: 'mjp-0529@qq.com',
            pass: 'xnvrorvcdjgdejgj'
        }
    });
    var mailOptions = {
        from: 'mjp-0529@qq.com', // sender address
        to: to, // list of receivers
        subject: subject, // Subject line
        text: text, // plaintext body
        html: html   // html body
    };
    transporter.sendMail(mailOptions, function(error, info){
        if(error){
            console.log(error);
        }else{
            console.log('Message sent: ' + info.response);
            req.pack.setMsg({type: 1, data: '发送成功'});
            res.send(req.pack);
        }
    });
}
module.exports = email