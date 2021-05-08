## 微信小程序-移动端图书馆-后台

## 项目说明：

- 微信小程序后台：为小程序前端提供数据接口，项目持续更新中

- 使用技术：**python3.5、django、mysql、uwsgi...**

- 前台：[m-mall](https://github.com/skyvow/m-mall)

- 后台：[m-mall-admin](https://github.com/skyvow/m-mall-admin)

## 项目使用

```
    pip install requirements.txt
    python manage.py runserver
```

## 目录结构：

        │  .gitignore               # gitignore 相关
        │  manage.py                # django管理相关
        │  README.md                
        │  requirements.txt         # 项目所需的包
        │  uwsgi.ini                # 项目服务器配置，uwsgi参数
        │
        ├─apps                      # django应用
        │  │  __init__.py
        │  ├─search
        │  ├─user
        │
        ├─gdutLibrary               # django主体
        │  │  settings.py
        │  │  urls.py
        │  │  wsgi.py
        │  │  __init__.py
        │
        └─libs                      # 项目引用的额外包
            │  django_jwt_session_auth.py
            │  WXBizDataCrypt.py
            │  __init__.py

