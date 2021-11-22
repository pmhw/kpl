<?php
namespace app\console\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Model;
use think\Request;


class Mysql extends Base
{
    /**
     * 数据库管理首页
    **/
    public function index(){
        
        
        return $this->fetch();
    }
    
    public function mysql_api(){
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        
        $res = get_mysql($bt['bt_url'],trim($bt['bt_key']));
        
        $name = array_column($res['data'],'name','pid');
        $username = array_column($res['data'],'username','pid');
        $password = array_column($res['data'],'password','pid');
        $id = array_column($res['data'],'id','pid');
        $host = Db::name('host')->where(['user_id'=>$this->id])->select();
        foreach($host as $key => $item){
            $_name = array_key_exists($item['bt_id'], $name);
            $host[$key]['name'] = $_name ? $name[$item['bt_id']] : '';
            $_username = array_key_exists($item['bt_id'], $username);
            $host[$key]['username'] = $_username ? $username[$item['bt_id']] : '';
            $_password = array_key_exists($item['bt_id'], $password);
            $host[$key]['password'] = $_password ? $password[$item['bt_id']] : '';
            $_id = array_key_exists($item['bt_id'], $id);
            $host[$key]['id'] = $_id ? $id[$item['bt_id']] : '';
        }
        
        $res['code']=0;
        $res['data']=$host;

        
        return json($res);
    }
    
    public function add_password_mysql(){
        $data['username'] = input('username');
        $data['password'] = input('password');
        $data['mysql_id'] = input('mysql_id');
        
        $this->assign('data',$data);
        
        
        return $this->fetch();
    }
    
    public function add_passwordMysql(){
        $name = input('name');
        if(!$name || $name==''){
            exit(json_encode(['code'=>1,'msg'=>'MYSQL名称为空']));
        }
        
        $password = input('password');
        if(!$password || $password==''){
            exit(json_encode(['code'=>1,'msg'=>'MYSQL密码为空']));
        }   
    
        $mysql_id = input('mysql_id');
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        
        
        $res = get_passwordMysql($bt['bt_url'],trim($bt['bt_key']),$mysql_id,$name,$password);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>$res['msg']]));}
        exit(json_encode(['code'=>0,'msg'=>$res['msg']]));
        
        
    }
    
}