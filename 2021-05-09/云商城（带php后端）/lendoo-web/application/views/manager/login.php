<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>灵动云商城后台管理系统</title>
	<!-- Add to homescreen for Safari on iOS -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="white">
	<meta name="apple-mobile-web-app-title" content="灵动云商"/>
	<link rel="apple-touch-icon-precomposed" href="/assets/images/logo180.png">
	<!-- icon for web -->
	<link rel="icon" type="image/png" href="/assets/images/logo180.png">
	<!-- Tell the browser to be responsive to screen width -->
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	<!-- jQuery 2.2.3 -->
	<script src="https://cdn.bootcss.com/jquery/2.2.3/jquery.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<!-- Bootstrap 3.3.6 -->
	<link href="https://cdn.bootcss.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
	<!-- Theme style -->
	<link href="https://cdn.bootcss.com/admin-lte/2.3.11/css/AdminLTE.min.css" rel="stylesheet">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
  folder instead of downloading all of them to reduce the load. -->
  <link href="https://cdn.bootcss.com/admin-lte/2.3.11/css/skins/_all-skins.min.css" rel="stylesheet">
    <!-- 引入 Vue -->
  <script src="https://cdn.bootcss.com/vue/2.2.4/vue.js"></script>
  <!-- 引入组件库 -->
  <script src="https://cdn.bootcss.com/element-ui/1.2.5/index.js"></script>
  <!-- 引入ele.css -->
  <link href="https://cdn.bootcss.com/element-ui/1.2.5/theme-default/index.css" rel="stylesheet">
<script src="https://cdn.bootcss.com/vue-resource/1.3.1/vue-resource.min.js"></script>


  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-offset-3 col-md-6" style="margin-top: 150px;">
					<div id="login" class="box box-info">
						<div class="box-header with-border">
							<h3 class="box-title">用户登录</h3>
						</div>
						<!-- /.box-header -->
						<!-- form start -->
						<form v-loading.body="loading" class="form-horizontal" action="verify" method="post">
							<div class="box-body">
								<div class="form-group">
									<label for="username" class="col-sm-2 control-label">用户名</label>

									<div class="col-sm-10">
										<input type="text" class="form-control" placeholder="请输入用户名" v-model="username" @keyup.13="login">
									</div>
								</div>
								<div class="form-group">
									<label for="password" class="col-sm-2 control-label">密码</label>

									<div class="col-sm-10">
										<input type="password" class="form-control" placeholder="请输入密码" v-model="password" @keyup.13="login">
									</div>
								</div>
							</div>
				        </form>
		            <!-- /.box-body -->
		            <div class="box-footer">
		            	<button type="button" @click="login" class="btn btn-primary">登录</button>
		            </div>
		            <!-- /.box-footer -->
		    </div>
		</div>
	</div>
</div>
<!-- bind click -->
<script type="text/javascript">
	Vue.http.options.emulateJSON = true;
	new Vue({
		el: '#login',
		data: {
			username: 'lendoo',
			password: 'lendoo888',
			loading: false
		},
		methods: {
			login: function() {
				this.loading = true;
				this.$http.post('verify', {username: this.username, password: this.password}).then(response => {
					console.log(response.data);
					if (response.data.success) {
						this.$message({
							type: 'success',
							message: response.data.message, 
							duration: 500,
							onClose: function() {
								window.location.href = '../dashboard/index';
							}
						});
					} else {
						this.$message({
							type: 'error',
							message: response.data.message
						});
						this.loading = false;
					}
				});
			}
		}
	});
</script>
<!-- ./wrapper -->
</body>
</html>
