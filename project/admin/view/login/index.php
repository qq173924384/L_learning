<!DOCTYPE html>
<!--
Beyond Admin - Responsive Admin Dashboard Template build with Twitter Bootstrap 3
Version: 1.0.0
Purchase: http://wrapbootstrap.com
-->

<html">
<!--Head-->
<head>
	<meta charset="utf-8" />
	<title>登录页</title>

	<meta name="description" content="login page" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="shortcut icon" href="/assets/img/favicon.png" type="image/x-icon">

	<!--Basic Styles-->
	<link href="/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link id="bootstrap-rtl-link" href="" rel="stylesheet" />
	<link href="/assets/css/font-awesome.min.css" rel="stylesheet" />

	<!--Beyond styles-->
	<link id="beyond-link" href="/assets/css/beyond.min.css" rel="stylesheet" />
	<link href="/assets/css/demo.min.css" rel="stylesheet" />
	<link href="/assets/css/animate.min.css" rel="stylesheet" />
	<link id="skin-link" href="" rel="stylesheet" type="text/css" />
	<style type="text/css">
		body{
			height: 100vh;
			overflow: hidden;
		}
		body canvas{
			background-color: rgba(0, 116, 162, 0.35);
		}
		div.login-container.animated.fadeInDown{
			position: absolute;
			top: 0;
			bottom: 0;
			left: 0;
			right: 0;
		}
		div.loginbox{
			height: auto !important;
			background-color: rgba(247, 250, 252, 0.8);
		}
		div.logobox.bg-themeprimary img{
			max-width: 100%;
			max-height: 100%;
		}
	</style>
	<!--Skin Script: Place this script in head to load scripts for skins and rtl support-->
	<script src="/assets/js/skins.min.js"></script>
</head>
<!--Head Ends-->
<!--Body-->
<body>
	<div class="login-container animated fadeInDown">
		<div class="loginbox">
			<form method="post" style="text-align: center;">
				<img src="/img/w-logo-blue.png" style="margin: 3%;" />
				<div class="loginbox-title">后台管理员</div>
				<div class="loginbox-textbox">
					<input type="text" name="name" class="form-control" placeholder="用户名" />
				</div>
				<div class="loginbox-textbox">
					<input type="password" name="password" class="form-control" placeholder="密码" />
				</div>
				<div class="loginbox-title">验证码:<?= $check_code; ?> = ?</div>
				<div class="loginbox-textbox">
					<input type="number" name="check_code" class="form-control" placeholder="验证码" />
				</div>
				<div class="loginbox-submit">
					<input type="submit" class="btn btn-primary btn-block" value="登录">
				</div>
			</form>
		</div>
		<div class="logobox bg-themeprimary">
			<img src="/assets/img/logo.png" alt=""/>
		</div>
	</div>

	<!--Basic Scripts-->
	<script src="/assets/js/jquery-2.0.3.min.js"></script>
	<script src="/assets/js/bootstrap.min.js"></script>

	<!--Beyond Scripts-->
	<script src="/assets/js/beyond.min.js"></script>
	<script src="/js/Particleground.js"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$('body').particleground({
				dotColor: 'rgba(0, 116, 162, 0.5)',
				lineColor: 'rgba(0, 116, 162, 0.8)'
			});
		});
	</script>
</body>
<!--Body Ends-->
</html>
