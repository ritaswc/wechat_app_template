# coding: utf-8

from __future__ import unicode_literals

import hashlib

import datetime

from django.utils.timezone import get_current_timezone
from django.http import HttpResponseRedirect
from core.Mixin.StatusWrapMixin import ERROR_PERMISSION_DENIED, ERROR_TOKEN, INFO_EXPIRE
from core.models import Secret


class CheckSecurityMixin(object):
    secret = None
    expire = datetime.timedelta(seconds=30)

    def get_current_secret(self):
        self.secret = Secret.objects.all()[0].secret
        return self.secret

    def check_expire(self):
        timestamp = int(self.request.GET.get('timestamp', 0))
        request_time = datetime.datetime.fromtimestamp(timestamp, tz=get_current_timezone())
        now_time = datetime.datetime.now(tz=get_current_timezone())
        if now_time - request_time > self.expire:
            self.message = '请求超时,请重新验证'
            self.status_code = INFO_EXPIRE
            return False
        else:
            return True

    def check_sign(self):
        timestamp = self.request.GET.get('timestamp', '')
        sign = unicode(self.request.GET.get('sign', '')).upper()
        check = unicode(hashlib.md5('{0}{1}'.format(timestamp, self.secret)).hexdigest()).upper()
        # print check
        if check == sign:
            return True
        return False

    def wrap_check_sign_result(self):
        if not self.check_expire():
            self.message = 'sign 已过期'
            self.status_code = ERROR_PERMISSION_DENIED
            return False
        self.get_current_secret()
        result = self.check_sign()
        if not result:
            self.message = 'sign 验证失败'
            self.status_code = ERROR_PERMISSION_DENIED
            return False
        return True

    def get(self, request, *args, **kwargs):
        if not self.wrap_check_sign_result():
            return self.render_to_response(dict())
        return super(CheckSecurityMixin, self).get(request, *args, **kwargs)

    def post(self, request, *args, **kwargs):
        if not self.wrap_check_sign_result():
            return self.render_to_response(dict())
        return super(CheckSecurityMixin, self).post(request, *args, **kwargs)

    def put(self, request, *args, **kwargs):
        if not self.wrap_check_sign_result():
            return self.render_to_response(dict())
        return super(CheckSecurityMixin, self).put(request, *args, **kwargs)

    def patch(self, request, *args, **kwargs):
        if not self.wrap_check_sign_result():
            return self.render_to_response(dict())
        return super(CheckSecurityMixin, self).patch(request, *args, **kwargs)


# class CheckTokenMixin(object):
#     token = None
#     user = None
#
#     def get_current_token(self):
#         self.token = self.request.GET.get('token') or self.request.session.get('token', '')
#         return self.token
#
#     def check_token(self):
#         self.get_current_token()
#         # user_list = PartyUser.objects.filter(token=self.token)
#         if user_list.exists():
#             self.user = user_list[0]
#             return True
#         return False
#
#     def wrap_check_token_result(self):
#         result = self.check_token()
#         if not result:
#             self.message = 'token 错误, 请重新登陆'
#             self.status_code = ERROR_TOKEN
#             return False
#         return True
#
#
# class CheckAdminPermissionMixin(object):
#     token = None
#     admin = None
#
#     def get_current_token(self):
#         self.token = self.request.GET.get('token') or self.request.session.get('token', '')
#         return self.token
#
#     def check_token(self):
#         self.get_current_token()
#         admin_list = HAdmin.objects.filter(token=self.token)
#         if admin_list.exists():
#             self.admin = admin_list[0]
#             return True
#         return False
#
#     def wrap_check_token_result(self):
#         result = self.check_token()
#         if not result:
#             self.message = 'token已过期, 请重新登陆'
#             self.status_code = ERROR_TOKEN
#             return False
#         return True
#
#
# class CheckAdminPagePermissionMixin(object):
#     def dispatch(self, request, *args, **kwargs):
#         token = request.session.get('token')
#         if token:
#             if HAdmin.objects.filter(token=token).exists():
#                 return super(CheckAdminPagePermissionMixin, self).dispatch(request, *args, **kwargs)
#         return HttpResponseRedirect('/admin/login')
