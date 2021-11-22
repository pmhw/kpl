<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Cookie;
/**
* 身份验证
*/
class Base extends Controller
{
     public function __construct(){
        parent::__construct();
        $this->_user = session('admin');
        // 未登录的用户不允许访问
		if(!$this->_user){
			header('Location: /index');
			exit;
		}
     }
}