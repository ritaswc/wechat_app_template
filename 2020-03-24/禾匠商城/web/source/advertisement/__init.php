<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
header('Location: ' . url('account/display'));
exit();
defined('IN_IA') or exit('Access Denied');
define('FRAME', 'advertisement');
if ('display' == $do) {
	define('ACTIVE_FRAME_URL', url('advertisement/content-provider/account_list'));
}
