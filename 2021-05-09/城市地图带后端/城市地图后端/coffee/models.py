from django.conf import settings
from django.db import models
from django.contrib.auth.models import User
from django.utils import timezone

# Create your models here.

class Spot(models.Model):
  city = models.CharField(max_length=70)
  name = models.CharField(max_length=70)
  longitude = models.FloatField()
  latitude = models.FloatField()

  download_speed = models.CharField(max_length=70, null=True, blank=True)
  upload_speed = models.CharField(max_length=70, null=True, blank=True)
  speed_test_link = models.URLField(max_length=100, null=True, blank=True)
  price_indication = models.CharField(max_length=70, null=True, blank=True, default='')
  bathroom = models.BooleanField(default=False)

  commit_user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
  commit_user_name = models.CharField(max_length=70, default='', null=True, blank=True)
  commit_message = models.CharField(max_length=140, null=True, blank=True)
  commit_date = models.DateTimeField(default=timezone.now)

  def __str__(self):
      return self.name


class Comment(models.Model):
  spot = models.ForeignKey('coffee.spot', on_delete=models.CASCADE)
  comment_message = models.CharField(max_length=140, default='', null=True, blank=True)
  comment_user = models.ForeignKey(settings.AUTH_USER_MODEL, on_delete=models.CASCADE)
  comment_user_name = models.CharField(max_length=70, default='', null=True, blank=True)
  comment_date = models.DateTimeField(default=timezone.now)

  comment_mark = models.CharField(max_length=10, default='comment', null=True, blank=True)
  comment_user_avatarurl = models.URLField(max_length=200, null=True, blank=True)

