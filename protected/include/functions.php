<?php
/**
 * 获取用户ip地址
 */
function get_ip()
{
    $ip = '0.0.0.0';
    $client  = isset($_SERVER['HTTP_CLIENT_IP']) ? $_SERVER['HTTP_CLIENT_IP'] : null;
    $forward = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
    $remote  = $_SERVER['REMOTE_ADDR'];
    if(filter_var($client, FILTER_VALIDATE_IP)) $ip = $client;
    elseif(filter_var($forward, FILTER_VALIDATE_IP)) $ip = $forward;
    else $ip = $remote;
    return $ip;
}

/**
  * 接收HTTP变量
 */
function request($name, $default = FALSE, $method = 'request')
{
    switch($method)
    {
        case 'get': $value = isset($_GET[$name]) ? $_GET[$name]: FALSE; break;
        case 'post': $value = isset($_POST[$name]) ? $_POST[$name] : FALSE; break;
        case 'cookie': $value = isset($_COOKIE[$name]) ? $_COOKIE[$name] : FALSE; break;
        case 'request': $value = isset($_REQUEST[$name]) ? $_REQUEST[$name] : FALSE; break;
    }
    if(FALSE === $value) return $default;
    return $value;
}

/**
 * 随机字符
 */
function random_chars($length = 20, $is_numeric = FALSE)
{
    $hex = base_convert(md5(microtime().str_shuffle($GLOBALS['encrypt_key'])), 16, $is_numeric ? 10 : 35);
    $hex = $is_numeric ? (str_replace('0', '', $hex).'012340567890') : ($hex.'zZ'.strtoupper($hex));
    $random = '';
    if(!$is_numeric)
    {
        $random = chr(rand(1, 26) + rand(0, 1) * 32 + 64);
        $length --;
    }
    for($i = 0; $i < $length; $i++) $random .= $hex[mt_rand(0, strlen($hex) - 1)];
    return $random;
}

/**
 * 页面跳转
 */
function jump($url, $delay = 0)
{
    echo "<html><head><meta http-equiv='refresh' content='{$delay};url={$url}'></head><body></body></html>";
    exit;
}

/**
 * 数据加密
 */
function vencrypt($val, $timed = FALSE, $key = null)
{
    if(is_string($val) && strlen($val) > 0)
    {
        $key = empty($key) ? sha1($GLOBALS['encrypt_key']) : sha1($key);
        $val = base64_encode($val) . ($timed ? $_SERVER['REQUEST_TIME'] : '');
        $key = str_pad($key, strlen($val), $key, STR_PAD_RIGHT);
        $key_arr = str_split($key);
        $en = '';
        foreach(str_split($val) as $k => $v) $en .= str_pad(ord($v) + ord($key_arr[$k]), 3, 0, STR_PAD_LEFT);
        if(ord($key)%2) $en = strrev($en);
        return $en;
    }
    return FALSE;
}
/**
 * 数据解密
 */
function vdecrypt($val, $expires = FALSE, $key = null)
{
    if(is_string($val) && strlen($val) > 0)
    {
        $key = empty($key) ? sha1($GLOBALS['encrypt_key']) : sha1($key);
        if(ord($key)%2) $val = strrev($val);
        $val_arr = str_split($val, 3);
        $key_arr = str_split(str_pad($key, count($val_arr), $key, STR_PAD_RIGHT));
        $de = '';
        foreach($val_arr as $k => $v) $de .= chr($v - ord($key_arr[$k]));
        if($expires)
        {
            if(((int)substr($de, -10) + (int)$expires) - $_SERVER['REQUEST_TIME'] <= 0) return FALSE;
            $de = substr($de, 0, strlen($de) - 10);
        }
        return base64_decode($de);
    }
    return FALSE;
}

function md5e($string, $salt = 'SSCABC')
{
    return md5($string.$salt);
}

/**
 * 数组分页
 */
function array_paging($data, $page_no = 1, $per_qty = 10, $scope = 10)
{
    $start = ($page_no - 1) * $per_qty;
    $end = $start + $per_qty;
    $count = count($data);
    $results = array('slice' => null, 'pagination' => null);
    if($start < 0 || $count <= $start) return $results;
    if($count <= $end) $results['slice'] = array_slice($data, $start);
    else $results['slice'] = array_slice($data, $start, $end - $start);
    if($count > $per_qty)
    { 
        $total_page = ceil($count / $per_qty);
        $page_no = min(intval(max($page_no, 1)), $count);
        $pagination = array
        (
            'total_count' => $count, 
            'page_size'   => $per_qty,
            'total_page'  => $total_page,
            'first_page'  => 1,
            'prev_page'   => (( 1 == $page_no ) ? 1 : ($page_no - 1)),
            'next_page'   => (( $page_no == $total_page ) ? $total_page : ($page_no + 1)),
            'last_page'   => $total_page,
            'current_page'=> $page_no,
            'all_pages'   => array(),
            'scope'       => $scope,
            'offset'      => ($page_no - 1) * $per_qty,
            'limit'       => $per_qty,
        );
        if($total_page <= $scope) $pagination['all_pages'] = range(1, $total_page);
        else if($page_no <= $scope/2) $pagination['all_pages'] = range(1, $scope);
        else if($page_no <= $total_page - $scope/2 ) $pagination['all_pages'] = range(($page_no + intval($scope/2))- $scope + 1, $page_no + intval($scope/2));
        else $pagination['all_pages'] = range($total_page - $scope + 1, $total_page);
        $results['pagination'] = $pagination;
    }
    return $results;
}

function truncate($string, $length = 30, $replace = '...')
{
    if(mb_strlen($string, 'utf-8') > $length)
    {
        $length -= min($length, strlen($replace));
        return mb_substr($string, 0, $length, 'utf-8') . $replace;
    }
    return $string;
}

function transtime($timestamp, $formatdate = 'Y-m-d')
{
    $distance = $_SERVER['REQUEST_TIME'] - $timestamp;
    if($distance < 300)
    {
        $string = '刚刚';
    }
    elseif($distance < 3600)
    {
        $string = floor($distance / 60).'分钟前';
    }
    elseif($distance < 86400)
    {
        $string = floor($distance / 3600).'小时前';
    }
    elseif($distance < 604800)
    {
        $string = floor($distance / 86400).'天前';
    }
    else
    {
        $string = date($formatdate, $timestamp); 
    }
    return $string;
}

/**
 * Sql过滤
 */
function sql_escape($val)
{ 
    return preg_replace('/select|inert|update|delete|\'|\/\*|\*|\.\.\/|\.\/|union|into|load_file|outfile/i', '', $val);
}

/**
 * 32位系统上处理较大的数字
 * @return string $res
 */
function bigintstr($val)
{
    return ($res = preg_replace('/[^\-\d]*(\-?\d*).*/','$1', $val)) ? $res : '0';
}

function layout_paging($params = array())
{
    if(empty($params['paging'])) return '';
    
    $args = array();
    
    foreach($params as $k => $v)
    {
        if(!in_array($k, array('c','a','paging','class','index'))) $args[$k] = $v;
    }
    
    $index = isset($params['paging']['index']) ? $params['paging']['index'] : 'page';
    
    $html = "<div class=\"{$params['class']}\">";
    $html .= "<span class=\"tot\">共计<b>".$params['paging']['total_count']."</b>项</span>";
    
    if($params['paging']['current_page'] >= $params['paging']['scope'])
    {
        $html .= "<a href=\"".url($params['c'], $params['a'], $args + array($index => $params['paging']['first_page']))."\">首 页</a>";
    }

    if($params['paging']['current_page'] != $params['paging']['first_page'])
    {
		$html .= "<a href=\"".url($params['c'], $params['a'], $args + array($index => $params['paging']['prev_page']))."\">上一页</a>";
	}
    else
    {
        $html .= "<span class=\"disabled\">上一页</span>";
    }
    
    foreach($params['paging']['all_pages'] as $p)
    {
        if($p == $params['paging']['current_page'])
        {
            $html .= "<span class=\"cur\">{$p}</span>";
        }
        else
        {
            if(
                ($params['paging']['current_page'] <  $params['paging']['scope'] && $p <  $params['paging']['scope']) ||
                ($params['paging']['current_page'] > $params['paging']['last_page'] -  $params['paging']['scope'] && $p > $params['paging']['last_page'] -  $params['paging']['scope'] ) ||
                ($p < $params['paging']['current_page'] +  $params['paging']['scope'] && $p > $params['paging']['current_page'] -  $params['paging']['scope'])
            )
            $html .= "<a href=\"".url($params['c'], $params['a'], $args + array($index => $p))."\">{$p}</a>";
        }
    }
    
    if($params['paging']['current_page'] != $params['paging']['last_page'])
    {
        $html .= "<a href=\"".url($params['c'], $params['a'], $args + array($index => $params['paging']['next_page']))."\">下一页</a>";
    }
    else
    {
        $html .= "<span class=\"disabled\">下一页</span>";
    }
    
    if($params['paging']['total_page'] - $params['paging']['current_page'] >= $params['paging']['scope'])
    {
        $html .= "<a href=\"".url($params['c'], $params['a'], $args + array($index => $params['paging']['last_page']))."\">末 页</a>";
    }

    $html .= "<span class=\"pct\">页码 <b>{$params['paging']['current_page']}</b> / {$params['paging']['total_page']}</span>";
    $html .= '</div>';
    return $html;
}

/**
 * 客户端是否是移动设备
 */
function is_mobile_device()
{
    if(!empty($_COOKIE['IS_MOBILE'])) return TRUE;
    
    $agent = $_SERVER['HTTP_USER_AGENT'];
    if(
    preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i', $agent)
    ||
    preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($agent,0,4)))
    {
        setcookie('IS_MOBILE', 1, null, '/');
        return TRUE;
    }
    return FALSE;
}


?>
