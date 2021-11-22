<?php
    define('APP_PATH', __DIR__ . '/../../application/');
    define('SQL_P', __DIR__ . '/../../public/install/');

    if (file_exists('install.lock')) {
      header('Location: ' . $_SERVER['SERVER_NAME']);
      die;
    }
 
   //获取数据库连接信息
  $dbdizhi = $_POST['dizhi'];//数据库地址 127.0.0.1
  $dbname1 = $_POST['name1'];
  $dbname2 = $_POST['name2'];
  $dbpassword = $_POST['password'];
  $dbqianzhui = $_POST['qianzhui'];
  $duankou = $_POST['duankou'];
  $adminName = $_POST['adminName'];
  $adminPassword = $_POST['adminPassword'];
  
  if(!$dbdizhi){
      exit(json_encode(['code'=>1,'msg'=>'MySQL数据库地址是必填项']));
  }
  if(!$dbname1){
     exit(json_encode(['code'=>1,'msg'=>'MySQL数据库名称是必填项'])); 
  }
  if(!$dbname2){
     exit(json_encode(['code'=>1,'msg'=>'MySQL用户名是必填项'])); 
  }
  if(!$dbpassword){
     exit(json_encode(['code'=>1,'msg'=>'MySQL密码是必填项'])); 
  }
  if(!$dbqianzhui){
     exit(json_encode(['code'=>1,'msg'=>'数据表前缀是必填项'])); 
  }

  //获取php版本
  if (version_compare(PHP_VERSION, '5.6') < 0) {
    exit(json_encode(['code'=>1,'msg'=>'PHP版本过低，需要php5.6以上版本']));
  }

  
  // 数据库配置文件读写
  $database_file = APP_PATH . '/database.php';
  //var_dump($database_file);
  $default_database_file = APP_PATH . '/database_default.php';
  
  
  if (!is_readable($default_database_file) && !is_writeable($database_file)) {
    exit(json_encode(['code'=>1,'msg'=>'数据库配置文件不可读写，请设置 application/database.php 读写权限 | 755！']));
  }
  // sql文件是否可读
  if (!is_readable( SQL_P . '/dpp.sql')) {
    exit(json_encode(['code'=>1,'msg'=>'数据库文件不可读，请检查 /public/install/dpp.sql 的读写权限！！']));
  }
  // lock写入权限
  if (!is_readable(dirname(__FILE__))) {

    exit(json_encode(['code'=>1,'msg'=>'install.lock文件不可写，请检查/public/install的读写权限！']));
  }
  
 

  $res = create_db($dbname1, $dbname2, $dbpassword, $duankou, $dbqianzhui);
  if ($res['error'] == 1) {
    exit(json_encode(['code'=>1,'msg'=>"'" . $res['msg'] . "'"]));
  }
  exit(json_encode(['code'=>0,'msg'=>'安装成功']));
 
 
/**
 * 创建数据库并创建表
 */
function create_db($dbname1, $dbname2, $dbpassword, $duankou, $dbqianzhui) {
  $res = [
    'error' => 1,
    'msg' => '不可预期错误！请联系开发者'
  ];
  // 连接数据库
  $conn = @mysqli_connect($duankou, $dbname2, $dbpassword);
  if (!$conn) {
    $res = [
      'code' => 1,
      'msg' => '数据库连接错误，请核对数据库连接信息!'
    ];
    exit(json_encode($res));
  } 
  
  // 读取sql文件
  if (!file_exists(SQL_P . '/dpp.sql')) {
    $res = [
      'code' => 1,
      'msg' => '数据库文件不存在，请检查/public/install/dpp.sql是否存在！'
    ];
    exit(json_encode($res));
  }  
  
  
 // database配置文件是否可写
  $database_file = str_replace('/public/install', '', __DIR__) . '/application/database.php';
  
  $default_database_file = str_replace('/public/install', '', __DIR__) . '/application/database_default.php';
 
  $sql_file = file_get_contents(SQL_P . '/dpp.sql');
  //var_dump($sql_file);SQL_PSQL_P
  // 设置数据库编码
  $conn->query("SET NAMES 'utf8'");
  //创建数据库
  $sql = "CREATE DATABASE IF NOT EXISTS ".$dbname1." default character set utf8 COLLATE utf8_general_ci;";
  if (!$conn->query($sql)) {
    $res = [
      'code' => 1,
      'msg' => '数据库创建失败！'
    ];
    exit(json_encode($res));
  }
  //选择数据库
  $conn->select_db($dbname1);
 
  //替换数据表前缀
  if(!empty($dbqianzhui)) {
    $sql_file = str_replace('kpl_', $dbqianzhui, $sql_file);
  }
  // sql文件语句以;号结束，将每条语句分割到属猪
  $sql_arr = explode(';', $sql_file);
  foreach($sql_arr as $val) {
    // sql运行需要;号，所以还得加上
    $sql = $val . ';';
    $conn->query($sql);
  }
  // 关闭数据库
  mysqli_close($conn);
 
  // 修改database配置
  $database_config = file_get_contents($default_database_file);
  //var_dump($database_config);
  // 替换配置
  $database_config = str_ireplace('default_host', '127.0.0.1', $database_config);
  $database_config = str_ireplace('default_database', $dbname1, $database_config);
  $database_config = str_ireplace('default_user', $dbname2, $database_config);
  $database_config = str_ireplace('default_password', $dbpassword, $database_config);
  if (!empty($dbqianzhui)) {
    $database_config = str_ireplace('default_prefix', $dbqianzhui, $database_config);
  } else {
    $database_config = str_ireplace('default_prefix', 'kpl_', $database_config);
  }
 
  file_put_contents($database_file, $database_config);
  
  $content = "系统名称". $web_name .",创建时间: " .date("Y-m-d H:i:s")."作者：qq32579135 ©版权：归作者红牛所有";
  $file = file_put_contents(SQL_P . '/install.lock',$content);
}
 
 
/**
 * 保存基本信息
 */
function insert_base_info ($web_user, $web_password, $web_name) {
  $database_file = str_replace('\public\install', '', __DIR__) . '/application/database.php';
  $database_config = require($database_file);
  $conn = @mysqli_connect($database_config['hostname'], $database_config['username'], $database_config['password'], $database_config['database']);
  if (!$conn) {
    $res = [
      'error' => 1,
      'msg' => '数据库连接错误，请核对数据库连接信息!'
    ];
    return $res;
  }
  $conn->query("SET NAMES 'utf8'");
  // 插入用户名和密码
  $password = password_hash($web_password, PASSWORD_DEFAULT);
  $sql = "INSERT INTO " . $database_config['prefix'] . "admins (name, password, create_time, update_time) VALUES('". $web_user ."', '". $password ."', ". time() .", ". time() .")";
  $conn->query($sql);
  if (!empty($web_name)) {
    $web_name = "管理系统";
  }
  $sql = "INSERT INTO ". $database_config['prefix'] . "configs (name) VALUES('".$web_name."')";
  $conn->query($sql);
  mysqli_close($conn);
  //写入lock
  $content = "系统名称". $web_name .",创建时间: " .date("Y-m-d H:i:s");
  $file = fopen("./install.lock","1");
  fwrite($file, $content);
  fclose($file);
}
?>