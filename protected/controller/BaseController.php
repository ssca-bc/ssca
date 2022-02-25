<?php
class BaseController extends Controller{
//	public $layout = "layout.html";
    
	function init(){
//		header("Content-type: text/html; charset=utf-8");
        $this->APP_PATH = $GLOBALS['AppPath'];
        $this->common = array
        (
            'baseurl' => $GLOBALS['http_host'],
            'cssfolder' => $GLOBALS['http_host'].'/i/css/'.$GLOBALS['enabled_theme'],
            'jsfolder' =>  $GLOBALS['http_host'] . '/i/js/'.$GLOBALS['enabled_theme'],
        );
        
        $this->enabled_theme = $GLOBALS['enabled_theme'];
        $this->active_menu = 0;
        if(!isset($_SESSION['LANGUAGE']))
            $_SESSION['LANGUAGE'] = 0;
	}

    function tips($msg, $url){
    	$url = "location.href=\"{$url}\";";
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"{$msg}\");{$url}}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
    }
    function jump($url, $delay = 0){
        echo "<html><head><meta http-equiv='refresh' content='{$delay};url={$url}'></head><body></body></html>";
        exit;
    }
	
	//public static function err404($controller_name, $action_name){
	//	header("HTTP/1.0 404 Not Found");
	//	exit;
	//}
} 