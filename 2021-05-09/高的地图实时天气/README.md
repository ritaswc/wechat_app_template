##参考手册
###AMapWX构造函数
| 构造函数 | 说明 |
| -------| ---|
| AMapWX(OBJECT) | OBJECT为AMapWX必选参数对象，包括：<br/>key:高德开发者key

###方法概述
| 方法 | 说明 |
| -------| ---|
| getRegeo | 根据用户定位，返回用户所在位置附近poi信息 |
| getPoiAround | 根据用户定位，返回用户所在位置附近多个poi信息 |
| getWeather | 根据用户定位，返回用户所在位置附近天气信息 |

###方法详述

#####1.getRegeo(OBJECT)

#####OBJECT参数说明

|参数名     | 说明 | 类型  | 是否必选 | 备注 |
| --------| ----|------|-------|----|
| iconPath | 标注的图标 | String  |  是 | 项目目录下的图片路径，支持相对路径写法 
| width | 标注图标的宽度 | Number  |  否 | 默认为图片实际宽度
| height | 标注图标的高度 | Number  |  否 | 默认为图片实际高度
| success| 接口调用成功的回调函数 |Function| 否 |
| fail | 接口调用失败的回调函数 |Function| 否 |




###2.getPoiAround(OBJECT)

#####OBJECT参数说明

|参数名     | 说明 | 类型       | 是否必选 | 备注 |
| --------| ----|------|-------|----|
| iconPath | 未选中的图标 | String  |  是 | 项目目录下的图片路径，支持相对路径写法 |
| iconPathSelected | 选中的图标 | String  |  是 | 项目目录下的图片路径，支持相对路径写法 |
| width | 标注图标的宽度 | Number  |  否 | 默认为图片实际宽度|
| height | 标注图标的高度 | Number  |  否 | 默认为图片实际高度|
| success| 接口调用成功的回调函数 |Function| 否 | |
| fail | 接口调用失败的回调函数 |Function| 否 | |


###3.getWeather(OBJECT)

#####OBJECT参数说明

|参数名     | 说明 | 类型       | 是否必选 | 备注 |
| --------| ----|------|-------|----|
| success| 接口调用成功的回调函数 |Function| 否 | |
| fail | 接口调用失败的回调函数 |Function| 否 | |



