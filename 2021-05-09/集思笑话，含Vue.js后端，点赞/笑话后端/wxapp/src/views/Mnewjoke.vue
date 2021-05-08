<template>
<div class="page input js_show" style="background-color:#eee">
   <div class="page__bd">

     <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                    <textarea class="weui-textarea" placeholder="这一刻的笑话..." rows="3" id="content"></textarea>
                </div>
            </div>

           <div class="weui-cell">
                <div class="weui-cell__bd">
                    <div class="weui-uploader">
                         选择(图片/视频)
                        <div class="weui-uploader__hd">
                            <select class="weui-select" name="select2" id="select2" @change="selected">
                             <option value="1">图片上传 > </option>
                             <option value="2">视频上传 > </option>
                            </select>
                            <div class="weui-uploader__info">{{done}}/{{total}}</div>
                        </div>
                        <div class="weui-uploader__bd">
                            <ul class="weui-uploader__files" id="uploaderFiles" v-for="m in images">
                                <li class="weui-uploader__file" v-bind:style="'background-image:url('+m+')'"></li>
                            </ul>
                            
                            <ul class="weui-uploader__files" id="uploaderFiles" v-if="video">
                                <li class="weui-uploader__file"><video style="width:77px;height:77px;" v-bind:src='video'></video></li>
                            </ul>
                            <form enctype="multipart/form-data" method="POST" lang="zh-cn" id="uploadfile">
                            <div class="weui-uploader__input-box">
                                <input id="uploaderInput" class="weui-uploader__input" type="file" name="upload" accept="image/*" @change="Onchange">
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

      </div> <!-- weui-cells_form -->

     <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" @click="send" id="showTooltips">确定</a>
     </div>
     <div id="loadingToast" style="display:none;">
        <div class="weui-mask_transparent"></div>
        <div class="weui-toast">
            <i class="weui-loading weui-icon_toast"></i>
            <p class="weui-toast__content">视频上传中</p>
        </div>
    </div>


   </div> <!-- page__bd -->

</div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      images: [],
      video: null,
      total: 0,
      done: 0,
      uploadurl: '/uploader/uploadimage?responseType=json',
      jokeurl: '/api/jokes'
    }
  },
  created () {
  },

  methods: {
    selected () {
      /* eslint-disable */
      var s = $("#select2").val()
      var input1 = $("#uploaderInput")
      /* eslint-enable */
      if (s === '1') {
        input1.attr('accept', 'image/*')
      } else {
        input1.attr('accept', 'video/*')
      }
    },
    Onchange () {
      /* eslint-disable */
      // Don't select another...
      document.getElementById("select2").disabled=true;
      var s = $("#select2").val()
      $("#loadingToast").show()
      var formElement = document.querySelector("#uploadfile");
      var formData = new FormData(formElement);
      const config = { headers: { 'Content-Type': 'multipart/form-data' } };
      var that = this
      this.total += 1
      axios.post(this.uploadurl, formData, config)
        .then(function (response) {
          $("#loadingToast").hide()
          that.done += 1
          if (s === '1') {
            that.images.push(response.data.url)
          } else if(s === '2') {
            that.video = response.data.url
          }
        })
      /* eslint-enable */
    },
    send () {
      /* eslint-disable */
      var content1 = $('#content').val();
      var reg=new RegExp("\n","g")
      content1 = content1.replace(reg,'</p>')
      /* eslint-enable */
      var that = this
      var i
      for (i = 0; i < this.images.length; i++) {
        content1 += '<img src="' + this.images[i] + '" >'
      }
      if (this.video !== null) {
        content1 += '<video style="width:90%;" controls="controls" src="' + this.video + '" ></video>'
      }
      axios.post(this.jokeurl, {
        content: content1
      })
        .then(function (response) {
          that.$router.push('/')
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
