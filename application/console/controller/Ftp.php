<?php
namespace app\console\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Model;
use think\Request;


class Ftp extends Base
{
    
    public function index(){
        $ftp = 'ftp://173.82.105.82:21';
        
        $this->assign('ftp',$ftp);
        return $this->fetch();
    }
    
    public function ftp_api(){
        $host = Db::name('host')->where(['user_id'=>$this->id])->select();
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        
        $ftps  = get_ftplist($bt['bt_url'],trim($bt['bt_key']));
        
        $name = array_column($ftps['data'],'name','pid');
        $password = array_column($ftps['data'],'password','pid');
        $status = array_column($ftps['data'],'status','pid');
        $id = array_column($ftps['data'],'id','pid');
        
        foreach($host as $key => $item){
            $_name = array_key_exists($item['bt_id'], $name);
            $host[$key]['name'] = $_name ? $name[$item['bt_id']] : '';
            $_password = array_key_exists($item['bt_id'], $password);
            $host[$key]['password'] = $_password ? $password[$item['bt_id']] : '';
            $_status = array_key_exists($item['bt_id'], $status);
            $host[$key]['status'] = $_status ? $status[$item['bt_id']] : '';
            $_id = array_key_exists($item['bt_id'], $id);
            $host[$key]['id'] = $_id ? $id[$item['bt_id']] : '';
        }
        
        $res['code']=0;
        $res['data']=$host;

        
        return json($res);
        
    }
    
    /**
     * 修改FTP密码界面
    **/
    
    public function add_password_ftp(){
        
        $data['name'] = input('name');
        $data['password'] = input('password');
        $data['ftp_id'] = input('ftp_id');
        
        $this->assign('data',$data);
        return $this->fetch();
    }
    
    public function add_passwordFtp(){
        $name = input('name');
        if(!$name || $name==''){
            exit(json_encode(['code'=>1,'msg'=>'FTP名称为空']));
        }
        
        $password = input('password');
        if(!$password || $password==''){
            exit(json_encode(['code'=>1,'msg'=>'FTP密码为空']));
        }   
    
        $ftp_id = input('ftp_id');
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        
        $res = get_ftppassword($bt['bt_url'],trim($bt['bt_key']),$ftp_id,$name,$password);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>$res['msg']]));}
        exit(json_encode(['code'=>0,'msg'=>$res['msg']]));
    }
}