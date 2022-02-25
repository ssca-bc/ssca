<?php
class BaseController extends Controller{
    
    public $g_admin_type = array();
    
    public function __construct()
    {
        define("ADMIN_SUPER", 0);
        define("ADMIN_OPERATOR", 1);
        define("ADMIN_CELLGROUP",2);
        define("ADMIN_USHER",3);
        
        $this->g_admin_type[ADMIN_SUPER] = "超级管理员";
        $this->g_admin_type[ADMIN_OPERATOR] = "操作员";
        $this->g_admin_type[ADMIN_CELLGROUP] = "细胞组长";
        $this->g_admin_type[ADMIN_USHER] = "接待服事";
      
        $this->MOD = "admin";
        $this->APP_PATH = $GLOBALS['AppPath'];
        $this->is_localhost = true;
        $this->baseurl = "http://{$_SERVER['SERVER_NAME']}";
        
        $ty = (int)date('Y',time());
        $years = array();
        for($i=2014;$i<=$ty;$i++)
            array_push($years,$i);
        $this->years = $years;
    }
    
       
    //检查当前访问者是否已登录
    public function CheckPermission()
    {
        $back = url('admin'.'/main', 'index');
        $admin_model = new admin_model();
        
        if( !isset($_SESSION['ADMIN']) || !$admin_model->find(array('user_id' => $_SESSION['ADMIN']['USER_ID'])) )
            $this->prompt('error', '您无权访问当前资源! 请先登录。', $back, 5);
    }
    
    public function CheckFormToken($errMsg, $back)
    {
        if(empty($_SESSION['ADMIN_FORM_TOKEN']) || request($_SESSION['ADMIN_FORM_TOKEN']['KEY']) != $_SESSION['ADMIN_FORM_TOKEN']['VAL'])
            $this->prompt('error', $errMsg, $back, 5);
    }
    
    //return true or false
    public function CheckFormTokenFT($tokenKey, $tokenVal)
    {
        if(empty($_SESSION['ADMIN_FORM_TOKEN']) || $tokenKey != $_SESSION['ADMIN_FORM_TOKEN']['KEY']  || $tokenVal != $_SESSION['ADMIN_FORM_TOKEN']['VAL'])
            return false;
        else
            return true;
    }
    
    public static function err404($module, $controller, $action){
	   header("HTTP/1.0 404 Not Found");
	   $controlObj = new Controller;
	   $controlObj->display("404.html");
	   exit;
    }
    
    protected function prompt($type = 'default', $text = '', $redirect = '', $time = 3)
    {
        if(empty($redirect)) $redirect = 'javascript:history.back()';
        $this->rs = array('type' => $type, 'text' => $text, 'redirect' => $redirect, 'time' => $time);
        $this->display('prompt.html');
        exit;
    }
} 