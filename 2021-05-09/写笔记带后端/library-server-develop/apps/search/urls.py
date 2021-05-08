from django.conf.urls import url
from . import views

urlpatterns = [
    url(r'^list', views.get_books_list),
    url(r'^detail', views.get_book_detail)
]
