<?php
class CellGroupsController extends BaseController
{
    function actionList()
    {
        $cell_groups_model = new cell_groups_model();
        $members_model = new cellgroup_members_model();
        if(request('step','') == 'search')
        {
           $groups = $cell_groups_model->findAll();
            
           foreach($groups as &$group)
           {
               $sql = "SELECT COUNT(*) total FROM {$members_model->table_name} WHERE group_id={$group['id']}";
               $res = $members_model->query($sql);
               $group['total_members'] = $res[0]['total'];
           }
            
           $results = array(
               'status' => 'success',
               'list'   => $groups,
               'paging' => ''
           );
           echo json_encode($results);
           exit;
        }
        else
        {
            $this->display("admin/cellgroups/cellgroup_list.html");
        }
    }
    
    function actionAddGroup()
    {
        if(request('step') == 'submit')
        {
           $cell_groups_model = new cell_groups_model();
           $admin_model = new admin_model();
           $leader_id = (int)request('leader_id',0);
           $leader = $admin_model->find(array('user_id' => $leader_id));
           $data = array(
               'name' => request('name',''),
               'leader_id' => $leader_id,
               'leader_name' => $leader['name']
           );
            
           $cell_groups_model->create($data);
           $this->display('admin/cellgroups/cellgroup_list.html');
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $admin_model = new admin_model();
            $sql = "SELECT * FROM {$admin_model->table_name} WHERE type=".ADMIN_SUPER." OR type=".ADMIN_CELLGROUP;
            $leaders = $admin_model->query($sql);
            $this->leaders = $leaders;
            $this->display('admin/cellgroups/cellgroup.html');
        }
    }
    
    function actionMembers()
    {
        $this->CheckPermission();
        
        //cell group id
        $id = (int)request('id',0);
        
        $group_model = new cell_groups_model();
        $members_model = new cellgroup_members_model();
        $admin_model = new admin_model();
        $admin_id = $_SESSION['ADMIN']['USER_ID'];
        $admin = $admin_model->find(array('user_id' => $admin_id));
        
        if($id == 0)
            $group = $group_model->find(array('id' => $admin['group_id']));
        else
            $group = $group_model->find(array('id' => $id));
        if(empty($group))
        {
            $this->prompt('error', '没有权限做此操作', url($this->MOD.'/main', 'index'));
            exit;
        }
        
        
        if(request('step','') == "search")
        {
            $page = (int)request('page',1);
            $page_size = 20;
            
            $where = " WHERE 1";
            $binds = array();
            
            $where .= " AND group_id={$group['id']}";
            
            $kw = request('kw','');
            if($kw != '')
            {
               $where .= ' AND (name LIKE :kw OR email LIKE :kw)';
               $binds[':kw'] = '%'.$kw.'%';
            }
            
            $sql = "SELECT count(*) total FROM {$members_model->table_name} {$where}";
            $tot = $members_model->query($sql,$binds);
            $total = $tot[0]['total'];
            
            $pos = ($page-1)*$page_size;
           
            $sql = "SELECT * FROM {$members_model->table_name} {$where} ORDER BY head_id ASC limit {$pos},{$page_size}";
            $members = $members_model->query($sql,$binds);
            
            if(empty($members))
               $members = array();
        
        
            $paging = $members_model->pager($page, $page_size, 10, $total);
           
            $results = array(
               'status' => 'success',
               'list'   => $members,
               'paging' => $paging
            );
            echo json_encode($results);
            exit;
        }
        else
        {
//            $group = $group_model->find(array('leader_id' => $leader_id));
            $this->group = $group;
            if($admin['type'] != ADMIN_SUPER && $admin['group_id'] != $group['id'])
                $this->readonly = true;
            else
                $this->readonly = false;
            $this->display('admin/cellgroups/groupmember_list.html');
        }
    }
    
    function actionAddMember()
    {
        $this->CheckPermission();
        
        $group_model = new cell_groups_model();
        $members_model = new cellgroup_members_model();
        $leader_id = $_SESSION['ADMIN']['USER_ID'];
        
        $group = $group_model->find(array('leader_id' => $leader_id));
        if(empty($group))
        {
            $this->prompt('error', '你不是细胞小组组长，没有权限做此操作', url($this->MOD.'/main', 'index'));
            exit;
        }
        
        if(request('step') == 'submit')
        {
           $house_head = request('house_head',0);
           $head_id = request('head_id','');
           if($house_head == 0 && $head_id == '')
           {
              $this->prompt('error', '如果成员不是户主，必须选择一个户主', url($this->MOD.'/cellgroups', 'addMember'));
              exit; 
           }
           $data = array(
               'name' => request('name',''),
               'group_id' => $group['id'],
               'house_head' => request('house_head',0),
               'email' => request('email',''),
               'phone' => request('phone',''),
               'address' => request('address',''),
               'head_id' => $head_id
           );
            
           $id = $members_model->create($data);
           if($id && $house_head == 1)
           {
               $members_model->update(array('member_id' => $id),array('head_id' => $id));
           }
           $this->group = $group;
           $this->readonly = false;
           $this->display('admin/cellgroups/groupmember_list.html');
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $heads = $members_model->findAll(array('house_head' => 1));
            if(empty($heads))
                $heads = array();
            $this->heads = $heads;
            $this->display('admin/cellgroups/cellgroup_member.html');
        }
    }
    
    function actionEditMember()
    {
        $this->CheckPermission();
        
        $group_model = new cell_groups_model();
        $members_model = new cellgroup_members_model();
        $leader_id = $_SESSION['ADMIN']['USER_ID'];
        
        $member_id = request('id',0);
        
        $member = $members_model->find(array('member_id' => $member_id));
        if(empty($member))
        {
           $this->prompt('error', '找不到该成员', url($this->MOD.'/main', 'index'));
            exit; 
        }
        
        $group = $group_model->find(array('id' => $member['group_id']));
        if(empty($group))
        {
            $this->prompt('error', '细胞小组不存在', url($this->MOD.'/main', 'index'));
            exit;
        }
        
        if(request('step') == 'submit')
        {
           $house_head = request('house_head',0);
           $head_id = request('head_id','');
           if($house_head == 0 && $head_id == '')
           {
              $this->prompt('error', '如果成员不是户主，必须选择一个户主', url($this->MOD.'/cellgroups', 'addMember'));
              exit; 
           }
           $data = array(
               'name' => request('name',''),
               'house_head' => request('house_head',0),
               'email' => request('email',''),
               'phone' => request('phone',''),
               'address' => request('address',''),
               'head_id' => $house_head ? $member_id : $head_id
           );
            
           $members_model->update(array('member_id' => $member_id),$data);
           $this->group = $group;
           $this->readonly = false;
           $this->display('admin/cellgroups/groupmember_list.html');
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $member = $members_model->find(array('member_id' => $member_id));
            
            $heads = $members_model->findAll(array('house_head' => 1));
            if(empty($heads))
                $heads = array();
            $this->heads = $heads;
            
            $this->member = $member;
            $this->display('admin/cellgroups/cellgroup_member.html');
        }
    }
}