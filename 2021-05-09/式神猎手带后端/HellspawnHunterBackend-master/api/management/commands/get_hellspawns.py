# coding: utf-8
from __future__ import unicode_literals

import json
import requests
import urllib
from xpinyin import Pinyin

from django.core.management.base import BaseCommand

from core.models import Hellspawn


class Command(BaseCommand):
    def handle(self, *args, **options):
        h = Hellspawn.objects.all()
        h.delete()
        url = "https://g37simulator.webapp.163.com/get_heroid_list?callback=jQuery11130959811888616583_1487429691764&rarity=0&page=1&per_page=200&_=1487429691765"
        result = requests.get(url).content.replace('jQuery11130959811888616583_1487429691764(', '').replace(')', '')
        json_data = json.loads(result)
        hellspawn_list = json_data['data']
        p = Pinyin()
        for k, v in hellspawn_list.iteritems():
            file_name = p.get_pinyin(v.get('name'), '')
            print 'id: {0} name: {1}'.format(k, v.get('name'))
            big_url = 'http://static.rapospectre.com/{0}@big.png'.format(file_name)
            icon_url = 'http://static.rapospectre.com/{0}@icon.png'.format(file_name)
            rarity = {1: 4, 2: 3, 3: 2, 4: 1}
            Hellspawn(name=v.get('name'), picture=big_url, icon=icon_url, rarity=rarity[v.get('rarity')]).save()
