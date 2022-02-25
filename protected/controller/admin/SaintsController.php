<?php
class SaintsController extends BaseController
{
    //圣徒清单
    function actionList()
    {
        $saints_model = new saints_model();
        $admin_model = new admin_model();
        $admin_id = $_SESSION['ADMIN']['USER_ID'];
        $admin = $admin_model->find(array('user_id' => $admin_id));
        
        if(request('step','') == 'search')
        {
           $page = (int)request('page',1);
           $page_size = 20;
           
           $where = " WHERE 1 ";
           $binds = array();
                       
           $kw = request('kw','');
           if($kw != '')
           {
               $where .= ' AND (name LIKE :kw OR email LIKE :kw)';
               $binds[':kw'] = '%'.$kw.'%';
           }
            
           $sql = "SELECT count(*) total FROM {$saints_model->table_name} {$where}";
           $tot = $saints_model->query($sql,$binds);
           $total = $tot[0]['total'];
            
           $pos = ($page-1)*$page_size;
           
           $sql = "SELECT * FROM {$saints_model->table_name} {$where} ORDER BY head_id ASC, id ASC limit {$pos},{$page_size}";
           $saints = $saints_model->query($sql,$binds);
            
           $paging = $saints_model->pager($page, $page_size, 10, $total);
            
           if($saints)
           {
               foreach($saints as &$saint)
               {
                   $head = $saints_model->find(array('id' => $saint['head_id']));
                   if($head)
                       $saint['head_name'] = $head['name'];
                   else
                       $saint['head_name'] = $saint['name'];
               }
           }
           
           if(!empty($saints))
           {
                $results = array(
                    'status' => 'success',
                    'list'   => $saints,
                    'paging' => $paging
                );
           }
           else
           {
                $results = array(
                    'status' => 'nodata',
                    'list'   => array(),
                    'paging' => ''
                );
           }
           echo json_encode($results);
           exit;
        }
        else
        {
            $this->readonly = true;
            if($admin['type'] == ADMIN_SUPER || $admin['user_id'] ==4 || $admin['user_id'] == 15)
                $this->readonly = false;
            
            $this->display("admin/saints/saints_list.html");
        }
        
    }
    
    function actionAddSaints()
    {
        $this->CheckPermission();
        
        $saints_model = new saints_model();

        
        if(request('step') == 'submit')
        {
           $house_head = request('house_head',0);
           $head_id = request('head_id','');
           if($house_head == 0 && $head_id == '')
           {
              $this->prompt('error', '如果成员不是户主，必须选择一个户主', url($this->MOD.'/saints', 'addSaints'));
              exit; 
           }
           $data = array(
               'name' => request('name',''),
               'house_head' => request('house_head',0),
               'email' => request('email',''),
               'phone' => request('phone',''),
               'address' => request('address',''),
               'head_id' => $head_id
           );
            
           $id = $saints_model->create($data);
           if($id && $house_head == 1)
           {
               $saints_model->update(array('id' => $id),array('head_id' => $id));
           }
           $this->readonly = false;
           $this->display('admin/saints/saints_list.html');
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $heads = $saints_model->findAll(array('house_head' => 1));
            if(empty($heads))
                $heads = array();
            $this->heads = $heads;
            $this->display('admin/saints/saints.html');
        }
    }
    
    function actionEditSaints()
    {
        $this->CheckPermission();
        
        $saints_model = new saints_model();

    
        $id = request('id',0);
        
        $saint = $saints_model->find(array('id' => $id));
        if(empty($saint))
        {
           $this->prompt('error', '找不到该圣徒记录', url($this->MOD.'/main', 'index'));
            exit; 
        }
        
        
        if(request('step') == 'submit')
        {
           $house_head = request('house_head',0);
           $head_id = request('head_id','');
           if($house_head == 0 && $head_id == '')
           {
              $this->prompt('error', '如果成员不是户主，必须选择一个户主', url($this->MOD.'/saints', 'list'));
              exit; 
           }
           $data = array(
               'name' => request('name',''),
               'house_head' => request('house_head',0),
               'email' => request('email',''),
               'phone' => request('phone',''),
               'address' => request('address',''),
               'head_id' => $house_head ? $id : $head_id
           );
            
           $saints_model->update(array('id' => $id),$data);
           $this->readonly = false;
           $this->display('admin/saints/saints_list.html');
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $saint = $saints_model->find(array('id' => $id));
            
            $heads = $saints_model->findAll(array('house_head' => 1));
            if(empty($heads))
                $heads = array();
            $this->heads = $heads;
            
            $this->saint = $saint;
            $this->display('admin/saints/saints.html');
        }
    }
    
    function actionNewcomer()
    {
        $this->CheckPermission();
        $new_comers_model = new new_comers_model();
        if(request('step','') == 'search')
        {
           $page = (int)request('page',1);
           $page_size = 20;
            
           $where = " WHERE 1";
           $binds = array();
            
           $kw = request('kw','');
           if($kw != '')
           {
               $where .= ' AND (name LIKE :kw OR email LIKE :kw)';
               $binds[':kw'] = '%'.$kw.'%';
           }
            
           $baptized = request('baptized','');
           if($baptized != '')
           {
               $where .= ' AND baptized=:baptized';
               $binds[':baptized'] = $baptized;
           }
            
           $sql = "SELECT count(*) total FROM {$new_comers_model->table_name} {$where}";
           $tot = $new_comers_model->query($sql,$binds);
           $total = $tot[0]['total'];
            
           $pos = ($page-1)*$page_size;
           
           $sql = "SELECT * FROM {$new_comers_model->table_name} {$where} ORDER BY ts DESC limit {$pos},{$page_size}";
           $new_comers = $new_comers_model->query($sql,$binds);
           if(empty($new_comers))
               $new_comers = array();
         
           foreach($new_comers as &$nc)
           {
               if($nc['baptized'] == 0)
                   $nc['baptized_msg'] = 'No';
               else
                   $nc['baptized_msg'] = 'Yes';
               
               if(empty($nc['email']))
                   $nc['email'] = 'N/A';
               
               switch($nc['contact'])
               {
                   case 3: $nc['contact_msg'] = '不用了';break;
                   case 0: $nc['contact_msg'] = '电话';break;
                   case 1: $nc['contact_msg'] = '电邮';break;
                   case 2: $nc['contact_msg'] = '电话或电邮';break;
                   default:$nc['contact_msg'] = '不用了';break;
               }
           }
            
           $paging = $new_comers_model->pager($page, $page_size, 10, $total);
           
           $results = array(
               'status' => 'success',
               'list'   => $new_comers,
               'paging' => $paging
           );
           echo json_encode($results);
           exit;
        }
        else
        {
            $this->display("admin/saints/newcomer_list.html");
        }
    }
    
    function actionDeleteNewcomer()
    {
        $this->CheckPermission();
        
        $id = (int)request('id',0);
        
        $new_comers_model = new new_comers_model();
        
        $new_comers_model->delete(array('user_id' => $id));
 
        jump(url('admin/saints','newcomer'));
        exit;
    }
    
    function actionEditNewcomer()
    {
        $this->CheckPermission();
        $id = (int)request('id',0);
        $new_comers_model = new new_comers_model();
        if(request('step','') == 'update')
        {
            $name = strip_tags(trim(request('name','N/a')));
            if(strlen($name) > 255)
                $email = substr($name,0,255);
            $phone = strip_tags(request('phone',''));
            $email = strip_tags(trim(request('email','')));
            if(strlen($email) > 255)
                $email = substr($email,0,255);
            $referral = strip_tags(trim(request('referral','')));
            if(strlen($referral)>255)
                $referral = substr($referral,0,255);
            $address = strip_tags(trim(request('address','')));
            if(strlen($address)>255)
                $referral = substr($address,0,255);
            $baptized = (int)request('baptized',0);
            $contact = (int)request('contact',0);
            
            $data = array(
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'referral' => $referral,
                'address' => $address,
                'baptized' => $baptized,
                'contact' => $contact,
            ); 
            
            $new_comers_model->update(array('user_id' => $id),$data); 
            $this->display("admin/saints/newcomer_list.html");
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            if($new_comer = $new_comers_model->find(array('user_id' => $id)))
            {
                   $this->new_comer = $new_comer;
                   $this->display('admin/saints/newcomer.html');
                   exit; 
            }
        }
    }
    
    function actionSendWelcomeApi()
    {
        $ids = request('ids','');
        if($ids == '')
        {
            echo json_encode(array('status'=>'error','msg'=>'无效ID'));
            exit;
        }
        $user_ids = explode('_',$ids);
        $new_comers_model = new new_comers_model();
        if(is_array($user_ids))
        {
            foreach($user_ids as $id)
            {
                if($new_comer = $new_comers_model->find(array('user_id' => $id,'email_sent' => 0)))
                {
                   if(strlen($new_comer['email'])>1)
                   {
                       $mailer = new Mailer($new_comer['email']);
                       $mailer->sendWelcomeEmail($new_comer);
                       $new_comers_model->update(array('user_id' => $id),array('email_sent' => 1));
                   }
                }
            }
        }
        
        echo json_encode(array('status' => 'success','msg' => '成功发送欢迎电邮'));
        exit;
    }
}