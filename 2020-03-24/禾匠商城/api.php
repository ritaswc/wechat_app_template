<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
define('IN_API', true);
require_once './framework/bootstrap.inc.php';
load()->model('reply');
load()->model('attachment');
load()->model('visit');
load()->app('common');
load()->classs('wesession');
$hash = $_GPC['hash'];
if(!empty($hash)) {
	$id = pdo_fetchcolumn("SELECT acid FROM " . tablename('account') . " WHERE hash = :hash", array(':hash' => $hash));
}
if(!empty($_GPC['appid'])) {
	$appid = ltrim($_GPC['appid'], '/');
	if ($appid == 'wx570bc396a51b8ff8') {
		$_W['account'] = array(
			'type' => '3',
			'key' => 'wx570bc396a51b8ff8',
			'level' => 4,
			'token' => 'platformtestaccount'
		);
	} else {
		$id = pdo_fetchcolumn("SELECT acid FROM " . tablename('account_wechats') . " WHERE `key` = :appid", array(':appid' => $appid));
	}
}
if(empty($id)) {
	$id = intval($_GPC['id']);
}
if (!empty($id)) {
	$uniacid = pdo_getcolumn('account', array('acid' => $id), 'uniacid');
	$_W['account'] = $_W['uniaccount'] = uni_fetch($uniacid);
	if (!empty($_W['account']['uniacid']) && visit_app_pass_visit_limit($_W['account']['uniacid'])) {
		exit('success');
	}
}
if(empty($_W['account'])) {
	exit('initial error hash or id');
}
if(empty($_W['account']['token'])) {
	exit('initial missing token');
}
$_W['debug'] = intval($_GPC['debug']);
$_W['acid'] = $_W['account']['acid'];
$_W['uniacid'] = $_W['account']['uniacid'];
$_W['account']['groupid'] = $_W['uniaccount']['groupid'];
$_W['account']['qrcode'] = $_W['attachurl'].'qrcode_'.$_W['acid'].'.jpg?time='.$_W['timestamp'];
$_W['account']['avatar'] = $_W['attachurl'].'headimg_'.$_W['acid'].'.jpg?time='.$_W['timestamp'];
$_W['attachurl'] = attachment_set_attach_url();

register_shutdown_function('visit_update_today', 'app', 'we7_api');

$engine = new WeEngine();
if (!empty($_W['setting']['copyright']['status'])) {
	$engine->died('抱歉，站点已关闭，关闭原因：' . $_W['setting']['copyright']['reason']);
}
if (!empty($_W['uniaccount']['endtime']) && TIMESTAMP > $_W['uniaccount']['endtime'] && !in_array($_W['uniaccount']['endtime'], array(USER_ENDTIME_GROUP_EMPTY_TYPE, USER_ENDTIME_GROUP_UNLIMIT_TYPE))) {
	$engine->died('抱歉，您的公众号已过期，请及时联系管理员');
}

if($_W['isajax'] && $_W['ispost'] && $_GPC['flag'] == 1) {
	$engine->encrypt();
}
if($_W['isajax'] && $_W['ispost'] && $_GPC['flag'] == 2) {
	$engine->decrypt();
}
$_W['isajax'] = false;
$engine->start();


class WeEngine {
	
	private $account = null;
	
	private $modules = array();
	
	public $keyword = array();
	
	public $message = array();

	
	public function __construct() {
		global $_W;
		$this->account = WeAccount::create($_W['account']);
		if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			$_W['modules'] = uni_modules();
			$this->modules = array_keys($_W['modules']);
			$this->modules[] = 'cover';
			$this->modules[] = 'default';
			$this->modules[] = 'reply';
			$this->modules = array_unique ($this->modules);
		}
	}

	
	public function encrypt() {
		global $_W;
		if(empty($this->account)) {
			exit('Miss Account.');
		}
		$timestamp = TIMESTAMP;
		$nonce = random(5);
		$token = $_W['account']['token'];
		$signkey = array($token, TIMESTAMP, $nonce);
		sort($signkey, SORT_STRING);
		$signString = implode($signkey);
		$signString = sha1($signString);

		$_GET['timestamp'] = $timestamp;
		$_GET['nonce'] = $nonce;
		$_GET['signature'] = $signString;
		$postStr = file_get_contents('php://input');
		if(!empty($_W['account']['encodingaeskey']) && strlen($_W['account']['encodingaeskey']) == 43 && !empty($_W['account']['key']) && $_W['setting']['development'] != 1) {
			$data = $this->account->encryptMsg($postStr);
			$array = array('encrypt_type' => 'aes', 'timestamp' => $timestamp, 'nonce' => $nonce, 'signature' => $signString, 'msg_signature' => $data[0], 'msg' => $data[1]);
		} else {
			$data = array('', '');
			$array = array('encrypt_type' => '', 'timestamp' => $timestamp, 'nonce' => $nonce, 'signature' => $signString, 'msg_signature' => $data[0], 'msg' => $data[1]);
		}
		exit(json_encode($array));
	}

	
	public function decrypt() {
		global $_W;
		if(empty($this->account)) {
			exit('Miss Account.');
		}
		$postStr = file_get_contents('php://input');
		if(!empty($_W['account']['encodingaeskey']) && strlen($_W['account']['encodingaeskey']) == 43 && !empty($_W['account']['key']) && $_W['setting']['development'] != 1) {
			$resp = $this->account->local_decryptMsg($postStr);
		} else {
			$resp = $postStr;
		}
		exit($resp);
	}

	
	public function start() {
		global $_W;
		if(empty($this->account)) {
			exit('Miss Account.');
		}
		if(!$this->account->checkSign()) {
			exit('Check Sign Fail.');
		}
		if(strtolower($_SERVER['REQUEST_METHOD']) == 'get') {
			$row = array();
			$row['isconnect'] = 1;
			pdo_update('account', $row, array('uniacid' => $_W['uniacid']));
			cache_delete(cache_system_key('uniaccount', array('uniacid' => $_W['uniacid'])));
			exit(htmlspecialchars($_GET['echostr']));
		}
		if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
			$postStr = file_get_contents('php://input');
						if(!empty($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes') {
				$postStr = $this->account->decryptMsg($postStr);
			}
			WeUtility::logging('trace', $postStr);
			$message = $this->account->parse($postStr);

			$this->message = $message;
			if(empty($message)) {
				WeUtility::logging('waring', 'Request Failed');
				exit('Request Failed');
			}
			$_W['openid'] = $message['from'];
			$_W['fans'] = array('from_user' => $_W['openid']);
			$this->booking($message);
			if($message['event'] == 'unsubscribe') {
				$this->receive(array(), array(), array());
				exit();
			}
			$sessionid = md5($message['from'] . $message['to'] . $_W['uniacid']);
			session_id($sessionid);
			WeSession::start($_W['uniacid'], $_W['openid']);

			$_SESSION['openid'] = $_W['openid'];
			$pars = $this->analyze($message);
			$pars[] = array(
				'message' => $message,
				'module' => 'default',
				'rule' => '-1',
			);
			$hitParam['rule'] = -2;
			$hitParam['module'] = '';
			$hitParam['message'] = $message;

			$hitKeyword = array();
			$response = array();
			foreach($pars as $par) {
				if(empty($par['module'])) {
					continue;
				}
				$par['message'] = $message;
				$response = $this->process($par);
				if($this->isValidResponse($response)) {
					$hitParam = $par;
					if(!empty($par['keyword'])) {
						$hitKeyword = $par['keyword'];
					}
					break;
				}
			}
			$response_debug = $response;
			$pars_debug = $pars;
			if($hitParam['module'] == 'default' && is_array($response) && is_array($response['params'])) {
				foreach($response['params'] as $par) {
					if(empty($par['module'])) {
						continue;
					}
					$response = $this->process($par);
					if($this->isValidResponse($response)) {
						$hitParam = $par;
						if(!empty($par['keyword'])) {
							$hitKeyword = $par['keyword'];
						}
						break;
					}
				}
			}
			WeUtility::logging('params', var_export($hitParam, true));
			WeUtility::logging('response', $response);
			$resp = $this->account->response($response);
						if(!empty($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes') {
				$resp = $this->account->encryptMsg($resp);
				$resp = $this->account->xmlDetract($resp);
			}
			if($_W['debug']) {
				$_W['debug_data'] = array(
					'resp' => $resp,
					'is_default' => 0
				);
				if(count($pars_debug) == 1) {
					$_W['debug_data']['is_default'] = 1;
					$_W['debug_data']['params'] = $response_debug['params'];
				} else {
					array_pop($pars_debug);
					$_W['debug_data']['params'] = $pars_debug;
				}
				$_W['debug_data']['hitparam'] = $hitParam;
				$_W['modules']['cover'] = array('title' => '入口封面', 'name' => 'cover');

				load()->web('template');
				$process = template('utility/emulator', TEMPLATE_FETCH);
				echo json_encode(array('resp' => $resp, 'process' => $process));
				exit();
			}
			if ($resp !== 'success') {
				$mapping = array(
					'[from]' => $this->message['from'],
					'[to]' => $this->message['to'],
					'[rule]' => $this->params['rule']
				);
				$resp = str_replace(array_keys($mapping), array_values($mapping), $resp);
			}

			$reply_times_info = (array)$_SESSION['__reply_times'];
			if ($reply_times_info['content'] == $message['content']) {
				$new_times = intval($reply_times_info['times']) + 1;
			} else {
				$new_times = 1;
			}
			$_SESSION['__reply_times'] = array('content' => $message['content'], 'date' => date('Y-m-d'), 'times' => $new_times);
			ob_start();
			echo $resp;
			ob_start();
			$this->receive($hitParam, $hitKeyword, $response);
			ob_end_clean();
			exit();
		}
		WeUtility::logging('waring', 'Request Failed');
		exit('Request Failed');
	}

	private function isValidResponse($response) {
		if ($response === 'success') {
			return true;
		}
		if(is_array($response)) {
			if($response['type'] == 'text' && !empty($response['content'])) {
				return true;
			}
			if($response['type'] == 'news' && !empty($response['items'])) {
				return true;
			}
			if(!in_array($response['type'], array('text', 'news', 'image'))) {
				return true;
			}
		}
		return false;
	}

	
	private function booking($message) {
		global $_W;
		if ($message['event'] == 'unsubscribe' || $message['event'] == 'subscribe') {
			$todaystat = pdo_get('stat_fans', array('date' => date('Ymd'), 'uniacid' => $_W['uniacid']));
			if ($message['event'] == 'unsubscribe') {
				if (empty($todaystat)) {
					$updatestat = array(
						'new' => 0,
						'uniacid' => $_W['uniacid'],
						'cancel' => 1,
						'cumulate' => 0,
						'date' => date('Ymd'),
					);
					pdo_insert('stat_fans', $updatestat);
				} else {
					$updatestat = array(
						'cancel' => $todaystat['cancel'] + 1,
					);
					$updatestat['cumulate'] = 0;
					pdo_update('stat_fans', $updatestat, array('id' => $todaystat['id']));
				}
			} elseif ($message['event'] == 'subscribe') {
				if (empty($todaystat)) {
					$updatestat = array(
						'new' => 1,
						'uniacid' => $_W['uniacid'],
						'cancel' => 0,
						'cumulate' => 0,
						'date' => date('Ymd'),
					);
					pdo_insert('stat_fans', $updatestat);
				} else {
					$updatestat = array(
						'new' => $todaystat['new'] + 1,
						'cumulate' => 0,
					);
					pdo_update('stat_fans', $updatestat, array('id' => $todaystat['id']));
				}
			}
		}

		load()->model('mc');
		$setting = uni_setting($_W['uniacid'], array('passport'));
		$fans = mc_fansinfo($message['from']);
		$default_groupid = cache_load(cache_system_key('defaultgroupid', array('uniacid' => $_W['uniacid'])));
		if (empty($default_groupid)) {
			$default_groupid = pdo_fetchcolumn('SELECT groupid FROM ' .tablename('mc_groups') . ' WHERE uniacid = :uniacid AND isdefault = 1', array(':uniacid' => $_W['uniacid']));
			cache_write(cache_system_key('defaultgroupid', array('uniacid' => $_W['uniacid'])), $default_groupid);
		}
		if(!empty($fans)) {
			if ($message['event'] == 'unsubscribe') {
				cache_build_memberinfo($fans['uid']);
				pdo_update('mc_mapping_fans', array('follow' => 0, 'unfollowtime' => TIMESTAMP), array('fanid' => $fans['fanid']));
				pdo_delete('mc_fans_tag_mapping', array('fanid' => $fans['fanid']));
			} elseif ($message['event'] != 'ShakearoundUserShake' && $message['type'] != 'trace') {
				$rec = array();
				if (empty($fans['follow'])) {
					$rec['follow'] = 1;
					$rec['followtime'] = $message['time'];
				}
				$member = array();
				if(!empty($fans['uid'])){
					$member = mc_fetch($fans['uid']);
				}
				if (empty($member)) {
					if (!isset($setting['passport']) || empty($setting['passport']['focusreg'])) {
						$data = array(
							'uniacid' => $_W['uniacid'],
							'email' => md5($message['from']).'@we7.cc',
							'salt' => random(8),
							'groupid' => $default_groupid,
							'createtime' => TIMESTAMP,
						);
						$data['password'] = md5($message['from'] . $data['salt'] . $_W['config']['setting']['authkey']);
						pdo_insert('mc_members', $data);
						$rec['uid'] = pdo_insertid();
					}
				}
				if(!empty($rec)){
					pdo_update('mc_mapping_fans', $rec, array('openid' => $message['from']));
				}
			}
		} else {
			if ($message['event'] == 'subscribe' || $message['type'] == 'text' || $message['type'] == 'image') {
				load()->model('mc');
				$force_init_member = false;
				if (!isset($setting['passport']) || empty($setting['passport']['focusreg'])) {
					$force_init_member = true;
				}
				mc_init_fans_info($message['from'], $force_init_member);
			}
		}
	}

	private function receive($par, $keyword, $response) {
		global $_W;
		fastcgi_finish_request();

		$subscribe = cache_load(cache_system_key('module_receive_enable'));
		if (empty($subscribe)) {
			$subscribe = cache_build_module_subscribe_type();
		}
		$modules = uni_modules();
		$obj = WeUtility::createModuleReceiver('core');
		$obj->message = $this->message;
		$obj->params = $par;
		$obj->response = $response;
		$obj->keyword = $keyword;
		$obj->module = 'core';
		$obj->uniacid = $_W['uniacid'];
		$obj->acid = $_W['acid'];
		if(method_exists($obj, 'receive')) {
			@$obj->receive();
		}
		load()->func('communication');
		if (empty($subscribe[$this->message['type']]) && !empty($this->message['event'])) {
			$subscribe[$this->message['type']] = $subscribe[strtolower($this->message['event'])];
		}
		if (!empty($subscribe[$this->message['type']])) {
			foreach ($subscribe[$this->message['type']] as $modulename) {
				if (!in_array($modulename, array_keys($modules))) {
					continue;
				}
																$params = array(
					'i' => $GLOBALS['uniacid'],
					'modulename' => $modulename,
					'request' => json_encode($par),
					'response' => json_encode($response),
					'message' => json_encode($this->message),
				);
				$response = ihttp_request(wurl('utility/subscribe/receive'), $params, array(), 10);
				if (is_error($response) || $response['code'] != 200) {
					$response = ihttp_request($_W['siteroot'] . 'web/' . wurl('utility/subscribe/receive'), $params, array(), 10);
				}
			}
		}
	}

	
	private function analyze(&$message) {
		global $_W;
		$params = array();
		if(in_array($message['type'], array('event', 'qr'))) {
			$params = call_user_func_array(array($this, 'analyze' . $message['type']), array(&$message));
			if(!empty($params)) {
				return (array)$params;
			}
		}
		if(!empty($_SESSION['__contextmodule']) && in_array($_SESSION['__contextmodule'], $this->modules)) {
			if($_SESSION['__contextexpire'] > TIMESTAMP) {
				$params[] = array(
					'message' => $message,
					'module' => $_SESSION['__contextmodule'],
					'rule' => $_SESSION['__contextrule'],
					'priority' => $_SESSION['__contextpriority'],
					'context' => true
				);
				return $params;
			} else {
				unset($_SESSION);
				session_destroy();
			}
		}

		$reply_times_info = (array)$_SESSION['__reply_times'];
		if (!empty($_W['account']['setting']) && !empty($reply_times_info) && intval($_W['account']['setting']['reply_setting']) > 0 && strtotime($reply_times_info['date']) >= strtotime(date('Y-m-d')) && $reply_times_info['times'] >= $_W['account']['setting']['reply_setting'] && $reply_times_info['content'] == $message['content']) {
			exit('success');
		}

		if(method_exists($this, 'analyze' . $message['type'])) {
			$temp = call_user_func_array(array($this, 'analyze' . $message['type']), array(&$message));
			if(!empty($temp) && is_array($temp)){
				$params += $temp;
			}
		} else {
			$params += $this->handler($message['type']);
		}
		return $params;
	}

	private function analyzeSubscribe(&$message) {
		global $_W;
		$params = array();
		$message['type'] = 'text';
		$message['redirection'] = true;
		if(!empty($message['scene'])) {
			$message['source'] = 'qr';
			$sceneid = trim($message['scene']);
			if (is_numeric($sceneid)) {
				$scene_condition = " `qrcid` = :sceneid";
			}else{
				$scene_condition = " `scene_str` = :sceneid";
			}
			$condition = array(':sceneid' => $sceneid, ':uniacid' => $_W['uniacid']);
			$qr = pdo_fetch("SELECT `id`, `keyword` FROM " . tablename('qrcode') . " WHERE {$scene_condition} AND `uniacid` = :uniacid", $condition);
			if(!empty($qr)) {
				$message['content'] = $qr['keyword'];
				if (!empty($qr['type']) && $qr['type'] == 'scene') {
					$message['msgtype'] = 'text';
				}
				$params += $this->analyzeText($message);
				return $params;
			}
		}
		$message['source'] = 'subscribe';
		$setting = uni_setting($_W['uniacid'], array('welcome'));
		if(!empty($setting['welcome'])) {
			$message['content'] = $setting['welcome'];
			$params += $this->analyzeText($message);
		}

		return $params;
	}

	private function analyzeQR(&$message) {
		global $_W;
		$params = array();
		$message['type'] = 'text';
		$message['redirection'] = true;
		if(!empty($message['scene'])) {
			$message['source'] = 'qr';
			$sceneid = trim($message['scene']);
			if (is_numeric($sceneid)) {
				$scene_condition = " `qrcid` = :sceneid";
			}else{
				$scene_condition = " `scene_str` = :sceneid";
			}
			$condition_params = array(':sceneid' => $sceneid, ':uniacid' => $_W['uniacid']);
			$qr = pdo_fetch("SELECT `id`, `keyword` FROM " . tablename('qrcode') . " WHERE {$scene_condition} AND `uniacid` = :uniacid AND `type` = 'scene'", $condition_params);

		}
		if (empty($qr) && !empty($message['ticket'])) {
			$message['source'] = 'qr';
			$ticket = trim($message['ticket']);
			if(!empty($ticket)) {
				$qr = pdo_fetchall("SELECT `id`, `keyword` FROM " . tablename('qrcode') . " WHERE `uniacid` = :uniacid AND ticket = :ticket", array(':uniacid' => $_W['uniacid'], ':ticket' => $ticket));
				if(!empty($qr)) {
					if(count($qr) != 1) {
						$qr = array();
					} else {
						$qr = $qr[0];
					}
				}
			}
		}
		if(!empty($qr)) {
			$message['content'] = $qr['keyword'];
			if (!empty($qr['type']) && $qr['type'] == 'scene') {
				$message['msgtype'] = 'text';
			}
			$params += $this->analyzeText($message);
		}
		if (empty($qr)) {
			$params = $this->handler($message['type']);
		}
		return $params;
	}

	public function analyzeText(&$message, $order = 0) {
		global $_W;

		$pars = array();

		$order = intval($order);
		if(!isset($message['content'])) {
			return $pars;
		}
				$cachekey = cache_system_key('keyword', array('content' => md5($message['content']), 'uniacid' => $_W['uniacid']));
		$keyword_cache = cache_load($cachekey);
		if (!empty($keyword_cache) && $keyword_cache['expire'] > TIMESTAMP) {
						foreach ($keyword_cache['data'] as $key => &$value) {
				$value['message'] = $message;
			}
			unset($value);
			return $keyword_cache['data'];
		}
		$condition = <<<EOF
`uniacid` IN ( 0, {$_W['uniacid']} )
AND
(
	( `type` = 1 AND `content` = :c1 )
	or
	( `type` = 2 AND instr(:c2, `content`) )
	or
	( `type` = 3 AND :c3 REGEXP `content` )
	or
	( `type` = 4 )
)
AND `status`=1
EOF;

		$params = array();
		$params[':c1'] = $message['content'];
		$params[':c2'] = $message['content'];
		$params[':c3'] = $message['content'];

		if (intval($order) > 0) {
			$condition .= " AND `displayorder` > :order";
			$params[':order'] = $order;
		}

		$keywords = reply_keywords_search($condition, $params);
		if(empty($keywords)) {
			return $pars;
		}

				$system_module_reply = true;
		foreach($keywords as $keyword) {
			if (!in_array($keyword['module'], array('defalut', 'cover', 'reply'))) {
				$system_module_reply = false;
			}
			$params = array(
				'message' => $message,
				'module' => $keyword['module'],
				'rule' => $keyword['rid'],
				'priority' => $keyword['displayorder'],
				'keyword' => $keyword,
				'reply_type' => $keyword['reply_type']
			);
			$pars[] = $params;
		}
		if (!empty($system_module_reply)) {
			$cache = array(
				'data' => $pars,
				'expire' => TIMESTAMP + 5 * 60,
			);
			cache_write($cachekey, $cache);
		}
		return $pars;
	}

	private function analyzeEvent(&$message) {
		if (strtolower($message['event']) == 'subscribe') {
			return $this->analyzeSubscribe($message);
		}
		if (strtolower($message['event']) == 'click') {
			$message['content'] = strval($message['eventkey']);
			return $this->analyzeClick($message);
		}
		if (in_array($message['event'], array('pic_photo_or_album', 'pic_weixin', 'pic_sysphoto'))) {
			pdo_delete('menu_event', array('createtime <' => $GLOBALS['_W']['timestamp'] - 100, 'openid' => $message['from']), 'OR');
			if (!empty($message['sendpicsinfo']['count'])) {
				foreach ($message['sendpicsinfo']['piclist'] as $item) {
					pdo_insert('menu_event', array(
						'uniacid' => $GLOBALS['_W']['uniacid'],
						'keyword' => $message['eventkey'],
						'type' => $message['event'],
						'picmd5' => $item,
						'openid' => $message['from'],
						'createtime' => TIMESTAMP,
					));
				}
			} else {
				pdo_insert('menu_event', array(
					'uniacid' => $GLOBALS['_W']['uniacid'],
					'keyword' => $message['eventkey'],
					'type' => $message['event'],
					'picmd5' => $item,
					'openid' => $message['from'],
					'createtime' => TIMESTAMP,
				));
			}
			$message['content'] = strval($message['eventkey']);
			$message['source'] = $message['event'];
			return $this->analyzeText($message);
		}
		if (!empty($message['eventkey'])) {
			$message['content'] = strval($message['eventkey']);
			$message['type'] = 'text';
			$message['redirection'] = true;
			$message['source'] = $message['event'];
			return $this->analyzeText($message);
		}
		return $this->handler($message['event']);
	}

	private function analyzeClick(&$message) {
		if(!empty($message['content']) || $message['content'] !== '') {
			$message['type'] = 'text';
			$message['redirection'] = true;
			$message['source'] = 'click';
			return $this->analyzeText($message);
		}

		return array();
	}

	private function analyzeImage(&$message) {
		load()->func('communication');
		if (!empty($message['picurl'])) {
			$response = ihttp_get($message['picurl']);
			if (!empty($response)) {
				$md5 = md5($response['content']);
				$event = pdo_get('menu_event', array('picmd5' => $md5), array('keyword', 'type'));
				if (!empty($event['keyword'])) {
					pdo_delete('menu_event', array('picmd5' => $md5));
				} else {
					$event = pdo_get('menu_event', array('openid' => $message['from']), array('keyword', 'type'));
				}
				if (!empty($event)) {
					$message['content'] = $event['keyword'];
					$message['eventkey'] = $event['keyword'];
					$message['type'] = 'text';
					$message['event'] = $event['type'];
					$message['redirection'] = true;
					$message['source'] = $event['type'];
					return $this->analyzeText($message);
				}
			}
			return $this->handler('image');
		}
	}

	private function analyzeVoice(&$message) {
		$params = $this->handler('voice');
		if (empty($params) && !empty($message['recognition'])) {
			$message['type'] = 'text';
			$message['redirection'] = true;
			$message['source'] = 'voice';
			$message['content'] = $message['recognition'];
			return $this->analyzeText($message);
		} else {
			return $params;
		}
	}

	
	private function handler($type) {
		if(empty($type)) {
			return array();
		}
		global $_W;
		$params = array();
		$setting = uni_setting($_W['uniacid'], array('default_message'));
		$default_message = $setting['default_message'];
		if(is_array($default_message) && !empty($default_message[$type]['type'])) {
			if ($default_message[$type]['type'] == 'keyword') {
				$message = $this->message;
				$message['type'] = 'text';
				$message['redirection'] = true;
				$message['source'] = $type;
				$message['content'] = $default_message[$type]['keyword'];
				return $this->analyzeText($message);
			} else {
				$params[] = array(
					'message' => $this->message,
					'module' => is_array($default_message[$type]) ? $default_message[$type]['module'] : $default_message[$type],
					'rule' => '-1',
				);
				return $params;
			}
		}
		return array();
	}

	
	private function process($param) {
		global $_W;
		if(empty($param['module']) || !in_array($param['module'], $this->modules)) {
			return false;
		}
		if ($param['module'] == 'reply') {
			$processor = WeUtility::createModuleProcessor('core');
		} else {
			$processor = WeUtility::createModuleProcessor($param['module']);
		}
		$processor->message = $param['message'];
		$processor->rule = $param['rule'];
		$processor->reply_type = $param['reply_type'];
		$processor->priority = intval($param['priority']);
		$processor->inContext = $param['context'] === true;
		$response = $processor->respond();
		if(empty($response)) {
			return false;
		}

		return $response;
	}

	
	public function died($content = '') {
		global $_W, $engine;
		if (empty($content)) {
			exit('');
		}
		$response['FromUserName'] = $engine->message['to'];
		$response['ToUserName'] = $engine->message['from'];
		$response['MsgType'] = 'text';
		$response['Content'] = htmlspecialchars_decode($content);
		$response['CreateTime'] = TIMESTAMP;
		$response['FuncFlag'] = 0;
		$xml = array2xml($response);
		if(!empty($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes') {
			$resp = $engine->account->encryptMsg($xml);
			$resp = $engine->account->xmlDetract($resp);
		} else {
			$resp = $xml;
		}
		exit($resp);
	}
}