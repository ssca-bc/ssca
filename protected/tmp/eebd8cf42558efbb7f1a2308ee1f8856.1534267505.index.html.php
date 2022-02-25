<?php if(!class_exists("View", false)) exit("no direct access allowed");?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include $_view_obj->compile('admin/lib/meta.html'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/vfb2b.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/login.css" />
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/vfb2b.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/md5.js"></script>
<script type="text/javascript">
var url = window.location.href; 
if (url.indexOf("https") < 0 && !<?php echo htmlspecialchars($is_localhost, ENT_QUOTES, "UTF-8"); ?>) { 
url = url.replace("http:", "https:"); 
window.location.replace(url); 
}
function login(btn){
  $('#username').vfLoginChecker();
  $('#password').vfLoginChecker();
 
  $('form').vfLoginFormChecker({
    beforeSubmit: function(){ 
      $(btn).addClass('disabled').text('正在登陆').prop('disabled', true);
      dbaSetCipher('password', 'SSCABC');
    } 
  });
}

function resetCaptcha(){
  var rand = Math.random();
  var src = "<?php echo url(array('m'=>'api', 'c'=>'captcha', 'a'=>'image', ));?>";
  $('#captcha-img').attr('src', src);	
}
</script>
</head>
<body>
<div class="wrap">
  <div class="banner fl">
    <h1><a href="http://www.ssca-bc.org/">SOUTH SURREY CHRISTIAN ASSEMBLY</a></h1>
    <h2 class="mt10">南素里基督教会</h2>
    <p class="mt8">SSCA BACKEND MANAGEMENT</p>
  </div>
  <?php if (empty($lockout)) : ?>
  <form method="post" action="<?php echo url(array('m'=>'admin', 'c'=>'main', 'a'=>'login', ));?>">
    <input type="password" value="" class="hide" />
    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['LOGIN_TOKEN']['KEY'], ENT_QUOTES, "UTF-8"); ?>" value="<?php echo htmlspecialchars($_SESSION['LOGIN_TOKEN']['VAL'], ENT_QUOTES, "UTF-8"); ?>" />
    <div class="login">
      <h2 class="c666">系统管理登陆</h2>
      <dl class="username mt20">
        <dt><i class="icon"></i></dt>
        <dd><input name="username" id="username" type="text" placeholder="请输入登录名" /></dd>
      </dl>
      <dl class="pwd mt20">
        <dt><i class="icon"></i></dt>
        <dd><input name="password" id="password" type="password" placeholder="请输入密码" autocomplete="off" /></dd>
      </dl>
      <?php if ($captcha == 1) : ?>
      <div class="captcha module mt20 cut">
        <div class="module cut">
          <dl class="fl cut"><dd><input name="captcha" id="captcha" type="text" placeholder="请输入验证码" /></dd></dl>
          <div class="fr"><a onclick="resetCaptcha()">看不清换一张</a></div>
        </div>
        <div class="mt10"><img src="<?php echo url(array('c'=>'api/captcha', 'a'=>'image', ));?>" id="captcha-img" onclick="resetCaptcha()" /></div>
      </div>
      <?php endif; ?>
      <div class="login-btn cut">
        <div class="fl"><a class="btn unslt" onclick="login(this)">登 陆</a></div>
        <div class="fr"><label><input name="stay" type="checkbox" value="1" /><font class="unslt">一周内保持登陆</font></label></div>
      </div>
    </div>
  </form>
  <?php else : ?>
  <div class="login">
    <h2 class="c666">系统管理登陆</h2>
    <div class="lockout pad10">由于您多次输入错误的登录信息，系统将拒绝您的所有登录请求，请于<b><?php echo htmlspecialchars($lockout, ENT_QUOTES, "UTF-8"); ?>分钟</b>后刷新本页面重新尝试登录！</div>
  </div>
  <?php endif; ?>
</div>
</body>
</html>