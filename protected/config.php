<?php

date_default_timezone_set('America/Vancouver');


$config = array(
	'rewrite' => array(
        'api/captcha'      => 'api/captcha/image',
		'admin/<c>_<a>.html'    => 'admin/<c>/<a>', 
        '<c>/<a>?m=admin'       => 'admin/<c>/<a>',
        '<m>/<c>/<a>'          => '<m>/<c>/<a>',
		'<c>/<a>'          => '<c>/<a>',
        'admin'            => 'admin/main/index',
		'/'                => 'main/index',
	),
    
    'enabled_theme' => 'default',
    'encrypt_key' => 'e5f1021e23b1a641943dff77ce6728a0',
    
    'captcha_admin_login' => '0',
    'admin_mult_ip_login' => '1',
);

$domain = array(
	"localhost" => array( // 调试配置
		'debug' => 1,
		'mysql' => array(

				'MYSQL_HOST' => 'localhost',
				'MYSQL_PORT' => '3306',
				'MYSQL_USER' => 'root',
				'MYSQL_DB'   => 'sscadb20140309',
				'MYSQL_PASS' => '',
				'MYSQL_CHARSET' => 'utf8',

		),
     "AppPath" => "/SSCA",
     "http_host" => 'http://localhost/SSCA',
	),
    "192.168.0.9" => array( // 调试配置
		'debug' => 1,
		'mysql' => array(

				'MYSQL_HOST' => 'localhost',
				'MYSQL_PORT' => '3306',
				'MYSQL_USER' => 'root',
				'MYSQL_DB'   => 'pekopekodb',
				'MYSQL_PASS' => '',
				'MYSQL_CHARSET' => 'utf8',

		),
     "AppPath" => "/carinofood",
	),
	"www.ssca-bc.org" => array( //线上配置
		'debug' => 1,
		'mysql' => array(

				'MYSQL_HOST' => 'localhost',
				'MYSQL_PORT' => '3306',
				'MYSQL_USER' => 'sscadbuser0801',
				'MYSQL_DB'   => 'sscadb20140309',
				'MYSQL_PASS' => 'Ssca20140309',
				'MYSQL_CHARSET' => 'utf8',

		),
     "AppPath" => "",
     "http_host" => 'http://www.ssca-bc.org',
	),
    "ssca-bc.org" => array( //线上配置
		'debug' => 1,
		'mysql' => array(

				'MYSQL_HOST' => 'localhost',
				'MYSQL_PORT' => '3306',
				'MYSQL_USER' => 'sscadbuser0801',
				'MYSQL_DB'   => 'sscadb20140309',
				'MYSQL_PASS' => 'Ssca20140309',
				'MYSQL_CHARSET' => 'utf8',

		),
     "AppPath" => "",
     "http_host" => 'http://ssca-bc.org',
	),
);
// 为了避免开始使用时会不正确配置域名导致程序错误，加入判断
if(empty($domain[$_SERVER["HTTP_HOST"]])) die("配置域名不正确，请确认".$_SERVER["HTTP_HOST"]."的配置是否存在！");

return $domain[$_SERVER["HTTP_HOST"]] + $config;
