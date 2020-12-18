# 我要圣诞帽
采用微信小程序编写       实现了为图片带帽子的功能<br>
## 程序结构如下
* image (在此放置所有圣诞帽的素材)
* pages (包含了index imageeditor combine文件夹，每个文件夹中都有四个文件，后缀名分别为 .js .json .wxss .wxml)
  * index (第一步：选择底图，程序设计三个底图来源 即微信头像、相机、相册)
  * imageeditor(第二步：实现选择圣诞帽  并通过移动和旋转调整圣诞帽的大小和位置)
  * combine(第三步：将底图和调整后的圣诞帽合成新的图片 并保存至微信相册)
* app.js
* app.json
* app.wxss
* project.config.json
## 过程展示
##### step 1  旋转底图
![step1](https://github.com/jasscia/ChristmasHat/raw/master/shortcut/step1.jpg)
##### step 2 确定底图后 进入下一步
![step2](https://github.com/jasscia/ChristmasHat/raw/master/shortcut/step2.jpg)
##### step 3 选择帽子 调整帽子 确定进入下一步
![step3](https://github.com/jasscia/ChristmasHat/raw/master/shortcut/step3.jpg)
##### step 4 canvas绘制合成图 并保存至相册
![step4](https://github.com/jasscia/ChristmasHat/raw/master/shortcut/step4.jpg)
##### 最终成果 
![成果图](https://github.com/jasscia/ChristmasHat/raw/master/shortcut/avatarWithHat.png)
## 核心算法介绍
* 核心算法1:怎么实现 帽子的实时转动
   * 当touchstart时，保存此时的touch起始点，并以此时的底图和帽子位置作为旋转角度和缩放比例值计算的参考点
   * 当touchmove时，根据起始点 和 临时的终止点 计算在x/y方向上的移动距离，计算参考点分别 加上这个距离，得到移动后的位置，通过移动前后的位置 计算移动前后位置的变动 计算旋转角和缩放比例
   * 当touchend时，重置底图和帽子的位置及旋转角和缩放比例
* 核心算法2:怎么实现 合成图片(利用canvas)
    * 首先绘制底图（根据屏幕大小、图片大小计算左上角和右下角坐标）
    * 绘制帽子（计算最终帽子的大小及中心位置 旋转角度,移动画布原点到帽子的中心位置，旋转画布 并绘制帽子）
