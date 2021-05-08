<template>
   <div>
    <div class="box box-info">
           
            <div class="box-header">
              <h3 class="box-title">
                我的 信息 
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
            
            <div class="box-body pad" id="info_win">
                <a @click="setavatar">
                 <img v-bind:src='userinfo.avatar' alt="User Image" class="img-circle" style="width:100px;height:100px;padding:20px;">
                </a>
                <div class="row" style="padding:20px;">
      
                 <div class="input-group input-group-sm form-group col-xs-4">
                    <span class="input-group-btn">
                      <a class="btn btn-info btn-flat">我的昵称</a>
                    </span>
                    <input type="text" class="form-control" id="nickname" placeholder="" v-bind:val='userinfo.nickname'>
                 </div>
                 <div class="input-group input-group-sm form-group col-xs-4">
                    <span class="input-group-btn">
                      <a class="btn btn-info btn-flat">我的积分</a>
                    </span>
                    <input disabled="" type="text" class="form-control" id="level" placeholder="" v-bind:val='userinfo.level'>
                 </div>

                </div> <!--row-->
                <button class="btn btn-primary" @click=setUserInfo> 确认修改  </button>
            </div> <!-- box-body pad -->

            <!-- /.box-header -->
            <div class="box-body pad" id="upload_win" style="z-index:200; display:none">
            
             <form v-on:submit.prevent="uploadimg"  enctype="multipart/form-data" method="POST" dir="ltr" lang="zh-cn" id="uploadfile">
             <label >建议图像使用：100x100</label>
             <input style="width:100%" id="cke_168_fileInput_input" aria-labelledby="cke_167_label" type="file" name="upload" size="38">
              <div class="box-footer">
                <button class="btn btn-primary" > 上传 </button>
              </div>
             </form>
            </div> <!-- upload_win -->
          <!-- /.box -->

          </div>
          <!-- /.box bos-info -->
   </div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      userinfo: { avatar: '/static/default-img.png' },
      userurl: '/my',
      uploadurl: '/uploader/uploadimage?responseType=json'
    }
  },
  created () {
    this.getUserInfo()
  },
  methods: {
    getUserInfo () {
      var that = this
      axios.get(this.userurl)
        .then(function (response) {
          that.userinfo.nickname = response.data.nickname
          that.userinfo.level = response.data.level
          if (response.data.avatar) {
            that.userinfo.avatar = response.data.avatar
          /* eslint-disable */
          $('#nickname').val(that.userinfo.nickname);
          $('#level').val(that.userinfo.level);
          /* eslint-enable */
          }
        })
    },
    setUserInfo () {
      /* eslint-disable */
      this.userinfo.nickname = $('#nickname').val();
      console.log(this.userinfo)
      axios.post(this.userurl, this.userinfo)
        .then(function (response) {
					alert("修改成功！")
        })
      /* eslint-enable */
    },
    setavatar () {
      /* eslint-disable */
      $('#info_win').hide();
      $('#upload_win').show();
      /* eslint-enable */
    },
    uploadimg () {
      /* eslint-disable */
      var formElement = document.querySelector("#uploadfile");
      var formData = new FormData(formElement);
      const config = { headers: { 'Content-Type': 'multipart/form-data' } }; 
      var that = this
      axios.post(this.uploadurl, formData, config)
        .then(function (response) {
          that.userinfo.avatar = response.data.url
          /* eslint-disable */
          $('#upload_win').hide();
          $('#info_win').show();
          /* eslint-enable */
        })
      /* eslint-enable */
    }
  },
  components: {
  }
}
</script>

