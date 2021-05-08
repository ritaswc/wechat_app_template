# coding: utf-8

import time
import datetime
from functools import partial

try:
    from django.utils import timezone
except ImportError:
    raise RuntimeError('Django is required for django simple serializer.')


class TimeFormatFactory(object):
    def __init__(self):
        super(TimeFormatFactory, self).__init__()

    @staticmethod
    def datetime_to_string(datetime_time, time_format='%Y-%m-%d %H:%M:%S'):
        if isinstance(datetime_time, datetime.datetime):
            if datetime_time.tzinfo:
                datetime_time = datetime_time.astimezone(timezone.get_current_timezone())
            return datetime_time.strftime(time_format)
        elif isinstance(datetime_time, datetime.time):
            time_format = '%H:%M:%S'
        elif isinstance(datetime_time, datetime.date):
            time_format = '%Y-%m-%d'
        return datetime_time.strftime(time_format)

    @staticmethod
    def datetime_to_timestamp(datetime_time, time_format=None):
        if isinstance(datetime_time, datetime.datetime):
            if datetime_time.tzinfo:
                datetime_time = datetime_time.astimezone(timezone.get_current_timezone())
            return time.mktime(datetime_time.timetuple())
        return time.mktime(datetime_time.timetuple())

    @staticmethod
    def get_time_func(func_type='string'):
        if func_type == 'string':
            return TimeFormatFactory.datetime_to_string
        elif func_type == 'timestamp':
            return TimeFormatFactory.datetime_to_timestamp
        else:
            return TimeFormatFactory.datetime_to_string
