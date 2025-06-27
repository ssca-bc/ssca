<?php
class MainController extends BaseController {
	// 首页
	function actionIndex(){
        $children_stories = array(
            array('id' => 1, 'theme' => '美丽的世界'),
            array('id' => 2, 'theme' => '狡猾的蛇'),
            array('id' => 3, 'theme' => '挪亚与彩虹'),
            array('id' => 4, 'theme' => '天火焚城记'),
            array('id' => 5, 'theme' => '以撒的新娘'),
            array('id' => 6, 'theme' => '约瑟的故事'),
            array('id' => 7, 'theme' => '美丽的皇后'),
            array('id' => 8, 'theme' => '好媳妇路得'),
            array('id' => 9, 'theme' => '真假先知'),
            array('id' => 10, 'theme' => '勇敢的小牧童'),
            array('id' => 11, 'theme' => '智慧的国王'),
            array('id' => 12, 'theme' => '元帅洗澡'),
            array('id' => 13, 'theme' => '葡萄园的秘密'),
            array('id' => 14, 'theme' => '约拿奇遇记'),
            array('id' => 15, 'theme' => '信心的勇士'),
            array('id' => 16, 'theme' => '我不是铁笼人'),
            array('id' => 17, 'theme' => '平安夜'),
            array('id' => 18, 'theme' => '小二回家'),
            array('id' => 19, 'theme' => '浪子回头'),
            array('id' => 20, 'theme' => '拉萨路的故事'),
            array('id' => 21, 'theme' => '两块钱'),
            array('id' => 22, 'theme' => '好心人'),
            array('id' => 23, 'theme' => '七十个七次'),
            array('id' => 24, 'theme' => '小庄难忘的一天'),
            array('id' => 26, 'theme' => '王子的婚礼'),
            array('id' => 27, 'theme' => '无知的财主'),
            array('id' => 28, 'theme' => '恩典的便当'),
            array('id' => 29, 'theme' => '撒该最快乐的一天'),
            array('id' => 30, 'theme' => '翠玉花瓶'),
            array('id' => 31, 'theme' => '佳佳的故事'),
            array('id' => 33, 'theme' => '耶稣复活'),
            array('id' => 34, 'theme' => '十字架的故事'),
            array('id' => 35, 'theme' => '天堂门外'),
            array('id' => 36, 'theme' => '快乐天使'),
        );
        
        $this->children_stories = $children_stories;
        $this->display($GLOBALS['enabled_theme'].'/index.html');
	}
    function actionTest(){
        $children_stories = array();
        $this->children_stories = $children_stories;
        $this->display($GLOBALS['enabled_theme'].'/test.html');
    }
    
    function actionSundayMsgApi()
    {
        $sunday_model = new sunday_msg_model();
        $page = (int)request('page',1);
        $page_size = 9;
        
        $sql = "SELECT count(*) total FROM {$sunday_model->table_name} WHERE gospel=0";
        $tot = $sunday_model->query($sql);
        $total = $tot[0]['total'];
        $messages = $sunday_model->findAll(array('gospel' => 0),"date_ts DESC","*",array($page,$page_size));
        
 //       if(is_mobile_device())
        {
            foreach($messages as &$message)
            {
 //               $message['theme'] = mb_substr($message['theme'],0,5,"utf-8")."...";
                $message['date'] = date("y-m-d",$message['date_ts']);
                if(!empty($message['youtube_link']))
                    $message['has_video'] = 1;
                else
                    $message['has_video'] = 0;
            }
        }
        
        $paging = $sunday_model->pager($page, $page_size, 5, $total);
        
        $results = array
        (
            'status' => 'success',
            'list' => $messages,
            'paging' => $paging,
        );
        
        echo json_encode($results);
        exit;
    }
    
    function actionGospelMsgApi()
    {
        $sunday_model = new sunday_msg_model();
        $page = (int)request('page',1);
        $page_size = 9;
        
        $sql = "SELECT count(*) total FROM {$sunday_model->table_name} WHERE gospel=1";
        $tot = $sunday_model->query($sql);
        $total = $tot[0]['total'];
        $messages = $sunday_model->findAll(array('gospel' => 1),"id DESC","*",array($page,$page_size));
        
//        if(is_mobile_device())
        {
            foreach($messages as &$message)
            {
 //               $message['theme'] = mb_substr($message['theme'],0,5,"utf-8")."...";
                $message['date'] = date("y-m-d",$message['date_ts']);
                if(!empty($message['youtube_link']))
                    $message['has_video'] = 1;
                else
                    $message['has_video'] = 0;
            }
        }
        
        $paging = $sunday_model->pager($page, $page_size, 5, $total);
        
        $results = array
        (
            'status' => 'success',
            'list' => $messages,
            'paging' => $paging,
        );
        
        echo json_encode($results);
        exit;
    }
    
    function actionSpecialMsgApi()
    {
        $special_model = new special_msg_model();
        $page = (int)request('page',1);
        $page_size = 9;
        
        $sql = "SELECT count(*) total FROM {$special_model->table_name}";
        $tot = $special_model->query($sql);
        $total = $tot[0]['total'];
        $messages = $special_model->findAll(null,"id DESC","*",array($page,$page_size));
        
//        if(is_mobile_device())
        {
            foreach($messages as &$message)
            {
 //               $message['theme'] = mb_substr($message['theme'],0,5,"utf-8")."...";
                
                $message['date'] = date("y-m-d",$message['date_ts']);
                if(!empty($message['youtube_link']))
                    $message['has_video'] = 1;
                else
                    $message['has_video'] = 0;
            }
        }
        
        $paging = $special_model->pager($page, $page_size, 5, $total);
        
        $results = array
        (
            'status' => 'success',
            'list' => $messages,
            'paging' => $paging,
        );
        
        echo json_encode($results);
        exit;
    }
    
    function actionTransformData()
    {
        $sundaymsg_model = new sundaymsg_model();
        $sunday_msg_model = new sunday_msg_model();
        $old = $sundaymsg_model->findAll(null,"msg_date ASC");
        foreach($old as $msg)
        {
            $data = array(
                'date' => $msg['msg_date'],
                'date_ts' => strtotime($msg['msg_date']),
                'speaker' => $msg['speaker'],
                'theme' => $msg['theme'],
                'gospel' => $msg['gospel'],
                'audio_file' => $msg['audio_file']
            );
            $sunday_msg_model->create($data);
            echo $msg['msg_date']." | ".strtotime($msg['msg_date']);echo "<br />";
            unset($data);
        }
    }
    
    function actionLanguage(){
        if($_SESSION['LANGUAGE'] == 0)
            $_SESSION['LANGUAGE'] = 1;
        else
            $_SESSION['LANGUAGE'] = 0;
        if($_SESSION['LANGUAGE'] == 1)
		  $this->display("home.html");
        else
          $this->display("home_cn.html");
    }
	
	// 接收提交表单
	function actionReceive(){
		// 把提交的数据先dump($_POST)出来看看是良好的习惯。
		
		if(isset($_POST["username"])){
			echo "已经提交了".$_POST["username"];
		}else{
			echo "没有填东东呢";
		}
	}
}