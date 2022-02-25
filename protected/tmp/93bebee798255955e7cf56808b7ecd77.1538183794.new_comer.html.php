<?php if(!class_exists("View", false)) exit("no direct access allowed");?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta name="description" content="" />
<meta name="robots" content="NOARCHIVE,NOCACHE" />
<meta HTTP-EQUIV="pragma" content="no-cache"> 
<meta HTTP-EQUIV="Cache-Control" content="no-cache, must-revalidate"> 
<meta HTTP-EQUIV="expires" content="0">
<link rel="shortcut icon" type="image/png" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/images/logo.png" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/vfb2b.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/main.css" />
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/vfb2b.js"></script>
<script type="text/javascript">
function submitForm(){
  $('#name').dbaFieldChecker({rules:{required:[true, '姓名不能为空'], maxlen:[60, '姓名不能超过60个字符']}});
  //$('#email').dbaFieldChecker({rules:{required:[true, '电子邮箱不能为空'], email:[true, '无效的电子邮箱地址']}});
  $('#phone').dbaFieldChecker({rules:{required:[true, '电话不能为空'], phone:[true, '无效的电话格式']}});
  if($('input[name=baptized]:checked').length<=0)
  {
    $('#baptized_err').dbaFieldChecker({rules:{required:[true, '请告诉我们您是否已受洗？']}});
  }
  else
  {
    $('#baptized_err').dbaFieldChecker({rules:{required:[false, '请告诉我们您是否已受洗？']}});     
  }
  if($('input[name=contact]:checked').length<=0)
  {
    $('#contact_err').dbaFieldChecker({rules:{required:[true, '您是否愿意我们继续联络您？']}});
  }
  else
  {
    $('#contact_err').dbaFieldChecker({rules:{required:[false, '您是否愿意我们继续联络您？']}}); 
  }
  
  
  $('form').dbaFormChecker();
}

function logout(){
    window.location.href="<?php echo url(array('m'=>$MOD, 'c'=>'main', 'a'=>'logout', ));?>";
}
</script>
</head>
<body>
<?php if ($_GET['a'] == 'add') : ?>
<div class="content">
  <div class="loc ta-c"><h1 style="font-size:16px;"><i class="icon"></i>南素里基督教会欢迎您</h1></div>
  <form method="post" action="<?php echo url(array('m'=>$MOD, 'c'=>'usher', 'a'=>'add', 'step'=>'submit', ));?>">
    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['KEY'], ENT_QUOTES, "UTF-8"); ?>" value="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['VAL'], ENT_QUOTES, "UTF-8"); ?>" />
    <div class="box">
      <div class="module">
        <table class="dataform f16">
          <tr>
            <th class="c000" width="80">姓名<br />Name</th>
            <td><input class="w200 txt" name="name" id="name" type="text" /><p class="c999 mt10">请告诉我们您的姓名</p></td>
          </tr>
          <tr>
            <th class="c000">电话<br />Phone</th>
            <td><input class="w200 txt" name="phone" id="phone" type="text" /><p class="c999 mt10">请告诉我们您的联系电话</p></td>
          </tr>
          <tr>
            <th class="c000">电邮<br />Email</th>
            <td><input class="w200 txt" name="email" id="email" type="text" /><p class="c999 mt10">请告诉我们您的电子邮件地址</p>
            </td>
          </tr>
          <tr>
            <th class="c000">介绍人<br />Referral</th>
            <td><input class="w200 txt" name="referral" id="referral" type="text" /><p class="c999 mt10">请告诉我们谁介绍您来到这里的</p>
            </td>
          </tr>
          <tr>
            <th class="c000">住址<br />Address</th>
            <td><input class="w200 txt" name="address" id="address" type="text" /><p class="c999 mt10">请填写您的通讯地址(Optional)</p>
            </td>
          </tr>
          <tr>
            <th class="c000">受洗?<br />Baptized?</th>
            <td>
                <input type="radio" name="baptized" id="baptized" value="1" />已受洗
                &nbsp;<input type="radio" name="baptized" value="0" />未受洗
                <input type="hidden" id="baptized_err" />
                <p class="c999 mt10">您是否已经受洗归入主耶稣基督的名下</p>
            </td>
          </tr>
          <tr>
            <th class="c000">联系您?<br />Contact?</th>
            <td>
                <input type="radio" name="contact" value="0" /> 电话
                <input type="radio" name="contact" value="1" onclick="typechosen()"> 电邮
                <input type="radio" name="contact" value="2" onclick="typechosen()"> 都可以
                <input type="radio" name="contact" value="3" onclick="typechosen()"> 不用了
                <input type="hidden" id="contact_err" />
                <p class="c999 mt10">您是否愿意我们关于信仰问题继续联络您？</p>
            </td>
          </tr>
        </table>
      </div>
      <div class="submitbtn" style="padding-left:10px;padding-right:10px;">
        <p>我们获取您的资料只为向您更多的介绍主耶稣基督，您若愿意提供以上信息给我们，请点击下面的"提交"按钮。</p>
        <p class="ta-c mt20"><button type="button" class="ubtn btn" onclick="submitForm()">提交</button> <button type="button" class="ubtn btn" onclick="logout()">退出</button></p>
      </div>
    </div>
  </form>
</div>
<?php else : ?>
<div class="content">
  <div class="loc ta-c"><h1 style="font-size:16px;"><i class="icon"></i>南素里基督教会欢迎您</h1></div>
  <form method="post" action="<?php echo url(array('m'=>$MOD, 'c'=>'usher', 'a'=>'edit', 'step'=>'submit', ));?>">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($new_comer['user_id'], ENT_QUOTES, "UTF-8"); ?>" />
    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['KEY'], ENT_QUOTES, "UTF-8"); ?>" value="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['VAL'], ENT_QUOTES, "UTF-8"); ?>" />
    <div class="box">
      <div class="module">
        <table class="dataform f16">
          <tr>
            <th class="c000" width="80">姓名<br />Name</th>
            <td><input class="w200 txt" name="name" id="name" type="text" value="<?php echo htmlspecialchars($new_comer['name'], ENT_QUOTES, "UTF-8"); ?>" /><p class="c999 mt10">请告诉我们您的姓名</p></td>
          </tr>
          <tr>
            <th class="c000">电话<br />Phone</th>
            <td><input class="w200 txt" name="phone" id="phone" type="text" value="<?php echo htmlspecialchars($new_comer['phone'], ENT_QUOTES, "UTF-8"); ?>" /><p class="c999 mt10">请告诉我们您的联系电话</p></td>
          </tr>
          <tr>
            <th class="c000">电邮<br />Email</th>
            <td><input class="w200 txt" name="email" id="email" type="text"  value="<?php echo htmlspecialchars($new_comer['email'], ENT_QUOTES, "UTF-8"); ?>"/><p class="c999 mt10">请告诉我们您的电子邮件地址</p>
            </td>
          </tr>
          <tr>
            <th class="c000">介绍人<br />Referral</th>
            <td><input class="w200 txt" name="referral" id="referral" type="text"  value="<?php echo htmlspecialchars($new_comer['referral'], ENT_QUOTES, "UTF-8"); ?>"/><p class="c999 mt10">请告诉我们谁介绍您来到这里的</p>
            </td>
          </tr>
          <tr>
            <th class="c000">住址<br />Address</th>
            <td><input class="w200 txt" name="address" id="address" type="text"  value="<?php echo htmlspecialchars($new_comer['address'], ENT_QUOTES, "UTF-8"); ?>"/><p class="c999 mt10">请填写您的通讯地址(Optional)</p>
            </td>
          </tr>
          <tr>
            <th class="c000">受洗?<br />Baptized?</th>
            <td>
                <input type="radio" name="baptized" id="baptized" value="1" <?php if ($new_comer['baptized']==1) : ?>checked<?php endif; ?> />已受洗
                &nbsp;<input type="radio" name="baptized" value="0" <?php if ($new_comer['baptized']==0) : ?>checked<?php endif; ?> />未受洗
                <input type="hidden" id="baptized_err" />
                <p class="c999 mt10">您是否已经受洗归入主耶稣基督的名下</p>
            </td>
          </tr>
          <tr>
            <th class="c000">联系您?<br />Contact?</th>
            <td>
                <input type="radio" name="contact" value="1" <?php if ($new_comer['contact']==1) : ?>checked<?php endif; ?> /> 电话
                <input type="radio" name="contact" value="2" <?php if ($new_comer['contact']==2) : ?>checked<?php endif; ?> /> 电邮
                <input type="radio" name="contact" value="3" <?php if ($new_comer['contact']==3) : ?>checked<?php endif; ?> /> 都可以
                <input type="radio" name="contact" value="0" <?php if ($new_comer['contact']==0) : ?>checked<?php endif; ?> /> 不用了
                <input type="hidden" id="contact_err" />
                <p class="c999 mt10">您是否愿意我们关于信仰问题继续联络您？</p>
            </td>
          </tr>
        </table>
      </div>
      <div class="submitbtn" style="padding-left:10px;padding-right:10px;">
        <p>我们获取您的资料只为向您更多的介绍主耶稣基督，您若愿意提供以上信息给我们，请点击下面的"提交修改"按钮。</p>
        <p class="ta-c mt20"><button type="button" class="ubtn btn" onclick="submitForm()">提交修改</button></p>
      </div>
    </div>
  </form>
</div>
<?php endif; ?>
</body>
</html>