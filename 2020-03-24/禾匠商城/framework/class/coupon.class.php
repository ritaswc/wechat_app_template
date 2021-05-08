<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->classs('weixin.account');
class coupon extends WeixinAccount {
	public $account = null;

	public function __construct($acid = '') {
		$this->account_api = self::create($acid);
		$this->account = $this->account_api->account;
	}

	public function getAccessToken() {
		return $this->account_api->getAccessToken();
	}

	public function getCardTicket() {
		$cachekey = cache_system_key('cardticket', array('uniacid' => $this->account['uniacid']));
		$cache = cache_load($cachekey);
		if (!empty($cache) && !empty($cache['ticket']) && $cache['expire'] > TIMESTAMP) {
			$this->account['card_ticket'] = $cache;

			return $cache['ticket'];
		}
		load()->func('communication');
		$access_token = $this->getAccessToken();
		if (is_error($access_token)) {
			return $access_token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access_token}&type=wx_card";
		$content = ihttp_get($url);
		if (is_error($content)) {
			return error(-1, '调用接口获取微信公众号 card_ticket 失败, 错误信息: ' . $content['message']);
		}
		$result = @json_decode($content['content'], true);
		if (empty($result) || 0 != intval(($result['errcode'])) || 'ok' != $result['errmsg']) {
			return error(-1, '获取微信公众号 card_ticket 结果错误, 错误信息: ' . $result['errmsg']);
		}
		$record = array();
		$record['ticket'] = $result['ticket'];
		$record['expire'] = TIMESTAMP + $result['expires_in'] - 200;
		$this->account['card_ticket'] = $record;
		cache_write($cachekey, $record);

		return $record['ticket'];
	}

	
	public function LocationLogoupload($logo) {
		global $_W;
		if (!strexists($logo, 'http://') && !strexists($logo, 'https://')) {
			$path = rtrim(IA_ROOT . '/' . $_W['config']['upload']['attachdir'], '/') . '/';
			if (empty($logo) || !file_exists($path . $logo)) {
				return error(-1, '商户LOGO不存在');
			}
		} else {
			return error(-1, '商户LOGO只能上传本地图片');
		}

		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token={$token}";
		$data = array(
			'buffer' => '@' . $path . $logo,
		);
		load()->func('communication');
		$response = ihttp_request($url, $data);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function SetTestWhiteList($data) {
		global $_W;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/testwhitelist/set?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},信息详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function LocationAdd($data) {
		if (empty($data)) {
			return error(-1, '门店信息错误');
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		if (!empty($data['category'])) {
			$data['category'] = array(rtrim(implode(',', array_values($data['category'])), ','));
		}
		$data['categories'] = $data['category'];
		unset($data['category']);
		$data['offset_type'] = 1;
		$post = array(
			'business' => array(
				'base_info' => $data,
			),
		);
		$post = stripslashes(urldecode(ijson_encode($post, JSON_UNESCAPED_UNICODE)));
		$url = "http://api.weixin.qq.com/cgi-bin/poi/addpoi?access_token={$token}";
		$result = $this->requestApi($url, $post);

		return $result;
	}

		public function LocationEdit($data) {
		if (empty($data)) {
			return error(-1, '门店信息错误');
		}
		$post = array(
			'business' => array(
				'base_info' => $data,
			),
		);
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "http://api.weixin.qq.com/cgi-bin/poi/updatepoi?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, urldecode(json_encode($post)));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function LocationDel($id) {
		if (empty($id)) {
			return error(-1, '门店信息错误');
		}
		$post = array(
			'poi_id' => $id,
		);
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "http://api.weixin.qq.com/cgi-bin/poi/delpoi?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($post));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	public function LocationBatchGet($data = array()) {
		if (empty($data['begin'])) {
			$data['begin'] = 0;
		}
		if (empty($data['limit'])) {
			$data['limit'] = 50;
		}
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "http://api.weixin.qq.com/cgi-bin/poi/getpoilist?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	public function LocationGet($id) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$data = array(
			'poi_id' => $id,
		);
		$url = "http://api.weixin.qq.com/cgi-bin/poi/getpoi?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function GetColors() {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/getcolors?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	public function isCouponSupported() {
		global $_W;
		load()->model('module');
		$we7_coupon_module = module_fetch('we7_coupon');
		$setting = array();
		if (!empty($we7_coupon_module)) {
			$setting = $we7_coupon_module['config'];
		} else {
			$setting = uni_setting($_W['uniacid'], array('coupon_type'));
		}
		if ($_W['account']['level'] != ACCOUNT_SERVICE_VERIFY && $_W['account']['level'] != ACCOUNT_SUBSCRIPTION_VERIFY) {
			return false;
		} else {
			if (!empty($setting['setting']['coupon_type'])) {
				if ($setting['setting']['coupon_type'] == SYSTEM_COUPON) {
					return false;
				} else {
					return true;
				}
			} else {
				if (SYSTEM_COUPON == $setting['coupon_type']) {
					return false;
				} else {
					return true;
				}
			}
		}
	}

		public function CreateCard($card) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/create?access_token={$token}";
		load()->func('communication');
		$card = stripslashes(urldecode(ijson_encode($card, JSON_UNESCAPED_UNICODE)));
		$response = $this->requestApi($url, $card);

		return $response;
	}

		public function DeleteCard($card_id) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/delete?access_token={$token}";
		load()->func('communication');
		$card = json_encode(array('card_id' => $card_id));
		$response = ihttp_request($url, $card);
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function setActivateUserForm($card_id) {
		global $_W;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$data['required_form']['common_field_id_list'] = array('USER_FORM_INFO_FLAG_MOBILE');
		$data['card_id'] = $card_id;
		$data['bind_old_card'] = array('name' => '绑定老会员卡', 'url' => 'www.weixin.qq.com');
		$url = "https://api.weixin.qq.com/card/membercard/activateuserform/set?access_token={$token}";
		load()->func('communication');
		$result = $this->requestApi($url, json_encode($data));

		return $result;
	}

		public function activateMemberCard($data) {
		global $_W;
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/membercard/activate?access_token={$token}";
		load()->func('communication');
		$result = $this->requestApi($url, json_encode($data));

		return $result;
	}

	
	public function ModifyStockCard($card_id, $num) {
		$data['card_id'] = trim($card_id);
		$data['increase_stock_value'] = 0;
		$data['reduce_stock_value'] = 0;
		$num = intval($num);
		($num > 0) && ($data['increase_stock_value'] = $num);
		($num < 0) && ($data['reduce_stock_value'] = abs($num));
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/modifystock?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	
	public function QrCard($card_id, $sceneid, $expire = '') {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/qrcode/create?access_token={$token}";
		load()->func('communication');
		$data = array(
			'action_name' => 'QR_CARD',
			'expire_seconds' => "{$expire}",
			'action_info' => array(
				'card' => array(
					'card_id' => strval($card_id),
					'code' => '',
					'openid' => '',
					'is_unique_code' => false,
					'outer_id' => $sceneid,
				),
			),
		);
		$result = $this->requestApi($url, json_encode($data));

		return $result;
	}

		public function sendCoupons($coupon, $openids) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$post = array(
			'touser' => $openids,
			'wxcard' => array('card_id' => $coupon),
			'msgtype' => 'wxcard',
		);
		$url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token=' . $token;
		$result = $this->requestApi($url, json_encode($post));

		return $result;
	}

		public function UnavailableCode($data) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/code/unavailable?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function ConsumeCode($data) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/code/consume?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function selfConsume($data) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/selfconsumecell/set?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function DecryptCode($data) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/code/decrypt?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function fetchCard($card_id) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$data = array(
			'card_id' => $card_id,
		);
		$url = "https://api.weixin.qq.com/card/get?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result['card'];
	}

	public function updateMemberCard($post) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/update?access_token={$token}";
		$result = $this->requestApi($url, urldecode(json_encode($post)));

		return $result;
	}

		public function batchgetCard($data) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$url = "https://api.weixin.qq.com/card/batchget?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

	public function updateCard($card_id) {
		$token = $this->getAccessToken();
		if (is_error($token)) {
			return $token;
		}
		$data = array(
			'card_id' => $card_id,
		);
		$url = "https://api.weixin.qq.com/card/membercard/activate?access_token={$token}";
		load()->func('communication');
		$response = ihttp_request($url, json_encode($data));
		if (is_error($response)) {
			return error(-1, "访问公众平台接口失败, 错误: {$response['message']}");
		}
		$result = @json_decode($response['content'], true);
		if (empty($result)) {
			return error(-1, "接口调用失败, 元数据: {$response['meta']}");
		} elseif (!empty($result['errcode'])) {
			return error(-1, "访问微信接口错误, 错误代码: {$result['errcode']}, 错误信息: {$result['errmsg']},错误详情：{$this->errorCode($result['errcode'])}");
		}

		return $result;
	}

		public function PayConsumeCode($data) {
		$code_error['uniacid'] = $this->account['uniacid'];
		$code_error['acid'] = $this->account['acid'];
		$code_error['type'] = 2;
		$code_error['message'] = $data['encrypt_code'];
		$code_error['dateline'] = time();
		$code_error['module'] = $data['module'];
		$code_error['params'] = $data['card_id'];

		$code = $this->DecryptCode(array('encrypt_code' => $data['encrypt_code']));
		if (is_error($code)) {
			pdo_insert('core_queue', $code_error);
		} else {
			$sumecode = $this->ConsumeCode(array('code' => $code['code']));
			if (is_error($sumecode)) {
				pdo_insert('core_queue', $code_error);
			} else {
				pdo_update('coupon_record', array('status' => 3, 'usetime' => time()), array('acid' => $this->account['acid'], 'code' => $code['code'], 'card_id' => $data['card_id']));
			}
		}

		return true;
	}

		public function SignatureCard($data) {
		$ticket = $this->getCardTicket();
		if (is_error($ticket)) {
			return $ticket;
		}
		$data[] = $ticket;
		sort($data, SORT_STRING);

		return sha1(implode($data));
	}

	
	public function BuildCardExt($id, $openid = '', $type = 'coupon') {
		global $_W;
		if ('membercard' == $type) {
			$card_id = pdo_getcolumn('mc_card', array('uniacid' => $_W['uniacid']), 'card_id');
		} else {
			$acid = $this->account['acid'];
			$card_id = pdo_fetchcolumn('SELECT card_id FROM ' . tablename('coupon') . ' WHERE acid = :acid AND id = :id', array(':acid' => $acid, ':id' => $id));
			if (empty($card_id)) {
				return error(-1, '卡券id不合法');
			}
		}
		if (empty($card_id)) {
			$card_id = $id;
		}
		$time = TIMESTAMP;
		$sign = array($card_id, $time);
		$signature = $this->SignatureCard($sign);
		if (is_error($signature)) {
			return $signature;
		}
		$cardExt = array('timestamp' => $time, 'signature' => $signature);
		$cardExt = json_encode($cardExt);

		return array('card_id' => $card_id, 'card_ext' => $cardExt);
	}

	public function AddCard($id) {
		$card = $this->BuildCardExt($id);
		if (is_error($card)) {
			return $card;
		}
		$url = murl('activity/coupon/mine');

		return <<<EOF
			wx.ready(function(){
				wx.addCard({
					cardList:[
						{
							cardId:'{$card['card_id']}',
							cardExt:'{$card['card_ext']}'
						}
					],
					success: function (res) {
						location.href="{$url}";
					}
				});
			});
EOF;
	}

	public function OpenCard($id, $code) {
		$card = $this->BuildCardExt($id);
		if (is_error($card)) {
			return $card;
		}
		$url = murl('activity/coupon/mine');

		return <<<EOF
			wx.ready(function(){
				wx.openCard({
					cardList:[
						{
							cardId : "{$card['card_id']}",
							code : "{$code}"
						}
					],
				});
			});
EOF;
	}

	public function ChooseCard($card_id) {
		if (empty($card_id)) {
			return error(-1, '卡券不存在');
		}
		$time = TIMESTAMP;
		$randstr = random(8);
		$sign = array($card_id, $time, $randstr, $this->account['key']);
		$signature = $this->SignatureCard($sign);
		if (is_error($signature)) {
			return $signature;
		}
		$url = murl('wechat/pay/card');

		return <<<EOF
			wx.ready(function(){
				wx.chooseCard({
					shopId: '',
					cardType: '',
					cardId:'{$card_id}',
					timestamp:{$time},
					nonceStr:'{$randstr}',
					signType:'SHA1',
					cardSign:'{$signature}',
					success: function(res) {
						if(res.errMsg == 'chooseCard:ok') {
							eval("var rs = " + res.cardList);
							$.post('{$url}', {'card_id':rs[0].card_id}, function(data){
								var data = $.parseJSON(data);
								if(!data.errno) {
									var card = data.error;
									if(card.type == 'discount') {

									}
								} else {
									u.message('卡券不存在', '', 'error');
								}
							});
						} else {
							u.message('使用卡券失败', '', 'error');
						}
					}
				});
			});
EOF;
	}

	
	public function BatchAddCard($data) {
		$acid = $this->account['acid'];
		$condition = '';
		$params = array();
		if (!empty($data['type'])) {
			$condition .= ' AND type = :type';
			$params[':type'] = $data['type'];
		} else {
			$ids = array();
			foreach ($data as $da) {
				$da = intval($da);
				if ($da > 0) {
					$ids[] = $da;
				}
			}
			if (empty($ids)) {
				$condition = '';
			} else {
				$ids_str = implode(', ', $ids);
				$condition .= " AND id IN ({$ids_str})";
			}
		}

		$card = array();
		if (!empty($condition)) {
			$params[':acid'] = $acid;
			$card = pdo_fetchall('SELECT id, card_id FROM ' . tablename('coupon') . ' WHERE acid = :acid ' . $condition, $params);
		}
				foreach ($card as $ca) {
						$time = TIMESTAMP;
			$sign = array($ca['card_id'], $time);
			$signature = $this->SignatureCard($sign);
			if (is_error($signature)) {
				return $signature;
			}
			$post[] = array(
				'cardId' => trim($ca['card_id']),
				'cardExt' => array('timestamp' => $time, 'signature' => $signature),
			);
		}
		if (!empty($post)) {
			$card_json = json_encode($post);
			echo <<<EOF
			<script>
				wx.ready(function(){
					wx.addCard({
						cardList : {$card_json}, // 需要添加的卡券列表
						success: function (res) {

							 alert(JSON.stringify(res));
							var cardList = res.cardList; // 添加的卡券列表信息
						}
					});
				});

			</script>
EOF;
		} else {
			echo <<<EOF
			<script>

			</script>
EOF;
		}
	}
}

define('COUPON_CODE_TYPE_TEXT', 1);
define('COUPON_CODE_TYPE_QRCODE', 2);
define('COUPON_CODE_TYPE_BARCODE', 3);

define('COUPON_TIME_TYPE_RANGE', 1);
define('COUPON_TIME_TYPE_FIX', 2);
class Card {
	public $card_id = '';
	public $logo_url = '';
	public $brand_name = '';
	public $code_type = CODE_TYPE_BARCODE;
	public $title = '';
	public $sub_title = '';
	public $color = 'Color082';
	public $notice = '';
	public $service_phone = '';
	public $description = '';
	public $sku = array('quantity' => 50000);
	public $date_info = array('type' => COUPON_TIME_TYPE_RANGE);
	public $location_id_list = array();
	public $get_limit = 10; 	public $can_share = true;
	public $can_give_friend = true; 	public $use_custom_code = false; 	public $bind_openid = false; 	public $source = ''; 	public $status = ''; 	public $promotion_url_name = ''; 	public $promotion_url_sub_title = '';
	public $promotion_url = '';
	public $custom_url_name = ''; 	public $custom_url_sub_title = '';
	public $custom_url = '';
	public $center_title = ''; 	public $center_sub_title = '';
	public $center_url = '';
	public $need_push_on_view = false; 	public $pay_info = array(); 
		public $get_custom_code_mode = ''; 
	private $types = array('', 'DISCOUNT', 'CASH', 'GROUPON', 'GIFT', 'GENERAL_COUPON', 'MEMBER_CARD', 'SCENIC_TICKET', 'MOVIE_TICKET');
	private $code_types = array(COUPON_CODE_TYPE_TEXT => 'CODE_TYPE_TEXT', COUPON_CODE_TYPE_QRCODE => 'CODE_TYPE_QRCODE', COUPON_CODE_TYPE_BARCODE => 'CODE_TYPE_BARCODE');

	public static function create($type) {
		$card_class = array(
			COUPON_TYPE_DISCOUNT => 'Discount',
			COUPON_TYPE_CASH => 'Cash',
			COUPON_TYPE_GENERAL => 'General',
			COUPON_TYPE_GIFT => 'Gift',
			COUPON_TYPE_GROUPON => 'Groupon',
			COUPON_TYPE_MEMBER => 'Member',
		);
		if (empty($card_class[$type])) {
			return error(-1, '卡券类型错误');
		}
		$classname = $card_class[$type] . 'Card';
		$card = new $classname();
		$card->type = $type;

		return $card;
	}

	public function setDateinfoRange($starttime, $endtime) {
		$this->date_info = array(
			'type' => 'DATE_TYPE_FIX_TIME_RANGE', 			'begin_timestamp' => strtotime($starttime),
			'end_timestamp' => strtotime($endtime),
		);

		return true;
	}

	
	public function setDateinfoFix($begin, $term) {
		$this->date_info = array(
			'type' => 'DATE_TYPE_FIX_TERM', 			'fixed_term' => $term,
			'fixed_begin_term' => $begin,
		);

		return true;
	}

	public function setCodetype($type) {
		$this->code_type = $this->code_types[$type];

		return true;
	}

	public function setLocation($location) {
		$store = pdo_getall('activity_stores', array('id' => $location), array('location_id'), 'location_id');
		if (!empty($store)) {
			$this->location_id_list = array_keys($store);
		}
	}

		public function setCenterMenu($title, $subtitle, $url) {
		$this->center_title = urlencode($title);
		$this->center_sub_title = urlencode($subtitle);
		$this->center_url = urlencode($url);

		return true;
	}

		public function setCustomMenu($title, $subtitle, $url) {
		$this->custom_url_name = urlencode($title);
		$this->custom_url_sub_title = urlencode($subtitle);
		$this->custom_url = urlencode($url);

		return true;
	}

		public function setPromotionMenu($title, $subtitle, $url) {
		$this->promotion_url_name = urlencode($title);
		$this->promotion_url_sub_title = urlencode($subtitle);
		$this->promotion_url = urlencode($url);

		return true;
	}

	public function setQuantity($quantity) {
		$this->sku = $sku = array('quantity' => intval($quantity));
	}

	public function validate() {
		if (empty($this->logo_url)) {
			return error(7, '未设置商户logo');
		}
		if (empty($this->brand_name)) {
			return error(8, '未设置商户名称');
		}
		if (empty($this->title)) {
			return error(9, '未设置卡券标题');
		}
		if (empty($this->service_phone)) {
			return error(11, '客服电话不能为空');
		}
		if (empty($this->description)) {
			return error(12, '使用须知不能为空');
		}

		return true;
	}

	private function getBaseinfo() {
		$fields = array(
			'logo_url', 'brand_name', 'code_type', 'title', 'sub_title', 'color', 'notice',
			'service_phone', 'description', 'date_info', 'sku', 'get_limit', 'use_custom_code',
			'bind_openid', 'can_share', 'can_give_friend', 'location_id_list',
			'center_title', 'center_sub_title', 'center_url',
			'custom_url_name', 'custom_url', 'custom_url_sub_title',
			'promotion_url_name', 'promotion_url', 'promotion_url_sub_title', 'source', 'get_custom_code_mode',
		);
		if (6 == $this->type) {
			$fields[] = 'need_push_on_view';
			$fields[] = 'pay_info';
		}
		$baseinfo = array();
		foreach ($this as $filed => $value) {
			if (in_array($filed, $fields)) {
				$baseinfo[$filed] = $value;
			}
		}

		return $baseinfo;
	}

	private function getAdvinfo() {
		return array();
	}

	public function getCardData() {
		$carddata = array(
			'base_info' => $this->getBaseinfo(),
					);
		$carddata = array_merge($carddata, $this->getCardExtraData());
		$card = array(
			'card' => array(
				'card_type' => $this->types[$this->type],
				strtolower($this->types[$this->type]) => $carddata,
			),
		);

		return $card;
	}

	public function getCardArray() {
		$data = array(
			'card_id' => $this->card_id,
			'type' => $this->type,
			'logo_url' => urldecode($this->logo_url),
			'code_type' => array_search($this->code_type, $this->code_types),
			'brand_name' => $this->brand_name,
			'title' => $this->title,
			'sub_title' => $this->sub_title,
			'color' => $this->color,
			'notice' => $this->notice,
			'description' => $this->description,
			'quantity' => $this->sku['quantity'],
			'use_custom_code' => intval($this->use_custom_code),
			'bind_openid' => intval($this->bind_openid),
			'can_share' => intval($this->can_share),
			'can_give_friend' => intval($this->can_give_friend),
			'get_limit' => $this->get_limit,
			'service_phone' => $this->service_phone,
			'status' => $this->status,
			'is_display' => '1',
			'is_selfconsume' => '0',
			'promotion_url_name' => urldecode($this->promotion_url_name),
			'promotion_url' => urldecode($this->promotion_url),
			'promotion_url_sub_title' => urldecode($this->promotion_url_sub_title),
			'source' => $this->source,
		);
		$data['date_info'] = array(
			'time_type' => 'DATE_TYPE_FIX_TIME_RANGE' == $this->date_info['type'] ? 1 : 2,
			'time_limit_start' => date('Y.m.d', $this->date_info['begin_timestamp']),
			'time_limit_end' => date('Y.m.d', $this->date_info['end_timestamp']),
			'deadline' => $this->date_info['fixed_begin_term'],
			'limit' => $this->date_info['fixed_term'],
		);
		$data['date_info'] = iserializer($data['date_info']);
		$data['extra'] = iserializer($this->getCardExtraData());

		return $data;
	}
}

class MemberCard extends Card {
	public $background_pic_url = '';
	public $supply_bonus = true; 	public $bonus_rule = array(
		'cost_money_unit' => 100, 		'increase_bonus' => '', 		'max_increase_bonus' => '', 		'init_increase_bonus' => '', 		'cost_bonus_unit' => '', 		'reduce_money' => 100, 		'least_money_to_use_bonus' => '', 		'max_reduce_bonus' => '', 	); 	public $supply_balance = true; 	public $prerogative = ''; 	public $auto_activate = false; 	public $custom_field1 = array('name_type' => 'FIELD_NAME_TYPE_COUPON', 'url' => '');
	public $activate_url = ''; 	public $wx_activate = false; 	public $bonus_url = ''; 	public $balance_url = ''; 	public $bonus_rules = ''; 	public $balance_rules = ''; 	public $custom_cell1 = array('name' => '账单', 'tips' => '', 'url' => 'http://06.we7.cc/app/index.php?i=76&c=mc&a=bond&do=credits&credittype=credit2&type=record&period=1&wxref=mp.weixin.qq.com#wechat_redirect');
	public $discount = ''; 	public $bonus_cleared = ''; 	public $format_type = true;
	public $grant_rate = '';
	public $offset_rate = '';
	public $offset_max = '';
	public $fields = array();
	public $grant = array();
	public $discount_type = '';
	public $nums_status = '';
	public $nums_text = '';
	public $times_status = '';
	public $times_text = '';
	public $params = '';
	public $html = '';

	public function GetCardArray() {
		return array(
			'card_id' => $this->card_id,
			'source' => $this->source,
			'title' => $this->title,
			'brand_name' => $this->brand_name,
			'format_type' => $this->format_type,
			'color' => $this->color,
			'background' => $this->background_pic_url,
			'logo' => $this->logo_url,
			'description' => $this->description,
			'grant_rate' => $this->grant_rate,
			'offset_rate' => $this->offset_rate,
			'offset_max' => $this->offset_max,
			'fields' => $this->fields,
			'grant' => $this->grant,
			'discount_type' => $this->discount_type,
			'nums_status' => $this->nums_status,
			'nums_text' => $this->nums_text,
			'times_status' => $this->times_status,
			'times_text' => $this->times_text,
			'params' => $this->params,
			'html' => $this->html,
			'notice' => $this->notice,
			'quantity' => $this->sku['quantity'],
			'least_money_to_use_bonus' => $this->bonus_rule['least_money_to_use_bonus'],
			'max_increase_bonus' => $this->bonus_rule['max_increase_bonus'],
		);
	}

	public function getMemberCardUpdateArray() {
		$update['card_id'] = $this->card_id;
		$card = $this->getCardData();
		$update = array_merge($update, $card['card']);
		unset($update['card_type']);
		unset($update['member_card']['base_info']['source']);
		unset($update['member_card']['base_info']['sub_title']);
		unset($update['member_card']['base_info']['sku']);
		unset($update['member_card']['base_info']['use_custom_code']);
		unset($update['member_card']['base_info']['promotion_url_name']);
		unset($update['member_card']['base_info']['promotion_url']);
		unset($update['member_card']['base_info']['custom_url_name']);
		unset($update['member_card']['base_info']['custom_url']);
		unset($update['member_card']['base_info']['brand_name']);
		unset($update['member_card']['custom_cell1']);
		$update['member_card']['base_info']['promotion_url_name'] = urlencode('广播');
		$update['member_card']['base_info']['custom_url_name'] = urlencode('个人消息');
		$update['member_card']['base_info']['center_title'] = urlencode('付款');
		$update['member_card']['base_info']['title'] = urlencode($update['member_card']['base_info']['title']);
		$update['member_card']['base_info']['description'] = urlencode($update['member_card']['base_info']['description']);
		$update['member_card']['prerogative'] = urlencode($update['member_card']['prerogative']);

		return $update;
	}

	public function GetMemberCardArray() {
		$data = $this->getcardarray();

		return $data;
	}

	public function setBonusRule($cost_money_unit, $increase_bonus, $max_increase_bonus, $init_increase_bonus, $cost_bonus_unit, $reduce_money, $least_money_to_use_bonus, $max_reduce_bonus) {
		$this->bonus_rule = array(
			'cost_money_unit' => $cost_money_unit,
			'increase_bonus' => $increase_bonus,
			'max_increase_bonus' => $max_increase_bonus,
			'init_increase_bonus' => $init_increase_bonus,
			'cost_bonus_unit' => $cost_bonus_unit,
			'reduce_money' => $reduce_money,
			'least_money_to_use_bonus' => $least_money_to_use_bonus,
			'max_reduce_bonus' => $max_reduce_bonus,
		);

		return true;
	}

	public function setCustomCell($name, $tips, $url) {
		$this->custom_cell1 = array(
			'name' => $name,
			'tips' => $tips,
			'url' => $url,
		);

		return true;
	}

	public function setCustomField($name_type, $url, $num) {
		$array = array(
			'name_type' => $name_type,
			'url' => $url,
		);
		if (1 == $num) {
			$this->custom_field1 = $array;
		}
		if (2 == $num) {
			$this->custom_field2 = $array;
		}
		if (3 == $num) {
			$this->custom_field3 = $array;
		}

		return true;
	}

	public function validate() {
		$error = parent::validate();
		if (is_error($error) && 11 != $error['errno']) {
			return $error;
		}
		if (!empty($this->supply_bonus)) {
			if (empty($this->bonus_rule['cost_money_unit'])) {
				return error(13, '未填写积分说明中的消费金额');
			}
			if (empty($this->bonus_rule['increase_bonus'])) {
				return error(14, '未填写积分说明中的对应增加金额');
			}
			if (empty($this->bonus_rule['max_increase_bonus'])) {
				return error(15, '未填写积分说明中的用户单次可获取的积分上限');
			}
			if (empty($this->bonus_rule['init_increase_bonus'])) {
				return error(16, '未填写积分说明中的初始设置积分');
			}
			if (empty($this->bonus_rule['cost_bonus_unit'])) {
				return error(17, '未填写积分说明中的每次使用积分');
			}
			if (empty($this->bonus_rule['reduce_money'])) {
				return error(18, '未填写积分说明中的会员卡可抵扣多少元');
			}
			if (empty($this->bonus_rule['least_money_to_use_bonus'])) {
				return error(19, '未填写积分说明中的满xx元可用');
			}
			if (empty($this->bonus_rule['max_reduce_bonus'])) {
				return error(20, '未填写积分说明中的单笔最多使用xx积分');
			}
		}
		if (!empty($this->custom_cell1['name']) || !empty($this->custom_cell1['tips']) || !empty($this->custom_cell1['url'])) {
			if (empty($this->custom_cell1['name'])) {
				return error(21, '未填写入口名称');
			}
			if (empty($this->custom_cell1['url'])) {
				return error(23, '未填写入口跳转链接');
			}
		}
		if (empty($this->prerogative)) {
			return error(24, '未填写会员卡特权说明');
		}
		if (empty($this->wx_activate) && empty($this->activate_url)) {
			return error(25, '未填写激活会员卡url');
		}

		return true;
	}

	public function getCardExtraData() {
		return array(
			'background_pic_url' => $this->background_pic_url,
			'supply_bonus' => $this->supply_bonus,
			'bonus_rule' => $this->bonus_rule,
			'supply_balance' => $this->supply_balance,
			'prerogative' => $this->prerogative,
			'auto_activate' => $this->auto_activate,
			'custom_field1' => $this->custom_field1,
			'activate_url' => $this->activate_url,
			'wx_activate' => $this->wx_activate,
			'bonus_url' => $this->bonus_url,
			'balance_url' => $this->balance_url,
			'bonus_rules' => $this->bonus_rules,
			'balance_rules' => $this->balance_rules,
			'custom_cell1' => $this->custom_cell1,
			'discount' => $this->discount,
			'bonus_cleared' => $this->bonus_cleared,
		);
	}
}

class DiscountCard extends Card {
	public $discount = 0; 
	public function validate() {
		$error = parent::validate();
		if (is_error($error)) {
			return $error;
		}
		if (empty($this->discount)) {
			return error(1, '未设置折扣券折扣');
		}

		return true;
	}

	public function getCardExtraData() {
		return array(
			'discount' => $this->discount,
		);
	}
}

class CashCard extends Card {
	public $least_cost = 0; 	public $reduce_cost = 0; 
	public function validate() {
		$error = parent::validate();
		if (is_error($error)) {
			return $error;
		}
		if (!isset($this->least_cost)) {
			return error(2, '未设置代金券起用金额');
		}
		if (empty($this->least_cost)) {
			return error(3, '未设置代金券减免金额');
		}

		return true;
	}

	public function getCardExtraData() {
		return array(
			'least_cost' => $this->least_cost,
			'reduce_cost' => $this->reduce_cost,
		);
	}
}

class GiftCard extends Card {
	public $gift = ''; 
	public function validate() {
		$error = parent::validate();
		if (is_error($error)) {
			return $error;
		}
		if (empty($this->gift)) {
			return error(4, '未设置礼品券兑换内容');
		}

		return true;
	}

	public function getCardExtraData() {
		return array(
			'gift' => $this->gift,
		);
	}
}

class GrouponCard extends Card {
	public $deal_detail = ''; 
	public function validate() {
		$error = parent::validate();
		if (is_error($error)) {
			return $error;
		}
		if (empty($this->deal_detail)) {
			return error(5, '未设置团购券详情内容');
		}

		return true;
	}

	public function getCardExtraData() {
		return array(
			'deal_detail' => $this->deal_detail,
		);
	}
}

class GeneralCard extends Card {
	public $default_detail = ''; 
	public function validate() {
		$error = parent::validate();
		if (is_error($error)) {
			return $error;
		}
		if (empty($this->default_detail)) {
			return error(6, '未设置优惠券优惠详情');
		}

		return true;
	}

	public function getCardExtraData() {
		return array(
			'default_detail' => $this->default_detail,
		);
	}
}
