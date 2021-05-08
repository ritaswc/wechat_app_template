# coding: utf-8

from __future__ import unicode_literals

import datetime
import json

from decimal import Decimal

from .TimeFormatFactory import TimeFormatFactory
from .Warning import remove_check

try:
    from django.db import models
    from django.db.models import manager
    from django.core.paginator import Page
    from django.db.models.query import QuerySet
    from django.db.models.fields.files import ImageFieldFile, FileField
    import django
except ImportError:
    raise RuntimeError('django is required in django simple serializer')


class Serializer(object):
    include_attr = []
    exclude_attr = []
    objects = []
    origin_data = None
    output_type = 'raw'
    datetime_format = 'timestamp'
    foreign = False
    many = False
    through = True

    def __init__(self, data, datetime_format='timestamp', output_type='raw', include_attr=None, exclude_attr=None,
                 foreign=False, many=False, through=True, *args, **kwargs):
        if include_attr:
            self.include_attr = include_attr
        if exclude_attr:
            self.exclude_attr = exclude_attr
        self.origin_data = data
        self.output_type = output_type
        self.foreign = foreign
        self.many = many
        self.through = through
        self.through_fields = []
        self.source_field = None
        self.datetime_format = datetime_format
        self.time_func = TimeFormatFactory.get_time_func(datetime_format)
        self._dict_check = kwargs.get('dict_check', False)

    def check_attr(self, attr):
        if self.exclude_attr and attr in self.exclude_attr:
            return False
        if self.include_attr and attr not in self.include_attr:
            return False
        return True

    def data_inspect(self, data, extra=None):
        if isinstance(data, (QuerySet, Page, list)):
            convert_data = []
            if extra:
                for i, obj in enumerate(data):
                    convert_data.append(self.data_inspect(obj, extra.get(**{self.through_fields[0]: obj, self.through_fields[1]: self.source_field})))
            else:
                for obj in data:
                    convert_data.append(self.data_inspect(obj))
            return convert_data
        elif isinstance(data, models.Model):
            obj_dict = {}
            concrete_model = data._meta.concrete_model
            for field in concrete_model._meta.local_fields:
                if field.rel is None:
                    if self.check_attr(field.name) and hasattr(data, field.name):
                        obj_dict[field.name] = self.data_inspect(getattr(data, field.name))
                else:
                    if self.check_attr(field.name) and self.foreign:
                        obj_dict[field.name] = self.data_inspect(getattr(data, field.name))
            for field in concrete_model._meta.many_to_many:
                if self.check_attr(field.name) and self.many:
                    obj_dict[field.name] = self.data_inspect(getattr(data, field.name))
            for k, v in data.__dict__.iteritems():
                if not unicode(k).startswith('_') and k not in obj_dict.keys() and self.check_attr(k):
                    obj_dict[k] = self.data_inspect(v)
            if extra:
                for field in extra._meta.concrete_model._meta.local_fields:
                    if field.name not in obj_dict.keys() and field.name not in self.through_fields:
                        if field.rel is None:
                            if self.check_attr(field.name) and hasattr(extra, field.name):
                                obj_dict[field.name] = self.data_inspect(getattr(extra, field.name))
                        else:
                            if self.check_attr(field.name) and self.foreign:
                                obj_dict[field.name] = self.data_inspect(getattr(extra, field.name))
            return obj_dict
        elif isinstance(data, manager.Manager):
            through_list = data.through._meta.concrete_model._meta.local_fields
            through_data = data.through._default_manager
            self.through_fields = [data.target_field.name, data.source_field.name]
            self.source_field = data.instance
            if len(through_list) > 3 and self.through:
                return self.data_inspect(data.all(), through_data)
            else:
                return self.data_inspect(data.all())
        elif isinstance(data, (datetime.datetime, datetime.date, datetime.time)):
            return self.time_func(data)
        elif isinstance(data, (ImageFieldFile, FileField)):
            return data.name
        elif isinstance(data, Decimal):
            return float(data)
        elif isinstance(data, dict):
            obj_dict = {}
            if self._dict_check:
                for k, v in data.iteritems():
                    obj_dict[k] = self.data_inspect(v)
            else:
                for k, v in data.iteritems():
                    if self.check_attr(k):
                        obj_dict[k] = self.data_inspect(v)
            return obj_dict
        elif isinstance(data, (unicode, str, bool, float, int, long)):
            return data
        else:
            return None

    def data_format(self):
        self.objects = self.data_inspect(self.origin_data)

    def get_values(self):
        output_switch = {'dict': self.objects,
                         'raw': self.objects,
                         'json': json.dumps(self.objects, indent=4)}
        return output_switch.get(self.output_type, self.objects)

    def __call__(self):
        self.data_format()
        return self.get_values()


def serializer(data, datetime_format='timestamp', output_type='raw', include_attr=None, exclude_attr=None,
               foreign=False, many=False, through=True, *args, **kwargs):
    s = Serializer(data, datetime_format, output_type, include_attr, exclude_attr,
                   foreign, many, through, *args, **kwargs)
    return s()
