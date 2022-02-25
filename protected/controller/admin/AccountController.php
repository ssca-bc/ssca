<?php
class AccountController extends BaseController
{
    public function actionIndex()
    {
        
        $admin_model = new admin_model();
    
        $results = $admin_model->findAll(null,"user_id ASC","*");
        foreach($results as &$admin )
        {
            $admin['type_name'] = $this->g_admin_type[$admin['type']];
        }
        $this->results = $results;
 //       $this->results = $admin_model->findAll(null, 'user_id DESC', '*', array(request('page', 1), 15));

        $this->display('admin/admin_list.html');
    }
    
    public function actionAdd()
    {
        if(request('step') == 'submit')
        {
            $this->CheckFormToken('错误的Token', url('admin'.'/account', 'index'));
            
            $data = array
            (
                'username' => trim(request('username', '')),
                'password' => trim(request('password', '')),
                'repassword' => trim(request('repassword', '')),
                'phone'      => trim(request('phone','')),
                'email' => trim(request('email', '')),
                'name' => trim(request('name', '')),
                'type' => request('type'),
                'created_date' => $_SERVER['REQUEST_TIME'],
            );
            
            $admin_model = new admin_model();
//            $verifier = $admin_model->verifier($data);
            $verifier = TRUE;
            if(TRUE === $verifier)
            {
                $data['password'] = md5e($data['password']);
                $data['hash'] = sha1(uniqid(rand(), TRUE));
                unset($data['repassword']);
                if($user_id = $admin_model->create($data))
                {
                   $this->clear_cache();
                }
                $this->prompt('success', '添加管理员成功', url($this->MOD.'/account', 'index'));
            }
            else
            {
                $this->prompt('error', $verifier);
            }
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $this->display('admin/admin.html');
        }
    }
    
    public function actionEdit()
    {
                
        if(request('step') == 'submit')
        {
            $this->CheckFormToken('错误的Token', url('admin'.'/account', 'index'));
            
            $user_id = request('id');
            $condition = array('user_id' => $user_id);
            $data = array
            (
                'username' => trim(request('username', '')),
                'password' => trim(request('password', '')),
                'repassword' => trim(request('repassword', '')),
                'phone' => trim(request('phone','')),
                'email' => trim(request('email', '')),
                'name' => trim(request('name', '')),
                'group_id' => request('group_id',0),
            );
            
            $rule_slices = array();
            $admin_model = new admin_model();
            $user = $admin_model->find($condition);
            if($user['username'] == $data['username'])
            {
                 $rule_slices['username'] = FALSE;
                 unset($data['username']);
            }
            if(empty($data['password']) && empty($data['repassword']))
            {
                $rule_slices['password'] = $rule_slices['repassword'] = FALSE;
                unset($data['password']);
            }
             
 //           $verifier = $admin_model->verifier($data, $rule_slices);
            $verifier = TRUE;
             if(TRUE === $verifier)
            {
                if(isset($data['password'])) $data['password'] = md5e($data['password']);
                unset($data['repassword']);
                $admin_model->update($condition, $data);
            /***    
                $admin_role_model = new admin_role_model();
                $admin_role_model->delete($condition);
                $role_ids = (array)request('role_ids');
                if(!empty($role_ids))
                {
                    $admin_role_model = new admin_role_model();
                    foreach($role_ids as $v) $admin_role_model->create(array('user_id' => $user_id, 'role_id' => (int)$v));
                }
            ***/
                    
                $this->clear_cache();
                $this->prompt('success', '更新账户成功', url('admin'.'/account', 'index'));        
            }
            else
            {
                $this->prompt('error', $verifier);
            }
        }
        else
        {
            $user_id = (int)request('id', 0);  
            $condition = array('user_id' => $user_id);
            $admin_model = new admin_model();
            $cellgroup_model = new cell_groups_model();
            
            if($rs = $admin_model->find($condition))
            {
                $this->rs = $rs;
                $this->cellgroups = $cellgroup_model->findAll();

                $this->type_name = $this->g_admin_type[$rs['type']];
                $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
                $this->display('admin/admin.html');
            }
            else
            {
                $this->prompt('error', '未找到相应的数据记录');
            }
        }
    }

    public function actionDelete()
    {
        $id = arg('id');
        if($id == 1) $this->prompt('error', '此超级管理员账户不能被删除');

        $admin_model = new admin_model();
        if($admin_model->delete(array('user_id' => $id)) > 0) $this->prompt('success', '删除账户成功', url($this->MOD.'/account', 'index'));
        $this->prompt('error', '删除账户失败'); 
    }
    
    public function actionNameExists()
    {
        $name = arg('name',"");
        $retData = array(
            "exists" => 0,
            "name"   => $name,
        );
        $condition = array("username" => $name);
        $admin_model = new admin_model();
        if($rs = $admin_model->find($condition))
        {
             $retData['exists'] = 1;
        }
        else
        {
             $retData['exists'] = 0;
        }
        echo json_encode( $retData );
    }
    
    //清除缓存
    private function clear_cache()
    {
//        return vcache::instance()->admin_model('indexed_list', null, -1);
    }

}