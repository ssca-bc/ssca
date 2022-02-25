<?php
class MainController extends BaseController{
	function actionIndex(){
		$client_ip = get_ip();
        if($cookie = request('ADMIN_STAYED', null, 'cookie'))
        {
            $admin_model = new admin_model();
            if($admin_model->check_stayed($cookie, $client_ip))
            {
                if($_SESSION['ADMIN']['TYPE'] == ADMIN_USHER)
                    jump(url('admin/usher','add'));
                else
                    jump(url('admin/main', 'panel'));
            }
        }
        
        $error_model = new request_error_model();
        $lockout = $error_model->lockout($client_ip); 
        if($lockout == 0)
        {
            $_SESSION['LOGIN_TOKEN'] = array('KEY' => random_chars(5), 'VAL' => random_chars(9, TRUE));
 //           $this->captcha = $error_model->check($client_ip, $GLOBALS['captcha_admin_login']);
            $this->captcha = 0;

        }
        else
        {
            $this->lockout = $lockout;
        }
        $this->display('admin/index.html');
	}

	function actionLogin(){
        $back = url('admin'.'/main', 'index');
        if(empty($_SESSION['LOGIN_TOKEN']) || request($_SESSION['LOGIN_TOKEN']['KEY']) != $_SESSION['LOGIN_TOKEN']['VAL'])
        {
            $this->prompt('error', '非法请求!', $back);
        }
        
        $client_ip = get_ip();
        $error_model = new request_error_model();
        $lockout = $error_model->lockout($client_ip, $_SERVER['REQUEST_TIME']);
        if($lockout > 0)
        {
            $this->prompt('error', "由于您多次输入错误的登录信息，本次登录请求已被拒绝，请于{$lockout}分钟后重新尝试!", $back);
        }
        
        if($error_model->check($client_ip, $GLOBALS['captcha_admin_login']))
        {
            //$_SESSION['CAPTCHA'] was set in lib/captcha.php
            $captcha = strtolower(trim(request('captcha', ''))); 
            if(empty($_SESSION['CAPTCHA']) || $_SESSION['CAPTCHA'] != $captcha)
            {
                unset($_SESSION['CAPTCHA']);
                $this->prompt('error', '你输入的验证码不正确, 请重新尝试!', $back);
            }
        }
        
        $admin_model = new admin_model();
        $username = trim(request('username', '', 'post'));
        $password = request('password', '', 'post'); 
//echo $password;exit;
        if($admin = $admin_model->find(array('username' => $username, 'password' => $password)))
        { 
            if($admin_model->set_logined_info($admin, $client_ip))
            {
                if(request('stay', 0) == 1)
                {
                    $cookie = vencrypt(md5($client_ip.$admin['hash']).$admin['user_id'], TRUE);
                    setcookie('ADMIN_STAYED', $cookie, $_SERVER['REQUEST_TIME'] + 604800, '/');
                }
                if($admin['type'] == ADMIN_USHER)
                    jump(url('admin/usher','add'));
                else
                    jump(url('admin/main', 'panel'));
            }
            else
            {
                $logged_time = date('Y-m-d H:i:s', $admin['last_date']);
                $this->prompt('error', array('该用户已登录系统', "登录IP：{$admin['last_ip']}", "登录时间：{$logged_time}"), $back, 5);
            }
        }
        
        unset($_SESSION['LOGIN_TOKEN']);
        $error_model->incr_err($client_ip);
        $this->prompt('error', '错误的用户名或密码, 请重新尝试！', $back);
		
	}
    
    public function actionLogout()
    {   
        $active_model = new admin_active_model();
        $active_model->delete(array('sess_id' => session_id()));
        unset($_SESSION['ADMIN']);
        setcookie('ADMIN_STAYED', null, $_SERVER['REQUEST_TIME'] - 3600, '/');
        jump(url($this->MOD.'/main', 'index'));
    }
    
    
    public function actionPanel()
    {
        $this->CheckPermission();
        $admin_model = new admin_model();
        $admin = $admin_model->find(array('user_id' => $_SESSION['ADMIN']['USER_ID']));
        $admin['last_ip'] = $_SESSION['ADMIN']['LAST_IP'];
        $admin['last_date'] = $_SESSION['ADMIN']['LAST_DATE'];
        
        $this->admin = $admin;
        switch($admin['type'])
        {
            case ADMIN_SUPER:   //super administrator 
                $this->menus = include(INCL_DIR.DS.'super_menu.php');
                break;
            case ADMIN_OPERATOR:  //Sales Representive
                $this->menus = include(INCL_DIR.DS.'admin_menu.php');
                break;
            case ADMIN_CELLGROUP:
                $this->menus = include(INCL_DIR.DS.'cellgroup_menu.php');
                break;
            
        }
      
        $this->display('admin/panel.html');
    }
    
    
    
    public function actionDashboard()
    {
        //检查当前访问者是否登录 Mar. 8
                      
//        $this->CheckPermission();
                        
        switch(request('step'))
        {
            case 'totals':
            
                $totals = array
                (
                    'order' => $this->get_condition_count('order_model'),
                    'revenue' => $this->get_condition_count('order_model', 'order_status >= 1', 'SUM(order_amount)'),
                    'merchant' => $this->get_condition_count('merchant_model'),
                    'products' => $this->get_condition_count('products_model'),
                    'adv' => 0,
                    'article' => 1,
                );
                echo json_encode($totals);
            
            break;
            
            case 'today':
                
                $today_timestamp = strtotime('today');
                $today = array
                (
                    'order' => $this->get_condition_count('order_model', "created_date >= {$today_timestamp}"),
                    'revenue' => $this->get_condition_count('order_model', "created_date >= {$today_timestamp}", 'SUM(order_amount)'),
                    'merchant' => $this->get_condition_count('merchant_model', "created_date >= {$today_timestamp}"),
                 );
                echo json_encode($today);
            
            break;
            
            case 'pending':
                
                $today_timestamp = strtotime('today');
                $pending = array
                (
                    'order_1' => $this->get_condition_count('order_model', 'order_status = 1'),
                    'order_2' => $this->get_condition_count('order_model', 'order_status = 2'),
                    'order_3' => $this->get_condition_count('order_model', 'order_status = 3'),
                    'order_4' => $this->get_condition_count('order_model', 'order_status = 3'),
                    'order_5' => $this->get_condition_count('order_model', 'order_status = 3'),
                );
                echo json_encode($pending);
            
            break;
            
            case 'sysinfo':
                
                $setting_model = new setting_model();
                $sysinfo = array
                (
                    'vds_version' => "Verydows {$GLOBALS['verydows']['VERSION']} Release {$GLOBALS['verydows']['RELEASE']}",
                    'server_ip' => $_SERVER['SERVER_ADDR'],
                    'server_os' => PHP_OS,
                    'server_soft' => $_SERVER['SERVER_SOFTWARE'],
                    'php_version' => PHP_VERSION,
                    'db_version' => $setting_model->get_db_version(),
                    'db_size' => $setting_model->get_db_size(),
                    'upload_max' => ini_get('file_uploads') ? ini_get('upload_max_filesize') : 'OFF',
                    'upload_size' => $setting_model->get_upload_size(),
                    'timezone' => date_default_timezone_get(),
                    'visitor_stats' => $GLOBALS['cfg']['visitor_stats'] == 1 ? '开启' : '关闭',
                    'rewrite_enable' => $GLOBALS['cfg']['rewrite_enable'] == 1 ? '开启' : '关闭',
                );
                echo json_encode($sysinfo);
            
            break;
            
            default:
                
                $active_model = new admin_active_model();
                $active_model->update_active();
                $this->admin_list = $active_model->get_active_list();
                $this->display('admin/dashboard.html');
        }
    }
    
    public function actionResetpassword()
    {
        $data['password'] = trim(request('new_password', '', 'post'));
        $data['repassword'] = trim(request('repassword', '', 'post'));
        $admin_model = new admin_model();
//        $verifier = $admin_model->verifier($data, array('username' => FALSE, 'email' => FALSE, 'name' => FALSE));
        $verifier = TRUE;
        if(TRUE === $verifier)
        {
            $user_id = $_SESSION['ADMIN']['USER_ID'];
            $old_password = md5e(trim(request('old_password', '', 'post')));
            if($admin_model->find(array('user_id' => $user_id, 'password' => $old_password)))
            {
                $admin_model->update(array('user_id' => $user_id), array('password' => md5e($data['password'])));
                $this->prompt('success', '修改密码成功');
            }
            else
            {
                $this->prompt('error', '原密码不正确，请重试');
            }
        }
        else
        {
            $this->prompt('error', $verifier);
        }
    }
    
    public function actionUnderconstruction()
    {
        $this->display("underconstruction.html");
    }
    
    /**
     * 获取符合条件的相关数据总数
     */
    private function get_condition_count($model_name, $condition = null, $col = null)
    {
        $condition = empty($condition) ? '1' : $condition;
        $col = empty($col) ? 'COUNT(*)' : $col;
        $model = new $model_name();
        $sql = "SELECT {$col} AS count FROM {$model->table_name} WHERE {$condition}";
        $rs = $model->query($sql);
        return $rs[0]['count'];
    }
}