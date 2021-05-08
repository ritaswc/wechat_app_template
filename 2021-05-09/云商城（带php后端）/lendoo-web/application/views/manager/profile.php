<!--引入CSS-->
<link rel="stylesheet" href="/bower_components/AdminLTE/plugins/select2/select2.min.css">
<!-- Select2 -->
<script src="/bower_components/AdminLTE/plugins/select2/select2.full.min.js"></script>

<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <ol class="breadcrumb">
      <li><a href="../dashboard/index"><i class="fa fa-dashboard"></i> 首页</a></li>
      <li class="active">修改资料</li>
    </ol>
  </section>
  <!-- Main content -->
  <section class="content">
    <div id="profile" class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">修改资料</h3>
                <div class="box-tools pull-right">
                </div><!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
              <form class="form-horizontal" action="save" method="post" enctype="multipart/form-data">
                <div class="form-group">
                  <label for="title" class="col-sm-2 control-label">旧密码</label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="oldPassword" v-model="oldPassword" placeholder="请输入旧密码">
                  </div>
                </div>
                <div class="form-group">
                  <label for="title" class="col-sm-2 control-label">新密码</label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="newPassword" v-model="newPassword" placeholder="请输入新密码">
                  </div>
                </div>
                <div class="form-group">
                  <label for="title" class="col-sm-2 control-label">重复密码</label>
                  <div class="col-sm-8">
                    <input type="password" class="form-control" id="confirmPassword" v-model="confirmPassword" placeholder="再次请输入新密码">
                  </div>
                </div>
                
              </div>
              <!-- /.box-body -->
              <div class="box-footer">
                <button type="button"  @click="update" class="btn btn-primary">保存</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>
  </section>
  <!-- /.content -->
</div>
<script>
  Vue.http.options.emulateJSON = true;
  $(function () { 
    $('select').select2({
    });
  });
  new Vue({
    el: '#profile',
    data: {
      oldPassword: '',
      newPassword: '',
      confirmPassword: '',
      error_message: ''
    },
    methods: {
      update: function () {
        // 表单验证
        if (this.oldPassword === '') {
          this.$message('请输入旧密码');
          oldPassword.focus();
          return;
        }
        if (this.newPassword === '') {
          this.$message('请输入新密码');
          newPassword.focus();
          return;
        }
        if (this.confirmPassword === '') {
          this.$message('请再次输入新密码');
          confirmPassword.focus();
          return;
        }
        if (this.newPassword !== this.confirmPassword) {
          this.$message('两次密码输入不一致');
          newPassword.focus();
          return;
        }
        // 提交网络，修改密码
        this.$http.post('updatePassword', {
          oldPassword: this.oldPassword,
          newPassword: this.newPassword
        }).then(function (response) {
          this.$message({
            type: response.data.success ? 'success' : 'error',
            message: response.data.message
          });
        });
      }
    }
  });
</script>