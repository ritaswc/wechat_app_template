<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

final class sp_conf{
		static public $SP_NO;
				static public $SP_KEY_FILE;
			static public $LOG_FILE;
		const SP_PAY_RESULT_SUCCESS = 1;
		const SP_PAY_RESULT_WAITING = 2;
		const BFB_PAY_DIRECT_NO_LOGIN_URL = "https://www.baifubao.com/api/0/pay/0/direct";
		const BFB_PAY_DIRECT_LOGIN_URL = "https://www.baifubao.com/api/0/pay/0/direct/0";
		const BFB_PAY_WAP_DIRECT_URL = "https://www.baifubao.com/api/0/pay/0/wapdirect";
		const BFB_QUERY_ORDER_URL = "https://www.baifubao.com/api/0/query/0/pay_result_by_order_no";
		const BFB_QUERY_RETRY_TIME = 3;
		const BFB_PAY_RESULT_SUCCESS = 1;
		const BFB_NOTIFY_META = "<meta name=\"VIP_BFB_PAYMENT\" content=\"BAIFUBAO\">";
		const SIGN_METHOD_MD5 = 1;
	const SIGN_METHOD_SHA1 = 2;
		const BFB_PAY_INTERFACE_SERVICE_ID = 1;
		const BFB_QUERY_INTERFACE_SERVICE_ID = 11;
		const BFB_INTERFACE_VERSION = 2;
		const BFB_INTERFACE_ENCODING = 1;
		const BFB_INTERFACE_OUTPUT_FORMAT = 1;
		const BFB_INTERFACE_CURRENTCY = 1;
}

sp_conf::$LOG_FILE = IA_ROOT . '/data/logs/bfb_' . date('Ymd') . '.log';
sp_conf::$SP_NO = $payment['mchid'];
sp_conf::$SP_KEY_FILE = $payment['signkey'];