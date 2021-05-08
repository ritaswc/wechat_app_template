from django import forms
from django.core import urlresolvers
from django.core.validators import RegexValidator, MinLengthValidator
from django.core.exceptions import ValidationError

from captcha.fields import CaptchaField

from .models import User


class LoginForm(forms.ModelForm):
    email = forms.EmailField(label='邮箱', required=True)
    password = forms.CharField(widget=forms.PasswordInput(),
                               label='密码',
                               required=True)

    class Meta:
        model = User
        fields = ('email', 'password')
        fields_order = ('email', 'password')

class SignupForm(forms.ModelForm):
    captcha = CaptchaField(label='验证码', required=True)
    email = forms.EmailField(label='邮箱', required=True)
    password = forms.CharField(widget=forms.PasswordInput(),
                               label='密码',
                               required=True,
                               validators=[MinLengthValidator(8, message="密码过短")])

    class Meta:
        model = User
        fields = ('email', 'password', 'captcha')
        fields_order = ('email', 'password', 'captcha')

    def clean_password(self):
        password = self.cleaned_data['password']
        if len(password) < 8:
            raise forms.ValidationError('密码长度不能小于 8 个字符！')
        return password

class UserProfileForm(forms.ModelForm):
    email = forms.EmailField(label='邮箱', required=True)
    username = forms.CharField(label='用户名', required=True)
    nickname = forms.CharField(validators=[RegexValidator(regex=r'^[a-zA-Z0-9\u4e00-\u9fa5]+$',
                                                          message="除了普通汉字、字母和数字外，昵称中不能包含任何特殊符号"
                                                          )])
    bio = forms.CharField(label='Bio')
    url = forms.CharField(label='URL')
    location = forms.CharField(label='地址')

    class Meta:
        model = User
        fields = ('email', 'username', 'nickname', 'bio', 'url', 'location')
        fields_order = ('email', 'username', 'nickname', 'bio', 'url', 'location')



class AvatarForm(forms.ModelForm):
    class Meta:
        model = User
        fields = ('avatar',)
