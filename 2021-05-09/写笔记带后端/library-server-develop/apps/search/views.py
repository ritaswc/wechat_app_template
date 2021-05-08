from django.shortcuts import render
from django.http import HttpResponse
from bs4 import BeautifulSoup
import urllib.parse
import json
import re


def get_books_list(request):
    # 获取request query参数
    book_name = request.POST.get('book', '')
    school = request.POST.get('school', 'gdut')
    lib_path = request.POST.get('lib_path', '0')
    page = request.POST.get('page', 1)
    if school == 'gdut':
        send_data = _get_all_book_list(book_name, school, lib_path, page)
        while (int(send_data['book_count']) > 200) and (len(send_data['books']) < 200):
            page += 1
            send_data['books'].extend(_get_all_book_list(
                book_name, school, lib_path, page)['books'])
        send_data['books'][:199]
    else:
        send_data = _get_corner_book_list(book_name, school)
    return HttpResponse(json.dumps(send_data, ensure_ascii=False), content_type="application/json")


def get_book_detail(request):
    # 获取query参数
    marc_no = request.POST.get('marc_no', '')
    school = request.POST.get('school', 'gdut')
    ctrl_no = request.POST.get('ctrl_no', '')

    if school == 'gdut':
        # url初始化
        library_url = 'http://if4.zhaobenshu.com:7781/Find/find_ifa_GetDetail.ashx?'
        str_a = '[Lib={{school}}][CtrlRd={{marc_no}}]'

        # detail正则初始化
        reg = re.compile(r'<b>内容简介</b><hr/>(.+)<b>作者简介</b>')

        # 请求参数
        query_obj = {}
        query_obj['a'] = str_a.replace(
            'school', school).replace('marc_no', marc_no)
        query_obj['z1'] = '99MOUUIS2ZGDPNFVEBDV'
        query_obj['z2'] = ''
        query_obj['z3'] = ''
        query_obj['z4'] = '04'
        query_obj['z5'] = ''

        # 响应处理
        response = urllib.request.urlopen(
            library_url + urllib.parse.urlencode(query_obj))
        data = json.loads(response.read().decode())
        soup = BeautifulSoup(data['DetailInfo'], 'html.parser')

        # 详情对象初始化
        detail_obj = {}
        description_obj = {}
        description_obj['image_url'] = soup.find(
            'center').find('img').get('src')
        reg_match = reg.match(data['DetailIntro'])
        detail_str = reg_match.group(1).replace('<br>', '\n').replace(
            '<br/>', '\n').replace('<br />', '\n').replace('<p>', '\n').replace('/p', '\n')
        description_obj['summary'] = detail_str
        for tr in soup.find('table').find_all('tr'):
            temp_arr = []
            for td in tr.find_all('td'):
                temp_arr.append(td.get_text())
            detail_obj[temp_arr[0]] = temp_arr[1]
        coll_obj = _get_book_status(school, ctrl_no)
        # 发送对象
        send_obj = {}
        send_obj['book_name'] = detail_obj['书名']
        send_obj['book_author'] = detail_obj['作者']
        send_obj['books'] = coll_obj['status_list']
        send_obj['code'] = coll_obj['code']
        send_obj['description'] = description_obj
    else:
        send_obj = _get_corner_book_detail(marc_no, school)
    return HttpResponse(json.dumps(send_obj, ensure_ascii=False), content_type="application/json")


def _get_all_book_list(book_name, school, lib_path, page):
    # url 初始化
    library_url = 'http://if4.zhaobenshu.com:7781/Find/find_ifa_FindFullPage.ashx?'
    str_a = '[Lib={{school}}][SublibSn={{lib_path}}][_PageNo={{page}}][Keys={{book}}]'

    # 请求参数
    query_obj = {}
    query_obj['a'] = str_a.replace(
        'school', school).replace('lib_path', lib_path).replace('page', str(page)).replace('book', book_name)
    query_obj['z1'] = '99MOUUIS2ZGDPNFVEBDV'
    query_obj['z2'] = ''
    query_obj['z3'] = ''
    query_obj['z4'] = '04'
    query_obj['z5'] = ''

    # 响应处理
    response = urllib.request.urlopen(
        library_url + urllib.parse.urlencode(query_obj))
    data = json.loads(response.read().decode())
    send_data = {}
    temp_list = []
    for li in data['find_ifa_FindFullPage_list1']:
        temp_list.append({
            'description': {'author': li['Author'], 'press': li['Publish']},
            'marc_no': li['CtrlRd'],
            'title': li['Title'],
            'ctrl_no': li['CtrlNo']
        })
    send_data['books'] = temp_list
    send_data['book_count'] = data['FindCount']
    send_data['is_alot'] = True if (int(data['FindCount']) > 200) else False
    return send_data


def _get_book_status(school, ctrl_no):
    # url 初始化
    library_url = 'http://ifg.zhaobenshu.com:7778/Find/find_ifa_GetOpacColl.aspx?'

    # 请求参数
    query_obj = {}
    query_obj['a'] = school
    query_obj['b'] = ctrl_no
    query_obj['z1'] = '99MOUUIS2ZGDPNFVEBDV'
    query_obj['z2'] = ''
    query_obj['z3'] = ''
    query_obj['z4'] = '04'
    query_obj['z5'] = ''

    # 响应处理
    response = urllib.request.urlopen(
        library_url + urllib.parse.urlencode(query_obj))
    data = json.loads(response.read().decode())
    soup = BeautifulSoup(data['OpacColl'], 'html.parser')
    reason = {}
    reason['status_list'] = []
    for tr in soup.find('table').find_all('tr'):
        temp_obj = {}
        temp_arr = []
        for td in tr.find_all('td'):
            temp_arr.append(td.get_text())
        temp_obj['place'] = temp_arr[0]
        temp_obj['lib_book_no'] = temp_arr[1]
        temp_obj['status'] = temp_arr[2]
        reason['status_list'].append(temp_obj)
    reason['code'] = soup.find('b').get_text()
    return reason


def _get_corner_book_list(book_name, school):
    library_url = 'https://cornerapp.applinzi.com/api/search'
    query_obj = {}
    query_obj['book'] = book_name
    query_obj['school'] = school
    value = urllib.parse.urlencode(query_obj).encode('utf-8')
    req = urllib.request.Request(library_url, value)
    req.add_header('Content-Type', 'application/json')
    response = urllib.request.urlopen(req)
    data = json.loads(response.read().decode())
    return data


def _get_corner_book_detail(marc_no, school):
    library_url = 'https://cornerapp.applinzi.com/api/detail'
    query_obj = {}
    query_obj['marc_no'] = marc_no
    query_obj['school'] = school
    value = urllib.parse.urlencode(query_obj).encode('utf-8')
    req = urllib.request.Request(library_url, value)
    req.add_header('Content-Type', 'application/json')
    response = urllib.request.urlopen(req)
    data = json.loads(response.read().decode())
    return data
