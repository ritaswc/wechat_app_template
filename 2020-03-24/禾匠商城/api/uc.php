<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */

error_reporting(0);

define('UC_CLIENT_VERSION', '1.6.0');
define('UC_CLIENT_RELEASE', '20110501');

define('API_DELETEUSER', 1);
define('API_RENAMEUSER', 1);
define('API_GETTAG', 1);
define('API_SYNLOGIN', 1);
define('API_SYNLOGOUT', 1);
define('API_UPDATEPW', 1);
define('API_UPDATEBADWORDS', 1);
define('API_UPDATEHOSTS', 1);
define('API_UPDATEAPPS', 1);
define('API_UPDATECLIENT', 1);
define('API_UPDATECREDIT', 1);
define('API_GETCREDIT', 1);
define('API_GETCREDITSETTINGS', 1);
define('API_UPDATECREDITSETTINGS', 1);
define('API_ADDFEED', 1);
define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '1');

define('IN_SYS', true);
require '../framework/bootstrap.inc.php';
$queryString = str_replace('?', '&', $_SERVER['QUERY_STRING']);
parse_str($queryString, $query);
if(is_array($query) && count($query) == 2) {
	$sql = "SELECT `uc` FROM " . tablename('uni_settings') . " WHERE `uniacid`=:uniacid LIMIT 1";
	$setting = pdo_fetch($sql, array(':uniacid' => $query['uniacid']));
	if(!empty($setting) && !empty($setting['uc'])) {
		$uc = iunserializer($setting['uc']);
		if(!empty($uc) && $uc['status'] == '1') {
			define('UC_CONNECT', $uc['connect'] == 'mysql' ? 'mysql' : '');
			
			define('UC_DBHOST', $uc['dbhost']);
			define('UC_DBUSER', $uc['dbuser']);
			define('UC_DBPW', $uc['dbpw']);
			define('UC_DBNAME', $uc['dbname']);
			define('UC_DBCHARSET', $uc['dbcharset']);
			define('UC_DBTABLEPRE', $uc['dbtablepre']);
			define('UC_DBCONNECT', $uc['dbconnect']);
			
			define('UC_CHARSET', $uc['charset']);
			define('UC_KEY', $uc['key']);
			define('UC_API', $uc['api']);
			define('UC_APPID', $uc['appid']);
			define('UC_IP', $uc['ip']);
			
			$get = $post = array();
			parse_str(authcode($query['code'], 'DECODE', UC_KEY), $get);
		
			if(TIMESTAMP - $get['time'] > 3600) {
				exit('Authracation has expiried');
			}
			if(empty($get)) {
				exit('Invalid Request');
			}
		
			include_once IA_ROOT . '/framework/library/uc/lib/xml.class.php';
			$input = file_get_contents('php://input');
			$post = xml_unserialize($input);
		
			if(in_array($get['action'], array('test', 'deleteuser', 'renameuser', 'gettag', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcredit', 'getcreditsettings', 'updatecreditsettings', 'addfeed'))) {
				$note = new uc_note();
								$function = $get['action'];
				echo $note->$function($get, $post);
				exit();
			} else {
				exit(API_RETURN_FAILED);
			}
		}
	}
}

class uc_note {

	function _serialize($arr, $htmlon = 0) {
		return xml_serialize($arr, $htmlon);
	}

	function test($get, $post) {
		return API_RETURN_SUCCEED;
	}

	function deleteuser($get, $post) {
		global $_G;
		if(!API_DELETEUSER) {
			return API_RETURN_FORBIDDEN;
		}
		$uids = str_replace("'", '', stripslashes($get['ids']));
		$ids = array();
		$ids = array_keys(C::t('common_member')->fetch_all($uids));
		require_once DISCUZ_ROOT.'./source/function/function_delete.php';
		$ids && deletemember($ids);

		return API_RETURN_SUCCEED;
	}

	function renameuser($get, $post) {
		global $_G;

		if(!API_RENAMEUSER) {
			return API_RETURN_FORBIDDEN;
		}



		$tables = array(
			'common_block' => array('id' => 'uid', 'name' => 'username'),
			'common_invite' => array('id' => 'fuid', 'name' => 'fusername'),
			'common_member_verify_info' => array('id' => 'uid', 'name' => 'username'),
			'common_mytask' => array('id' => 'uid', 'name' => 'username'),
			'common_report' => array('id' => 'uid', 'name' => 'username'),

			'forum_thread' => array('id' => 'authorid', 'name' => 'author'),
			'forum_activityapply' => array('id' => 'uid', 'name' => 'username'),
			'forum_groupuser' => array('id' => 'uid', 'name' => 'username'),
			'forum_pollvoter' => array('id' => 'uid', 'name' => 'username'),
			'forum_post' => array('id' => 'authorid', 'name' => 'author'),
			'forum_postcomment' => array('id' => 'authorid', 'name' => 'author'),
			'forum_ratelog' => array('id' => 'uid', 'name' => 'username'),

			'home_album' => array('id' => 'uid', 'name' => 'username'),
			'home_blog' => array('id' => 'uid', 'name' => 'username'),
			'home_clickuser' => array('id' => 'uid', 'name' => 'username'),
			'home_docomment' => array('id' => 'uid', 'name' => 'username'),
			'home_doing' => array('id' => 'uid', 'name' => 'username'),
			'home_feed' => array('id' => 'uid', 'name' => 'username'),
			'home_feed_app' => array('id' => 'uid', 'name' => 'username'),
			'home_friend' => array('id' => 'fuid', 'name' => 'fusername'),
			'home_friend_request' => array('id' => 'fuid', 'name' => 'fusername'),
			'home_notification' => array('id' => 'authorid', 'name' => 'author'),
			'home_pic' => array('id' => 'uid', 'name' => 'username'),
			'home_poke' => array('id' => 'fromuid', 'name' => 'fromusername'),
			'home_share' => array('id' => 'uid', 'name' => 'username'),
			'home_show' => array('id' => 'uid', 'name' => 'username'),
			'home_specialuser' => array('id' => 'uid', 'name' => 'username'),
			'home_visitor' => array('id' => 'vuid', 'name' => 'vusername'),

			'portal_article_title' => array('id' => 'uid', 'name' => 'username'),
			'portal_comment' => array('id' => 'uid', 'name' => 'username'),
			'portal_topic' => array('id' => 'uid', 'name' => 'username'),
			'portal_topic_pic' => array('id' => 'uid', 'name' => 'username'),
		);

		if(!C::t('common_member')->update($get['uid'], array('username' => $get[newusername])) && isset($_G['setting']['membersplit'])){
			C::t('common_member_archive')->update($get['uid'], array('username' => $get[newusername]));
		}

		loadcache("posttableids");
		if($_G['cache']['posttableids']) {
			foreach($_G['cache']['posttableids'] AS $tableid) {
				$tables[getposttable($tableid)] = array('id' => 'authorid', 'name' => 'author');
			}
		}

		foreach($tables as $table => $conf) {
			DB::query("UPDATE ".DB::table($table)." SET `$conf[name]`='$get[newusername]' WHERE `$conf[id]`='$get[uid]'");
		}
		return API_RETURN_SUCCEED;
	}

	function gettag($get, $post) {
		global $_G;
		if(!API_GETTAG) {
			return API_RETURN_FORBIDDEN;
		}
		return $this->_serialize(array($get['id'], array()), 1);
	}

	function synlogin($get, $post) {
		global $_G;

		if(!API_SYNLOGIN) {
			return API_RETURN_FORBIDDEN;
		}

		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

		$cookietime = 31536000;
		$uid = intval($get['uid']);
		if(($member = getuserbyuid($uid, 1))) {
			dsetcookie('auth', authcode("$member[password]\t$member[uid]", 'ENCODE'), $cookietime);
		}
	}

	function synlogout($get, $post) {
		global $_G;

		if(!API_SYNLOGOUT) {
			return API_RETURN_FORBIDDEN;
		}

		header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

		dsetcookie('auth', '', -31536000);
	}

	function updatepw($get, $post) {
		global $_G;

		if(!API_UPDATEPW) {
			return API_RETURN_FORBIDDEN;
		}

		$username = $get['username'];
		$newpw = md5(time().rand(100000, 999999));
		$uid = 0;
		if(($uid = C::t('common_member')->fetch_uid_by_username($username))) {
			$ext = '';
		} elseif(($uid = C::t('common_member_archive')->fetch_uid_by_username($username))) {
			$ext = '_archive';
		}
		if($uid) {
			C::t('common_member'.$ext)->update($uid, array('password' => $newpw));
		}

		return API_RETURN_SUCCEED;
	}

	function updatebadwords($get, $post) {
		global $_G;

		if(!API_UPDATEBADWORDS) {
			return API_RETURN_FORBIDDEN;
		}

		$data = array();
		if(is_array($post)) {
			foreach($post as $k => $v) {
				$data['findpattern'][$k] = $v['findpattern'];
				$data['replace'][$k] = $v['replacement'];
			}
		}
		$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/badwords.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		return API_RETURN_SUCCEED;
	}

	function updatehosts($get, $post) {
		global $_G;

		if(!API_UPDATEHOSTS) {
			return API_RETURN_FORBIDDEN;
		}

		$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/hosts.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		return API_RETURN_SUCCEED;
	}

	 function updateapps($get, $post) { if($post['UC_API']) { $post['UC_API'] = addslashes($post['UC_API']); } 
		global $_G;

		if(!API_UPDATEAPPS) {
			return API_RETURN_FORBIDDEN;
		}

		$UC_API = '';
		if($post['UC_API']) {
			$UC_API = str_replace(array('\'', '"', '\\', "\0", "\n", "\r"), '', $post['UC_API']);
			unset($post['UC_API']);
		}

		$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/apps.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		if($UC_API && is_writeable(DISCUZ_ROOT.'./config/config_ucenter.php')) {
			if(preg_match('/^https?:\/\//is', $UC_API)) {
				$configfile = trim(file_get_contents(DISCUZ_ROOT.'./config/config_ucenter.php'));
				$configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
				$configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '".addslashes($UC_API)."');", $configfile);
				if($fp = @fopen(DISCUZ_ROOT.'./config/config_ucenter.php', 'w')) {
					@fwrite($fp, trim($configfile));
					@fclose($fp);
				}
			}
		}
		return API_RETURN_SUCCEED;
	}

	function updateclient($get, $post) {
		global $_G;

		if(!API_UPDATECLIENT) {
			return API_RETURN_FORBIDDEN;
		}

		$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/settings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		return API_RETURN_SUCCEED;
	}

	function updatecredit($get, $post) {
		global $_G;

		if(!API_UPDATECREDIT) {
			return API_RETURN_FORBIDDEN;
		}

		$credit = $get['credit'];
		$amount = $get['amount'];
		$uid = $get['uid'];
		if(!getuserbyuid($uid)) {
			return API_RETURN_SUCCEED;
		}

		updatemembercount($uid, array($credit => $amount));
		C::t('common_credit_log')->insert(array('uid' => $uid, 'operation' => 'ECU', 'relatedid' => $uid, 'dateline' => time(), 'extcredits'.$credit => $amount));

		return API_RETURN_SUCCEED;
	}

	function getcredit($get, $post) {
		global $_G;

		if(!API_GETCREDIT) {
			return API_RETURN_FORBIDDEN;
		}
		$uid = intval($get['uid']);
		$credit = intval($get['credit']);
		$_G['uid'] = $_G['member']['uid'] = $uid;
		return getuserprofile('extcredits'.$credit);
	}

	function getcreditsettings($get, $post) {
		global $_G;

		if(!API_GETCREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}

		$credits = array();
		foreach($_G['setting']['extcredits'] as $id => $extcredits) {
			$credits[$id] = array(strip_tags($extcredits['title']), $extcredits['unit']);
		}

		return $this->_serialize($credits);
	}

	function updatecreditsettings($get, $post) {
		global $_G;

		if(!API_UPDATECREDITSETTINGS) {
			return API_RETURN_FORBIDDEN;
		}

		$outextcredits = array();
		foreach($get['credit'] as $appid => $credititems) {
			if($appid == UC_APPID) {
				foreach($credititems as $value) {
					$outextcredits[$value['appiddesc'].'|'.$value['creditdesc']] = array(
						'appiddesc' => $value['appiddesc'],
						'creditdesc' => $value['creditdesc'],
						'creditsrc' => $value['creditsrc'],
						'title' => $value['title'],
						'unit' => $value['unit'],
						'ratiosrc' => $value['ratiosrc'],
						'ratiodesc' => $value['ratiodesc'],
						'ratio' => $value['ratio']
					);
				}
			}
		}
		$tmp = array();
		foreach($outextcredits as $value) {
			$key = $value['appiddesc'].'|'.$value['creditdesc'];
			if(!isset($tmp[$key])) {
				$tmp[$key] = array('title' => $value['title'], 'unit' => $value['unit']);
			}
			$tmp[$key]['ratiosrc'][$value['creditsrc']] = $value['ratiosrc'];
			$tmp[$key]['ratiodesc'][$value['creditsrc']] = $value['ratiodesc'];
			$tmp[$key]['creditsrc'][$value['creditsrc']] = $value['ratio'];
		}
		$outextcredits = $tmp;

		$cachefile = DISCUZ_ROOT.'./uc_client/data/cache/creditsettings.php';
		$fp = fopen($cachefile, 'w');
		$s = "<?php\r\n";
		$s .= '$_CACHE[\'creditsettings\'] = '.var_export($outextcredits, TRUE).";\r\n";
		fwrite($fp, $s);
		fclose($fp);

		return API_RETURN_SUCCEED;
	}

	function addfeed($get, $post) {
		global $_G;

		if(!API_ADDFEED) {
			return API_RETURN_FORBIDDEN;
		}
		return API_RETURN_SUCCEED;
	}
}