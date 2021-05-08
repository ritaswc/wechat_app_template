from django.shortcuts import render
from django.core import serializers
from django.http import JsonResponse
import simplejson
from random import randint

from .models import Spot, Comment
from user.models import User

from rest_framework.decorators import api_view
from rest_framework.response import Response
from coffee.serializers import CitySpotListSerializer, SpotsSerializer, CommentSerializer

# Create your views here.

def index(request):
    return render(request, 'coffee/index.html', {'title': 'index'})


def spots_view(request):
    return render(request, 'coffee/spots.html', {'title': 'spots', 'spots': Spot.objects.all().order_by('-id')})


@api_view(['GET'])
def city_spot_list(request, city):
    if request.method == 'GET':
        city_spot_list = Spot.objects.filter(city=city).order_by('-id')
        serializer = CitySpotListSerializer(city_spot_list, many=True)
        return Response(serializer.data)


@api_view(['GET', 'POST'])
def spots(request):
    if request.method == 'GET':
        spots = Spot.objects.all().order_by('-id')
        serializer = SpotsSerializer(spots, many=True)
        return Response(serializer.data)
    elif request.method == 'POST':
        if request.POST.get('bathroom') == "1":
            bathroom = True
        else:
            bathroom = False

        spot = Spot(city = request.POST.get('city'),
                    name = request.POST.get('name'),
                    longitude = request.POST.get('longitude'),
                    latitude = request.POST.get('latitude'),
                    download_speed = request.POST.get('download_speed'),
                    upload_speed = request.POST.get('upload_speed'),
                    speed_test_link = request.POST.get('speed_test_link'),
                    price_indication = request.POST.get('price_indication'),
                    bathroom = bathroom,
                    commit_user_name = request.POST.get('commit_user_name'),
                    commit_message = request.POST.get('commit_message'),
                    commit_user_id = request.POST.get('commit_user_id'))
        spot.save()
        serializer = SpotsSerializer(spot)
        return Response(serializer.data)


@api_view(['GET'])
def spot_detail(request, pk):
    if request.method == 'GET':
        spot_detail = Spot.objects.get(pk=pk)
        serializer = SpotsSerializer(spot_detail)
        return Response(serializer.data)


@api_view(['GET'])
def spot_comment_list(request, pk):
    if request.method == 'GET':
        spot = Spot.objects.get(pk=pk)
        spot_comment_list = spot.comment_set.all().order_by('-comment_date')[:5]
        serializer = CommentSerializer(spot_comment_list, many=True)
        return Response(serializer.data)


@api_view(['GET'])
def user_comment_list(request, pk):
    if request.method == 'GET':
        user = User.objects.get(pk=pk)
        user_comment_list = user.comment_set.all().order_by('-comment_date')[:5]
        serializer = CommentSerializer(user_comment_list, many=True)
        return Response(serializer.data)


@api_view(['GET'])
def all_spot_comment_list(request, pk):
    if request.method == 'GET':
        spot = Spot.objects.get(pk=pk)
        spot_comment_list = spot.comment_set.all().order_by('-comment_date')
        serializer = CommentSerializer(spot_comment_list, many=True)
        return Response(serializer.data)


@api_view(['GET'])
def all_user_comment_list(request, pk):
    if request.method == 'GET':
        user = User.objects.get(pk=pk)
        user_comment_list = user.comment_set.all().order_by('-comment_date')
        serializer = CommentSerializer(user_comment_list, many=True)
        return Response(serializer.data)


@api_view(['GET', 'POST'])
def comments(request):
    if request.method == 'GET':
        comments = Comments.objects.all().order_by('-id')
        serializer = CommentSerializer(comments, many=True)
        return Response(serializer.data)
    elif request.method == 'POST':
        comment = Comment(spot_id = request.POST.get('spot_id'),
                    comment_message = request.POST.get('comment_message'),
                    comment_user_id = request.POST.get('comment_user_id'),
                    comment_user_name = request.POST.get('comment_user_name'),
                    comment_user_avatarurl = request.POST.get('comment_user_avatarurl'),
                    comment_mark = request.POST.get('comment_mark'))
        comment.save()
        serializer = CommentSerializer(comment)
        return Response(serializer.data)


@api_view(['GET'])
def comment_detail(request, pk):
    if request.method == 'GET':
        user = User.objects.get(pk=pk)
        user_comment_list = user.comment_set.all().order_by('-comment_date')
        serializer = CommentSerializer(user_comment_list, many=True)
        return Response(serializer.data)


@api_view(['GET'])
def user_spot_list(request, pk):
    if request.method == 'GET':
        user = User.objects.get(pk=pk)
        user_spot_list = user.spot_set.all().order_by('-id')[:5]
        serializer = CommentSerializer(user_spot_list, many=True)
        return Response(serializer.data)


@api_view(['GET'])
def all_user_spot_list(request, pk):
    if request.method == 'GET':
        user = User.objects.get(pk=pk)
        all_user_spot_list = user.spot_set.all().order_by('-id')
        serializer = CommentSerializer(all_user_spot_list, many=True)
        return Response(serializer.data)


@api_view(['GET'])
def random_spots(request):
    if request.method == 'GET':
        last = Spot.objects.count() - 1
        index1 = randint(0, last)
        index2 = randint(0, last - 1)
        if index2 == index1:
            index2 = last
        spots1 = Spot.objects.all()[index1]
        spots2 = Spot.objects.all()[index2]

        last2 = Spot.objects.count() - 4
        index3 = randint(0, last)
        index4 = randint(0, last - 1)
        if index4 == index3:
            index4 = last2
        spots3 = Spot.objects.all()[index3]
        spots4 = Spot.objects.all()[index4]

        spots = [spots1, spots2, spots3, spots4]
        serializer = SpotsSerializer(spots, many=True)
        return Response(serializer.data)



