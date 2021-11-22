<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Model;
use think\Request;

/**
 * 后台登录 控制器
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/

class Login extends Controller
{
    public function login(){
        if(session('admin')){
            $this->success('您已登录', '/admin');
        }
        
        
        return $this->fetch();
    }
    
    public function dulogin(){
        $data = input('post.');
        
        if(!$data['name'] || !$data['password'] || !$data['captcha']){
            exit(json_encode(['code'=>1,'msg'=>'error']));
        }
        
        $chaxun = Db::name('index')->where('key',$data['name'])->find();
        if(!$chaxun){
            exit(json_encode(['code'=>1,'msg'=>'账号密码错误请重试']));
        }
        $_data = json_decode($chaxun['value'],true);
        if($_data['password'] != md5($data['password'])){
            exit(json_encode(['code'=>1,'msg'=>'账号密码错误请重试']));
        }
        
        session('admin',$chaxun);
        exit(json_encode(['code'=>0,'msg'=>'登录成功']));
    }
    
    public function outlogin(){
        session('admin', null);
    }
    
    // public function ceshi(){
    //     $chaxun = Db::name('index')->where('key','admin')->find();
    //     $data = json_decode($chaxun['value'],true);
    //     var_dump($data);
    // }
}