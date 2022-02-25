<?php if(!class_exists("View", false)) exit("no direct access allowed");?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php include $_view_obj->compile('admin/lib/meta.html'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/vfb2b.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/main.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/products.css" />
<link rel="stylesheet" type="text/css" href="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/css/admin/poper.css" />
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/vfb2b.js"></script>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/list.js"></script>
<!--<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/admin/products.js"></script>-->
<script type="text/javascript">
$(function(){
    searchRes(1);
    $('#kw').bind('keypress',function(event){
      if(event.keyCode == "13")
          searchRes(1);
  });
});
 
function searchRes(page_id){
  var dataset = {
    kw: $('#kw').val(),
    page: page_id,
  }; 
    
  var url;
  url = "<?php echo url(array('m'=>$MOD, 'c'=>'cellgroups', 'a'=>'list', 'step'=>'search', ));?>";

  $.asynList(url, dataset, function(data){
    if(data.status == 'success'){
      juicer.register('format_date', function(v){return formatTimestamp(v, 'y-m-d<br />h:i:s');});
      $('#rows').append(juicer($('#table-tpl').html(), data));
      $('#rows tr').vdsRowHover();
      $('#rows tr:even').addClass('even');
 //     set_selectAll();
      if(data.paging != null) $('#rows').append(juicer($('#paging-tpl').html(), data));
    }else{
      $('#rows').append("<div class='nors mt5'>未找到相关数据记录...</div>");
    }
  });
}
//翻页
function pageturn(page_id){searchRes(page_id);}
    
function addCellGroup(){
    window.location.href = "<?php echo url(array('m'=>$MOD, 'c'=>'cellgroups', 'a'=>'addGroup', ));?>";
}
    
</script>
</head>
<body>
<div class="content">
  <div class="loc"><h2><i class="icon"></i>细胞小组列表</h2></div>
  <div class="box">
    <?php if ($_SESSION['ADMIN']['TYPE'] == ADMIN_SUPER) : ?>
    <div class="doacts">
      <a class="ae add btn" onclick="addCellGroup()"><i></i><font>添加新细胞小组</font></a>
      <a class="ae btn" onclick="doslvent('<?php echo url(array('m'=>$MOD, 'c'=>'cellgroups', 'a'=>'deleteGroup', ));?>', 'id')"><i class="remove"></i><font>删除</font></a>
    </div>
    <?php endif; ?>
    <div class="module mt5" id="rows"></div>
  </div>
</div>

<script type="text/template" id="table-tpl">
<table class="datalist">
  <tr>
    <th width="60" colspan="2"><input class="list_select_all" name="selectall" type="checkbox"  value="" />编号</th>
    <th width="250">组名</th>
    <th width="100">组长</th>
    <th>成员人数</th>
  </tr>
  {@each list as v}
  <tr>
    <td width="20"><input name="id" type="checkbox" value="${v.id}" /></td>
    <td width="40">${v.id}</td>
    <td>
      <a href="<?php echo url(array('m'=>$MOD, 'c'=>'cellgroups', 'a'=>'members', ));?>&id=${v.id}">
      ${v.name}
      </a>
    </td>
    <td>
      ${v.leader_name}
    </td>
    <td>
      ${v.total_members}
    </td>
  </tr>
  {@/each}
</table>

</script>
<?php /* include file='admin/lib/paging.html'*/?>
<script type="text/javascript" src="<?php echo htmlspecialchars($APP_PATH, ENT_QUOTES, "UTF-8"); ?>/i/js/juicer.js"></script>
</body>
</html>