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
$(function(){
    $("input[name='house_head']").on('click',function(){
        if($("input[name='house_head']").is(':checked')){
          $('#tr_phone').removeClass('hide');
          $('#tr_email').removeClass('hide');
          $('#tr_address').removeClass('hide');
          $('#tr_head_id').addClass('hide');
        }
        else{
          $('#tr_phone').addClass('hide');
          $('#tr_email').addClass('hide');
          $('#tr_address').addClass('hide');
          $('#tr_head_id').removeClass('hide'); 
        }
    });
});
    
function submitForm(){
  $('#name').dbaFieldChecker({rules:{required:[true, '姓名不能为空'], maxlen:[60, '姓名不能超过60个字符']}});
  if($("input[name='house_head']").is(':checked'))
  {
 //    $('#email').dbaFieldChecker({rules:{required:[true, '电子邮箱不能为空'], email:[true, '无效的电子邮箱地址']}}); 
     $('#phone').dbaFieldChecker({rules:{required:[true, '电话不能为空'], phone:[true, '无效的电话格式']}});
  }
  else
  {
     if($('#tr_head_id').val() == '')
         $("select[name='head_id']").dbaFieldChecker({rules:{required:[true, 'Choose a house head']}});
      else
         $("select[name='head_id']").dbaFieldChecker({rules:{required:[false, 'Choose a house head']}}); 
         
  }
    
  $('form').dbaFormChecker();
}

</script>
</head>
<body>
<?php if ($_GET['a'] == 'addSaints') : ?>
<div class="content">
  <div class="loc"><h1><i class="icon"></i>添加圣徒成员</h1></div>
  <form method="post" action="<?php echo url(array('m'=>$MOD, 'c'=>'saints', 'a'=>'addSaints', 'step'=>'submit', ));?>">
    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['KEY'], ENT_QUOTES, "UTF-8"); ?>" value="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['VAL'], ENT_QUOTES, "UTF-8"); ?>" />
    <div class="box">
      <div class="module">
        <table class="dataform">
          <tr>
            <th width="100">姓名</th>
            <td><input class="w200 txt" name="name" id="name" type="text" /><p class="c999 mt10">Please fill in member's name</p></td>
          </tr>
          <tr>
            <th>户主</th>
            <td>
                <input type="checkbox" name="house_head" id="house_head" value="1" /> 是否户主 ?
                <p class="c999 mt10">如果此圣徒是户主，请打勾</p>
            </td>
          </tr>
          <tr id="tr_head_id">
            <th>选择户主</th>
            <td>
              <select name="head_id">
                <option value=''>从列表中选择一个户主</option>
                <?php $_foreach_head_counter = 0; $_foreach_head_total = count($heads);?><?php foreach( $heads as $head ) : ?><?php $_foreach_head_index = $_foreach_head_counter;$_foreach_head_iteration = $_foreach_head_counter + 1;$_foreach_head_first = ($_foreach_head_counter == 0);$_foreach_head_last = ($_foreach_head_counter == $_foreach_head_total - 1);$_foreach_head_counter++;?>
                  <option value="<?php echo htmlspecialchars($head['id'], ENT_QUOTES, "UTF-8"); ?>"><?php echo htmlspecialchars($head['name'], ENT_QUOTES, "UTF-8"); ?></option>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr id="tr_phone" class="hide">
            <th>电话</th>
            <td><input class="w200 txt" name="phone" id="phone" type="text" /><p class="c999 mt10">请输入圣徒电话</p></td>
          </tr>
          <tr id="tr_email" class="hide">
            <th>电邮</th>
            <td><input class="w200 txt" name="email" id="email" type="text" /><p class="c999 mt10">请输入圣徒电邮地址</p>
            </td>
          </tr>
          <tr id="tr_address" class="hide">
            <th>住址</th>
            <td><input class="w200 txt" name="address" id="address" type="text" /><p class="c999 mt10">请输入圣徒地址</p>
            </td>
          </tr>
        </table>
      </div>
      <div class="submitbtn">
        <p class="mt20"><button type="button" class="ubtn btn" onclick="submitForm()">提交</button></p>
      </div>
    </div>
  </form>
</div>
<?php else : ?>
<div class="content">
  <div class="loc"><h1><i class="icon"></i>编辑圣徒信息</h1></div>
  <form method="post" action="<?php echo url(array('m'=>$MOD, 'c'=>'saints', 'a'=>'editSaints', 'step'=>'submit', ));?>">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($saint['id'], ENT_QUOTES, "UTF-8"); ?>" />
    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['KEY'], ENT_QUOTES, "UTF-8"); ?>" value="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['VAL'], ENT_QUOTES, "UTF-8"); ?>" />
    <div class="box">
      <div class="module">
        <table class="dataform">
          <tr>
            <th width="100">姓名</th>
            <td><input class="w200 txt" name="name" id="name" type="text" value="<?php echo htmlspecialchars($saint['name'], ENT_QUOTES, "UTF-8"); ?>" /><p class="c999 mt10">请输入圣徒姓名</p></td>
          </tr>
          <tr>
            <th>户主</th>
            <td>
                <input type="checkbox" name="house_head" id="house_head" value="1" <?php if ($saint['house_head'] == 1) : ?>checked<?php endif; ?> /> 是否户主 ?
                <p class="c999 mt10">如果此圣徒是户主，请打勾</p>
            </td>
          </tr>
          <tr id="tr_head_id" <?php if ($saint['house_head'] == 1) : ?>class="hide"<?php endif; ?>>
            <th>选择户主</th>
            <td>
              <select name="head_id">
                <?php $_foreach_head_counter = 0; $_foreach_head_total = count($heads);?><?php foreach( $heads as $head ) : ?><?php $_foreach_head_index = $_foreach_head_counter;$_foreach_head_iteration = $_foreach_head_counter + 1;$_foreach_head_first = ($_foreach_head_counter == 0);$_foreach_head_last = ($_foreach_head_counter == $_foreach_head_total - 1);$_foreach_head_counter++;?>
                  <option value="<?php echo htmlspecialchars($head['id'], ENT_QUOTES, "UTF-8"); ?>" <?php if ($saint['head_id'] == $head['id']) : ?>selected<?php endif; ?>><?php echo htmlspecialchars($head['name'], ENT_QUOTES, "UTF-8"); ?></option>
                <?php endforeach; ?>
              </select>
            </td>
          </tr>
          <tr id="tr_phone" <?php if ($saint['house_head'] == 0) : ?>class="hide"<?php endif; ?>>
            <th>电话</th>
            <td><input class="w200 txt" name="phone" id="phone" type="text" value="<?php echo htmlspecialchars($saint['phone'], ENT_QUOTES, "UTF-8"); ?>" /><p class="c999 mt10">请输入圣徒电话</p></td>
          </tr>
          <tr id="tr_email" <?php if ($saint['house_head'] == 0) : ?>class="hide"<?php endif; ?>>
            <th>电邮</th>
            <td><input class="w200 txt" name="email" id="email" type="text" value="<?php echo htmlspecialchars($saint['email'], ENT_QUOTES, "UTF-8"); ?>" /><p class="c999 mt10">请输入圣徒电邮地址</p>
            </td>
          </tr>
          <tr id="tr_address" <?php if ($saint['house_head'] == 0) : ?>class="hide"<?php endif; ?>>
            <th>住址</th>
            <td><input class="w200 txt" name="address" id="address" type="text" value="<?php echo htmlspecialchars($saint['address'], ENT_QUOTES, "UTF-8"); ?>" /><p class="c999 mt10">请输入圣徒地址</p>
            </td>
          </tr>
        </table>
      </div>
      <div class="submitbtn">
        <p class="mt20"><button type="button" class="ubtn btn" onclick="submitForm()">提交修改</button></p>
      </div>
    </div>
  </form>
</div>
<?php endif; ?>
</body>
</html>