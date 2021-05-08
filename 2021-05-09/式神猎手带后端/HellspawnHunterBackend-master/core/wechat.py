# coding: utf-8
from __future__ import unicode_literals

import json
import requests

from django.core.cache import cache
from django.utils import timezone

APP_KEY = 'wxf8a77094412c62d8'
APP_SECRET = '0bd1ca668a42fa8d65753c8a77f68595'


def get_access_token():
    access_token = cache.get('access_token')
    if access_token:
        return access_token
    res = requests.get(
        'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={0}&secret={1}'.format(APP_KEY,
                                                                                                           APP_SECRET))
    json_data = json.loads(res.content)
    access_token = json_data.get('access_token', None)
    if access_token:
        cache.set('access_token', access_token, 60 * 60 * 2)
        return access_token
    return None


def send_template_message(feedback):
    access_token = get_access_token()
    url = 'https://api.weixin.qq.com/cgi-bin/message/wxopen/template/send?access_token={0}'.format(access_token)
    time = feedback.create_time
    time = time.astimezone(timezone.get_current_timezone())
    data = {'touser': feedback.author.openid,
            'template_id': 'WcNGPs2DNoa6-hi3WrpxGT4x8CSFx2UFT8pnXlah15c',
            'form_id': feedback.form_id,
            'data': {
                "keyword1": {"value": time.strftime("%Y-%m-%d %H:%M:%S")},
                "keyword2": {"value": feedback.content},
                "keyword3": {"value": feedback.reply}
            }}
    res = requests.post(url, data=json.dumps(data)).content
    json_data = json.loads(res)
    status = json_data.get('errcode')
    if status == 0:
        return True
    return False


def get_session_key(code):
    url = 'https://api.weixin.qq.com/sns/jscode2session?appid={0}&secret={1}&js_code={2}&grant_type=authorization_code'.format(
        APP_KEY, APP_SECRET, code)
    res = requests.get(url).content
    json_data = json.loads(res)
    openid = json_data.get('openid', None)
    session = json_data.get('session_key', None)
    if openid and session:
        return True, openid, session
    return False, None, None
