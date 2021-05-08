# coding: utf-8
from __future__ import unicode_literals
from django.http import HttpResponse

# Info Code
ERROR_UNKNOWN = 0
INFO_SUCCESS = 1
ERROR_PERMISSION_DENIED = 2
ERROR_ACCOUNT_NO_EXIST = 3
ERROR_TOKEN = 3
ERROR_DATA = 4
ERROR_PASSWORD = 5
INFO_EXISTED = 6
INFO_NO_EXIST = 7
INFO_EXPIRE = 8
INFO_NO_VERIFY = 10
ERROR_VERIFY = 11
INFO_NO_MATCH = 20
INFO_ROOM_DESTROY = 21


class StatusWrapMixin(object):
    status_code = INFO_SUCCESS
    message = 'success'

    def render_to_response(self, context, **response_kwargs):
        context_dict = self.context_serialize(context)
        json_context = self.json_serializer(self.wrapper(context_dict))
        return HttpResponse(json_context, content_type='application/json', **response_kwargs)

    def wrapper(self, context):
        return_data = dict()
        return_data['body'] = context
        return_data['status'] = self.status_code
        return_data['msg'] = self.message
        if self.status_code != INFO_SUCCESS and self.status_code != INFO_NO_MATCH:
            return_data['body'] = {}
        return return_data


class AdminStatusWrapMixin(StatusWrapMixin):
    def wrapper(self, context):
        data = super(AdminStatusWrapMixin, self).wrapper(context)
        if isinstance(self.message, unicode):
            data['msg'] = {'message': self.message}
            return data
        error_data = {}
        if isinstance(self.message, list):
            for itm in self.message:
                error_data[itm[0]] = itm[1]
        if isinstance(self.message, dict):
            for k, v in self.message.iteritems():
                error_data[k] = v[0].get('message', '')
        data['msg'] = error_data
        return data
