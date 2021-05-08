# coding: utf-8
from __future__ import unicode_literals

import json
import requests
import urllib
from xpinyin import Pinyin

from django.core.management.base import BaseCommand

from core.models import Scene, Team, Membership, Hellspawn


class Command(BaseCommand):
    def handle(self, *args, **options):
        data = requests.get('https://onmyoji.rapospectre.com/s/file/scene_hard.json').content
        json_data = json.loads(data)
        num = 0
        for item in json_data:
            scene = Scene(name=item.get('scene_name'))
            scene.save()
            for itm in item.get('team_list'):
                team = Team(name=itm.get('name'), index=itm.get('index'), belong=scene)
                team.save()
                for mitm in itm.get('monsters'):
                    hs = Hellspawn.objects.get(name=mitm.get('name'))
                    Membership(hellspawn=hs, count=mitm.get('count'), team=team).save()





