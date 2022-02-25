$(function(){
  $('.box table tr:even').addClass('even');
  $('.box table tr').vdsRowHover();
});

function doslvent(uri, chosen, uk){
  chosen = chosen || 'id[]'; 
  var size = $('input[name="'+chosen+'"]:checked').length; 
  if(size < 1){ 
    $('body').vdsAlert({msg:"请在列表中选择一项."});
  }else if(size > 1){
    $('body').vdsAlert({msg:'只能同时从列表中选择一项.'});
  }else{
    uk = uk || 'id';
    window.location.href= uri+"&"+uk+"="+$('input[name="'+chosen+'"]:checked').eq(0).val();
  }
}

function domulent(action, form, chosen){
  form = form || 'mulentform';
  chosen = chosen || 'id[]';
  if($('input[name="'+chosen+'"]:checked').length > 0){
    $('#'+form).attr('action', action).submit();
  }
  else{
    $('body').vdsAlert({msg:'至少从列表中选择一项'});
  }
}


function delmarchant(popupid){
    var size = $('input[name="id"]:checked').size();
  if(size < 1){ 
    $('body').vdsAlert({msg:"请在列表中选择一项."});
  }else if(size > 1){
    $('body').vdsAlert({msg:'只能同时从列表中选择一项.'});
  }else{
      dbaMsgbox(popupid);
  }
}

function set_selectAll(){
    $("input.list_select_all").click(function(){
        var items;
        if($('input[name="id[]"]').size() > 0)
            items = $('input[name="id[]"]');
        else
            items = $('input[name="id"]');
        if(this.checked)
            items.prop('checked',true);
        else
            items.removeAttr('checked');
    });
}