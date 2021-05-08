from .models import Spot, Comment

from rest_framework import serializers


class CitySpotListSerializer(serializers.ModelSerializer):
    class Meta:
        model = Spot
        fields = ('id', 'city', 'name', 'commit_user_name', 'commit_message')


class SpotsSerializer(serializers.ModelSerializer):
    commit_date = serializers.DateTimeField(format="%Y-%m-%d")
    class Meta:
        model = Spot
        fields = ('id', 'city', 'name', 'longitude', 'latitude', 'download_speed', 'upload_speed',
          'price_indication', 'bathroom', 'commit_user', 'commit_user_name', 'commit_message', 'commit_date')


class CommentSerializer(serializers.ModelSerializer):
    comment_date = serializers.DateTimeField(format="%Y-%m-%d")
    class Meta:
        model = Comment
        fields = ('id', 'comment_message', 'comment_user_name', 'comment_user_avatarurl', 'comment_date', 'spot_id', 'comment_user_id', 'comment_mark')
