<!DOCTYPE html>
<html lang="zh-cn">
<head>
	<meta charset="UTF-8">
	<title>修改密码</title>
	<?php include '../head.inc.html'; ?>
	<style type="text/css">
	div .login{
		width: 285px;
		position:relative;
		left:50%;
		margin-left: -150px;
		padding: 15px;
		/*margin-top:20px;*/
	}
	span{
		display: inline-block;
		width: 70px;
	}

	.btn-primary{
		width: 100px;
		margin-top: 5px;

	}
	.text {
		width: 140px;
	}
</style>
</head>
<body>
<?php include '../nav.html.inc.php'; ?>
	<div class="container">
	<h4>当前登录：<?php echo $_SESSION['depname'];?></h4>	<br>
	<?php if(isset($msg)){
		echo "<p>$msg</p>";
		} ?>
	<div class="login">
	<div class="well">
		<form action="?changepwd" method="post">
			<div>
				<span class="left">
					<label for="oldpwd">原密码：</label>
				</span>
				<span>
					<input class="text" type="text" id="oldpwd" name="oldpwd">
				</span>
			</div>
			<div>
				<span>
					<label for="newpwd1" class="left">新密码：</label>
				</span>
				<span>
					<input class="text" type="password" id="newpwd1" name="newpwd1">
				</span>
			</div>
			<div>
				<span>
					<label for="newpwd2" class="left">再次输入：</label>
				</span>
				<span>
					<input class="text" type="password" id="newpwd2" name="newpwd2">
				</span>
			</div>
			<div>
				<input class="btn btn-primary center-block" type="submit" value="确认更改">
			</div>
		</form>
	</div>
		
	</div>	
</div>
<?php include '../foot.inc.html'; ?>
</body>
</html>