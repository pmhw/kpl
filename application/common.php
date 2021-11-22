<?php
// 应用公共文件
header("content-type:text/html;charset=utf-8");


//设置到期时间/site?action=SetEdate
function SetEdate($url,$key,$id,$edate){
   $_url = $url.'/site?action=SetEdate'; 
   $p_data = GetKeyData($key);
   $p_data['id'] = $id;
   $p_data['edate'] = $edate;
   
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data; 
}


//创建网站即服务器
function AddSite($url,$key,$webname,$path,$type_id,$type,$version,$port,$ps,$ftp,$ftp_username,$ftp_password,$sql,$codeing,$datauser,$datapassword){
    $_url = $url.'/site?action=AddSite'; 
    $p_data = GetKeyData($key);
    $p_data['webname']=json_encode($webname);
    $p_data['path'] = $path;
    $p_data['type_id'] = $type_id;
    $p_data['type'] = $type;
    $p_data['version'] = $version;
    $p_data['port'] = $port;
    $p_data['ps'] = $ps;
    $p_data['ftp'] = $ftp;
    $p_data['ftp_username'] = $ftp_username;
    $p_data['ftp_password'] = $ftp_password;
    $p_data['sql'] = $sql;
    $p_data['codeing'] = $codeing;
    $p_data['datauser'] = $datauser;
    $p_data['datapassword'] = $datapassword;
    
    $result = HttpPostCookie($_url,$p_data, 60,$url);
    $data = json_decode($result,true);
    return $data;  
}


//更新mysql密码
function get_passwordMysql($url,$key,$id,$name,$password){
   $_url = $url.'/database?action=ResDatabasePassword'; 
   $p_data = GetKeyData($key);
   $p_data['id'] = $id;   
   $p_data['name'] = $name; 
   $p_data['password'] = $password; 
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data;  
}


//获取mysql列表
function get_mysql($url,$key){
   $_url = $url.'/data?action=getData'; 
   $p_data = GetKeyData($key);
   $p_data['tojs'] = 'database.get_list';   
   $p_data['table'] = 'databases'; 
   $p_data['limit'] = 100000; 
   $p_data['p'] = 1; 
   $p_data['search'] = '';
   $p_data['order'] = 'id desc';
   
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data;    
}

//更新FTP密码 ftp?action=SetUserPassword
function get_ftppassword($url,$key,$id,$name,$password){
   $_url = $url.'/ftp?action=SetUserPassword'; 
   $p_data = GetKeyData($key);
   $p_data['id'] = $id;   
   $p_data['ftp_username'] = $name; 
   $p_data['new_password'] = $password; 
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data;  
}

//获取FTP列表

function get_ftplist($url,$key){
   $_url = $url.'/data?action=getData';
   $p_data = GetKeyData($key);
   $p_data['table'] = 'ftps';
   $p_data['limit'] = 100000;
   $p_data['p'] = 1;
   $p_data['search'] = '';
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data;      
}

//保存站点伪静态
function get_add_rewrite($url,$key,$webname,$data){
   $_url = $url.'/files?action=SaveFileBody'; 
   $p_data = GetKeyData($key);
   $p_data['data'] = $data;
   $p_data['path'] = '/www/server/panel/vhost/rewrite/' . $webname . '.conf';
   $p_data['encoding'] = 'utf-8';
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data;   
}

//获取想要修改的伪静态内容
function get_rewrites_config($url,$key,$webname){
   $_url = $url.'/files?action=GetFileBody'; 
   $p_data = GetKeyData($key);
   $p_data['path'] = '/www/server/panel/rewrite/nginx/' . $webname . '.conf';
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data;   
}

//获取当前网站伪静态内容
function get_rewrite_config($url,$key,$webname){
   $_url = $url.'/files?action=GetFileBody'; 
   $p_data = GetKeyData($key);
   $p_data['path'] = '/www/server/panel/vhost/rewrite/' . $webname . '.conf';
   $result = HttpPostCookie($_url,$p_data, 60,$url);
   $data = json_decode($result,true);
   return $data;   
}

//获取伪静态列表
function get_rewrite_list($url,$key,$siteName){
    $_url = $url.'/site?action=GetRewriteList';
    $p_data = GetKeyData($key);
    $p_data['siteName'] = $siteName;
    $result = HttpPostCookie($_url,$p_data, 60,$url);
	$data = json_decode($result,true);
  	return $data;
}

//设置网站运行目录
function add_runDirectory($url,$key,$bt_id,$path){
    $_url = $url.'/site?action=SetSiteRunPath';
    $p_data = GetKeyData($key);
    $p_data['id'] = $bt_id;  
    $p_data['runPath'] = $path;
    $result = HttpPostCookie($_url,$p_data, 60,$url);
	$data = json_decode($result,true);
  	return $data;
}

//获取网站运行目录
function get_run_directory($url,$key,$bt_id,$path){
    $_url = $url.'/site?action=GetDirUserINI';
    $p_data = GetKeyData($key);
    $p_data['id'] = $bt_id;  
    $p_data['path'] = $path;
    $result = HttpPostCookie($_url,$p_data, 60,$url);
	$data = json_decode($result,true);
  	return $data;
}

//取回指定网站的根目录
function get_key($url,$key,$bt_id){
    $_url = $url.'/data?action=getKey&table=sites&key=path';
    $p_data = GetKeyData($key);
    $p_data['id'] = $bt_id;
    $result = HttpPostCookie($_url,$p_data, 60,$url);
	$data = json_decode($result,true);
  	return $data;
}

//添加网站绑定域名
function get_add_yuming($url,$key,$bt_id,$webname,$domain){
    $_url = $url.'/site?action=AddDomain';
    $p_data = GetKeyData($key);
    $p_data['id'] = $bt_id;
    $p_data['webname']=$webname;
    $p_data['domain']=$domain;
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	$data = json_decode($result,true);
  	return $data;    
}


//删除网站绑定的域名site?action=DelDomain
function get_delete_yuming($url,$key,$bt_id,$name,$webname){
    $_url = $url.'/site?action=DelDomain';
    $p_data = GetKeyData($key);
    $p_data['id'] = $bt_id;
    $p_data['webname']=$webname;
    $p_data['domain']=$name;
    $p_data['port'] = 80;
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	$data = json_decode($result,true);
  	return $data; 
}

//获取网站的域名列表 /data?action=getData&table=doma

function get_yuming_list($url,$key,$bt_id){
    $_url = $url.'/data?action=getData&table=domain';
    $p_data = GetKeyData($key);
    $p_data['search'] = $bt_id;
    $p_data['list'] = true;
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	$data = json_decode($result,true);
  	return $data; 
  	
}


//查询网站大小
function get_path_size($url,$key,$name){

    $_url = $url.'/files?action=GetDirSize';
    $p_data = GetKeyData($key);	
    $p_data['p']  = 1;
    $p_data['showRow'] = 100;
    $p_data['path'] = '/www/wwwroot/'.$name;
    $p_data['is_operating'] = true;
  
	//请求面板接口
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	
	//解析JSON数据
	$data = json_decode($result,true);
	
  	return $data; 
    
}


//停用网站
function SiteStop($url,$key,$id,$name){
    $_url = $url.'/site?action=SiteStop';
    $p_data = GetKeyData($key);		//取签名
	$p_data['id'] = $id;
	$p_data['name'] = $name;   
	
	//请求面板接口
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	
	//解析JSON数据
	$data = json_decode($result,true);
	
  	return $data; 
}

//启用网站
function SiteStart($url,$key,$id,$name){
    $_url = $url.'/site?action=SiteStart';
    $p_data = GetKeyData($key);		//取签名
	$p_data['id'] = $id;
	$p_data['name'] = $name;
	
	//请求面板接口
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	
	//解析JSON数据
	$data = json_decode($result,true);
	
  	return $data; 
}

//获取网站列表
function WebList($url,$key,$p = '',$limit = '',$name = ''){
    $_url = $url.'/data?action=getData&table=sites';
    //准备POST数据
	$p_data = GetKeyData($key);		//取签名
	
	$p_data['tojs'] = 'get_site_list';
	
	if($p){
	    $p_data['p'] = $p;
	}
	
	if($limit){
	    $p_data['limit'] = $limit;
	}else{
	    $p_data['limit'] = 100000;//必传
	}
	
	if($name){
	    $p_data['search'] = $name;
	}
	
	//请求面板接口
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	
	//解析JSON数据
	$data = json_decode($result,true);
	
  	return $data;   
}

//获取系统基础统计
function GetSystemTotal($url,$key){
    $_url = $url.'/system?action=GetSystemTotal';
    //准备POST数据
	$p_data = GetKeyData($key);		//取签名
	
	//请求面板接口
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	
	//解析JSON数据
	$data = json_decode($result,true);
  	return $data;   
}

//获取实时状态信息(CPU、内存、网络、负载)
function GetNetWork($url,$key){
    $_url = $url.'/system?action=GetNetWork';
    //准备POST数据
	$p_data = GetKeyData($key);		//取签名
	
	//请求面板接口
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	
	//解析JSON数据
	$data = json_decode($result,true);
  	return $data;
}


//取面板日志	
function GetLogs($url,$key){
	//拼接URL地址
	$_url = $url.'/data?action=getData';
	
	//准备POST数据
	$p_data = GetKeyData($key);		//取签名
	$p_data['table'] = 'logs';
	$p_data['limit'] = 10;
	$p_data['tojs'] = 'test';
	
	//请求面板接口
	$result = HttpPostCookie($_url,$p_data, 60,$url);
	
	//解析JSON数据
	$data = json_decode($result,true);
  	return $data;
}


/**
* 构造带有签名的关联数组
*/
function GetKeyData($key){
  	$now_time = time();
	$p_data = array(
		'request_token'	=>	md5($now_time.''.md5($key)),
		'request_time'	=>	$now_time
	);
	return $p_data;    
}

function info(){
        $info = array(
        'czxt'=>PHP_OS,
        'yxhj'=>$_SERVER["SERVER_SOFTWARE"],
        'phpyxfs'=>php_sapi_name(),
        'scfj'=>ini_get('upload_max_filesize'),
        'jxsj'=>ini_get('max_execution_time').'秒',
        'fwq_time'=>date("Y年n月j日 H:i:s"),
        'bj_time'=>gmdate("Y年n月j日 H:i:s",time()+8*3600),
        'ip'=>$_SERVER['SERVER_NAME'].' [ '.gethostbyname($_SERVER['SERVER_NAME']).' ]',
        'sykj'=>round((disk_free_space(".")/(1024*1024)),2).'M',
        'register_globals'=>get_cfg_var("register_globals")=="1" ? "ON" : "OFF",
        'magic_quotes_gpc'=>(1===get_magic_quotes_gpc())?'YES':'NO',
        'magic_quotes_runtime'=>(1===get_magic_quotes_runtime())?'YES':'NO',
        'zz'=>'qq32579135'
        );
        
        return $info;
}


//配置Config文件
function setconfig($pat, $rep)
{
    if ($pat){
        $pats = '/\'' . $pat . '\'(.*?),/';
        $reps =  "'". $pat. "'". "=>" . "'". $rep ."',";
        $fileurl = APP_PATH . "config.php";
        $string = file_get_contents($fileurl); 
        $string = preg_replace($pats, $reps, $string); 
        file_put_contents($fileurl, $string);
        return true;
    } else {
        return false;
    }
    
}


/**
 * 发起POST请求
 * @param String $url 目标网填，带http://
 * @param Array|String $data 欲提交的数据
 * @return string
 */
function HttpPostCookie($_url, $data, $timeout = 60, $url)
{
	//定义cookie保存位置
    $cookie_file=APP_PATH . '/tpl/' . md5($url) . '.cookie';
    if(!file_exists($cookie_file)){
        $fp = fopen($cookie_file,'w+');
        fclose($fp);
    }
	
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_url);
    curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function posturl($url,$data){
        $data  = json_encode($data);    
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
}


//随机密码 $length => 密码长度
function Random_password($length){
   	// 密码字符集，可任意添加你需要的字符
	$chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

	$password = '';
	for ( $i = 0; $i < $length; $i++ ) {
		// 这里提供两种字符获取方式
		// 第一种是使用 substr 截取$chars中的任意一位字符；
		// 第二种是取字符数组 $chars 的任意元素
		// $password .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		$password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
	} 
	return $password;
}

function xss($data){
    
    if (isset($data)){  

        $str = trim($data);  //清理空格  
        
        $str = strip_tags($str);   //过滤html标签  
        
        $str = htmlspecialchars($str);   //将字符内容转化为html实体  
        
        $str = addslashes($str);  //防止SQL注入
        
        return $str;  

    }  
}

/**
 * 温馨提示：网站已申请著作权，修改前请先咨询作者qq：32579135
*/