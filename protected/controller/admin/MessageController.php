<?php
class MessageController extends BaseController{
    function actionSunday()
    {
        $sunday_msg_model = new sunday_msg_model();
        if(request('step','') == 'search')
        {
           $page = (int)request('page',1);
           $page_size = 20;
           
           $where = " WHERE 1 AND gospel=0";
           $binds = array();
           $year = request('year','');
           if($year != '')
           {
               $year_start = strtotime($year."-01-01");
               $year_end = strtotime($year."-12-31");
               $where .= " AND date_ts>=:year_start AND date_ts<=:year_end";
               $binds[':year_start'] = $year_start;
               $binds[':year_end'] = $year_end;
           }
            
           $kw = request('kw','');
           if($kw != '')
           {
               $where .= ' AND (theme LIKE :kw OR speaker LIKE :kw)';
               $binds[':kw'] = '%'.$kw.'%';
           }
            
           $sql = "SELECT count(*) total FROM {$sunday_msg_model->table_name} {$where}";
           $tot = $sunday_msg_model->query($sql,$binds);
           $total = $tot[0]['total'];
            
           $pos = ($page-1)*$page_size;
           
           $sql = "SELECT * FROM {$sunday_msg_model->table_name} {$where} ORDER BY date_ts DESC limit {$pos},{$page_size}";
           $messages = $sunday_msg_model->query($sql,$binds);
            
           $paging = $sunday_msg_model->pager($page, $page_size, 10, $total);
           
           $results = array(
               'status' => 'success',
               'list'   => $messages,
               'paging' => $paging
           );
           echo json_encode($results);
           exit;
        }
        else
        {
            $this->title = "主日信息";
            $this->type = 0;   //0 : 主日信息， 1 : 特别聚会
            $this->gospel = 0;
            $this->display("admin/message_list.html");
        }
    }
    
    function actionGospel()
    {
       $sunday_msg_model = new sunday_msg_model();
        if(request('step','') == 'search')
        {
           $page = (int)request('page',1);
           $page_size = 20;
           
           $where = " WHERE 1 AND gospel=1";
           $binds = array();
           $year = request('year','');
           if($year != '')
           {
               $year_start = strtotime($year."-01-01");
               $year_end = strtotime($year."-12-31");
               $where .= " AND date_ts>=:year_start AND date_ts<=:year_end";
               $binds[':year_start'] = $year_start;
               $binds[':year_end'] = $year_end;
           }
            
           $kw = request('kw','');
           if($kw != '')
           {
               $where .= ' AND (theme LIKE :kw OR speaker LIKE :kw)';
               $binds[':kw'] = '%'.$kw.'%';
           }
            
           $sql = "SELECT count(*) total FROM {$sunday_msg_model->table_name} {$where}";
           $tot = $sunday_msg_model->query($sql,$binds);
           $total = $tot[0]['total'];
            
           $pos = ($page-1)*$page_size;
           
           $sql = "SELECT * FROM {$sunday_msg_model->table_name} {$where} ORDER BY date_ts DESC limit {$pos},{$page_size}";
           $messages = $sunday_msg_model->query($sql,$binds);
            
           $paging = $sunday_msg_model->pager($page, $page_size, 10, $total);
           
           $results = array(
               'status' => 'success',
               'list'   => $messages,
               'paging' => $paging
           );
           echo json_encode($results);
           exit;
        }
        else
        {
            $this->title = "福音信息";
            $this->type = 0;   //0 : 主日信息， 1 : 特别聚会
            $this->gospel = 1;
            $this->display("admin/message_list.html");
        } 
    }
    
    function actionSpecial()
    {
        $special_msg_model = new special_msg_model();
        if(request('step','') == 'search')
        {
           $page = (int)request('page',1);
           $page_size = 20;
           
           $where = " WHERE 1 ";
           $binds = array();
           $year = request('year','');
           if($year != '')
           {
               $year_start = strtotime($year."-01-01");
               $year_end = strtotime($year."-12-31");
               $where .= " AND date_ts>=:year_start AND date_ts<=:year_end";
               $binds[':year_start'] = $year_start;
               $binds[':year_end'] = $year_end;
           }
            
           $kw = request('kw','');
           if($kw != '')
           {
               $where .= ' AND (theme LIKE :kw OR speaker LIKE :kw)';
               $binds[':kw'] = '%'.$kw.'%';
           }
            
           $sql = "SELECT count(*) total FROM {$special_msg_model->table_name} {$where}";
           $tot = $special_msg_model->query($sql,$binds);
           $total = $tot[0]['total'];
            
           $pos = ($page-1)*$page_size;
           
           $sql = "SELECT * FROM {$special_msg_model->table_name} {$where} ORDER BY date_ts DESC limit {$pos},{$page_size}";
           $messages = $special_msg_model->query($sql,$binds);
            
           $paging = $special_msg_model->pager($page, $page_size, 10, $total);
           
           $results = array(
               'status' => 'success',
               'list'   => $messages,
               'paging' => $paging
           );
           echo json_encode($results);
           exit;
        }
        else
        {
            $this->title = "特别聚会信息";
            $this->type = 1;   //0 : 主日信息， 1 : 特别聚会
            $this->gospel = 1;
            $this->display("admin/message_list.html");
        } 
    }
    
    function actionAdd()
    {
        $sunday_model = new sunday_msg_model();
        $special_model = new special_msg_model();
        if(request('step','') == 'submit')
        {
            $date = request('date',date("Y-m-d",time()));
            $speaker = trim(request('speaker','N/A'));
            $theme = trim(request('theme','N/A'));
            $gospel = (int)request('gospel',0);
            $special = (int)request('special',0);
            $youtube_link =trim(htmlentities(request('youtube_link','')));
            
            if($special==0)
                $target_dir = "messages/sundaymsg/";
            else
                $target_dir = "messages/specialmsg/";
            
            $mp3_file = str_replace("-","_",$date."_".time().".mp3");
            
            $audio_file = $target_dir . $mp3_file;
            
            
            $data = array(
                'date' => $date,
                'date_ts' => strtotime($date),
                'speaker' => $speaker,
                'theme' => $theme,
                'gospel' => $gospel,
                'audio_file' => $mp3_file,
                'youtube_link' => $youtube_link
            );
            
            if($special == 0)
                $sunday_model->create($data);
            else
                $special_model->create($data);
            
            move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file);
            
            if($special == 0)
            {
                if($gospel == 0)
                    jump(url('admin/message', 'sunday'));
                else
                    jump(url('admin/message', 'gospel'));
            }
            else
                jump(url('admin/message','special'));
            
            exit;
            
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            $this->display('admin/message.html');
        }
    }
    
    function actionEdit()
    {
        $id = (int)request('id',0);
        $type = (int)request('type',0);
        $sunday_msg_model = new sunday_msg_model();
        $special_msg_model = new special_msg_model();
        
        if($type == 0)
            $back = url('admin/message','sunday');
        else
            $back = url('admin/message','special');
        
        if(request('step','') == 'update')
        {
            $id = (int)request('mid',0);
            $date = request('date',date("Y-m-d",time()));
            $speaker = trim(request('speaker','N/A'));
            $theme = trim(request('theme','N/A'));
            $gospel = (int)request('gospel',0);
            $special = (int)request('special',0);
            $youtube_link =trim(htmlentities(request('youtube_link','')));
            
            if($special==0)
                $target_dir = "messages/sundaymsg/";
            else
                $target_dir = "messages/specialmsg/";
            
            $mp3_file = str_replace("-","_",$date."_".time().".mp3");
            
            $audio_file = $target_dir . $mp3_file;
            
            $data = array(
                'date' => $date,
                'date_ts' => strtotime($date),
                'speaker' => $speaker,
                'theme' => $theme,
                'gospel' => $gospel,
                'youtube_link' => $youtube_link
            );
          
            if(isset($_FILES['audio_file']) && !empty($_FILES['audio_file']['tmp_name']))
            {
                $data['audio_file'] = $mp3_file;
            }
            
            if($special == 0)
                $sunday_msg_model->update(array('id' => $id), $data);
            else
                $special_msg_model->update(array('id' => $id), $data);
            
            if(isset($_FILES['audio_file']) && !empty($_FILES['audio_file']['tmp_name']))
                move_uploaded_file($_FILES["audio_file"]["tmp_name"], $audio_file);
            
            if($special == 0)
            {
                if($gospel == 0)
                    jump(url('admin/message', 'sunday'));
                else
                    jump(url('admin/message', 'gospel'));
            }
            else
                jump(url('admin/message','special'));
            
            exit;
        }
        else
        {
            $_SESSION['ADMIN_FORM_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(16, TRUE));
            
            if($type == 0)  //主日信息
            {
                if($message = $sunday_msg_model->find(array('id' => $id)))
                {
                   $message['type'] = $type;
                   $this->rs = $message;
                   $this->display('admin/message.html');
                   exit;
                }
                else
                {
                    $this->prompt('error', '找不到讲道信息, 请重新尝试！', $back);
                    exit;
                }
                
            }
            else
            {
                if($message = $special_msg_model->find(array('id' => $id)))
                {
                   $message['type'] = $type;
                   $this->rs = $message;
                   $this->display('admin/message.html');
                   exit; 
                }
                else
                {
                    $this->prompt('error', '找不到特别聚会信息, 请重新尝试！', $back);
                    exit;
                }
            }
        }
    }
    
    function actionDelete()
    {
        $this->CheckPermission();
        
        $id = (int)request('id',0);
        $type = (int)request('type',0);
        
        $sunday_model = new sunday_msg_model();
        $special_model = new special_msg_model();
        
        if($type == 0)
            $sunday_model->delete(array('id' => $id));
        else
            $special_model->delete(array('id' => $id));
        
        if($type == 0)
            jump(url('admin/message', 'sunday'));
        else
            jump(url('admin/message','special'));
        exit;
    }
}