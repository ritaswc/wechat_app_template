# coding: utf-8
from __future__ import unicode_literals

from bs4 import BeautifulSoup
import requests

from django.core.management.base import BaseCommand

from core.models import Hellspawn


class Command(BaseCommand):
    def handle(self, *args, **options):
        url = 'http://www.18183.com/yys/201609/706902.html'
        result = requests.get(url).content
        soup = BeautifulSoup(result)
        clues = soup.findAll('tr', {'class': 'li_on'})
        for itm in clues:
            clue = itm.find('td', {'class': "jiacu"}).text.strip()
            hellspawn = itm.findAll('td')[1].find('span').text.strip()
            print clue, hellspawn
            hs = Hellspawn.objects.get(name=hellspawn)
            hs.clue1 = clue
            hs.save()
        clues = soup.findAll('tr', {'class': 'li'})
        for itm in clues:
            clue = itm.find('td', {'class': "jiacu"}).text.strip()
            hellspawn = itm.findAll('td')[1].find('span').text.strip()
            print clue, hellspawn
            if clue == 'xxxx':
                break
            else:
                hs = Hellspawn.objects.filter(name=hellspawn)
                if hs.exists():
                    hs = hs[0]
                    hs.clue1 = clue
                    hs.save()
                else:
                    print 'no exist: {0}'.format(hellspawn)

