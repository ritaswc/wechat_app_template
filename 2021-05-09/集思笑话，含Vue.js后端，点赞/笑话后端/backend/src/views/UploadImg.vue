<template>
   <div>
    <div class="box box-info">
           
            <div class="box-header">
              <h3 class="box-title">
                上传图片
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
            
             <form v-on:submit.prevent="uploadimg"  enctype="multipart/form-data" method="POST" dir="ltr" lang="zh-cn" id="uploadfile">
             <label id="cke_167_label" for="cke_168_fileInput_input" style="display:none">上传到服务器</label>
             <input style="width:100%" id="cke_168_fileInput_input" aria-labelledby="cke_167_label" type="file" name="upload" size="38">
              <div class="box-footer">
                <button class="btn btn-primary" > 发表</button>
              </div>
             </form>
            </div>
          <!-- /.box -->

          </div>
          <!-- /.box bos-info -->
   </div>
</template>

<script>
import '../js/editor'
import axios from 'axios'

export default {
  data () {
    window.menuvue.$options.data().menus[3].vclass = 'treeview active'
    return {
      uploadurl: '/uploader/uploadimage'
    }
  },

  methods: {
    uploadimg () {
      /* eslint-disable */
      var formElement = document.querySelector("#uploadfile");
      var formData = new FormData(formElement);
      const config = { headers: { 'Content-Type': 'multipart/form-data' } }; 
      var that = this
      axios.post(this.uploadurl, formData, config)
        .then(function (response) {
          that.$router.push('/admin/allimg')
        })
      /* eslint-enable */
    }
  },
  components: {
  }
}
</script>

