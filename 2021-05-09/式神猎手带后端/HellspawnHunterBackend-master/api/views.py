# coding: utf-8
from __future__ import unicode_literals

import random
import string

from django.db.models import Q
from django.shortcuts import render

# Create your views here.
from django.views.generic import ListView, DetailView

from core.Mixin.CheckMixin import CheckSecurityMixin
from core.Mixin.JsonRequestMixin import JsonRequestMixin
from core.Mixin.StatusWrapMixin import StatusWrapMixin
from core.dss.Mixin import MultipleJsonResponseMixin, JsonResponseMixin
from core.dss.Serializer import serializer
from core.models import Hellspawn, Scene, Team, Membership, Feedback, WeUser
from core.Mixin import StatusWrapMixin as SW
from django.core.cache import cache

from core.wechat import get_session_key


class HellspawnListView(CheckSecurityMixin, StatusWrapMixin, MultipleJsonResponseMixin, ListView):
    model = Hellspawn
    paginate_by = 20


class HellspawnDetailView(CheckSecurityMixin, StatusWrapMixin, JsonResponseMixin, DetailView):
    model = Hellspawn
    pk_url_kwarg = 'id'

    def get_object(self, queryset=None):
        obj = super(HellspawnDetailView, self).get_object(queryset)
        cache.set(obj.id, obj.name, 60 * 60 * 6)
        return obj


class SceneListView(CheckSecurityMixin, StatusWrapMixin, MultipleJsonResponseMixin, ListView):
    model = Scene
    many = True
    foreign = True
    paginate_by = 10
    exclude_attr = ['create_time', 'modify_time', 'belong']

    def get_queryset(self):
        queryset = super(SceneListView, self).get_queryset()
        map(lambda obj: setattr(obj, 'team_list', obj.scene_teams.all()), queryset)
        return queryset


class SceneDetailView(CheckSecurityMixin, StatusWrapMixin, JsonResponseMixin, DetailView):
    model = Scene
    pk_url_kwarg = 'id'


class SearchListView(CheckSecurityMixin, StatusWrapMixin, MultipleJsonResponseMixin, ListView):
    model = Hellspawn

    def get_queryset(self):
        value = self.kwargs.get('value', '')
        queryset = super(SearchListView, self).get_queryset()
        queryset = queryset.filter(Q(name__icontains=value) | Q(clue1__icontains=value) | Q(clue2__icontains=value) | Q(
            name_pinyin__icontains=value) | Q(name_abbr__icontains=value))
        return queryset


class HellspawnSceneListView(CheckSecurityMixin, StatusWrapMixin, JsonResponseMixin, ListView):
    model = Scene
    many = True

    exclude_attr = ['create_time', 'modify_time']

    def get(self, request, *args, **kwargs):
        hellspawns = Hellspawn.objects.filter(id=kwargs.get('id'))
        if not hellspawns.exists():
            self.message = 'id 不存在'
            self.status_code = SW.INFO_NO_EXIST
            return self.render_to_response({})
        hellspawn = hellspawns[0]
        # queryset = self.get_queryset()
        teams = Team.objects.filter(monsters=hellspawn)
        scenes = []
        for team in teams:
            scenes.append(team.belong)
        scenes = set(scenes)
        scene_list = []
        for scene in scenes:
            teams = Team.objects.filter(belong=scene)
            setattr(scene, 'team_list', teams)
            hellspawn_count = 0
            for team in teams:
                mems = Membership.objects.filter(team=team, hellspawn=hellspawn)
                if mems.exists():
                    mem = mems[0]
                    hellspawn_count += mem.count
            setattr(scene, 'hellspawn_info', {'name': hellspawn.name, 'count': hellspawn_count})
        return self.render_to_response(
            {'scene_list': sorted(scenes, key=lambda x: x.hellspawn_info['count'], reverse=True)})


class FeedbackView(CheckSecurityMixin, StatusWrapMixin, JsonResponseMixin, JsonRequestMixin, DetailView):
    model = Feedback
    http_method_names = ['post']

    def post(self, request, *args, **kwargs):
        if not self.wrap_check_sign_result():
            return self.render_to_response(dict())
        content = request.POST.get('content')
        session = request.POST.get('session', None)
        user = WeUser.objects.filter(session=session)
        # if not user.exists():
        #     self.message = 'session 不存在或已过期'
        #     self.status_code = SW.ERROR_PERMISSION_DENIED
        #     return self.render_to_response({})
        # user = user[0]
        if content:
            is_advice = True if request.POST.get('is_advice') == 'true' else False
            scene_id = request.POST.get("scene_id")
            form_id = request.POST.get('form_id', '')
            new_feedback = Feedback(content=content)
            new_feedback.form_id = form_id
            new_feedback.feed_type = 2 if is_advice else 1
            if user.exists():
                new_feedback.author = user[0]
            if scene_id:
                scenes = Scene.objects.filter(id=scene_id)
                if scenes.exists():
                    new_feedback.scene = scenes[0]
            new_feedback.save()
            return self.render_to_response({})
        self.message = '参数缺失'
        self.status_code = SW.ERROR_DATA
        return self.render_to_response({})


class PopularListView(CheckSecurityMixin, StatusWrapMixin, MultipleJsonResponseMixin, ListView):
    model = Hellspawn
    http_method_names = ['get']

    def get(self, request, *args, **kwargs):
        if not self.wrap_check_sign_result():
            return self.render_to_response(dict())
        key_list = cache.keys("*")
        if 'access_token' in key_list:
            key_list.remove('access_token')
        if key_list:
            if len(key_list) > 8:
                key_list = key_list[:8]
            popular_list = []
            for itm in key_list:
                key_dict = {"name": cache.get(itm),
                            "id": itm}
                popular_list.append(key_dict)
            return self.render_to_response({'popular_list': popular_list})
        return self.render_to_response({'popular_list': []})


class UserAuthView(CheckSecurityMixin, StatusWrapMixin, JsonResponseMixin, JsonRequestMixin, DetailView):
    model = WeUser
    http_method_names = ['get', 'post']

    def generate_session(self, count=64):
        ran = string.join(
            random.sample('ZYXWVUTSRQPONMLKJIHGFEDCBA1234567890zyxwvutsrqponmlkjihgfedcbazyxwvutsrqponmlkjihgfedcba',
                          count)).replace(" ", "")
        return ran

    def get(self, request, *args, **kwargs):
        session = request.GET.get('session')
        user = WeUser.objects.filter(session=session)
        if user.exists():
            return self.render_to_response({})
        self.message = 'session 已过期或不存在'
        self.status_code = SW.ERROR_PERMISSION_DENIED
        return self.render_to_response({})

    def post(self, request, *args, **kwargs):
        code = request.POST.get('code', None)
        if code:
            status, openid, session = get_session_key(code)
            if status:
                my_session = self.generate_session()
                user = WeUser.objects.filter(openid=openid)
                if user.exists():
                    user = user[0]
                    user.weapp_session = session
                    user.session = my_session
                    user.save()
                else:
                    WeUser(openid=openid, weapp_session=session, session=my_session).save()
                return self.render_to_response({'session': my_session})
            self.message = 'code 错误'
            self.status_code = SW.ERROR_VERIFY
            return self.render_to_response({})
        self.message = 'code 缺失'
        self.status_code = SW.INFO_NO_EXIST
        return self.render_to_response({})

