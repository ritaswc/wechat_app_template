# coding=utf-8
"""
MIT License
Copyright (c) [2017] [weidwonder]
Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:
The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
"""

import jwt
from django.conf import settings as django_settings
from django.core.cache import cache
from django.dispatch.dispatcher import Signal
from django.utils.module_loading import import_string
from six import text_type

user_jwt_logged_in = Signal(['request', 'user'])

DEFAULT_JWT_AUTH_SETTING = {
    'PREFIX': 'JWT',
    'ALGORITHM': 'HS256',
    'DECODER': 'django_jwt_session_auth.jwt_decoder',
    'ENCODER': 'django_jwt_session_auth.jwt_encoder',
    'SECRET': '',  # default is django.conf.settings.SECRET_KEY
    'PAYLOAD_TO_USER': None,
    'USER_TO_PAYLOAD': None,
    'USER_KEY': 'pk',
    'TEST_USER_GETTER': None,
    'SESSION': {
        'EXPIRE': 2592000,
        'PREFIX': 'JWT_AUTH_CACHE:'
    }
}


class LazyJwtSettings(object):
    """ Lazy loaded settings for JWT_AUTH
    """

    _settings = {}

    def __getitem__(self, item):
        if not self._settings:
            self._load_settings()
        return self._settings[item]

    def _load_settings(self):
        self._settings = {}

        # check settings
        user_defined_setting = getattr(django_settings, 'JWT_AUTH', {})

        self._settings = DEFAULT_JWT_AUTH_SETTING.copy()
        self._settings['SESSION'].update(
            user_defined_setting.pop('SESSION', {}))
        self._settings.update(user_defined_setting)

        assert self._settings.get('PAYLOAD_TO_USER'), (u'JWT_AUTH settings\' `PAYLOAD_TO_USER` must be settled.\n'
                                                       u'And it should return a user instance(whither it user-defined or not) '
                                                       u'or None.')
        assert self._settings.get('USER_TO_PAYLOAD'), (u'JWT_AUTH settings\' `USER_TO_PAYLOAD` must be settled.\n'
                                                       u'And it should return a serializable dict payload')


jwt_settings = LazyJwtSettings()


def get_authorization_header(request):
    """
    Return request's 'Authorization:' header, as a bytestring.

    Hide some test client ickyness where the header can be unicode.
    """
    auth = request.META.get('HTTP_AUTHORIZATION', b'')
    if isinstance(auth, text_type):
        # Work around django test client oddness
        auth = auth.encode('utf-8')
    return auth


def jwt_decoder(token):
    try:
        return jwt.decode(token, jwt_settings['SECRET'] or django_settings.SECRET_KEY,
                          algorithms=(jwt_settings['ALGORITHM'],))
    except:
        return None


def jwt_encoder(payload):
    return jwt.encode(payload, jwt_settings['SECRET'] or django_settings.SECRET_KEY,
                      algorithm=jwt_settings['ALGORITHM'])


class JwtSession(object):
    """ jwt authenticated session
    """

    __slot__ = ('__storage__', '__key__', '__expire__')

    @classmethod
    def get(cls, key, expire=None):
        """ get jwt session corresponding to the key.
        :param key: session cache key
        :param expire: default expire time(Seconds)
        :return: JwtSession instance
        """
        data = cache.get(key, {})
        default_expire = expire or jwt_settings['SESSION']['EXPIRE']
        return cls(key, default_expire, storage=data)

    def save(self):
        """ save session to cache
        """
        if self.__storage__:
            cache.set(self.__key__, self.__storage__, self.__expire__)

    def __init__(self, key, expire, storage=None):
        self.__dict__['__key__'] = key
        self.__dict__['__expire__'] = expire
        self.__dict__['__storage__'] = storage or {}

    def __getattr__(self, item):
        return self.__storage__[item]

    def __setattr__(self, key, value):
        self.__storage__[key] = value

    def __getitem__(self, item):
        return self.__storage__[item]

    def __setitem__(self, key, value):
        self.__storage__[key] = value


class JwtAuthMiddleware(object):
    """ jwt auth middleware

    Check the `AUTHORIZATION` header in request, and add the correspond `jwt_user` and `jwt_session` to request.

    if the authentication failed, the `request.jwt_user` will be None, and `jwt_session` will be a empty JwtSession.
    """

    user_key_prefix = ''

    _test_user = None

    def __init__(self, get_response=None):
        self.get_response = get_response
        self.user_key_prefix = jwt_settings['SESSION']['PREFIX']

    def __call__(self, request, *args, **kws):
        jwt_val = get_authorization_header(request)
        token = self._get_token(jwt_val)
        if not token:
            request.jwt_user = None or (
                self.test_user if django_settings.DEBUG else None)
            request.jwt_session = self._get_empty_jwt_session()
        else:
            decoder = self._get_decoder()
            payload = decoder(token)
            payload_to_user_func = self._get_payload_to_user_handler()
            user = payload_to_user_func(payload)
            request.jwt_user = user
            request.jwt_session = self.get_jwt_session(user)
        response = self.get_response(request, *args, **kws)
        request.jwt_session.save()
        return response

    @property
    def test_user(self):
        """ load test user if TEST_USER_GETTER is exists
        """
        test_user_getter = jwt_settings['TEST_USER_GETTER']
        if not self._test_user and test_user_getter:
            test_user_getter = import_string(test_user_getter)
            self.__class__._test_user = test_user_getter()
        return self._test_user

    @classmethod
    def get_jwt_session(cls, user, expire=None):
        """ get specific user jwt_session
        :param user: user
        :param expire: expire time in seconds.
        :return: JwtSession instance
        """
        user_key = jwt_settings['USER_KEY']
        if not user:
            return cls._get_empty_jwt_session()
        session_key = cls.user_key_prefix + str(getattr(user, user_key))
        session = JwtSession.get(session_key, expire=expire)
        return session

    @classmethod
    def jwt_login(cls, user, request, expire=None):
        """ jwt login user function
        :param user_object user: user instance
        :param request request: request instance
        :return str: token
        """
        user_to_payload_handler = import_string(
            jwt_settings['USER_TO_PAYLOAD'])
        payload = user_to_payload_handler(user)
        encoder = cls._get_encoder()
        token = encoder(payload)
        request.jwt_user = user
        request.jwt_session = cls.get_jwt_session(user, expire=expire)
        user_jwt_logged_in.send(user.__class__, request=request, user=user)
        return token

    @classmethod
    def _get_empty_jwt_session(cls):
        """ get empty jwt session
        """
        return JwtSession(cls.user_key_prefix + 'empty', None, {})

    @classmethod
    def _get_decoder(cls):
        """ get jwt token decoder
        """
        decoder_entry = jwt_settings['DECODER']
        if decoder_entry == 'django_jwt_session_auth.jwt_decoder':
            return jwt_decoder
        elif isinstance(decoder_entry, basestring):
            return import_string(decoder_entry)
        else:
            return decoder_entry

    @classmethod
    def _get_encoder(cls):
        """ get jwt token decoder
        """
        encoder_entry = jwt_settings['ENCODER']
        if encoder_entry == 'django_jwt_session_auth.jwt_encoder':
            return jwt_encoder
        elif isinstance(encoder_entry, basestring):
            return import_string(encoder_entry)
        else:
            return encoder_entry

    def _get_token(self, jwt_val):
        """get token from jwt_val
        """
        try:
            prefix, token = jwt_val.split()
        except ValueError:
            return None
        default_prefix = jwt_settings['PREFIX']
        if prefix != default_prefix:
            return None
        return token

    def _get_payload_to_user_handler(self):
        return import_string(jwt_settings['PAYLOAD_TO_USER'])


jwt_login = JwtAuthMiddleware.jwt_login
