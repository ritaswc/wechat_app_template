import os

from django.db import models
from django.core.files.base import ContentFile
from django.contrib.auth.models import AbstractUser
from django.core import urlresolvers

from imagekit.models import ImageSpecField
from imagekit.processors import ResizeToFill

from avatar_generator import Avatar

def user_avatar_path(instance, filename):
    return os.path.join('avatars', instance.email, filename)


class User(AbstractUser):
    nickname = models.CharField(max_length=50, unique=True)
    bio = models.CharField(max_length=120, blank=True)
    url = models.URLField(max_length=100, blank=True)
    location = models.CharField(max_length=100, blank=True)
    avatar = models.ImageField(upload_to=user_avatar_path)
    avatar_thumbnail = ImageSpecField(source='avatar',
                                       processors=[ResizeToFill(96, 96)],
                                       format='JPEG',
                                       options={'quality': 100})
    last_login_ip = models.GenericIPAddressField(unpack_ipv4=True, null=True, blank=True)
    ip_joined = models.GenericIPAddressField(unpack_ipv4=True, null=True, blank=True)

    client_mark = models.CharField(max_length=10, default='weixin', null=True, blank=True)

    weixin_nickName = models.CharField(max_length=50, null=True, blank=True)
    weixin_avatarUrl = models.URLField(max_length=200, null=True, blank=True)

    def __str__(self):
        return self.email

    def save(self, *args, **kwargs):
        if not self.nickname:
            self.nickname = self.email.split('@')[0]
            self.username = self.email.split('@')[0]

        if not self.avatar:
            email = self.email
            image_byte_array =  Avatar.generate(128, email, "PNG")

            self.avatar.save('default_avatar.png', ContentFile(image_byte_array), save=False)
        super().save(*args, **kwargs)

    def get_absolute_url(self):
        return urlresolvers.reverse('user:profile', args=(self.email,))
