<!doctype html>
<html class="no-js">
<head>
	<meta charset="utf-8">
	<title>灵动云商</title>
	<link href="//cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">
	<script src="//cdn.bootcss.com/jquery/1.11.1/jquery.js"></script>
	<script src="//cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.js"></script>
	<style>
		/*分隔线*/
		.line {
			border: none;
			border-top: 1px solid #eee;
			margin: 20px 0;
		}

		footer {
			padding: 10px;
			line-height: 20px;
		}
	</style>
</head>
<body>
	<!-- 导航 -->
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="javascript:;"><img style="margin-top:-5px;" width="32" height="32" src="<?php echo base_url('assets/images/logo48.png');?>" /></a>
	    </div>
	    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
	      <ul class="nav navbar-nav" style="margin-left: 400px;">
	        <li class="active"><a href="javascript:;">首页<span class="sr-only">(current)</span></a></li>
	        <li><a id="service" href="http://www.it577.net">关于我们</a></li>
	      </ul>
	    </div>
	  </div>
	</nav>
	<!-- 巨屏展播 -->
	<div class="jumbotron" style="padding: 100px; height: 800px; background: center center url(<?=base_url('assets/images/banner.jpg') ?>);">
		<h1 style="color: #fff;">灵动云商</h1>
		<p>手机买好货利器</p>
		<p>
			<button type="button" class="btn btn-success btn-lg popover-show" style="width: 256px; height: 60px" title="小程序" data-container="body" data-placement="bottom" data-toggle="popover" data-html="true" data-trigger="hover focus click" data-content="<img src='<?=base_url('assets/images/qrcode.png')?>' />"><span class="glyphicon glyphicon-qrcode" style="margin-right: 5px;"></span> 扫一扫</button>
        </p>
	</div>
	<hr class="line"/>
	<footer>
		<div class="text-center">&copy;2014-2017 灵犀网络 版权所有</div>
	</footer>
	<script>
		$(function () { 
		$("[data-toggle='popover']").popover();
	});
</script>
</script>
</body>
</html>