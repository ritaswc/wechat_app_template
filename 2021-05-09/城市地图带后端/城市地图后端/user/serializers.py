from .models import User
from coffee.models import Spot

from rest_framework import serializers


class UserSerializer(serializers.ModelSerializer):
  # spot = serializers.PrimaryKeyRelatedField(many=True, queryset=Spot.objects.all())

  class Meta:
    model = User
    fields = ('id', 'username', 'email', 'nickname', 'bio', 'url',
      'location', 'avatar', 'client_mark', 'weixin_nickName', 'weixin_avatarUrl')
