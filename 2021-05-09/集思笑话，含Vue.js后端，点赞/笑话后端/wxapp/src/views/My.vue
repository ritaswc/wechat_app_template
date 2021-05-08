<template>

<div class="page preview js_show" style="background-color:#eee">
       <div class="weui-cells">
           <div class="page__bd page__bd_spacing" style="height:60px;">
                  <div class="weui-flex js_category">
                    <a href="/"> 
                     <p class="weui-flex__item" style="margin:15px;"> < </p>
                    </a>
                    <p class="weui-flex__item" style="margin:15px;"></p>
                    <a href="/m/newjoke">
                     <img src="/static/camera.png" style="width:40px;margin:15px;" alt="">
                    </a>
                </div>
           </div>
           <div class="weui-cell">
                <div class="weui-cell__hd" style="position: relative;margin-right: 10px;">
                    <img v-bind:src="userinfo.avatar||'/static/default-img.png'" style="width: 50px;display: block" @click="crop">
                </div>
                <div class="weui-cell__bd">
                    <p>{{userinfo.username}}</p>
                    <p style="font-size: 13px;color: #888888;" @click="changenick">{{userinfo.nickname||'添加昵称'}}</p>
                </div>
            </div>
            <div class="weui-cell weui-cell_vcode" style="display:none;" id="nickname">
                <div class="weui-cell__hd">
                    <label class="weui-label">改变昵称</label>
                </div>
                <div class="weui-cell__bd">
                    <input class="weui-input" type="text" id="nickinput" placeholder="请输入昵称">
                </div>
                <div class="weui-cell__ft">
                    <button class="weui-vcode-btn" @click="donenick">确认修改</button>
                </div>
            </div>
       </div>
 
<!-- my jokes --> 
   <div class="page__hd">
        <p class="page__desc" style="padding:60px;">我的所有笑话</p>
    </div>
 <div class="page__bd" v-for="j in jokes">
  <div class="weui-panel weui-panel_access">
    <div class="weui-panel__bd">
        <a href="javascript:void(0);" class="weui-media-box weui-media-box_appmsg" style="align-items:flex-start;padding:8px;">
            <div class="weui-media-box__hd" style="width:40px;height:40px;">
                <img class="weui-media-box__thumb" v-bind:src="j.author[0].avatar||'/static/default-img.png'" alt="" style="width:40px;height:40px;">
            </div>
            <div class="weui-media-box__bd">
                <h4 class="weui-media-box__title">{{j.author[0].nickname||j.author[0].username}}</h4>

                <p class="weui-media-box__desc">{{j.createdate | getYMD }}</p>
                <p class="weui-media-box__desc">
                   <article class="weui-article" style="padding-left:0px;">
                     <section>
                       <div v-html="j.content" style="white-space:pre-wrap"> </div>

                       <div class="weui-form-preview__ft">
                          <button type="submit" class="weui-form-preview__btn weui-form-preview__btn_primary" @click='joke(j,1)'><font><font><img  style="width:24px;" src="/static/joke.png"> {{j.joke}}</font></font></button>
                          <button type="submit" class="weui-form-preview__btn weui-form-preview__btn_primary" @click='comment(j)'><font><font><img  style="width:24px;" src="/static/comment.png"> {{j.comment}}</font></font></button>
                          <button type="submit" class="weui-form-preview__btn weui-form-preview__btn_primary" @click='joke(j,0)'><font><font><img style="width:24px;" src="/static/unjoke.png"> {{j.unjoke}}</font></font></button>
                       </div>

                       <template v-for="(c,index) in j.comments" >
                        <div class="triangle-up" style="margin-left:15px;" v-if="index===0"></div>
                        <div class="comment" > <font class="comment_name">{{c.author.nickname||c.author.username}}:</font><font class="comment_content">{{c.content}}</font></div>
                       </template>
                     </section>
                   </article>
                </p>
            </div>
         <img src="/static/del.png" style="width:24px;height:24px;" @click='del(j)'></img>
        </a>
     </div>


  </div>

  </br>
 </div> <!--page__bd-->



  <!-- end jokes -->
  
</div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      userinfo: {},
      jokes: [],
      userurl: '/api/my',
      jokeurl: '/api/my/jokes'
    }
  },
  created () {
    this.getMy()
    this.getJokes()
  },

  methods: {
    joke (j, jo) {
      if (jo) {
        j.joke += 1
        axios.get(this.jokeurl + '/' + j._id + '?joke=1')
      } else {
        j.unjoke += 1
        axios.get(this.jokeurl + '/' + j._id + '?unjoke=1')
      }
    },
    comment (j) {
      var url = '/m/comment?jokeid=' + j._id
      window.location.href = url
    },
    del (j) {
      var that = this
      axios.delete('/api/jokes' + '/' + j._id)
           .then(function (response) {
             that.getJokes()
           })
    },
    crop () {
      this.$router.push('/m/crop')
    },
    getMy () {
      var that = this
      axios.get(this.userurl)
        .then(function (response) {
          that.userinfo = response.data
        })
    },
    changenick () {
      /* eslint-disable */
      var nickname1 = $('#nickname');
      /* eslint-enable */
      nickname1.show()
    },
    donenick () {
      /* eslint-disable */
      var nickname1 = $('#nickname');
      var nickinput1 = $('#nickinput').val();
      /* eslint-enable */
      nickname1.hide()
      this.userinfo.nickname = nickinput1
      axios.post(this.userurl, this.userinfo)
        .then(function (response) {
          window.location.reload()
        })
    },
    getJokes () {
      var that = this
      axios.get(this.jokeurl)
        .then(function (response) {
          if (!response.data.err) {
            that.jokes = response.data
          }
        })
    }
  },
  components: {
  }
}
</script>
<style>
div > img {width:50%;}
a {text-decoration:none;}
</style>
