from django.db import models

# Create your models here.


class Person(models.Model):
    nick_name = models.CharField(max_length=256)
    gender = models.IntegerField(default=0)
    language = models.CharField(max_length=256)
    country = models.CharField(max_length=256)
    province = models.CharField(max_length=256)
    city = models.CharField(max_length=256)
    open_id = models.CharField(max_length=256)
    avatar_url = models.URLField(max_length=256)
    register_date = models.DateTimeField(auto_now_add=True, editable=True)

    def __str__(self):
        return self.nick_name
