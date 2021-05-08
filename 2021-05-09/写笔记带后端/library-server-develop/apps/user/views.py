from django.shortcuts import render
from django.http import HttpResponse
from django.core.exceptions import ObjectDoesNotExist
from libs.WXBizDataCrypt import WXBizDataCrypt
from libs.django_jwt_session_auth import jwt_login
from .models import Person
import urllib.parse
import json
# Create your views here.

appid = 'wxd8f471f80a7122e4'
app_secret = 'ab66ea663c909a770b00d686ae0b51e8'


def login(request):
    if request.method == 'POST':
        code = request.POST.get('code', '')
        iv = request.POST.get('iv', '')
        encrypted_data = request.POST.get('encryptedData', '')

        session_info = get_session_info(appid, app_secret, code)
        session_key = session_info['session_key']

        crypt = WXBizDataCrypt(appid, session_key)
        user_info = crypt.decrypt(encrypted_data, iv)
        open_id = user_info['openId']
        try:
            user = Person.objects.get(open_id=open_id)
        except ObjectDoesNotExist:
            user = register(user_info)
        token = jwt_login(user, request)
        user_info['token'] = token.decode()

        return HttpResponse(json.dumps(user_info, ensure_ascii=False), content_type="application/json")


def register(user_info):
    user = Person(nick_name=user_info['nickName'], gender=user_info['gender'],
                  language=user_info['language'], country=user_info['country'],
                  province=user_info['province'], city=user_info['city'],
                  open_id=user_info['openId'], avatar_url=user_info['avatarUrl'])
    user.save()
    return user


def get_session_info(appid, secret, js_code):
    base_url = 'https://api.weixin.qq.com/sns/jscode2session?'

    query_obj = {}
    query_obj['appid'] = appid
    query_obj['secret'] = secret
    query_obj['js_code'] = js_code
    query_obj['grant_type'] = 'authorization_code'

    response = urllib.request.urlopen(
        base_url + urllib.parse.urlencode(query_obj))
    data = json.loads(response.read().decode())
    return data
