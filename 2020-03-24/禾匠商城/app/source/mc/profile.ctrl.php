<?php
/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */
defined('IN_IA') or exit('Access Denied');
load()->model('site');
load()->func('tpl');

$title = $_W['account']['name'] . '微站';
$dos = array('index', 'editprofile', 'personal_info', 'contact_method', 'education_info', 'jobedit', 'avatar', 'address', 'addressadd');
$do = in_array($do, $dos) ? $do : 'index';
$navs = site_app_navs('profile');
$operate = safe_gpc_string($_GPC['operate']);
$operates = array('index', 'personal_info', 'contact_method', 'education_info', 'jobedit');
$operate = in_array($operate, $operates) ? $operate : 'index';

if (empty($_W['member']['uid'])) {
	message('请先登录!', url('auth/login', array('i' => $_W['uniacid'])), 'error');
}

$profile = mc_fetch($_W['member']['uid']);
if(!empty($profile)) {
	if(empty($profile['email']) || (!empty($profile['email']) && substr($profile['email'], -6) == 'we7.cc' && strlen($profile['email']) == 39)) {
		$profile['email'] = '';
		$profile['email_effective'] = 1;
	}
}
if(!empty($_W['openid'])) {
	$map_fans = table('mc_mapping_fans')
		->where(array(
			'uniacid' => $_W['uniacid'],
			'openid' => $_W['openid']
		))
		->getcolumn('tag');
	if(!empty($map_fans)) {
		if (is_base64($map_fans)){
			$map_fans = base64_decode($map_fans);
		}
		if (is_serialized($map_fans)) {
			$map_fans = iunserializer($map_fans);
		}
		if(!empty($map_fans) && is_array($map_fans)) {
						empty($profile['nickname']) ? ($data['nickname'] = strip_emoji($map_fans['nickname'])) : '';
			empty($profile['gender']) ? ($data['gender'] = $map_fans['sex']) : '';
			empty($profile['residecity']) ? ($data['residecity'] = ($map_fans['city']) ? $map_fans['city'] . '市' : '') : '';
			empty($profile['resideprovince']) ? ($data['resideprovince'] = ($map_fans['province']) ? $map_fans['province'] . '省' : '') : '';
			empty($profile['nationality']) ? ($data['nationality'] = $map_fans['country']) : '';
			empty($profile['avatar']) ? ($data['avatar'] = $map_fans['headimgurl']) : '';
			if(!empty($data)) {
				mc_update($_W['member']['uid'], $data);
			}
		}
	}
}


$mcFields = table('mc_member_fields')
	->searchWithProfileFields()
	->select(array('mf.*', 'pf.field'))
	->where(array(
		'mf.uniacid' => $_W['uniacid'],
		'mf.available' => 1
	))
	->getall('field');
$personal_info_hide = mc_card_settings_hide('personal_info');
$contact_method_hide = mc_card_settings_hide('contact_method');
$education_info_hide = mc_card_settings_hide('education_info');
$jobedit_hide = mc_card_settings_hide('jobedit');

if ($do == 'editprofile'){
	if ($_W['isajax'] && $_W['ispost']) {
		if ($operate == 'index') {
			$data = array(
				'nickname' => safe_gpc_string($_GPC['nickname']),
				'realname' => safe_gpc_string($_GPC['realname']),
				'birth' => array(
					'year' => safe_gpc_int($_GPC['birth']['year']),
					'month' => safe_gpc_int($_GPC['birth']['month']),
					'day' => safe_gpc_int($_GPC['birth']['day'])
				),
				'gender' => safe_gpc_int($_GPC['gender']),
			);
		}
		if ($operate == 'personal_info') {
			$data = array(
				'idcard' => safe_gpc_string($_GPC['idcard']),
				'height' => safe_gpc_int($_GPC['height']),
				'weight' => safe_gpc_int($_GPC['weight']),
				'bloodtype' => safe_gpc_string($_GPC['bloodtype']),
				'zodiac' => safe_gpc_string($_GPC['zodiac']),
				'constellation' => safe_gpc_string($_GPC['constellation']),
				'site' => safe_gpc_string($_GPC['site']),
				'bio' => safe_gpc_string($_GPC['bio']),
				'affectivestatus' => safe_gpc_string($_GPC['affectivestatus']),
				'lookingfor' => safe_gpc_string($_GPC['lookingfor']),
				'interest' => safe_gpc_string($_GPC['interest']),
			);
		}
		if ($operate == 'contact_method') {
			$data = array(
				'telephone' => safe_gpc_string($_GPC['telephone']),
				'qq' => safe_gpc_string($_GPC['qq']),
				'msn' => safe_gpc_string($_GPC['msn']),
				'taobao' => safe_gpc_string($_GPC['taobao']),
				'alipay' => safe_gpc_string($_GPC['alipay']),
			);
		}
		if ($operate == 'education_info') {
			$data = array(
				'education' => safe_gpc_string($_GPC['education']),
				'graduateschool' => safe_gpc_string($_GPC['graduateschool']),
				'studentid' => safe_gpc_string($_GPC['studentid']),
			);
		}
		if ($operate == 'jobedit') {
			$data = array(
				'company' => safe_gpc_string($_GPC['company']),
				'occupation' => safe_gpc_string($_GPC['occupation']),
				'position' => safe_gpc_string($_GPC['position']),
				'revenue' => safe_gpc_string($_GPC['revenue']),
			);
		}
		$result = mc_update($_W['member']['uid'], $data);
		if ($result) {
			message('更新资料成功！', referer(), 'success');
		} else {
			message('更新资料失败！', referer(), 'error');
		}
	}
}
if ($do == 'avatar') {
	$avatar = array('avatar' => safe_gpc_string($_GPC['avatar']));
	if (mc_update($_W['member']['uid'], $avatar)) {
		message('头像设置成功！', referer(), 'success');
	}
}

if ($do == 'address') {
	$address_id = intval($_GPC['id']);
	if ($_GPC['op'] == 'default') {
		table('mc_member_address')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'uid' => $_W['member']['uid']
			))
			->fill(array('isdefault' => 0))
			->save();
		table('mc_member_address')
			->where(array(
				'id' => $address_id,
				'uniacid' => $_W['uniacid']
			))
			->fill(array('isdefault' => 1))
			->save();
		mc_update($_W['member']['uid'], array('address' => safe_gpc_string($_GPC['address'])));
	}
	if ($_GPC['op'] == 'delete') {
		if (!empty($profile) && !empty($_W['openid'])) {
			table('mc_member_address')
				->where(array(
					'id' => $address_id,
					'uid' => $_W['member']['uid'],
					'uniacid' => $_W['uniacid']
				))
				->delete();
		}
	}
	$where = array(
		'uniacid' => $_W['uniacid'],
		'uid' => $_W['member']['uid']
	);
	if (!empty($_GPC['addid'])) {
		$where['id'] = ntval($_GPC['addid']);
	}
	if (empty($params[':id'])) {
		$psize = 10;
		$pindex = max(1, intval($_GPC['page']));
		$address = table('mc_member_address')
			->where($where)
			->limit(($pindex - 1) * $psize, $psize)
			->getall();
		$total = table('mc_member_address')
			->where($where)
			->getcolumn('COUNT(*)');
		$pager = pagination($total, $pindex, $psize);
	} else {
		$address = table('mc_member_address')->where($where)->get();
	}
}

if ($do == 'addressadd') {
	$addid = intval($_GPC['addid']);
	if ($_W['isajax'] && $_W['ispost']) {
		$post = safe_gpc_array($_GPC['address']);
		if (empty($post['username'])) {
			message('请输入您的姓名', referer(), 'error');
		}
		if (empty($post['mobile'])) {
			message('请输入您的手机号', referer(), 'error');
		}
		if (empty($post['zipcode'])) {
			message('请输入您的邮政编码', referer(), 'error');
		}
		if (empty($post['province'])) {
			message('请输入您的所在省', referer(), 'error');
		}
		if (empty($post['city'])) {
			message('请输入您的所在市', referer(), 'error');
		}
		if (empty($post['address'])) {
			message('请输入您的详细地址', referer(), 'error');
		}
		$address = array(
			'username' => $post['username'],
			'mobile' => $post['mobile'],
			'zipcode' => $post['zipcode'],
			'province' => $post['province'],
			'city' => $post['city'],
			'district' => empty($post['district']) ? '' : $post['district'],
			'address' => $post['address'],
		);
		$address_data = table('mc_member_address')
			->where(array(
				'uniacid' => $_W['uniacid'],
				'uid' => $_W['member']['uid']
			))
			->get();
		if (empty($address_data)) {
			$address['isdefault'] = 1;
		}
		if (!empty($addid)) {
			if (table('mc_member_address')
				->where(array(
					'id' => $addid,
					'uniacid' => $_W['uniacid'],
					'uid' => $_W['member']['uid']
				))
				->fill($address)
				->save()) {
				message('修改收货地址成功', url('mc/profile/address'), 'success');
			} else {
				message('修改收货地址失败，请稍后重试', url('mc/profile/address'), 'error');
			}
		} else {
			$address['uniacid'] = $_W['uniacid'];
			$address['uid'] = $_W['member']['uid'];
			if (table('mc_member_address')->fill($address)->save()) {
				$adres = table('mc_member_address')
					->where(array(
						'uniacid' => $_W['uniacid'],
						'uid' => $_W['member']['uid'],
						'isdefault'=> 1
					))
					->get();
				if (!empty($adres)) {
					$adres['address'] = $adres['province'].$adres['city'].$adres['district'].$adres['address'];
					mc_update($_W['member']['uid'], array('address' => $adres['address']));
				}
				message('地址添加成功', url('mc/profile/address'), 'success');
			}
		}
	}
	if (!empty($addid)) {
		$address = table('mc_member_address')->getById($addid, $_W['uniacid']);
	}
}
template('mc/profile');