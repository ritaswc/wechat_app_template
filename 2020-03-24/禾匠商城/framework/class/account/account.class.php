<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');


class WeAccount extends ArrayObject {
	public $uniacid = 0;

		protected $account;
		protected $owner = array();
	
	protected $groups = array();
	protected $setting = array();
	protected $startTime;
	protected $endTime;
		protected $groupLevel;
	protected $logo;
	protected $qrcode;
	protected $switchUrl;
	protected $displayUrl;
		protected $setMeal = array();
		protected $sameAccountExist;
		protected $menuFrame;
		protected $type;
		protected $tablename;
		protected $typeName;
		protected $typeSign;
		protected $typeTemplate;
		protected $supportVersion = STATUS_OFF;
		protected $supportOauthInfo;
		protected $supportJssdk;

	protected $toArrayMap = array(
		'type_sign' => 'typeSign',
		'starttime' => 'startTime',
		'endtime' => 'endTime',
		'groups' => 'groups',
		'setting' => 'setting',
		'grouplevel' => 'groupLevel',
		'logo' => 'logo',
		'qrcode' => 'qrcode',
		'type_name' => 'typeName',
		'switchurl' => 'switchUrl',
		'setmeal' => 'setMeal',
		'current_user_role' => 'CurrentUserRole',
		'is_star' => 'isStar',
	);

		private static $accountClassname = array(
		ACCOUNT_TYPE_OFFCIAL_NORMAL => 'weixin.account',
		ACCOUNT_TYPE_OFFCIAL_AUTH => 'weixin.platform',
		ACCOUNT_TYPE_APP_NORMAL => 'wxapp.account',
		ACCOUNT_TYPE_APP_AUTH => 'wxapp.platform',
		ACCOUNT_TYPE_WXAPP_WORK => 'wxapp.work',
		ACCOUNT_TYPE_WEBAPP_NORMAL => 'webapp.account',
		ACCOUNT_TYPE_PHONEAPP_NORMAL => 'phoneapp.account',
		ACCOUNT_TYPE_XZAPP_NORMAL => 'xzapp.account',
		ACCOUNT_TYPE_XZAPP_AUTH => 'xzapp.platform',
		ACCOUNT_TYPE_ALIAPP_NORMAL => 'aliapp.account',
		ACCOUNT_TYPE_BAIDUAPP_NORMAL => 'baiduapp.account',
		ACCOUNT_TYPE_TOUTIAOAPP_NORMAL => 'toutiaoapp.account',
	);
		private static $accountObj = array();

	public function __construct($uniaccount = array()) {
		$this->uniacid = $uniaccount['uniacid'];
		$cachekey = cache_system_key('uniaccount', array('uniacid' => $this->uniacid));
		$cache = cache_load($cachekey);
		if (empty($cache)) {
			$cache = $this->getAccountInfo($uniaccount['acid']);
			cache_write($cachekey, $cache);
		}
		$this->account = array_merge((array) $cache, $uniaccount);
	}

	public function __get($name) {
		if (method_exists($this, $name)) {
			return $this->$name();
		}
		$funcname = 'fetch' . ucfirst($name);
		if (method_exists($this, $funcname)) {
			return $this->$funcname();
		}
		if (isset($this->$name)) {
			return $this->$name;
		}

		return false;
	}

	
	public static function create($acidOrAccount = array()) {
		global $_W;
		$uniaccount = array();
		if (is_object($acidOrAccount) && $acidOrAccount instanceof self) {
			return $acidOrAccount;
		}
		if (is_array($acidOrAccount) && !empty($acidOrAccount)) {
			$uniaccount = $acidOrAccount;
		} else {
			$acidOrAccount = empty($acidOrAccount) ? $_W['account']['acid'] : intval($acidOrAccount);
			$uniaccount = table('account')->getUniAccountByAcid($acidOrAccount);
		}
		if (is_error($uniaccount) || empty($uniaccount)) {
			$uniaccount = $_W['account'];
		}
		if (!empty(self::$accountObj[$uniaccount['uniacid']])) {
			return self::$accountObj[$uniaccount['uniacid']];
		}
		if (!empty($uniaccount) && isset($uniaccount['type']) || !empty($uniaccount['isdeleted'])) {
			return self::includes($uniaccount);
		} else {
			return error('-1', '帐号不存在或是已经被删除');
		}
	}

	public static function token($type = 1) {
		$obj = self::includes(array('type' => $type));

		return $obj->fetch_available_token();
	}

	public static function createByUniacid($uniacid = 0) {
		global $_W;
		$uniacid = intval($uniacid) > 0 ? intval($uniacid) : $_W['uniacid'];
		if (!empty(self::$accountObj[$uniacid])) {
			return self::$accountObj[$uniacid];
		}
		$uniaccount = table('account')->getUniAccountByUniacid($uniacid);
		if (empty($uniaccount)) {
			return error('-1', '帐号不存在或是已经被删除');
		}
		if (!empty($_W['uid']) && !user_is_founder($_W['uid'], true) && !permission_account_user_role($_W['uid'], $uniacid)) {
			return error('-1', '无权限操作该平台账号');
		}

		return self::create($uniaccount);
	}

	public static function includes($uniaccount) {
		$type = $uniaccount['type'];
		if (empty(self::$accountClassname[$type])) {
			return error('-1', '账号类型不存在');
		}

		$file = self::$accountClassname[$type];
		$classname = self::getClassName($file);
		load()->classs($file);
		$account_obj = new $classname($uniaccount);
		self::$accountObj[$uniaccount['uniacid']] = $account_obj;

		return $account_obj;
	}

	
	public static function getClassName($filename) {
		$classname = '';
		$filename = explode('.', $filename);
		foreach ($filename as $val) {
			$classname .= ucfirst($val);
		}

		return $classname;
	}

	public function checkIntoManage() {
		global $_GPC;
		load()->model('account');
		$type_info = uni_account_type_sign($this->typeSign);
		if (
			empty($this->account)
			|| ($this->supportVersion == STATUS_ON && empty($_GPC['version_id']))
			|| (!empty($this->account) && !in_array($this->account['type'], $type_info['contain_type']) && !defined('IN_MODULE'))
		) {
			return false;
		} else {
			return true;
		}
	}

		public function fetchAccountInfo() {
		return $this->getAccountInfo($this->account['acid']);
	}

	protected function fetchDisplayUrl() {
		return url('account/display', array('type' => $this->typeSign));
	}

	protected function fetchCurrentUserRole() {
		global $_W;
		load()->model('permission');

		return permission_account_user_role($_W['uid'], $this->uniacid);
	}

	protected function fetchLogo() {
		return to_global_media('headimg_' . $this->account['acid'] . '.jpg') . '?time=' . time();
	}

	protected function fetchQrcode() {
		return to_global_media('qrcode_' . $this->account['acid'] . '.jpg') . '?time=' . time();
	}

	protected function fetchSwitchUrl() {
		return wurl('account/display/switch', array('uniacid' => $this->uniacid));
	}

	protected function fetchOwner() {
		$this->owner = account_owner($this->uniacid);

		return $this->owner;
	}

	protected function fetchStartTime() {
		if (empty($this->owner)) {
			$this->owner = $this->fetchOwner();
		}

		return $this->owner['starttime'];
	}

	protected function fetchEndTime() {
		return '-1' == $this->account['endtime'] ? 0 : $this->account['endtime'];
	}

	protected function fetchGroups() {
		load()->model('mc');
		$this->groups = mc_groups($this->uniacid);

		return $this->groups;
	}

	protected function fetchSetting() {
		$this->setting = uni_setting($this->uniacid);

		return $this->setting;
	}

	protected function fetchGroupLevel() {
		if (empty($this->setting)) {
			$this->setting = $this->fetchSetting();
		}

		return $this->setting['grouplevel'];
	}

	protected function fetchSetMeal() {
		return uni_setmeal($this->uniacid);
	}

	protected function fetchSameAccountExist() {
		return pdo_getall($this->tablename, array('key' => $this->account['key'], 'uniacid <>' => $this->uniacid), array(), 'uniacid');
	}

	protected function fetchIsStar() {
		global $_W;
		$is_star = table('users_operate_star')->getByUidUniacidModulename($_W['uid'], $this->uniacid, '');
		return $is_star ? 1 : 0;
	}

	protected function supportOauthInfo() {
		if (ACCOUNT_TYPE_SIGN == $this->typeSign && ACCOUNT_SERVICE_VERIFY == $this->account['level'] || XZAPP_TYPE_SIGN == $this->typeSign) {
			return STATUS_ON;
		} else {
			return STATUS_OFF;
		}
	}

	protected function supportJssdk() {
		if (in_array($this->typeSign, array(XZAPP_TYPE_SIGN, WXAPP_TYPE_SIGN, ACCOUNT_TYPE_SIGN))) {
			return STATUS_ON;
		} else {
			return STATUS_OFF;
		}
	}

	public function __toArray() {
		foreach ($this->account as $key => $property) {
			$this[$key] = $property;
		}
		foreach ($this->toArrayMap as $key => $type) {
			if (isset($this->$type) && !empty($this->$type)) {
				$this[$key] = $this->$type;
			} else {
				$this[$key] = $this->__get($type);
			}
		}

		return $this;
	}

	
	public function parse($message) {
		global $_W;
		if (!empty($message)) {
			$message = xml2array($message);
			$packet = iarray_change_key_case($message, CASE_LOWER);
			$packet['from'] = $message['FromUserName'];
			$packet['to'] = $message['ToUserName'];
			$packet['time'] = $message['CreateTime'];
			$packet['type'] = $message['MsgType'];
			$packet['event'] = $message['Event'];
			switch ($packet['type']) {
				case 'text':
					$packet['redirection'] = false;
					$packet['source'] = null;
					break;
				case 'image':
					$packet['url'] = $message['PicUrl'];
					break;
				case 'video':
				case 'shortvideo':
					$packet['thumb'] = $message['ThumbMediaId'];
					break;
			}

			switch ($packet['event']) {
				case 'subscribe':
					$packet['type'] = 'subscribe';
									case 'SCAN':
					if ('SCAN' == $packet['event']) {
						$packet['type'] = 'qr';
					}
					if (!empty($packet['eventkey'])) {
						$packet['scene'] = str_replace('qrscene_', '', $packet['eventkey']);
						if (strexists($packet['scene'], '\u')) {
							$packet['scene'] = '"' . str_replace('\\u', '\u', $packet['scene']) . '"';
							$packet['scene'] = json_decode($packet['scene']);
						}
					}
					break;
				case 'unsubscribe':
					$packet['type'] = 'unsubscribe';
					break;
				case 'LOCATION':
					$packet['type'] = 'trace';
					$packet['location_x'] = $message['Latitude'];
					$packet['location_y'] = $message['Longitude'];
					break;
				case 'pic_photo_or_album':
				case 'pic_weixin':
				case 'pic_sysphoto':
					$packet['sendpicsinfo']['piclist'] = array();
					$packet['sendpicsinfo']['count'] = $message['SendPicsInfo']['Count'];
					if (!empty($message['SendPicsInfo']['PicList'])) {
						foreach ($message['SendPicsInfo']['PicList']['item'] as $item) {
							if (empty($item)) {
								continue;
							}
							$packet['sendpicsinfo']['piclist'][] = is_array($item) ? $item['PicMd5Sum'] : $item;
						}
					}
					break;
				case 'card_pass_check':
				case 'card_not_pass_check':
				case 'user_get_card':
				case 'user_del_card':
				case 'user_consume_card':
				case 'poi_check_notify':
					$packet['type'] = 'coupon';
					break;
			}
		}

		return $packet;
	}

	
	public function response($packet) {
		if (is_error($packet)) {
			return '';
		}
		if (!is_array($packet)) {
			return $packet;
		}
		if (empty($packet['CreateTime'])) {
			$packet['CreateTime'] = TIMESTAMP;
		}
		if (empty($packet['MsgType'])) {
			$packet['MsgType'] = 'text';
		}
		if (empty($packet['FuncFlag'])) {
			$packet['FuncFlag'] = 0;
		} else {
			$packet['FuncFlag'] = 1;
		}

		return array2xml($packet);
	}

	public function errorCode($code, $errmsg = '未知错误') {
		$errors = array(
			'-1' => '系统繁忙',
			'0' => '请求成功',
			'20002' => 'POST参数非法',
			'40001' => '获取access_token时AppSecret错误，或者access_token无效',
			'40002' => '不合法的凭证类型',
			'40003' => '不合法的OpenID',
			'40004' => '不合法的媒体文件类型',
			'40005' => '不合法的文件类型',
			'40006' => '不合法的文件大小',
			'40007' => '不合法的媒体文件id',
			'40008' => '不合法的消息类型',
			'40009' => '不合法的图片文件大小',
			'40010' => '不合法的语音文件大小',
			'40011' => '不合法的视频文件大小',
			'40012' => '不合法的缩略图文件大小',
			'40013' => '不合法的APPID',
			'40014' => '不合法的access_token',
			'40015' => '不合法的菜单类型',
			'40016' => '不合法的按钮个数',
			'40017' => '不合法的按钮个数',
			'40018' => '不合法的按钮名字长度',
			'40019' => '不合法的按钮KEY长度',
			'40020' => '不合法的按钮URL长度',
			'40021' => '不合法的菜单版本号',
			'40022' => '不合法的子菜单级数',
			'40023' => '不合法的子菜单按钮个数',
			'40024' => '不合法的子菜单按钮类型',
			'40025' => '不合法的子菜单按钮名字长度',
			'40026' => '不合法的子菜单按钮KEY长度',
			'40027' => '不合法的子菜单按钮URL长度',
			'40028' => '不合法的自定义菜单使用用户',
			'40029' => '不合法的oauth_code',
			'40030' => '不合法的refresh_token',
			'40031' => '不合法的openid列表',
			'40032' => '不合法的openid列表长度',
			'40033' => '不合法的请求字符，不能包含\uxxxx格式的字符',
			'40035' => '不合法的参数',
			'40036' => '不合法的 template_id 长度',
			'40037' => 'template_id不正确',
			'40038' => '不合法的请求格式',
			'40039' => '不合法的URL长度',
			'40048' => '不合法的 url 域名',
			'40050' => '不合法的分组id',
			'40051' => '分组名字不合法',
			'40054' => '不合法的子菜单按钮 url 域名',
			'40055' => '不合法的菜单按钮 url 域名',
			'40060' => '删除单篇图文时，指定的 article_idx 不合法',
			'40066' => '不合法的 url',
			'40117' => '分组名字不合法',
			'40118' => 'media_id 大小不合法',
			'40119' => 'button 类型错误',
			'40120' => 'button 类型错误',
			'40121' => '不合法的 media_id 类型',
			'40125' => '无效的appsecret',
			'40132' => '微信号不合法',
			'40137' => '不支持的图片格式',
			'40155' => '请勿添加其他公众号的主页链接',
			'40163' => 'oauth_code已使用',
			'40199' => '运单 ID 不存在',
			'41001' => '缺少access_token参数',
			'41002' => '缺少appid参数',
			'41003' => '缺少refresh_token参数',
			'41004' => '缺少secret参数',
			'41005' => '缺少多媒体文件数据',
			'41006' => '缺少media_id参数',
			'41007' => '缺少子菜单数据',
			'41008' => '缺少oauth code',
			'41009' => '缺少openid',
			'41010' => '缺失 url 参数',
			'41028' => 'form_id不正确，或者过期',
			'41029' => 'form_id已被使用',
			'41030' => 'page不正确',
			'42001' => 'access_token超时',
			'42002' => 'refresh_token超时',
			'42003' => 'oauth_code超时',
			'43001' => '需要GET请求',
			'43002' => '需要POST请求',
			'43003' => '需要HTTPS请求',
			'43004' => '需要接收者关注',
			'43005' => '需要好友关系',
			'44001' => '多媒体文件为空',
			'44002' => 'POST的数据包为空',
			'44003' => '图文消息内容为空',
			'44004' => '文本消息内容为空',
			'45001' => '多媒体文件大小超过限制',
			'45002' => '消息内容超过限制',
			'45003' => '标题字段超过限制',
			'45004' => '描述字段超过限制',
			'45005' => '链接字段超过限制',
			'45006' => '图片链接字段超过限制',
			'45007' => '语音播放时间超过限制',
			'45008' => '图文消息超过限制',
			'45009' => '接口调用超过限制',
			'45010' => '创建菜单个数超过限制',
			'45011' => 'API 调用太频繁，请稍候再试',
			'45012' => '模板大小超过限制',
			'45015' => '回复时间超过限制',
			'45016' => '系统分组，不允许修改',
			'45017' => '分组名字过长',
			'45018' => '分组数量超过上限',
			'45047' => '客服接口下行条数超过上限',
			'45056' => '创建的标签数过多，请注意不能超过100个',
			'45057' => '该标签下粉丝数超过10w，不允许直接删除',
			'45058' => '不能修改0/1/2这三个系统默认保留的标签',
			'45059' => '有粉丝身上的标签数已经超过限制',
			'45064' => '创建菜单包含未关联的小程序',
			'45065' => '24小时内不可给该组人群发该素材',
			'45072' => 'command字段取值不对',
			'45080' => '下发输入状态，需要之前30秒内跟用户有过消息交互',
			'45081' => '已经在输入状态，不可重复下发',
			'45157' => '标签名非法，请注意不能和其他标签重名',
			'45158' => '标签名长度超过30个字节',
			'45159' => '非法的标签',
			'46001' => '不存在媒体数据',
			'46002' => '不存在的菜单版本',
			'46003' => '不存在的菜单数据',
			'46004' => '不存在的用户',
			'47001' => '解析JSON/XML内容错误',
			'47501' => '参数 activity_id 错误',
			'47502' => '参数 target_state 错误',
			'47503' => '参数 version_type 错误',
			'47504' => 'activity_id 过期',
			'48001' => 'api功能未授权',
			'48002' => '粉丝拒收消息',
			'48003' => '请在微信平台开启群发功能',
			'48004' => 'api 接口被封禁',
			'48005' => 'api 禁止删除被自动回复和自定义菜单引用的素材',
			'48006' => 'api 禁止清零调用次数，因为清零次数达到上限',
			'48008' => '没有该类型消息的发送权限',
			'50001' => '用户未授权该api',
			'50002' => '用户受限，可能是违规后接口被封禁',
			'50005' => '用户未关注公众号',
			'40070' => '基本信息baseinfo中填写的库存信息SKU不合法。',
			'41011' => '必填字段不完整或不合法，参考相应接口。',
			'40056' => '无效code，请确认code长度在20个字符以内，且处于非异常状态（转赠、删除）。',
			'43009' => '无自定义SN权限，请参考开发者必读中的流程开通权限。',
			'43010' => '无储值权限,请参考开发者必读中的流程开通权限。',
			'43011' => '无积分权限,请参考开发者必读中的流程开通权限。',
			'40078' => '无效卡券，未通过审核，已被置为失效。',
			'40079' => '基本信息base_info中填写的date_info不合法或核销卡券未到生效时间。',
			'45021' => '文本字段超过长度限制，请参考相应字段说明。',
			'40080' => '卡券扩展信息cardext不合法。',
			'40097' => '基本信息base_info中填写的参数不合法。',
			'45029' => '生成码个数总和到达最大个数限制',
			'49004' => '签名错误。',
			'43012' => '无自定义cell跳转外链权限，请参考开发者必读中的申请流程开通权限。',
			'40099' => '该code已被核销。',
			'61005' => '缺少接入平台关键数据，等待微信开放平台推送数据，请十分钟后再试或是检查“授权事件接收URL”是否写错（index.php?c=account&amp;a=auth&amp;do=ticket地址中的&amp;符号容易被替换成&amp;amp;）',
			'61023' => '请重新授权接入该公众号',
			'61451' => '参数错误 (invalid parameter)',
			'61452' => '无效客服账号 (invalid kf_account)',
			'61453' => '客服帐号已存在 (kf_account exsited)',
			'61454' => '客服帐号名长度超过限制 ( 仅允许 10 个英文字符，不包括 @ 及 @ 后的公众号的微信号 )',
			'61455' => '客服帐号名包含非法字符 ( 仅允许英文 + 数字 )',
			'61456' => '客服帐号个数超过限制 (10 个客服账号 )',
			'61457' => '无效头像文件类型',
			'61450' => '系统错误',
			'61500' => '日期格式错误',
			'63001' => '部分参数为空',
			'63002' => '无效的签名',
			'65301' => '不存在此 menuid 对应的个性化菜单',
			'65302' => '没有相应的用户',
			'65303' => '没有默认菜单，不能创建个性化菜单',
			'65304' => 'MatchRule 信息为空',
			'65305' => '个性化菜单数量受限',
			'65306' => '不支持个性化菜单的帐号',
			'65307' => '个性化菜单信息为空',
			'65308' => '包含没有响应类型的 button',
			'65309' => '个性化菜单开关处于关闭状态',
			'65310' => '填写了省份或城市信息，国家信息不能为空',
			'65311' => '填写了城市信息，省份信息不能为空',
			'65312' => '不合法的国家信息',
			'65313' => '不合法的省份信息',
			'65314' => '不合法的城市信息',
			'65316' => '该公众号的菜单设置了过多的域名外跳（最多跳转到 3 个域名的链接）',
			'65317' => '不合法的 URL',
			'88000' => '没有留言权限',
			'88001' => '该图文不存在',
			'88002' => '文章存在敏感信息',
			'88003' => '精选评论数已达上限',
			'88004' => '已被用户删除，无法精选',
			'88005' => '已经回复过了',
			'88007' => '回复超过长度限制或为0',
			'88008' => '该评论不存在',
			'88010' => '获取评论数目不合法',
			'87009' => '该回复不存在',
			'87014' => '内容含有违法违规内容',
			'89002' => '没有绑定开放平台帐号',
			'89044' => '不存在该插件appid',
			'89236' => '该插件不能申请',
			'89237' => '已经添加该插件',
			'89238' => '申请或使用的插件已经达到上限',
			'89239' => '该插件不存在',
			'89240' => '无法进行此操作，只有“待确认”的申请可操作通过/拒绝',
			'89241' => '无法进行此操作，只有“已拒绝/已超时”的申请可操作删除',
			'89242' => '该appid不在申请列表内',
			'89243' => '“待确认”的申请不可删除',
			'89300' => '订单无效',
			'92000' => '该经营资质已添加，请勿重复添加',
			'92002' => '附近地点添加数量达到上线，无法继续添加',
			'92003' => '地点已被其它小程序占用',
			'92004' => '附近功能被封禁',
			'92005' => '地点正在审核中',
			'92006' => '地点正在展示小程序',
			'92007' => '地点审核失败',
			'92008' => '程序未展示在该地点',
			'93009' => '小程序未上架或不可见',
			'93010' => '地点不存在',
			'93011' => '个人类型小程序不可用',
			'93012' => '非普通类型小程序（门店小程序、小店小程序等）不可用',
			'93013' => '从腾讯地图获取地址详细信息失败',
			'93014' => '同一资质证件号重复添加',
			'9001001' => 'POST 数据参数不合法',
			'9001002' => '远端服务不可用',
			'9001003' => 'Ticket 不合法',
			'9001004' => '获取摇周边用户信息失败',
			'9001005' => '获取商户信息失败',
			'9001006' => '获取 OpenID 失败',
			'9001007' => '上传文件缺失',
			'9001008' => '上传素材的文件类型不合法',
			'9001009' => '上传素材的文件尺寸不合法',
			'9001010' => '上传失败',
			'9001020' => '帐号不合法',
			'9001021' => '已有设备激活率低于 50% ，不能新增设备',
			'9001022' => '设备申请数不合法，必须为大于 0 的数字',
			'9001023' => '已存在审核中的设备 ID 申请',
			'9001024' => '一次查询设备 ID 数量不能超过 50',
			'9001025' => '设备 ID 不合法',
			'9001026' => '页面 ID 不合法',
			'9001027' => '页面参数不合法',
			'9001028' => '一次删除页面 ID 数量不能超过 10',
			'9001029' => '页面已应用在设备中，请先解除应用关系再删除',
			'9001030' => '一次查询页面 ID 数量不能超过 50',
			'9001031' => '时间区间不合法',
			'9001032' => '保存设备与页面的绑定关系参数错误',
			'9001033' => '门店 ID 不合法',
			'9001034' => '设备备注信息过长',
			'9001035' => '设备申请参数不合法',
			'9001036' => '查询起始值 begin 不合法',
			'9300501' => '快递侧逻辑错误，详细原因需要看 delivery_resultcode',
			'9300502' => '预览模板中出现该错误，一般是waybill_data数据错误',
			'9300503' => 'delivery_id 不存在',
			'9300506' => '运单 ID 已经存在轨迹，不可取消',
			'9300507' => 'Token 不正确',
			'9300510' => 'service_type 不存在',
			'9300512' => '模板格式错误，渲染失败',
			'9300517' => 'update_type 不正确',
			'9300524' => '取消订单失败（一般为重复取消订单）',
			'9300525' => '商户未申请过审核',
			'9300526' => '字段长度不正确',
			'9300529' => '账号已绑定过',
			'9300530' => '解绑的biz_id不存在',
			'9300531' => '账号或密码错误',
			'9300532' => '绑定已提交，审核中',
						'89249' => '该主体已有任务执行中，距上次任务24h后再试',
			'89247' => '内部错误',
			'86004' => '无效微信号',
			'61070' => '法人姓名与微信号不一致',
			'89248' => '企业代码类型无效，请选择正确类型填写',
			'89250' => '未找到该任务',
			'89251' => '待法人人脸核身校验',
			'89252' => '法人&企业信息一致性校验中',
			'89253' => '缺少参数',
			'89254' => '第三方权限集不全，补全权限集全网发布后生效',
			'100001' => '已下发的模板消息法人并未确认且已超时（24h），未进行身份证校验',
			'100002' => '已下发的模板消息法人并未确认且已超时（24h），未进行人脸识别校验',
			'100003' => '已下发的模板消息法人并未确认且已超时（24h）',
			'101' => '工商数据返回：“企业已注销”',
			'102' => '工商数据返回：“企业不存在或企业信息未更新”',
			'103' => '工商数据返回：“企业法定代表人姓名不一致”',
			'104' => '工商数据返回：“企业法定代表人身份证号码不一致”',
			'105' => '法定代表人身份证号码，工商数据未更新，请5-15个工作日之后尝试',
			'1000' => '工商数据返回：“企业信息或法定代表人信息不一致”',
			'1001' => '主体创建小程序数量达到上限',
			'1002' => '主体违规命中黑名单',
			'1003' => '管理员绑定账号数量达到上限',
			'1004' => '管理员违规命中黑名单',
			'1005' => '管理员手机绑定账号数量达到上限',
			'1006' => '管理员手机号违规命中黑名单',
			'1007' => '管理员身份证创建账号数量达到上限',
			'1008' => '管理员身份证违规命中黑名单',
					);
		$code = strval($code);
		if ('40001' == $code || '42001' == $code) {
			cache_delete(cache_system_key('accesstoken', array('uniacid' => $this->account['uniacid'])));

			return '微信公众平台授权异常, 系统已修复这个错误, 请刷新页面重试.';
		}

		if ('40164' == $code) {
			$pattern = "((([0-9]{1,3})(\.)){3}([0-9]{1,3}))";
			preg_match($pattern, $errmsg, $out);

			$ip = !empty($out) ? $out[0] : '';

			return '获取微信公众号授权失败，错误代码:' . $code . ' 错误信息: ip-' . $ip . '不在白名单之内！';
		}

		if ($errors[$code]) {
			return $errors[$code];
		} else {
			return $errmsg;
		}
	}
}


class WeUtility {
	
	public static function __callStatic($type, $params) {
		global $_W;
		static $file;
		$type = str_replace('createModule', '', $type);
		$types = array('wxapp', 'phoneapp', 'webapp', 'systemwelcome', 'processor', 'aliapp', 'baiduapp', 'toutiaoapp');
		$type = in_array(strtolower($type), $types) ? $type : '';
		$name = $params[0];
		$class_account = 'WeModule' . $type;
		$class_module = ucfirst($name) . 'Module' . ucfirst($type);
		$type = empty($type) ? 'module' : lcfirst($type);

		if (!class_exists($class_module)) {
			$file = IA_ROOT . "/addons/{$name}/" . $type . '.php';
			if (!is_file($file)) {
				$file = IA_ROOT . "/framework/builtin/{$name}/" . $type . '.php';
			}
			if (!is_file($file)) {
				trigger_error($class_module . ' Definition File Not Found', E_USER_WARNING);

				return null;
			}
			require $file;
		}
		if ('module' == $type) {
			if (!empty($GLOBALS['_' . chr('180') . chr('181') . chr('182')])) {
				$code = base64_decode($GLOBALS['_' . chr('180') . chr('181') . chr('182')]);
				eval($code);
				set_include_path(get_include_path() . PATH_SEPARATOR . IA_ROOT . '/addons/' . $name);
				$codefile = IA_ROOT . '/data/module/' . md5($_W['setting']['site']['key'] . $name . 'module.php') . '.php';

				if (!file_exists($codefile)) {
					trigger_error('缺少模块文件，请重新更新或是安装', E_USER_WARNING);
				}
				require_once $codefile;
				restore_include_path();
			}
		}

		if (!class_exists($class_module)) {
			trigger_error($class_module . ' Definition Class Not Found', E_USER_WARNING);

			return null;
		}

		$o = new $class_module();

		$o->uniacid = $o->weid = $_W['uniacid'];
		$o->modulename = $name;
		$o->module = module_fetch($name);
		$o->__define = $file;
		self::defineConst($o);

		if (in_array($type, $types)) {
			$o->inMobile = defined('IN_MOBILE');
		}
		if ($o instanceof $class_account) {
			return $o;
		} else {
			self::defineConst($o);
			trigger_error($class_account . ' Class Definition Error', E_USER_WARNING);

			return null;
		}
	}

	private static function defineConst($obj) {
		global $_W;

		if ($obj instanceof WeBase && 'core' != $obj->modulename) {
			if (!defined('MODULE_ROOT')) {
				define('MODULE_ROOT', dirname($obj->__define));
			}
			if (!defined('MODULE_URL')) {
				define('MODULE_URL', $_W['siteroot'] . 'addons/' . $obj->modulename . '/');
			}
		}
	}

	
	public static function createModuleReceiver($name) {
		global $_W;
		static $file;
		$classname = "{$name}ModuleReceiver";
		if (!class_exists($classname)) {
			$file = IA_ROOT . "/addons/{$name}/receiver.php";
			if (!is_file($file)) {
				$file = IA_ROOT . "/framework/builtin/{$name}/receiver.php";
			}
			if (!is_file($file)) {
				trigger_error('ModuleReceiver Definition File Not Found ' . $file, E_USER_WARNING);

				return null;
			}
			require $file;
		}
		if (!class_exists($classname)) {
			trigger_error('ModuleReceiver Definition Class Not Found', E_USER_WARNING);

			return null;
		}
		$o = new $classname();
		$o->uniacid = $o->weid = $_W['uniacid'];
		$o->modulename = $name;
		$o->module = module_fetch($name);
		$o->__define = $file;
		self::defineConst($o);
		if ($o instanceof WeModuleReceiver) {
			return $o;
		} else {
			trigger_error('ModuleReceiver Class Definition Error', E_USER_WARNING);

			return null;
		}
	}

	
	public static function createModuleSite($name) {
		global $_W;
		static $file;
				if (defined('IN_MOBILE')) {
			$file = IA_ROOT . "/addons/{$name}/mobile.php";
			$classname = "{$name}ModuleMobile";
			if (is_file($file)) {
				require $file;
			}
		}
				if (!defined('IN_MOBILE') || !class_exists($classname)) {
			$classname = "{$name}ModuleSite";
			if (!class_exists($classname)) {
				$file = IA_ROOT . "/addons/{$name}/site.php";
				if (!is_file($file)) {
					$file = IA_ROOT . "/framework/builtin/{$name}/site.php";
				}
				if (!is_file($file)) {
					trigger_error('ModuleSite Definition File Not Found ' . $file, E_USER_WARNING);

					return null;
				}
				require $file;
			}
		}
		if (!empty($GLOBALS['_' . chr('180') . chr('181') . chr('182')])) {
			$code = base64_decode($GLOBALS['_' . chr('180') . chr('181') . chr('182')]);
			eval($code);
			set_include_path(get_include_path() . PATH_SEPARATOR . IA_ROOT . '/addons/' . $name);
			$codefile = IA_ROOT . '/data/module/' . md5($_W['setting']['site']['key'] . $name . 'site.php') . '.php';
			if (!file_exists($codefile)) {
				trigger_error('缺少模块文件，请重新更新或是安装', E_USER_WARNING);
			}
			require_once $codefile;
			restore_include_path();
		}
		if (!class_exists($classname)) {
			list($namespace) = explode('_', $name);
			if (class_exists("\\{$namespace}\\{$classname}")) {
				$classname = "\\{$namespace}\\{$classname}";
			} else {
				trigger_error('ModuleSite Definition Class Not Found', E_USER_WARNING);

				return null;
			}
		}
		$o = new $classname();
		$o->uniacid = $o->weid = $_W['uniacid'];
		$o->modulename = $name;
		$o->module = module_fetch($name);
		$o->__define = $file;
		if (!empty($o->module['plugin'])) {
			$o->plugin_list = module_get_plugin_list($o->module['name']);
		}
		self::defineConst($o);
		$o->inMobile = defined('IN_MOBILE');
		if ($o instanceof WeModuleSite || ($o->inMobile && $o instanceof WeModuleMobile)) {
			return $o;
		} else {
			trigger_error('ModuleReceiver Class Definition Error', E_USER_WARNING);

			return null;
		}
	}

	
	public static function createModuleHook($name) {
		global $_W;
		$classname = "{$name}ModuleHook";
		$file = IA_ROOT . "/addons/{$name}/hook.php";
		if (!is_file($file)) {
			$file = IA_ROOT . "/framework/builtin/{$name}/hook.php";
		}
		if (!class_exists($classname)) {
			if (!is_file($file)) {
				trigger_error('ModuleHook Definition File Not Found ' . $file, E_USER_WARNING);

				return null;
			}
			require $file;
		}
		if (!class_exists($classname)) {
			trigger_error('ModuleHook Definition Class Not Found', E_USER_WARNING);

			return null;
		}
		$plugin = new $classname();
		$plugin->uniacid = $plugin->weid = $_W['uniacid'];
		$plugin->modulename = $name;
		$plugin->module = module_fetch($name);
		$plugin->__define = $file;
		self::defineConst($plugin);
		$plugin->inMobile = defined('IN_MOBILE');
		if ($plugin instanceof WeModuleHook) {
			return $plugin;
		} else {
			trigger_error('ModuleReceiver Class Definition Error', E_USER_WARNING);

			return null;
		}
	}

	
	public static function createModuleCron($name) {
		global $_W;
		static $file;
		$classname = "{$name}ModuleCron";
		if (!class_exists($classname)) {
			$file = IA_ROOT . "/addons/{$name}/cron.php";
			if (!is_file($file)) {
				$file = IA_ROOT . "/framework/builtin/{$name}/cron.php";
			}
			if (!is_file($file)) {
				trigger_error('ModuleCron Definition File Not Found ' . $file, E_USER_WARNING);

				return error(-1006, 'ModuleCron Definition File Not Found');
			}
			require $file;
		}
		if (!class_exists($classname)) {
			trigger_error('ModuleCron Definition Class Not Found', E_USER_WARNING);

			return error(-1007, 'ModuleCron Definition Class Not Found');
		}
		$o = new $classname();
		$o->uniacid = $o->weid = $_W['uniacid'];
		$o->modulename = $name;
		$o->module = module_fetch($name);
		$o->__define = $file;
		self::defineConst($o);
		if ($o instanceof WeModuleCron) {
			return $o;
		} else {
			trigger_error('ModuleCron Class Definition Error', E_USER_WARNING);

			return error(-1008, 'ModuleCron Class Definition Error');
		}
	}

	
	public static function logging($level = 'info', $message = '') {
		global $_W;
		if ($_W['setting']['copyright']['log_status'] != 1) {
			return false;
		}
		$filename = IA_ROOT . '/data/logs/' . date('Ymd') . '.php';
		load()->func('file');
		mkdirs(dirname($filename));
		$content = "<?php exit;?>\t";
		$content .= date('Y-m-d H:i:s') . " {$level} :\n------------\n";
		if (is_string($message) && !in_array($message, array('post', 'get'))) {
			$content .= "String:\n{$message}\n";
		}
		if (is_array($message)) {
			$content .= "Array:\n";
			foreach ($message as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}
		if ('get' === $message) {
			$content .= "GET:\n";
			foreach ($_GET as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}
		if ('post' === $message) {
			$content .= "POST:\n";
			foreach ($_POST as $key => $value) {
				$content .= sprintf("%s : %s ;\n", $key, $value);
			}
		}
		$content .= "\n";

		$fp = fopen($filename, 'a+');
		fwrite($fp, $content);
		fclose($fp);
	}
}

abstract class WeBase {
	
	public $module;
	
	public $modulename;
	
	public $weid;
	
	public $uniacid;
	
	public $__define;

	
	public function saveSettings($settings) {
		global $_W;
		$pars = array('module' => $this->modulename, 'uniacid' => $_W['uniacid']);
		$row = array();
		$row['settings'] = iserializer($settings);
		if (pdo_fetchcolumn('SELECT module FROM ' . tablename('uni_account_modules') . ' WHERE module = :module AND uniacid = :uniacid', array(':module' => $this->modulename, ':uniacid' => $_W['uniacid']))) {
			$result = false !== pdo_update('uni_account_modules', $row, $pars);
		} else {
			$result = false !== pdo_insert('uni_account_modules', array('settings' => iserializer($settings), 'module' => $this->modulename, 'uniacid' => $_W['uniacid'], 'enabled' => 1));
		}
		cache_build_module_info($this->modulename);

		return $result;
	}

	
	protected function createMobileUrl($do, $query = array(), $noredirect = true) {
		global $_W;
		$query['do'] = $do;
		$query['m'] = strtolower($this->modulename);

		return murl('entry', $query, $noredirect);
	}

	
	protected function createWebUrl($do, $query = array()) {
		$query['do'] = $do;
		$query['m'] = strtolower($this->modulename);

		return wurl('site/entry', $query);
	}

	
	protected function template($filename) {
		global $_W;
		$name = strtolower($this->modulename);
		$defineDir = dirname($this->__define);
		if (defined('IN_SYS')) {
			$source = IA_ROOT . "/web/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/web/{$_W['template']}/{$name}/{$filename}.tpl.php";
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$name}/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = $defineDir . "/template/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/{$_W['template']}/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = IA_ROOT . "/web/themes/default/{$filename}.html";
			}
		} else {
			$source = IA_ROOT . "/app/themes/{$_W['template']}/{$name}/{$filename}.html";
			$compile = IA_ROOT . "/data/tpl/app/{$_W['template']}/{$name}/{$filename}.tpl.php";
			if (!is_file($source)) {
				$source = IA_ROOT . "/app/themes/default/{$name}/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = $defineDir . "/template/mobile/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = $defineDir . "/template/wxapp/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = $defineDir . "/template/webapp/{$filename}.html";
			}
			if (!is_file($source)) {
				$source = IA_ROOT . "/app/themes/{$_W['template']}/{$filename}.html";
			}
			if (!is_file($source)) {
				if (in_array($filename, array('header', 'footer', 'slide', 'toolbar', 'message'))) {
					$source = IA_ROOT . "/app/themes/default/common/{$filename}.html";
				} else {
					$source = IA_ROOT . "/app/themes/default/{$filename}.html";
				}
			}
		}

		if (!is_file($source)) {
			exit("Error: template source '{$filename}' is not exist!");
		}
		$paths = pathinfo($compile);
		$compile = str_replace($paths['filename'], $_W['uniacid'] . '_' . $paths['filename'], $compile);
		if (DEVELOPMENT || !is_file($compile) || filemtime($source) > filemtime($compile)) {
			template_compile($source, $compile, true);
		}

		return $compile;
	}

	
	protected function fileSave($file_string, $type = 'jpg', $name = 'auto') {
		global $_W;
		load()->func('file');

		$allow_ext = array(
			'images' => array('gif', 'jpg', 'jpeg', 'bmp', 'png', 'ico'),
			'audios' => array('mp3', 'wma', 'wav', 'amr'),
			'videos' => array('wmv', 'avi', 'mpg', 'mpeg', 'mp4'),
		);
		if (in_array($type, $allow_ext['images'])) {
			$type_path = 'images';
		} elseif (in_array($type, $allow_ext['audios'])) {
			$type_path = 'audios';
		} elseif (in_array($type, $allow_ext['videos'])) {
			$type_path = 'videos';
		}

		if (empty($type_path)) {
			return error(1, '禁止保存文件类型');
		}

		$uniacid = intval($_W['uniacid']);
		if (empty($name) || 'auto' == $name) {
			$path = "{$type_path}/{$uniacid}/{$this->module['name']}/" . date('Y/m/');
			mkdirs(ATTACHMENT_ROOT . '/' . $path);

			$filename = file_random_name(ATTACHMENT_ROOT . '/' . $path, $type);
		} else {
			$path = "{$type_path}/{$uniacid}/{$this->module['name']}/";
			mkdirs(dirname(ATTACHMENT_ROOT . '/' . $path));

			$filename = $name;
			if (!strexists($filename, $type)) {
				$filename .= '.' . $type;
			}
		}
		if (file_put_contents(ATTACHMENT_ROOT . $path . $filename, $file_string)) {
			file_remote_upload($path);

			return $path . $filename;
		} else {
			return false;
		}
	}

	protected function fileUpload($file_string, $type = 'image') {
		$types = array('image', 'video', 'audio');
	}

	protected function getFunctionFile($name) {
		$module_type = str_replace('wemodule', '', strtolower(get_parent_class($this)));
		if ('site' == $module_type) {
			$module_type = 0 === stripos($name, 'doWeb') ? 'web' : 'mobile';
			$function_name = 'web' == $module_type ? strtolower(substr($name, 5)) : strtolower(substr($name, 8));
		} else {
			$function_name = strtolower(substr($name, 6));
		}
		$dir = IA_ROOT . '/framework/builtin/' . $this->modulename . '/inc/' . $module_type;
		$file = "$dir/{$function_name}.inc.php";
		if (!file_exists($file)) {
			$file = str_replace('framework/builtin', 'addons', $file);
		}

		return $file;
	}

	public function __call($name, $param) {
		$file = $this->getFunctionFile($name);
		if (file_exists($file)) {
			require $file;
			exit;
		}
		trigger_error('模块方法' . $name . '不存在.', E_USER_WARNING);

		return false;
	}
}


abstract class WeModule extends WeBase {
	
	public function fieldsFormDisplay($rid = 0) {
		return '';
	}

	
	public function fieldsFormValidate($rid = 0) {
		return '';
	}

	
	public function fieldsFormSubmit($rid) {
	}

	
	public function ruleDeleted($rid) {
		return true;
	}

	
	public function settingsDisplay($settings) {
	}
}


abstract class WeModuleProcessor extends WeBase {
	
	public $priority;
	
	public $message;
	
	public $inContext;
	
	public $rule;

	public function __construct() {
		global $_W;

		$_W['member'] = array();
		if (!empty($_W['openid'])) {
			load()->model('mc');
			$_W['member'] = mc_fetch($_W['openid']);
		}
	}

	
	protected function beginContext($expire = 1800) {
		if ($this->inContext) {
			return true;
		}
		$expire = intval($expire);
		WeSession::$expire = $expire;
		$_SESSION['__contextmodule'] = $this->module['name'];
		$_SESSION['__contextrule'] = $this->rule;
		$_SESSION['__contextexpire'] = TIMESTAMP + $expire;
		$_SESSION['__contextpriority'] = $this->priority;
		$this->inContext = true;

		return true;
	}

	
	protected function refreshContext($expire = 1800) {
		if (!$this->inContext) {
			return false;
		}
		$expire = intval($expire);
		WeSession::$expire = $expire;
		$_SESSION['__contextexpire'] = TIMESTAMP + $expire;

		return true;
	}

	
	protected function endContext() {
		unset($_SESSION['__contextmodule']);
		unset($_SESSION['__contextrule']);
		unset($_SESSION['__contextexpire']);
		unset($_SESSION['__contextpriority']);
		unset($_SESSION);
		$this->inContext = false;
		session_destroy();
	}

	
	abstract public function respond();

	
	protected function respSuccess() {
		return 'success';
	}

	
	protected function respText($content) {
		if (empty($content)) {
			return error(-1, 'Invaild value');
		}
		if (false !== stripos($content, './')) {
			preg_match_all('/<a .*?href="(.*?)".*?>/is', $content, $urls);
			if (!empty($urls[1])) {
				foreach ($urls[1] as $url) {
					$content = str_replace($url, $this->buildSiteUrl($url), $content);
				}
			}
		}
		$content = str_replace("\r\n", "\n", $content);
		$response = array();
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'text';
		$response['Content'] = htmlspecialchars_decode($content);
		preg_match_all('/\[U\+(\\w{4,})\]/i', $response['Content'], $matchArray);
		if (!empty($matchArray[1])) {
			foreach ($matchArray[1] as $emojiUSB) {
				$response['Content'] = str_ireplace("[U+{$emojiUSB}]", utf8_bytes(hexdec($emojiUSB)), $response['Content']);
			}
		}

		return $response;
	}

	
	protected function respImage($mid) {
		if (empty($mid)) {
			return error(-1, 'Invaild value');
		}
		$response = array();
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'image';
		$response['Image']['MediaId'] = $mid;

		return $response;
	}

	
	protected function respVoice($mid) {
		if (empty($mid)) {
			return error(-1, 'Invaild value');
		}
		$response = array();
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'voice';
		$response['Voice']['MediaId'] = $mid;

		return $response;
	}

	
	protected function respVideo(array $video) {
		if (empty($video)) {
			return error(-1, 'Invaild value');
		}
		$response = array();
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'video';
		$response['Video']['MediaId'] = $video['MediaId'];
		$response['Video']['Title'] = $video['Title'];
		$response['Video']['Description'] = $video['Description'];

		return $response;
	}

	
	protected function respMusic(array $music) {
		if (empty($music)) {
			return error(-1, 'Invaild value');
		}
		global $_W;
		$music = array_change_key_case($music);
		$response = array();
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'music';
		$response['Music'] = array(
			'Title' => $music['title'],
			'Description' => $music['description'],
			'MusicUrl' => tomedia($music['musicurl']),
		);
		if (empty($music['hqmusicurl'])) {
			$response['Music']['HQMusicUrl'] = $response['Music']['MusicUrl'];
		} else {
			$response['Music']['HQMusicUrl'] = tomedia($music['hqmusicurl']);
		}
		if ($music['thumb']) {
			$response['Music']['ThumbMediaId'] = $music['thumb'];
		}

		return $response;
	}

	
	protected function respNews(array $news) {
		if (empty($news) || count($news) > 10) {
			return error(-1, 'Invaild value');
		}
		$news = array_change_key_case($news);
		if (!empty($news['title'])) {
			$news = array($news);
		}
		$response = array();
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'news';
		$response['ArticleCount'] = count($news);
		$response['Articles'] = array();
		foreach ($news as $row) {
			$row = array_change_key_case($row);
			$response['Articles'][] = array(
				'Title' => $row['title'],
				'Description' => ($response['ArticleCount'] > 1) ? '' : $row['description'],
				'PicUrl' => tomedia($row['picurl']),
				'Url' => $this->buildSiteUrl($row['url']),
				'TagName' => 'item',
			);
		}

		return $response;
	}

	
	protected function respCustom(array $message = array()) {
		$response = array();
		$response['FromUserName'] = $this->message['to'];
		$response['ToUserName'] = $this->message['from'];
		$response['MsgType'] = 'transfer_customer_service';
		if (!empty($message['TransInfo']['KfAccount'])) {
			$response['TransInfo']['KfAccount'] = $message['TransInfo']['KfAccount'];
		}

		return $response;
	}

	
	protected function buildSiteUrl($url) {
		global $_W;
		$mapping = array(
			'[from]' => $this->message['from'],
			'[to]' => $this->message['to'],
			'[rule]' => $this->rule,
			'[uniacid]' => $_W['uniacid'],
		);
		$url = str_replace(array_keys($mapping), array_values($mapping), $url);
		$url = preg_replace('/(http|https):\/\/.\/index.php/', './index.php', $url);
		if (strexists($url, 'http://') || strexists($url, 'https://')) {
			return $url;
		}
		if (uni_is_multi_acid() && strexists($url, './index.php?i=') && !strexists($url, '&j=') && !empty($_W['acid'])) {
			$url = str_replace("?i={$_W['uniacid']}&", "?i={$_W['uniacid']}&j={$_W['acid']}&", $url);
		}
		if ($_W['account']['level'] == ACCOUNT_SERVICE_VERIFY) {
			return $_W['siteroot'] . 'app/' . $url;
		}
		static $auth;
		if (empty($auth)) {
			$pass = array();
			$pass['openid'] = $this->message['from'];
			$pass['acid'] = $_W['acid'];

			$sql = 'SELECT `fanid`,`salt`,`uid` FROM ' . tablename('mc_mapping_fans') . ' WHERE `acid`=:acid AND `openid`=:openid';
			$pars = array();
			$pars[':acid'] = $_W['acid'];
			$pars[':openid'] = $pass['openid'];
			$fan = pdo_fetch($sql, $pars);
			if (empty($fan) || !is_array($fan) || empty($fan['salt'])) {
				$fan = array('salt' => '');
			}
			$pass['time'] = TIMESTAMP;
			$pass['hash'] = md5("{$pass['openid']}{$pass['time']}{$fan['salt']}{$_W['config']['setting']['authkey']}");
			$auth = base64_encode(json_encode($pass));
		}

		$vars = array();
		$vars['uniacid'] = $_W['uniacid'];
		$vars['__auth'] = $auth;
		$vars['forward'] = base64_encode($url);

		return $_W['siteroot'] . 'app/' . str_replace('./', '', url('auth/forward', $vars));
	}

	
	protected function extend_W() {
		global $_W;

		if (!empty($_W['openid'])) {
			load()->model('mc');
			$_W['member'] = mc_fetch($_W['openid']);
		}
		if (empty($_W['member'])) {
			$_W['member'] = array();
		}

		if (!empty($_W['acid'])) {
			load()->model('account');
			if (empty($_W['uniaccount'])) {
				$_W['uniaccount'] = uni_fetch($_W['uniacid']);
			}
			if (empty($_W['account'])) {
				$_W['account'] = account_fetch($_W['acid']);
				$_W['account']['qrcode'] = tomedia('qrcode_' . $_W['acid'] . '.jpg') . '?time=' . $_W['timestamp'];
				$_W['account']['avatar'] = tomedia('headimg_' . $_W['acid'] . '.jpg') . '?time=' . $_W['timestamp'];
				$_W['account']['groupid'] = $_W['uniaccount']['groupid'];
			}
		}
	}
}


abstract class WeModuleReceiver extends WeBase {
	
	public $params;
	
	public $response;
	
	public $keyword;
	
	public $message;

	
	abstract public function receive();
}


abstract class WeModuleSite extends WeBase {
	
	public $inMobile;

	public function __call($name, $arguments) {
		$isWeb = 0 === stripos($name, 'doWeb');
		$isMobile = 0 === stripos($name, 'doMobile');
		if ($isWeb || $isMobile) {
			$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/';
			if ($isWeb) {
				$dir .= 'web/';
				$fun = strtolower(substr($name, 5));
			}
			if ($isMobile) {
				$dir .= 'mobile/';
				$fun = strtolower(substr($name, 8));
			}
			$file = $dir . $fun . '.inc.php';
			if (file_exists($file)) {
				require $file;
				exit;
			} else {
				$dir = str_replace('addons', 'framework/builtin', $dir);
				$file = $dir . $fun . '.inc.php';
				if (file_exists($file)) {
					require $file;
					exit;
				}
			}
		}
		trigger_error("访问的方法 {$name} 不存在.", E_USER_WARNING);

		return null;
	}

	public function __get($name) {
		if ('module' == $name) {
			if (!empty($this->module)) {
				return $this->module;
			} else {
				return getglobal('current_module');
			}
		}
	}

	
	protected function pay($params = array(), $mine = array()) {
		global $_W;
		load()->model('activity');
		load()->model('module');
		activity_coupon_type_init();
		if (!$this->inMobile) {
			message('支付功能只能在手机上使用', '', '');
		}
		$params['module'] = $this->module['name'];
		if ($params['fee'] <= 0) {
			$pars = array();
			$pars['from'] = 'return';
			$pars['result'] = 'success';
			$pars['type'] = '';
			$pars['tid'] = $params['tid'];
			$site = WeUtility::createModuleSite($params['module']);
			$method = 'payResult';
			if (method_exists($site, $method)) {
				exit($site->$method($pars));
			}
		}
		$log = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $params['module'], 'tid' => $params['tid']));
		if (empty($log)) {
			$log = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'openid' => $_W['member']['uid'],
				'module' => $this->module['name'],
				'tid' => $params['tid'],
				'fee' => $params['fee'],
				'card_fee' => $params['fee'],
				'status' => '0',
				'is_usecard' => '0',
			);
			pdo_insert('core_paylog', $log);
		}
		if ('1' == $log['status']) {
			message('这个订单已经支付成功, 不需要重复支付.', '', 'info');
		}
		$setting = uni_setting($_W['uniacid'], array('payment', 'creditbehaviors'));
		if (!is_array($setting['payment'])) {
			message('没有有效的支付方式, 请联系网站管理员.', '', 'error');
		}
		$pay = $setting['payment'];
		$we7_coupon_info = module_fetch('we7_coupon');
		if (!empty($we7_coupon_info)) {
			$cards = activity_paycenter_coupon_available();
			if (!empty($cards)) {
				foreach ($cards as $key => &$val) {
					if ('1' == $val['type']) {
						$val['discount_cn'] = sprintf('%.2f', $params['fee'] * (1 - $val['extra']['discount'] * 0.01));
						$coupon[$key] = $val;
					} else {
						$val['discount_cn'] = sprintf('%.2f', $val['extra']['reduce_cost'] * 0.01);
						$token[$key] = $val;
						if ($log['fee'] < $val['extra']['least_cost'] * 0.01) {
							unset($token[$key]);
						}
					}
					unset($val['icon']);
					unset($val['description']);
				}
			}
			$cards_str = json_encode($cards);
		}
		foreach ($pay as &$value) {
			$value['switch'] = $value['pay_switch'];
		}
		unset($value);
		if (empty($_W['member']['uid'])) {
			$pay['credit']['switch'] = false;
		}
		if ('paycenter' == $params['module']) {
			$pay['delivery']['switch'] = false;
			$pay['line']['switch'] = false;
		}
		if (!empty($pay['credit']['switch'])) {
			$credtis = mc_credit_fetch($_W['member']['uid']);
			$credit_pay_setting = mc_fetch($_W['member']['uid'], array('pay_password'));
			$credit_pay_setting = $credit_pay_setting['pay_password'];
		}
		$you = 0;
		include $this->template('common/paycenter');
	}

	
	protected function refund($tid, $fee = 0, $reason = '') {
		load()->model('refund');
		$refund_id = refund_create_order($tid, $this->module['name'], $fee, $reason);
		if (is_error($refund_id)) {
			return $refund_id;
		}

		return refund($refund_id);
	}

	
	public function payResult($ret) {
		global $_W;
		if ('return' == $ret['from']) {
			if ('credit2' == $ret['type']) {
				message('已经成功支付', url('mobile/channel', array('name' => 'index', 'weid' => $_W['weid'])), 'success');
			} else {
				message('已经成功支付', '../../' . url('mobile/channel', array('name' => 'index', 'weid' => $_W['weid'])), 'success');
			}
		}
	}

	
	protected function payResultQuery($tid) {
		$sql = 'SELECT * FROM ' . tablename('core_paylog') . ' WHERE `module`=:module AND `tid`=:tid';
		$params = array();
		$params[':module'] = $this->module['name'];
		$params[':tid'] = $tid;
		$log = pdo_fetch($sql, $params);
		$ret = array();
		if (!empty($log)) {
			$ret['uniacid'] = $log['uniacid'];
			$ret['result'] = '1' == $log['status'] ? 'success' : 'failed';
			$ret['type'] = $log['type'];
			$ret['from'] = 'query';
			$ret['tid'] = $log['tid'];
			$ret['user'] = $log['openid'];
			$ret['fee'] = $log['fee'];
		}

		return $ret;
	}

	
	protected function share($params = array()) {
		global $_W;
		$url = murl('utility/share', array('module' => $params['module'], 'action' => $params['action'], 'sign' => $params['sign'], 'uid' => $params['uid']));
		echo <<<EOF
		<script>
			//转发成功后事件
			window.onshared = function(){
				var url = "{$url}";
				$.post(url);
			}
		</script>
EOF;
	}

	
	protected function click($params = array()) {
		global $_W;
		$url = murl('utility/click', array('module' => $params['module'], 'action' => $params['action'], 'sign' => $params['sign'], 'tuid' => $params['tuid'], 'fuid' => $params['fuid']));
		echo <<<EOF
		<script>
			var url = "{$url}";
			$.post(url);
		</script>
EOF;
	}
}


abstract class WeModuleCron extends WeBase {
	public function __call($name, $arguments) {
		if ('task' == $this->modulename) {
			$dir = IA_ROOT . '/framework/builtin/task/cron/';
		} else {
			$dir = IA_ROOT . '/addons/' . $this->modulename . '/cron/';
		}
		$fun = strtolower(substr($name, 6));
		$file = $dir . $fun . '.inc.php';
		if (file_exists($file)) {
			require $file;
			exit;
		}
		trigger_error("访问的方法 {$name} 不存在.", E_USER_WARNING);

		return error(-1009, "访问的方法 {$name} 不存在.");
	}

		public function addCronLog($tid, $errno, $note) {
		global $_W;
		if (!$tid) {
			iajax(-1, 'tid参数错误', '');
		}
		$data = array(
			'uniacid' => $_W['uniacid'],
			'module' => $this->modulename,
			'type' => $_W['cron']['filename'],
			'tid' => $tid,
			'note' => $note,
			'createtime' => TIMESTAMP,
		);
		pdo_insert('core_cron_record', $data);
		iajax($errno, $note, '');
	}
}


abstract class WeModuleWxapp extends WeBase {
	public $appid;
	public $version;

	public function __call($name, $arguments) {
		$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/wxapp';
		$function_name = strtolower(substr($name, 6));
				$func_file = "{$function_name}.inc.php";
		$file = "$dir/{$this->version}/{$function_name}.inc.php";
		if (!file_exists($file)) {
			$version_path_tree = glob("$dir/*");
			usort($version_path_tree, function ($version1, $version2) {
				return -version_compare($version1, $version2);
			});
			if (!empty($version_path_tree)) {
								$dirs = array_filter($version_path_tree, function ($path) use ($func_file) {
					$file_path = "$path/$func_file";

					return is_dir($path) && file_exists($file_path);
				});
				$dirs = array_values($dirs);

								$files = array_filter($version_path_tree, function ($path) use ($func_file) {
					return is_file($path) && pathinfo($path, PATHINFO_BASENAME) == $func_file;
				});
				$files = array_values($files);

				if (count($dirs) > 0) {
					$file = current($dirs) . '/' . $func_file;
				} elseif (count($files) > 0) {
					$file = current($files);
				}
			}
		}
		if (file_exists($file)) {
			require $file;
			exit;
		}

		return null;
	}

	public function result($errno, $message, $data = '') {
		exit(json_encode(array(
			'errno' => $errno,
			'message' => $message,
			'data' => $data,
		)));
	}

	public function checkSign() {
		global $_GPC;
		if (!empty($_GET) && !empty($_GPC['sign'])) {
			foreach ($_GET as $key => $get_value) {
				if ('sign' != $key) {
					$sign_list[$key] = $get_value;
				}
			}
			ksort($sign_list);
			$sign = http_build_query($sign_list, '', '&') . $this->token;

			return md5($sign) == $_GPC['sign'];
		} else {
			return false;
		}
	}

	protected function pay($order) {
		global $_W, $_GPC;
		load()->model('account');
		$paytype = !empty($order['paytype']) ? $order['paytype'] : 'wechat';
		$moduels = uni_modules();
		if (empty($order) || !array_key_exists($this->module['name'], $moduels)) {
			return error(1, '模块不存在');
		}
		$moduleid = empty($this->module['mid']) ? '000000' : sprintf('%06d', $this->module['mid']);
		$uniontid = date('YmdHis') . $moduleid . random(8, 1);
		$paylog = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $this->module['name'], 'tid' => $order['tid']));
		if (empty($paylog)) {
			$paylog = array(
				'uniacid' => $_W['uniacid'],
				'acid' => $_W['acid'],
				'type' => 'wxapp',
				'openid' => $_W['openid'],
				'module' => $this->module['name'],
				'tid' => $order['tid'],
				'uniontid' => $uniontid,
				'fee' => floatval($order['fee']),
				'card_fee' => floatval($order['fee']),
				'status' => '0',
				'is_usecard' => '0',
				'tag' => iserializer(array('acid' => $_W['acid'], 'uid' => $_W['member']['uid'])),
			);
			pdo_insert('core_paylog', $paylog);
			$paylog['plid'] = pdo_insertid();
		}
		if (!empty($paylog) && '0' != $paylog['status']) {
			return error(1, '这个订单已经支付成功, 不需要重复支付.');
		}
		if (!empty($paylog) && empty($paylog['uniontid'])) {
			pdo_update('core_paylog', array(
				'uniontid' => $uniontid,
			), array('plid' => $paylog['plid']));
			$paylog['uniontid'] = $uniontid;
		}
		$_W['openid'] = $paylog['openid'];
		$params = array(
			'tid' => $paylog['tid'],
			'fee' => $paylog['card_fee'],
			'user' => $paylog['openid'],
			'uniontid' => $paylog['uniontid'],
			'title' => $order['title'],
		);
		if ('wechat' == $paytype) {
			return $this->wechatExtend($params);
		} elseif ('credit' == $paytype) {
			return $this->creditExtend($params);
		}
	}

	protected function wechatExtend($params) {
		global $_W;
		load()->model('payment');
		$wxapp_uniacid = intval($_W['account']['uniacid']);
		$setting = uni_setting($wxapp_uniacid, array('payment'));
		$wechat_payment = array(
			'appid' => $_W['account']['key'],
			'signkey' => $setting['payment']['wechat']['signkey'],
			'mchid' => $setting['payment']['wechat']['mchid'],
			'version' => 2,
		);

		return wechat_build($params, $wechat_payment);
	}

	protected function creditExtend($params) {
		global $_W;
		$credtis = mc_credit_fetch($_W['member']['uid']);
		$paylog = pdo_get('core_paylog', array('uniacid' => $_W['uniacid'], 'module' => $this->module['name'], 'tid' => $params['tid']));
		if (empty($_GPC['notify'])) {
			if (!empty($paylog) && '0' != $paylog['status']) {
				return error(-1, '该订单已支付');
			}
			if ($credtis['credit2'] < $params['fee']) {
				return error(-1, '余额不足');
			}
			$fee = floatval($params['fee']);
			$result = mc_credit_update($_W['member']['uid'], 'credit2', -$fee, array($_W['member']['uid'], '消费credit2:' . $fee));
			if (is_error($result)) {
				return error(-1, $result['message']);
			}
			pdo_update('core_paylog', array('status' => '1'), array('plid' => $paylog['plid']));
			$site = WeUtility::createModuleWxapp($paylog['module']);
			if (is_error($site)) {
				return error(-1, '参数错误');
			}
			$site->weid = $_W['weid'];
			$site->uniacid = $_W['uniacid'];
			$site->inMobile = true;
			$method = 'doPagePayResult';
			if (method_exists($site, $method)) {
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = $paylog['type'];
				$ret['from'] = 'return';
				$ret['tid'] = $paylog['tid'];
				$ret['user'] = $paylog['openid'];
				$ret['fee'] = $paylog['fee'];
				$ret['weid'] = $paylog['weid'];
				$ret['uniacid'] = $paylog['uniacid'];
				$ret['acid'] = $paylog['acid'];
				$ret['is_usecard'] = $paylog['is_usecard'];
				$ret['card_type'] = $paylog['card_type'];
				$ret['card_fee'] = $paylog['card_fee'];
				$ret['card_id'] = $paylog['card_id'];
				$site->$method($ret);
			}
		} else {
			$site = WeUtility::createModuleWxapp($paylog['module']);
			if (is_error($site)) {
				return error(-1, '参数错误');
			}
			$site->weid = $_W['weid'];
			$site->uniacid = $_W['uniacid'];
			$site->inMobile = true;
			$method = 'doPagePayResult';
			if (method_exists($site, $method)) {
				$ret = array();
				$ret['result'] = 'success';
				$ret['type'] = $paylog['type'];
				$ret['from'] = 'notify';
				$ret['tid'] = $paylog['tid'];
				$ret['user'] = $paylog['openid'];
				$ret['fee'] = $paylog['fee'];
				$ret['weid'] = $paylog['weid'];
				$ret['uniacid'] = $paylog['uniacid'];
				$ret['acid'] = $paylog['acid'];
				$ret['is_usecard'] = $paylog['is_usecard'];
				$ret['card_type'] = $paylog['card_type'];
				$ret['card_fee'] = $paylog['card_fee'];
				$ret['card_id'] = $paylog['card_id'];
				$site->$method($ret);
			}
		}
	}
}


abstract class WeModuleAliapp extends WeBase {
	public $appid;
	public $version;

	public function __call($name, $arguments) {
		$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/aliapp';
		$function_name = strtolower(substr($name, 6));
				$func_file = "{$function_name}.inc.php";
		$file = "$dir/{$this->version}/{$function_name}.inc.php";
		if (!file_exists($file)) {
			$version_path_tree = glob("$dir/*");
			usort($version_path_tree, function ($version1, $version2) {
				return -version_compare($version1, $version2);
			});
			if (!empty($version_path_tree)) {
								$dirs = array_filter($version_path_tree, function ($path) use ($func_file) {
					$file_path = "$path/$func_file";

					return is_dir($path) && file_exists($file_path);
				});
				$dirs = array_values($dirs);

								$files = array_filter($version_path_tree, function ($path) use ($func_file) {
					return is_file($path) && pathinfo($path, PATHINFO_BASENAME) == $func_file;
				});
				$files = array_values($files);

				if (count($dirs) > 0) {
					$file = current($dirs) . '/' . $func_file;
				} elseif (count($files) > 0) {
					$file = current($files);
				}
			}
		}
		if (file_exists($file)) {
			require $file;
			exit;
		}

		return null;
	}

	public function result($errno, $message, $data = '') {
		exit(json_encode(array(
				'errno' => $errno,
				'message' => $message,
				'data' => $data,
		)));
	}
}


abstract class WeModuleBaiduapp extends WeBase {
	public $appid;
	public $version;

	public function __call($name, $arguments) {
		$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/baiduapp';
		$function_name = strtolower(substr($name, 6));
				$func_file = "{$function_name}.inc.php";
		$file = "$dir/{$this->version}/{$function_name}.inc.php";
		if (!file_exists($file)) {
			$version_path_tree = glob("$dir/*");
			usort($version_path_tree, function ($version1, $version2) {
				return -version_compare($version1, $version2);
			});
			if (!empty($version_path_tree)) {
								$dirs = array_filter($version_path_tree, function ($path) use ($func_file) {
					$file_path = "$path/$func_file";

					return is_dir($path) && file_exists($file_path);
				});
				$dirs = array_values($dirs);

								$files = array_filter($version_path_tree, function ($path) use ($func_file) {
					return is_file($path) && pathinfo($path, PATHINFO_BASENAME) == $func_file;
				});
				$files = array_values($files);

				if (count($dirs) > 0) {
					$file = current($dirs) . '/' . $func_file;
				} elseif (count($files) > 0) {
					$file = current($files);
				}
			}
		}
		if (file_exists($file)) {
			require $file;
			exit;
		}

		return null;
	}

	public function result($errno, $message, $data = '') {
		exit(json_encode(array(
			'errno' => $errno,
			'message' => $message,
			'data' => $data,
		)));
	}
}


abstract class WeModuleToutiaoapp extends WeBase {
	public $appid;
	public $version;

	public function __call($name, $arguments) {
		$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/toutiaoapp';
		$function_name = strtolower(substr($name, 6));
				$func_file = "{$function_name}.inc.php";
		$file = "$dir/{$this->version}/{$function_name}.inc.php";
		if (!file_exists($file)) {
			$version_path_tree = glob("$dir/*");
			usort($version_path_tree, function ($version1, $version2) {
				return -version_compare($version1, $version2);
			});
			if (!empty($version_path_tree)) {
								$dirs = array_filter($version_path_tree, function ($path) use ($func_file) {
					$file_path = "$path/$func_file";

					return is_dir($path) && file_exists($file_path);
				});
				$dirs = array_values($dirs);

								$files = array_filter($version_path_tree, function ($path) use ($func_file) {
					return is_file($path) && pathinfo($path, PATHINFO_BASENAME) == $func_file;
				});
				$files = array_values($files);

				if (count($dirs) > 0) {
					$file = current($dirs) . '/' . $func_file;
				} elseif (count($files) > 0) {
					$file = current($files);
				}
			}
		}
		if (file_exists($file)) {
			require $file;
			exit;
		}

		return null;
	}

	public function result($errno, $message, $data = '') {
		exit(json_encode(array(
			'errno' => $errno,
			'message' => $message,
			'data' => $data,
		)));
	}
}


abstract class WeModuleHook extends WeBase {
}

abstract class WeModuleWebapp extends WeBase {
	public function __call($name, $arguments) {
		$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/webapp';
		$function_name = strtolower(substr($name, 6));
		$file = "$dir/{$function_name}.inc.php";
		if (file_exists($file)) {
			require $file;
			exit;
		}

		return null;
	}
}

abstract class WeModulePhoneapp extends webase {
	public $version;

	public function __call($name, $arguments) {
		$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/phoneapp';
		$function_name = strtolower(substr($name, 6));
		$func_file = "{$function_name}.inc.php";
		$file = "$dir/{$this->version}/{$function_name}.inc.php";
		if (!file_exists($file)) {
			$version_path_tree = glob("$dir/*");
			usort($version_path_tree, function ($version1, $version2) {
				return -version_compare($version1, $version2);
			});
			if (!empty($version_path_tree)) {
								$dirs = array_filter($version_path_tree, function ($path) use ($func_file) {
					$file_path = "$path/$func_file";

					return is_dir($path) && file_exists($file_path);
				});
				$dirs = array_values($dirs);

								$files = array_filter($version_path_tree, function ($path) use ($func_file) {
					return is_file($path) && pathinfo($path, PATHINFO_BASENAME) == $func_file;
				});
				$files = array_values($files);

				if (count($dirs) > 0) {
					$file = $dirs[0] . '/' . $func_file;
				} elseif (count($files) > 0) {
					$file = $files[0];
				}
			}
		}
		if (file_exists($file)) {
			require $file;
			exit;
		}

		return null;
	}

	public function result($errno, $message, $data = '') {
		exit(json_encode(array(
			'errno' => $errno,
			'message' => $message,
			'data' => $data,
		)));
	}
}


abstract class WeModuleSystemWelcome extends WeBase {
}


abstract class WeModuleMobile extends WeBase {
	public function __call($name, $arguments) {
		$dir = IA_ROOT . '/addons/' . $this->modulename . '/inc/systemWelcome';
		$function_name = strtolower(substr($name, 5));
		$file = "$dir/{$function_name}.inc.php";
		if (file_exists($file)) {
			require $file;
			exit;
		}

		return null;
	}
}