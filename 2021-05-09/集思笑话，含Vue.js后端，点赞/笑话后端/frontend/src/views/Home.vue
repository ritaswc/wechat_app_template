<template>
<div>
    <div id="content" class="content" role="main">
     <!-- 1 header-->
     <div class="filter without-isotope extras-off filter-bg-decoration"> 
      <div class="filter-categories">
          <a href="/" class="show-all act" data-filter="*">Jokes</a>
           <a href="http://my.jsjoke.net" data-filter="*">发笑话</a>
      </div>
      <!-- filter-categories-->
     </div>  
     <!-- header/filter without-isotope -->
     <!-- 2 content-->
     <div class="articles-list circle-fancy-style loading-effect-fade-in" data-cur-page="1" >

<article class="post post-14301 dt_gallery type-dt_gallery status-publish hentry dt_gallery_category-macro dt_gallery_category-objects dt_gallery_category-13 dt_gallery_category-243 media-wide bg-on fullwidth-img" v-for='j in jokes'>

<section class="items-grid wf-container round-images" style="margin:-10px;">

<div style="padding-left:10px;">
 <div class="borders">
  <article class="post-format-standard">
   <div class="wf-td">
    <a class="alignleft post-rollover this-ready">
     <img class="lazy-load preload-me is-loaded" style="width:60px;height:60px;" v-bind:src="j.author[0].avatar||'/static/default-img.png'">
    </a>
   </div>
   <div class="post-content">
     <a>{{j.author[0].nickname||j.author[0].username}}</a> </br>
    <time class="text-secondary" >{{j.createdate|GetYMD}}</time>
   </div>
  </article>
 </div>
</div>

</section>
    <div class="project-list-content" v-if="j.title"><h3 class="entry-title"> <a href="" title="Lorem ipsum elit nulla emet" rel="bookmark">{{j.title}}</a></h3></div>
    <div v-html='j.content' > </div>
 
    <div class="filter-categories">
     <a @click="joke(j,1)" class="show-all act" >可乐 {{j.joke}}</a>
     <a @click="joke(j,0)" class="show-all act" >无耻 {{j.unjoke}}</a>
    </div>
</article>

   
     </div>
     <!-- content/articles-list -->
     <!-- page 1,2,3, -->   
    </div>
    <!-- content -->
   

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
div > img {width:50%;}
a {text-decoration:none;}
</style>
