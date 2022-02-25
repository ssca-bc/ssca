<?php if(!class_exists("View", false)) exit("no direct access allowed");?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include $_view_obj->compile('admin/lib/meta.html'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/vfb2b.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/main.css" />
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/vfb2b.js"></script>
<script type="text/javascript">
function submitForm(){
  $('#username').dbaFieldChecker({rules:{username:[/^[a-zA-Z][_a-zA-Z0-9]{4,15}$/.test($('#username').val()), '用户名不符合格式要求']}});
  if($('#resetpwd').val() == 1){
    $('#password').dbaFieldChecker({rules:{required:[true, '请设置密码'], password:[true, '密码不符合格式要求']}});
  }
 $('#repassword').dbaFieldChecker({rules:{equal:[$('#password').val(), '两次密码不一致']}});
  $('#name').dbaFieldChecker({rules:{required:[true, '姓名称呼不能为空'], maxlen:[60, '姓名称呼不能超过60个字符']}});
  $('#email').dbaFieldChecker({rules:{required:[true, '电子邮箱不能为空'], email:[true, '无效的电子邮箱地址']}});
  $('#phone').dbaFieldChecker({rules:{required:[true, '联系电话不能为空'], phone:[true, '无效的电话格式']}});
  if($('input[name=type]:checked').length<=0)
  {
    $('#type_err').dbaFieldChecker({rules:{required:[true, '请选择一个账户类型']}});
  }
  $('#max_discount').dbaFieldChecker({rules:{seq:[true,'最大折扣权限必须为0～99之间的整数']}});
  
  $('form').dbaFormChecker();
}
    
function resetForm(){
    $('#username').attr("value","");
    $('#name').attr("value","");
    $('#phone').attr("value","");
    $('#email').attr("value","");
    $('#max_discount').attr("value","0");
}
    
function typechosen(){
    $('#type_err').next('span.dbafielderr').remove();
}

function resetPwd(btn){
  $('.setpwd').removeClass('hide');
  $('#resetpwd').val(1);
  $(btn).parentsUntil('tr').parent().addClass('hide');
}
</script>
</head>
<body>
<?php if ($_GET['a'] == 'add') : ?>
<div class="content">
  <div class="loc"><h2><i class="icon"></i>添加新账户</h2></div>
  <form method="post" action="<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'add', 'step'=>'submit', ));?>">
    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['KEY'], ENT_QUOTES, "UTF-8"); ?>" value="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['VAL'], ENT_QUOTES, "UTF-8"); ?>" />
    <div class="box">
      <div class="module">
        <table class="dataform">
          <tr>
            <th width="110">用户名</th>
            <td><input title="用户名" class="w200 txt" name="username" id="username" type="text" /><p class="c999 mt10">可以包含字母、数字或下划线，须以字母开头，长度为5-16个字符</p></td>
          </tr>
          <tr>
            <th>密码</th>
            <td>
              <input title="密码" class="w200 txt" name="password" id="password" type="password" />
              <input type="hidden" name="resetpwd" id="resetpwd" value="1" />
              <p class="c999 mt10">可以包含字母、数字以及特殊符号，长度为6-32个字符</p>
            </td>
          </tr>
          <tr>
            <th>确认密码</th>
            <td><input class="w200 txt" name="repassword" id="repassword" type="password" /></td>
          </tr>
          <tr>
            <th>姓名称呼</th>
            <td><input class="w200 txt" name="name" id="name" type="text" /></td>
          </tr>
          <tr>
            <th>联系电话</th>
            <td><input class="w200 txt" name="phone" id="phone" type="text" /></td>
          </tr>
          <tr>
            <th>电子邮箱</th>
            <td><input class="w200 txt" name="email" id="email" type="text" /></td>
          </tr>
          <input name="max_discount" id="max_discount" type="hidden" value="25" />
        <!-- 需求更改，不需最大折扣权限这一项
          <tr>
            <th>最大折扣权限</th>
            <td><input class="w200 txt" name="max_discount" id="max_discount" type="text" value="0" />
              <p class="c999 mt10">可以输入1~2位数字，例如：25 代表最大折扣权限为25% OFF</p>
            </td>
          </tr>
        -->
          <tr>
            <th>账户类型</th>
            <td>
                <input type="radio" name="type" value="0" onclick="typechosen()"> 超级管理员
                <input type="radio" name="type" value="1" onclick="typechosen()"> 操作员
                <input type="radio" name="type" value="2" onclick="typechosen()"> 细胞小组长
                <input type="radio" name="type" value="3" onclick="typechosen()"> Usher

                <input type="hidden" id="type_err" />
            </td>
          </tr>
        </table>
      </div>
      <div class="submitbtn">
        <button type="button" class="ubtn btn" onclick="submitForm()">保存并提交</button>
        <button type="reset" class="fbtn btn" onclick="resetForm()">重置表单</button>
      </div>
    </div>
  </form>
</div>
<?php else : ?>
<div class="content">
  <div class="loc"><h2><i class="icon"></i>编辑账户:<font class="ml5">[<?php echo htmlspecialchars($rs['user_id'], ENT_QUOTES, "UTF-8"); ?>]</font></h2></div>
  <form method="post" action="<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'edit', 'id'=>$rs['user_id'], 'step'=>'submit', ));?>">
    <input type="hidden" name="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['KEY'], ENT_QUOTES, "UTF-8"); ?>" value="<?php echo htmlspecialchars($_SESSION['ADMIN_FORM_TOKEN']['VAL'], ENT_QUOTES, "UTF-8"); ?>" />
    <div class="box">
      <div class="module">
        <table class="dataform">
          <tr>
            <th width="110">用户名</th>
            <td><input class="w200 txt" name="username" id="username" type="text" value="<?php echo htmlspecialchars($rs['username'], ENT_QUOTES, "UTF-8"); ?>" />
              <p class="c999 mt10">可以包含字母、数字或下划线，须以字母开头，长度为5-16个字符</p>
            </td>
          </tr>
          <tr>
            <th>重设密码</th>
            <td><button type="button" class="cbtn sm btn" onclick="resetPwd(this)">点击重新设置密码</button>
              <input type="hidden" name="resetpwd" id="resetpwd" value="" />
              <p class="c999 mt10">如需重设密码请点击以上按钮，否则密码保持不变</p>
            </td>
          </tr>
          <tr class="setpwd hide">
            <th>密码</th>
            <td><input class="w200 txt" name="password" id="password" type="password" />
              <p class="c999 mt10">可以包含字母、数字以及特殊符号，长度为6-32个字符</p>
            </td>
          </tr>
          <tr class="setpwd hide">
            <th>确认密码</th>
            <td><input class="w200 txt" name="repassword" id="repassword" type="password" /></td>
          </tr>
          <tr>
            <th>姓名称呼</th>
            <td><input class="w200 txt" name="name" id="name" type="text" value="<?php echo htmlspecialchars($rs['name'], ENT_QUOTES, "UTF-8"); ?>" /></td>
          </tr>
          <tr>
            <th>联系电话</th>
            <td><input class="w200 txt" name="phone" id="phone" type="text" value="<?php echo htmlspecialchars($rs['phone'], ENT_QUOTES, "UTF-8"); ?>" /></td>
          </tr>
          <tr>
            <th>电子邮箱</th>
            <td><input class="w200 txt" name="email" id="email" type="text" value="<?php echo htmlspecialchars($rs['email'], ENT_QUOTES, "UTF-8"); ?>" /></td>
          </tr>
          <input name="max_discount" id="max_discount" type="hidden" value="25" />
        <!-- 需求更改，不需最大折扣权限这一项
          <tr>
            <th>最大折扣权限</th>
            <td><input class="w200 txt" name="max_discount" id="max_discount" type="text" value="0" />
              <p class="c999 mt10">可以输入1~2位数字，例如：25 代表最大折扣权限为25% OFF</p>
            </td>
          </tr>
        -->
          <tr>
            <th>账户类型</th>
            <td><?php echo htmlspecialchars($type_name, ENT_QUOTES, "UTF-8"); ?></td>
          </tr>
          <?php if ($rs['type']==ADMIN_CELLGROUP) : ?>
            <tr>
            <th>细胞小组</th>
            <td>
               <select name="group_id">
                 <option value='0'>未指定细胞小组</option>
                 <?php $_foreach_group_counter = 0; $_foreach_group_total = count($cellgroups);?><?php foreach( $cellgroups as $group ) : ?><?php $_foreach_group_index = $_foreach_group_counter;$_foreach_group_iteration = $_foreach_group_counter + 1;$_foreach_group_first = ($_foreach_group_counter == 0);$_foreach_group_last = ($_foreach_group_counter == $_foreach_group_total - 1);$_foreach_group_counter++;?>
                 <option value="<?php echo htmlspecialchars($group['id'], ENT_QUOTES, "UTF-8"); ?>" <?php if ($rs['group_id'] == $group['id']) : ?>selected<?php endif; ?>><?php echo htmlspecialchars($group['name'], ENT_QUOTES, "UTF-8"); ?></option>
                 <?php endforeach; ?>
               </select>
            </td>
          </tr>
          <?php endif; ?>
          <tr>
            <th>上次登录日期</th>
            <td><p class="pad5 c999"><?php echo date('Y-m-d h:i:s', $rs['last_date']);?></p></td>
          </tr>
          <tr>
            <th>上次登录IP</th>
            <td><p class="pad5 c999"><?php echo htmlspecialchars($rs['last_ip'], ENT_QUOTES, "UTF-8"); ?></p></td>
          </tr>
          <tr>
            <th>创建日期</th>
            <td><p class="pad5 c999"><?php echo date('Y-m-d h:i:s', $rs['created_date']);?></p></td>
          </tr>
        </table>
      </div>
      <div class="submitbtn">
        <button type="button" class="ubtn btn" onclick="submitForm()">保存并更新</button>
        <button type="reset" class="fbtn btn" onclick="resetForm()">重置表单</button>
      </div>
    </div>
  </form>
</div>
<?php endif; ?>
</body>
</html>