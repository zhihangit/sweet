<?php /*a:1:{s:58:"D:\wamp64\www\sweet\application\view\back\login\index.html";i:1595259342;}*/ ?>
<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1">
    <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/bootstrap-clearmin.min.css">
    <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/roboto.css">
    <link rel="stylesheet" type="text/css" href="/sweet/public/back/css/font-awesome.min.css">
    <title>sweet登录</title>
    <style></style>
  </head>
  <body class="cm-login">
    <div class="text-center" style="padding:90px 0 30px 0;background:#fff;border-bottom:1px solid #ddd">
      <img src="/sweet/public/back/images/logo-big.svg" width="300" height="45">
    </div>
    
    <div class="col-sm-6 col-md-4 col-lg-3" style="margin:40px auto; float:none;">
      <form method="post" action="<?php echo url('back.login/dologin'); ?>">
	<div class="col-xs-12">
          <div class="form-group">
	    <div class="input-group">
	      <div class="input-group-addon"><i class="fa fa-fw fa-user"></i></div>
	      <input type="text" name="username"  value="<?php echo htmlentities(app('cookie')->get('user_name')); ?>" class="form-control" placeholder="Username">
	    </div>
          </div>
          <div class="form-group">
	    <div class="input-group">
	      <div class="input-group-addon"><i class="fa fa-fw fa-lock"></i></div>
	      <input type="password" name="userpwd"   value="<?php echo htmlentities(app('cookie')->get('olduser_pwd')); ?>" class="form-control" placeholder="Password">
	    </div>
          </div>
        </div>
	<div class="col-xs-6">
          <div class="checkbox">
              <label>
                  <?php if(app('cookie')->get('remember') == '1'): ?><input type="checkbox" name="remember" value="1" checked> 记住用户名和密码
                  <?php else: ?>  <input type="checkbox" name="remember" value="1" > 记住用户名和密码
                  <?php endif; ?>
             </label>
          </div>
	</div><div class="col-xs-6">
          <button type="submit" class="btn btn-block btn-primary">登录</button>
        </div>
      </form>
    </div>
  </body>
</html>
