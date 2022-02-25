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
function editInfo(){
  var url = "<?php echo url(array('m'=>$MOD, 'c'=>'usher', 'a'=>'edit', 'id'=>$id, ));?>";
  window.location.href = url;
}
function nextOne(){
  var url = "<?php echo url(array('m'=>$MOD, 'c'=>'usher', 'a'=>'add', ));?>";
  window.location.href = url;  
}
</script>
</head>
<body>
<?php if (!empty($new_comer)) : ?>
<div class="content">
  <div class="loc ta-c"><h1 style="font-size:16px;"><i class="icon"></i>谢谢您提交的信息</h1></div>
    <div class="box">
      <div class="module">
        <table class="dataform">
          <tr>
            <th width="100">姓名/Name</th>
            <td><?php echo htmlspecialchars($new_comer['name'], ENT_QUOTES, "UTF-8"); ?></td>
          </tr>
          <tr>
            <th>电话/Phone</th>
            <td><?php echo htmlspecialchars($new_comer['phone'], ENT_QUOTES, "UTF-8"); ?></td>
          </tr>
          <tr>
            <th>电邮/Email</th>
            <td><?php echo htmlspecialchars($new_comer['email'], ENT_QUOTES, "UTF-8"); ?></td>
          </tr>
          <tr>
            <th>介绍人/Referral</th>
            <td><?php echo htmlspecialchars($new_comer['referral'], ENT_QUOTES, "UTF-8"); ?></td>
          </tr>
          <tr>
            <th>住址/Address</th>
            <td><?php echo htmlspecialchars($new_comer['address'], ENT_QUOTES, "UTF-8"); ?></td>
          </tr>
          <tr>
            <th>受洗/Baptized?</th>
            <td><?php if ($new_comer['baptized'] ==1) : ?>已受洗<?php else : ?>未受洗<?php endif; ?></td>
          </tr>
          <tr>
            <th>联系您/Contact?</th>
            <td><?php echo htmlspecialchars($new_comer['contact_msg'], ENT_QUOTES, "UTF-8"); ?></td>
          </tr>
        </table>
      </div>
      <div class="submitbtn" style="padding-left:10px;padding-right:10px;">
        <p>感谢您提供以上信息给我们，若资料有误，请点击下面的"修改资料"按钮。否则请将设备交还给接待人员。</p>
        <p class="ta-c mt20"><button type="button" class="ubtn btn" onclick="editInfo()">修改资料</button><button type="button" class="ubtn btn" onclick="nextOne()">完成</button></p>
      </div>
    </div>
</div>
<?php else : ?>

<?php endif; ?>
</body>
</html>