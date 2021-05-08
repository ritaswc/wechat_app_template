<template>
   <div>
   <form v-on:submit.prevent="sendjoke">
    <div class="box box-info" id="addjoke">
           
            <div class="box-header">
              <h3 class="box-title">{{title}}
                <small>你这一发，他们都乐了...</small>
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
                  <input type="text" class="form-control" id="title" placeholder="笑一笑，标题最重要">
               </div>
                    <textarea id="editor1" name="editor1" rows="10" cols="80">
                                           
                    </textarea>
            </div>
          <!-- /.box -->

            <div class="box-footer">
                <button class="btn btn-primary" > 发表</button>
              </div>
          </div>
          <!-- /.box bos-info -->
     </form>

   </div>
</template>

<script>
import 'js/editor'
import axios from 'axios'

export default {
  data () {
    window.menuvue.$options.data().menus[1].vclass = 'treeview active'
    return {
      title: '快乐每一天',
      jokeurl: '/jokes'
    }
  },

  methods: {
    sendjoke () {
      /* eslint-disable */
      var title1 = $('#title').val();
      var content1 =  CKEDITOR.instances.editor1.getData();
      /* eslint-enable */
      var that = this
      axios.post(this.jokeurl, {
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

