# coding: utf-8
from __future__ import unicode_literals
from django.core.management.base import BaseCommand

from core.models import Feedback
from core.wechat import send_template_message


class Command(BaseCommand):
    def handle(self, *args, **options):
        feedbacks = Feedback.objects.filter(handle=True, send=False)
        for itm in feedbacks:
            if send_template_message(itm):
                print 'success: {0}'.format(itm.id)
                itm.send = True
                itm.save()
            else:
                print 'faild: {0}'.format(itm.id)





