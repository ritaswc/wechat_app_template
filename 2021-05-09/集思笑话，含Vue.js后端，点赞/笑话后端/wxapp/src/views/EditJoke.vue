<template>
   <div>
   <form v-on:submit.prevent="updatejoke">
    <div class="box box-info">
           
            <div class="box-header">
              <h3 class="box-title">{{title}}
                <small>每天乐一乐</small>
              </h3>
              <!-- tools box -->
              <div class="pull-right box-tools">
                <button type="button" class="btn btn-info btn-sm" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                  <i class="fa fa-minus"></i></button>
                <button type="button" class="btn btn-info btn-sm" data-widget="remove" data-toggle="tooltip" title="Remove">
                  <i class="fa fa-times"></i></button>
              </div>
              <!-- /. tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
              <div class="form-group">
                  <input type="text" class="form-control" id="title" placeholder="要不要脸" v-bind:value="joketitle">
               </div>
                    <textarea id="editor1" name="editor1" rows="10" cols="80">
                    </textarea>
            </div>
          <!-- /.box -->

            <div class="box-footer">
                <button class="btn btn-primary"> 确认修改</button>
              </div>
          </div>
          <!-- /.box bos-info -->
       </form>
   </div>
</template>

<script>
import '../js/editor'
import '../js/node_ckeditor_uploader'
import axios from 'axios'

export default {
  data () {
    return {
      title: '修改Joke',
      joketitle: null,
      jokecontent: null,
      jokeid: null,
      jokeurl: '/jokes'
    }
  },
  created () {
    this.getJokes()
    window.menuvue.$options.data().menus[1].vclass = 'treeview active'
  },

  methods: {
    getJokes () {
      var that = this
      this.jokeid = that.$route.query.jokeid
      axios.get(this.jokeurl + '/' + that.$route.query.jokeid)
        .then(function (response) {
          that.joketitle = response.data.title
          that.jokecontent = response.data.content
          /* eslint-disable */
					var nIntervId = setInterval(function(){
						if (CKEDITOR.instances.editor1.setData){
              CKEDITOR.instances.editor1.setData(that.jokecontent);
							clearInterval(nIntervId)
            }
          }, 500);
          /* eslint-enable */
        })
    },

    updatejoke () {
      /* eslint-disable */
      var title1 = $('#title').val();
      var content1 =  CKEDITOR.instances.editor1.getData();
      var jokeid1 = this.jokeid
      /* eslint-enable */
      var that = this
      axios.put(this.jokeurl + '/' + jokeid1, {
        title: title1,
        content: content1
      })
        .then(function (response) {
          that.$router.push('/admin/myjokes')
        })
    }
  },
  components: {
  }
}
</script>

