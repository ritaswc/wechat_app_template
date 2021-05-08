var CryptoJS =  require("./aes/crypto-js.js")

function getAesString(data,key,iv){//加密
    var key  = CryptoJS.enc.Hex.parse(key);
    var iv   = CryptoJS.enc.Latin1.parse(iv);
    var encrypted = CryptoJS.AES.encrypt(data,key,
            {
                iv:iv,
                mode:CryptoJS.mode.CBC,
                padding:CryptoJS.pad.Pkcs7
            });
    return encrypted;
}

/**
 * 解密用户信息
 */
function decryptUserInfo(encrypted,key,iv){//解密
    encrypted = CryptoJS.enc.Base64.parse(encrypted);
    key  = CryptoJS.enc.Base64.parse(key);
    iv   = CryptoJS.enc.Base64.parse(iv);
    key = key.toString(CryptoJS.enc.Hex);
    iv = iv.toString(CryptoJS.enc.Hex);
    return aesDecrypt(encrypted, key, iv);
}

/**
 * MD5加密
 */
function MD5(msg) { // md5
    
    var hash = ( CryptoJS.MD5(msg) ).toString(CryptoJS.enc.Hex);
    return hash.toUpperCase();
}

/**
 * 登陆加密
 */ 
function getVerifyModel(uid, versionCode, deviceType, timestamp) { // 登陆加密
    // 排序
    var argsArray=["uid=" + uid, "versionCode="+versionCode, "deviceType="+deviceType, "timestamp="+timestamp];
    argsArray = argsArray.sort();
    var msg = "";
    for ( var i=0; i < argsArray.length - 1; i++) {
        msg = msg + argsArray[i] + "&";
    }
    msg = msg + argsArray[argsArray.length - 1];
    // MD5
    var md5 = MD5(msg);
    console.log("MD5加密：", msg, md5);
    // 对MD5结果DES加密
    return des(msg);
}


/**
 * DES 加密
 */
function des(msg) {
    var keyhex = CryptoJS.enc.Utf8.parse("vzanlive"); // 秘钥
    var iv = CryptoJS.enc.Utf8.parse("32526978"); // iv
    var encrypted = CryptoJS.DES.encrypt(msg, keyhex, {
	    iv:iv,
	    mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    });
    var des = encrypted.ciphertext.toString(CryptoJS.enc.Hex);
    return des.toUpperCase();
}



/**
 * AES解密
 */
function aesDecrypt(msg, key, iv) {
    key = CryptoJS.enc.Hex.parse(key);
    iv = CryptoJS.enc.Hex.parse(iv);
    var decrypted = CryptoJS.AES.decrypt({ciphertext: msg}, key,{
        iv:iv,
        mode:CryptoJS.mode.CBC,
        padding:CryptoJS.pad.Pkcs7
    });
    return decrypted.toString(CryptoJS.enc.Utf8);
}

module.exports = {
    getAesString:getAesString,
    decryptUserInfo:decryptUserInfo,
    getVerifyModel:getVerifyModel
}