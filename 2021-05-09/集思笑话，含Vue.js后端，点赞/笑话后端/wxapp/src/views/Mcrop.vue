<template>
<div class="page input js_show" style="background-color:#eee">
   <div class="page__bd">

     <div class="weui-cells weui-cells_form">
            <div class="weui-cell">
                <div class="weui-cell__bd">
                   换一个头像
                </div>
            </div>

           <img v-bind:src="userinfo.avatar" id="preview" style="width:100%; padding:20px;">
           <div class="weui-cell">
                <div class="weui-cell__bd">
                    <div class="weui-uploader">
                        <div class="weui-uploader__hd">
                        </div>
                        <div class="weui-uploader__bd">
                            <form enctype="multipart/form-data" method="POST" lang="zh-cn" id="uploadfile">
                            <div class="weui-uploader__input-box">
                                <input id="uploaderInput" class="weui-uploader__input" type="file" name="upload" accept="image/*" @change="Onchange(this)">
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div> <!-- weui-cell -->

      </div> <!-- weui-cells_form -->

     <div class="weui-btn-area">
            <a class="weui-btn weui-btn_primary" @click="send" id="showTooltips">确定</a>
     </div>


   </div> <!-- page__bd -->

</div>
</template>

<script>
import axios from 'axios'

export default {
  data () {
    return {
      userurl: '/api/my',
      userinfo: {avatar: '/static/default-img.png'},
      x: 0,
      y: 0,
      x2: 0,
      y2: 0,
      clip: 0,
      loadfile: 0,
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
          that.userinfo.avatar = response.data.avatar
          if (response.data.avatar) {
            that.userinfo.avatar = response.data.avatar
          /* eslint-disable */
          $('#preview').src = that.userinfo.avatar;
          /* eslint-enable */
          }
        })
    },
    setUserInfo () {
      /* eslint-disable */
      var that = this
      axios.post(this.userurl, this.userinfo)
        .then(function (response) {
          alert("修改成功！")
          that.$router.push("/m/my")
        })
      /* eslint-enable */
    },
    Onchange (f) {
      /* eslint-disable */
      var target = f.target || window.event.srcElement
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#preview').attr('src', e.target.result);
      }
      var that = this
      reader.readAsDataURL(target.files[0]);
      /* $(function(){ $('#preview').Jcrop({
        onSelect: that.showCoords,
        onChange: that.showCoords
      }) }); */
      this.loadfile = 1
      /* eslint-enable */
    },
    showCoords (c) {
      console.log(c)
      this.x = c.x
      this.y = c.y
      this.x2 = c.x2
      this.y2 = c.y2
      this.clip = 1
    },
    send () {
      /* eslint-disable */
      // Don't select another...
      var formElement = document.querySelector("#uploadfile");
      var formData = new FormData(formElement);
      const config = { headers: { 'Content-Type': 'multipart/form-data' } };
      var that = this
      if (this.loadfile == 0) {
          return that.$router.push('/m/my')
      }
      var param = ''
      var w = $('#preview').width()
      if (this.clip == 1){
        param = '&clip=1&x=' + this.x + '&y=' + this.y + '&x2=' + this.x2 + '&y2=' + this.y2 + '&w=' + w
      }
      axios.post(this.uploadurl + param, formData, config)
        .then(function (response) {
          that.userinfo.avatar = response.data.url
          that.setUserInfo ()
        })
      /* eslint-enable */
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
