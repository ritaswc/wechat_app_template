from django.http import HttpResponseRedirect
from django.shortcuts import render
from django.contrib.auth import authenticate, login, logout
from django.contrib import messages
from django.contrib.auth.decorators import login_required

from .forms import LoginForm, SignupForm, UserProfileForm, AvatarForm
from .models import User
from django.http import JsonResponse

from rest_framework.decorators import api_view
from rest_framework.response import Response
from user.serializers import UserSerializer


# create a function to resolve email to username
def get_user(email):
    try:
        return User.objects.get(email=email.lower())
    except User.DoesNotExist:
        return None


def login_view(request):
    if request.user.is_authenticated():
        return HttpResponseRedirect('/')

    if request.method == 'POST':
        email = request.POST.get('email')
        password = request.POST.get('password')

        username = get_user(email)
        if username:
            user = authenticate(username=username, password=password)
        else:
            messages.warning(request, '无此用户，请注册使用！')
            return HttpResponseRedirect('/signup/')

        if user is not None:
            login(request, user)
            messages.success(request, '登录成功！')
            return HttpResponseRedirect('/')
        else:
            messages.warning(request, '邮箱或者密码错误，请重新输入！')
            form = LoginForm(data=request.POST)
            return render(request, 'user/login.html', {'form': form})
    else:
        return render(request, 'user/login.html', {})


def signup_view(request):
    if request.user.is_authenticated():
        return HttpResponseRedirect('/')

    registered = False
    if request.method == 'POST':
        username = get_user(request.POST.get('email'))
        if username:
            messages.warning(request, '此邮箱已经注册，请直接登录！')
            return HttpResponseRedirect('/login/')
        else:
            form = SignupForm(data=request.POST)
            if form.is_valid():
                user = form.save()
                user.set_password(user.password)
                user.client_mark = 'web'
                user.save()
                registered = True
                login(request, user)
                messages.success(request, '注册成功！')
                return HttpResponseRedirect('/')

    else:
        form = SignupForm
    return render(request, 'user/signup.html', {'form': form})


@login_required
def logout_view(request):
    logout(request)
    messages.success(request, '成功退出！')
    return HttpResponseRedirect('/')


@login_required
def profile_view(request):
    return render(request, 'user/profile.html', {'title': 'account'})


@login_required
def profile_edit_view(request):
    user = request.user
    user.email = request.POST.get('email')
    user.username = request.POST.get('username')
    user.nickname = request.POST.get('nickname')
    user.bio = request.POST.get('bio')
    user.url = request.POST.get('url')
    user.location = request.POST.get('location')
    user.save()

    messages.success(request, '修改个人信息成功')
    return render(request, 'user/profile.html', {'title': 'account'})


@login_required
def profile_delete_view(request):
    user = get_user(request.user.email)
    logout(request)
    user.delete()
    messages.success(request, '成功删除账户！')
    return HttpResponseRedirect('/')


@login_required
def password_edit_view(request):
    user = get_user(request.user.email)
    check_result = user.check_password(request.POST.get('old-password'))

    if check_result == True:
        new_password = request.POST.get('password')
        confirm_new_password = request.POST.get('confirmPassword')

        if new_password != confirm_new_password:
            messages.warning(request, '两次输入密码不一致，请重新输入！')
            return HttpResponseRedirect('/profile/')
        else:
            user.set_password(new_password)
            logout(request)
            messages.success(request, '密码修改成功，请重新登录')
            return HttpResponseRedirect('/login/')
    else:
        messages.warning(request, '请输入正确的当前密码！')
        return HttpResponseRedirect('/profile/')


# @login_required
# def AvatarEditView(request):
#     form_class = AvatarForm
#     template_name = 'user/avatar_edit.html'
#     success_url = '/profile'

#     def form_valid(self, form):
#         if form.has_changed():
#             self.request.user.avatar.delete(save=False)
#         return super().form_valid(form)

#     def get_object(self, queryset=None):
#         return User.objects.get(pk=self.request.user.pk)


@api_view(['GET', 'POST'])
def users(request):
    if request.method == 'GET':
        users = User.objects.all()
        serializer = UserSerializer(users, many=True)
        return Response(serializer.data)
    elif request.method == 'POST':
        user = User(email=request.POST.get('email'))
        user.set_password(request.POST.get('email'))
        user.save()
        serializer = UserSerializer(user)
        return Response(serializer.data)


@api_view(['GET'])
def user_detail(request, pk):
    if request.method == 'GET':
        user_detail = User.objects.filter(id=pk)
        if not user_detail:
            return JsonResponse({})
        else:
            serializer = UserSerializer(user_detail[0])
            return Response(serializer.data)


@api_view(['GET'])
def check_user(request, nickname):
    if request.method == 'GET':
        user_detail = User.objects.filter(nickname=nickname)
        if not user_detail:
            return JsonResponse({})
        else:
            serializer = UserSerializer(user_detail[0])
            return Response(serializer.data)

@api_view(['POST'])
def create_weixin_user(request):
    if request.method == 'POST':
        user = User(email=request.POST.get('weixin_nickName') + "@weixinclient.com",
            weixin_nickName=request.POST.get('weixin_nickName'),
            weixin_avatarUrl=request.POST.get('weixin_avatarUrl'))
        user.set_password(request.POST.get('email'))
        user.save()
        serializer = UserSerializer(user)
        return Response(serializer.data)
