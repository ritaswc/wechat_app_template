# coding: utf-8
from __future__ import unicode_literals

from django.core.management.base import BaseCommand

from core.models import Scene, Team, Membership, Hellspawn


class Command(BaseCommand):
    def handle(self, *args, **options):
        scenes = Scene.objects.all()
        scenes.delete()
