<?php if(!class_exists("View", false)) exit("no direct access allowed");?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include $_view_obj->compile('admin/lib/meta.html'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/vfb2b.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/main.css" />
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/vfb2b.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/list.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/dbamsgbox.js"></script>
</head>
<body>
<div class="content">
  <div class="loc"><h2><i class="icon"></i>后台账户列表</h2></div>
  <div class="box">
    <div class="doacts">
      <a class="ae add btn" href="<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'add', ));?>"><i></i><font>添加新账户</font></a>
      <a class="ae btn" onclick="doslvent('<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'edit', ));?>', 'id')"><i class="edit"></i><font>编辑后台账户</font></a>
      <a class="ae btn" onclick="delmarchant('dba-popup');"><i class="remove"></i><font>删除后台账户</font></a>
    </div>
    <div class="module mt5">
      <table class="datalist">
        <tr>
          <th width="50" colspan="2">编号</th>
          <th class="ta-l">用户名</th>
          <th class="ta-l" width="190">姓名称呼</th>
          <th class="ta-l">联系电话</th>
          <th class="ta-l" width="210">电子邮箱</th>
          <th width="130">上次登录日期</th>
          <th width="130">上次登录IP</th>
          <th width="130">账户类型</th>
        </tr>
        <?php $_foreach_v_counter = 0; $_foreach_v_total = count($results);?><?php foreach( $results as $v ) : ?><?php $_foreach_v_index = $_foreach_v_counter;$_foreach_v_iteration = $_foreach_v_counter + 1;$_foreach_v_first = ($_foreach_v_counter == 0);$_foreach_v_last = ($_foreach_v_counter == $_foreach_v_total - 1);$_foreach_v_counter++;?>
        <tr>
          <td width="20"><input name="id" type="checkbox" value="<?php echo htmlspecialchars($v['user_id'], ENT_QUOTES, "UTF-8"); ?>" /></td>
          <td width="30"><?php echo htmlspecialchars($v['user_id'], ENT_QUOTES, "UTF-8"); ?></td>
          <td class="ta-l"><a class="blue" href="<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'edit', 'id'=>$v['user_id'], ));?>"><?php echo htmlspecialchars($v['username'], ENT_QUOTES, "UTF-8"); ?></a></td>
          <td class="ta-l"><?php if (!empty($v['name'])) : ?><?php echo htmlspecialchars($v['name'], ENT_QUOTES, "UTF-8"); ?><?php else : ?><font class="c999">未设置</font><?php endif; ?></td>
          <td class="ta-l"><?php echo htmlspecialchars($v['phone'], ENT_QUOTES, "UTF-8"); ?></td>
          <td class="ta-l"><?php echo htmlspecialchars($v['email'], ENT_QUOTES, "UTF-8"); ?></td>
          <td class="c888"><?php echo date('Y-m-d', $v['last_date']);?></td>
          <td class="c888"><?php echo htmlspecialchars($v['last_ip'], ENT_QUOTES, "UTF-8"); ?></td>
          <td class="c888"><?php echo htmlspecialchars($v['type_name'], ENT_QUOTES, "UTF-8"); ?></td>
        </tr>
        <?php endforeach; ?>
      </table>
    </div>
    <?php if (!empty($pager)) : ?>
    <div class="libom mt5">
        <div class="pg">
            <a href="<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'index', 'page'=>$pager['prev_page'], ));?>" class="prev">上一页</a>
            <?php $_foreach_p_counter = 0; $_foreach_p_total = count($pager['all_pages']);?><?php foreach( $pager['all_pages'] as $p ) : ?><?php $_foreach_p_index = $_foreach_p_counter;$_foreach_p_iteration = $_foreach_p_counter + 1;$_foreach_p_first = ($_foreach_p_counter == 0);$_foreach_p_last = ($_foreach_p_counter == $_foreach_p_total - 1);$_foreach_p_counter++;?>
                <?php if ($p==$pager['current_page']) : ?>
                    <strong><?php echo htmlspecialchars($p, ENT_QUOTES, "UTF-8"); ?></strong>
                <?php else : ?>
                <a href="<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'index', 'page'=>$p, ));?>"><?php echo htmlspecialchars($p, ENT_QUOTES, "UTF-8"); ?></a>
                <?php endif; ?>
            <?php endforeach; ?>
            <a href="<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'index', 'page'=>$pager['next_page'], ));?>" class="nxt">下一页</a>
        </div>
    </div>
    <?php endif; ?>
  </div>
</div>
                    
<!-- warning message box -->
<div class="dba-popup hide" id="dba-popup"> <a class="close" onClick="closePrompt('dba-popup')">×</a>
  <h2 class="c999">删除后台账户提示</h2>
  <table>
          <tr>
              <td><img src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/images/warning.png" style="vertical-align:middle;"/></td>
              <td>警告：删除超级管理员或销售代表账户将会导致与该账户相关联的商户失去关联。删除供货商账户会导致该供货商的产品失去关联。点击 “继续” 将删除选中的后台账户。</td>
          </tr>
  </table>
  <p></p>
  <p><a class="ae btn dba-pop-btm" id="dba-msgbox-continue" onclick="doslvent('<?php echo url(array('m'=>$MOD, 'c'=>'account', 'a'=>'delete', ));?>', 'id')"><i class="remove"></i>继续</a>      <a class="ae btn dba-pop-btm" onclick="closePrompt('dba-popup')"><i class="forbid"></i>取消</a></p>
</div>
<!-- end warning message box -->
</body>
</html>