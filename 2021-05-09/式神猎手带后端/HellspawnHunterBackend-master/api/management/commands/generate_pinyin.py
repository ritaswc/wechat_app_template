# coding: utf-8
from __future__ import unicode_literals
from django.core.management.base import BaseCommand

from core.models import Hellspawn
from xpinyin import Pinyin

class Command(BaseCommand):
    def handle(self, *args, **options):
        hellspawns = Hellspawn.objects.all()
        p = Pinyin()
        for hell in hellspawns:
            hell.name_pinyin = p.get_pinyin(hell.name, '')
            hell.name_abbr = p.get_initials(hell.name, '')
            hell.save()