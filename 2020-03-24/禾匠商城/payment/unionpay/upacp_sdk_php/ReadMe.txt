
            ╭──────────────────────────────────────╮
    ────┤           银联全渠道支付插件包说明                                           ├────
            ╰──────────────────────────────────────╯                                                          
　       接口名称：银联全渠道支付统一接入接口
　 　    代码版本：1.1
         开发语言：PHP
         版    权：银联全渠道
　       制 作 者：银联全渠道
         联系方式： 

    ─────────────────────────────────

───────
 代码文件结构
───────


  ├gbk.func┈┈┈┈┈┈┈┈┈┈工具类文件夹
  │  │
  │  ├encryptParams.php┈┈┈┈┈┈┈┈┈┈┈ 对卡号，cvn2，密码，cvn2有效期处理类
  │  │
  │  ├PinBlock.php ┈┈┈┈┈┈┈┈┈┈密码解析类
  │  │
  │  ├httpClient.php┈┈┈┈┈┈┈┈┈后台交易通信处理类
  │  │
  │  ├SDKConfig.php ┈┈┈┈┈┈┈┈┈ 配置信息类
  │  │
  │  ├PublicEncrypte.php ┈┈┈┈┈┈┈┈┈┈ 密码/签名类
  │  │
  │  └common.php ┈┈┈┈┈┈┈┈报文方法类
  │  │
  │  └secureUtil.php┈┈┈┈┈┈┈┈签名/验签类
  │  │
  │  └log.class.php ┈┈┈┈┈┈┈┈日志打印工具类
  │
  
 
  

※注意※

 openssl证书需下载使用 其中的php_openssl.dll,ssleay32.dll,libeay32.dll3个文件拷到windows/system32/文件夹下，在重启Apache服务


─────────
主要类文件函数说明
─────────

--------------------------------------------------------------------


SDKConfig.php

 签名证书路径
const SDK_SIGN_CERT_PATH = '';

 签名证书密码
 const SDK_SIGN_CERT_PWD = '';
 
 验签证书
const SDK_VERIFY_CERT_PATH = '';

密码加密证书
const SDK_ENCRYPT_CERT_PATH = '';

 验签证书路径
const SDK_VERIFY_CERT_DIR = '';

 前台请求地址
const SDK_FRONT_TRANS_URL = '';

 后台返回结果地址
const SDK_BACK_TRANS_URL = '';

 批量交易
const SDK_BATCH_TRANS_URL = '';

批量交易状态查询
const SDK_BATCH_QUERY_URL = '';


单笔查询请求地址
const SDK_SINGLE_QUERY_URL = '';

文件传输请求地址
const SDK_FILE_QUERY_URL = '';

 前台通知地址
const SDK_FRONT_NOTIFY_URL = '';

后台通知地址
const SDK_BACK_NOTIFY_URL = '';

文件下载目录 
const SDK_FILE_DOWN_PATH = '';

日志 目录 
const SDK_LOG_FILE_PATH = '';

日志级别
const SDK_LOG_LEVEL = '';

有卡交易地址
const SDK_Card_Request_Url = '';

App交易地址
const SDK_App_Request_Url = '';

┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

common.php

function coverParamsToString($param)
功能：数组 排序后转化为字体串


function coverStringToArray($val )
功能：字符串转换为 数组

function deal_params(&$params)
功能：处理返回报文 解码客户信息 , 如果编码为GBK 则转为utf-8


function deflate_file(&$params)
功能：处理压缩文件

function deal_file($params)
功能：处理报文文件

function create_html($params, $action)
功能：构造自动提交表单



┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

HttpClient.php


function sendHttpRequest($params, $url)
功能：建立请求，以模拟远程HTTP的POST请求方式构造并获取银联的处理结果


function getRequestParamString($params)
功能：组装报文


┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

encryptParams.php

function encrypt_params(&$params) 
功能：对卡号 | cvn2 | 密码 | cvn2有效期进行处理


┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

PinBlock.php
function  Pin2PinBlock( &$sPin )
功能：密码转pin  验证转换



┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

PublicEncrypte.php

function EncryptedPin（$sPin, $sCardNo ,$sPubKeyURL）

功能：证书Id验证密码方法


┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉┉

secureUtil.php

function sign(&$params)

功能：签名方法

function verify($params)

功能：验签方法

function getPulbicKeyByCertId($certId)

功能：根据证书ID加载证书方法

function getCertId($cert_path)

功能：取证书ID方法

function getCertIdByCerPath($cert_path)

功能：取证书类型方法

function getPublicKey($cert_path)

功能：取证书公钥 -验签

function getPrivateKey($cert_path)

功能：返回(签名)证书私钥 

function encryptPan($pan)

功能：加密卡号方法

function encryptPin($pan, $pwd)

功能：pin加密方法

function encryptCvn2($cvn2)

功能：cvn2加密方法

function encryptDate($certDate) 

功能：有效期加密方法


