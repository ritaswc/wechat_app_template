# coding: utf-8
from __future__ import unicode_literals

import json


class JsonRequestMixin(object):
    def dispatch(self, request, *args, **kwargs):
        try:
            json_data = json.loads(request.body)
            setattr(request, 'POST', json_data)
        except Exception, e:
            pass
        return super(JsonRequestMixin, self).dispatch(request, *args, **kwargs)