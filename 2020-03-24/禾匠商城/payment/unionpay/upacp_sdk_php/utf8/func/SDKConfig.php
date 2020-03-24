<?php 
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */const SDK_CVN2_ENC = 0;
const SDK_DATE_ENC = 0;
const SDK_PAN_ENC = 0;

const SDK_SIGN_CERT_PATH = 'D:/certs/PM_700000000000001_acp.pfx';

const SDK_SIGN_CERT_PWD = '000000';

const SDK_ENCRYPT_CERT_PATH = 'D:/certs/verify_sign_acp.cer';

const SDK_VERIFY_CERT_DIR = 'D:/certs/';

const SDK_FRONT_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/frontTransReq.do';

const SDK_BACK_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/backTransReq.do';

const SDK_BATCH_TRANS_URL = 'https://101.231.204.80:5000/gateway/api/batchTrans.do';

const SDK_SINGLE_QUERY_URL = 'https://101.231.204.80:5000/gateway/api/queryTrans.do';

const SDK_FILE_QUERY_URL = 'https://101.231.204.80:9080/';

const SDK_Card_Request_Url = 'https://101.231.204.80:5000/gateway/api/cardTransReq.do';

const SDK_App_Request_Url = 'https://101.231.204.80:5000/gateway/api/appTransReq.do';


const SDK_FRONT_NOTIFY_URL = 'http://localhost:8085/upacp_sdk_php/demo/gbk/FrontReceive.php';
const SDK_BACK_NOTIFY_URL = 'http://114.82.43.123/upacp_sdk_php/demo/gbk/BackReceive.php';

const SDK_FILE_DOWN_PATH = 'd:/file/';

const SDK_LOG_FILE_PATH = 'd:/logs/';

const SDK_LOG_LEVEL = 'INFO';
?>