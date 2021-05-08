# coding: utf-8
from __future__ import unicode_literals

from django.db import models


# Create your models here.
class BaseModel(models.Model):
    create_time = models.DateTimeField(auto_now_add=True)
    modify_time = models.DateTimeField(auto_now=True)

    class Meta:
        abstract = True


class Hellspawn(BaseModel):
    rarity_choice = [(1, 'SSR'),
                     (2, 'SR'),
                     (3, 'R'),
                     (4, 'N')]

    name = models.CharField(max_length=20)
    name_pinyin = models.CharField(max_length=128, default='')
    name_abbr = models.CharField(max_length=10, default='')
    rarity = models.IntegerField(choices=rarity_choice, default=4)
    picture = models.CharField(max_length=128, null=True, blank=True)
    icon = models.CharField(max_length=128, null=True, blank=True)
    clue1 = models.CharField(max_length=30, null=True, blank=True)
    clue2 = models.CharField(max_length=30, null=True, blank=True)

    def __unicode__(self):
        return '{0}-{1}'.format(self.name, self.rarity_choice[self.rarity - 1][1])


class Scene(BaseModel):
    name = models.CharField(max_length=20)
    icon = models.CharField(max_length=128, null=True, blank=True)

    def __unicode__(self):
        return self.name


class Team(BaseModel):
    name = models.CharField(max_length=20)
    monsters = models.ManyToManyField(Hellspawn, related_name='hellspawn_teams', through='Membership',
                                      through_fields=('team', 'hellspawn'))
    belong = models.ForeignKey(Scene, related_name='scene_teams')
    index = models.IntegerField(default=0)

    def __unicode__(self):
        return '{0}: {1}'.format(self.belong.name, self.name)


class Secret(BaseModel):
    secret = models.CharField(max_length=256, unique=True)
    remark = models.CharField(default='web', max_length=10)

    def __unicode__(self):
        return self.remark


class Membership(BaseModel):
    team = models.ForeignKey(Team, on_delete=models.CASCADE, null=True, blank=True)
    hellspawn = models.ForeignKey(Hellspawn, on_delete=models.CASCADE)
    count = models.IntegerField(default=1)

    def __unicode__(self):
        return '{0}X{1} - {2}{3}'.format(self.hellspawn.name, self.count, self.team.belong.name, self.team.name)


class WeUser(BaseModel):
    openid = models.CharField(max_length=128, unique=True)
    session = models.CharField(max_length=256, unique=True)
    weapp_session = models.CharField(max_length=256, unique=True)
    nick = models.CharField(max_length=50, default='')
    avatar = models.CharField(max_length=128, default='')

    def __unicode__(self):
        return self.openid


class Feedback(BaseModel):
    feed_choice = [(1, '数据报错'),
                   (2, '吐槽建议')]

    content = models.TextField()
    feed_type = models.IntegerField(default=1, choices=feed_choice)
    scene = models.ForeignKey(Scene, null=True, blank=True)
    form_id = models.CharField(max_length=128, default='')
    handle = models.BooleanField(default=False)
    send = models.BooleanField(default=False)
    reply = models.CharField(max_length=128, default='感谢您的支持, 您提交错误已被修复!')
    author = models.ForeignKey(WeUser, related_name='my_feedbacks', null=True, blank=True)

    def __unicode__(self):
        if self.scene:
            return '{0}-{1}-{2}'.format(self.feed_choice[self.feed_type - 1][1], self.scene.name, self.handle)
        return '{0}-{1}'.format(self.feed_choice[self.feed_type - 1][1], self.handle)
