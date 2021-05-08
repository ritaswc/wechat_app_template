from django.conf.urls import url
from views import *

urlpatterns = [
    url(r'^hellspawn/(?P<id>(\d)+)/scenes', HellspawnSceneListView.as_view()),
    url(r'^hellspawn/(?P<id>(\d)+)', HellspawnDetailView.as_view()),
    url(r'^hellspawns/', HellspawnListView.as_view()),
    url(r'^scene/(?P<id>(\d)+)', SceneDetailView.as_view()),
    url(r'^scenes/', SceneListView.as_view()),
    url(r'^search/(?P<value>.+)', SearchListView.as_view()),
    url(r'^feedback', FeedbackView.as_view()),
    url(r'^populars', PopularListView.as_view()),
    url(r'^auth', UserAuthView.as_view()),
]
