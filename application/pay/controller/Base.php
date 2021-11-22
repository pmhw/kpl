<?php
namespace app\pay\controller;

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
        $this->_user = session('user');
        // 未登录的用户不允许访问支付
		if(!$this->_user){
			header('Location: /login');
			exit;
		}
        //顶部栏
        
        
       $header = Db::name('index')->where('key','frontend')->find();
       $_header = json_decode($header['value'],true);
       $header_title = $_header['title'];
       
       if($header_title){
           $this->assign('header_title',$header_title);
       }
       
       //获取用户ID
       $user = Db::name('user')->where(['username'=>$this->_user['username']])->find();
       $this->user = $user;
       $this->id = $user['id'];
    }
}