<template>
<div class="page preview js_show" style="background-color:#eee">

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
        </a>
     </div>



  </div>

  </br>
 </div> <!--page__bd-->
          <div class="weui-footer">
            <p class="weui-footer__links">
                <a href="http://github.com/asmcos/jsjoke" class="weui-footer__link">源代码</a>
                <a href="" class="weui-footer__link">集思笑话</a>
            </p>
            <p class="weui-footer__text">京ICP备17016493号</p>
            <p class="weui-footer__text">Copyright © 2017-2017 jsjoke.net</p>
        </div>

   <div style="height:60px;"></div>
   <div class="weui-tab">
    <div class="weui-tabbar" style="position:fixed;">
                <a href="javascript:;" class="weui-tabbar__item weui-bar__item_on">
                    <span style="display: inline-block;position: relative;">
                        <img src="/static/icon_tabbar.png" alt="" class="weui-tabbar__icon">
                    </span>
                    <p class="weui-tabbar__label">微信</p>
                </a>
                <a href="javascript:;" class="weui-tabbar__item">
                    <span style="display: inline-block;position: relative;">
                        <img src="/static/icon_tabbar.png" alt="" class="weui-tabbar__icon">
                    </span>
                    <p class="weui-tabbar__label">发现</p>
                </a>
                <a href="http://my.jsjoke.net/m" class="weui-tabbar__item">
                    <span style="display: inline-block;position: relative;">
                     <img src="/static/icon_tabbar.png" alt="" class="weui-tabbar__icon">
                     <span class="weui-badge weui-badge_dot" style="position: absolute;top: 0;right: -6px;"></span>
                    </span>
                    <p class="weui-tabbar__label">我</p>
                </a>
            </div>
        </div> <!-- weui-tab -->
 
</div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      jokes: null,
      jokeurl: '/jokes'
    }
  },
  created () {
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
      var url = 'http://my.jsjoke.net/m/comment?jokeid=' + j._id
      window.location.href = url
    },
    getJokes () {
      var that = this
      axios.get(this.jokeurl + '?sort=-_id')
        .then(function (response) {
          that.jokes = response.data
        })
    }
  },
  components: {
  }
}
</script>
<style>
div > img {width:90%;}
div > video {width:90%}
a {text-decoration:none;}
</style>
