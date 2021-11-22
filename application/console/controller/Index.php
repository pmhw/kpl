<?php
namespace app\console\controller;


use think\Controller;
use think\Db;
use think\Session;
use think\Model;
use think\Request;
/**
 * 控制台首页 console
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/

class Index extends Base
{
    //权限验证$id 开普勒服务器ID
    public function permissions($id){
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        $index = Db::name('index')->where('key','bt')->find();
        
        return $index;
    }
    
    
    /**提取站点名
     * 
     * $bt_id 宝塔ID
    **/
    public function web_name($bt_id){
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        $get_key = get_key($bt['bt_url'],trim($bt['bt_key']),$bt_id);
        
        $webname = substr($get_key,strripos($get_key,"/")+1);
        return $webname;
    }
    
    /**
     * 控制台header
    */
    public function index(){

               
       #获取用户信息
       $data['user'] = Db::name('user')->where(['id'=>$this->id])->find();
        
       $this->assign('data',$data);
       return $this->fetch();
    }
    //控制台首页
    public function consoles(){
        #服务器数量$this->id
        $host_count = Db::name('host')->where(['user_id'=>$this->id])->count();
        
        #ftp数量
        $host = Db::name('host')->where(['user_id'=>$this->id])->select();#获取数用户所拥有的服务器
        $index = Db::name('index')->where('key','bt')->find();#获取key
        $bt = json_decode($index['value'],true);#解析key
        
        $ftps  = get_ftplist($bt['bt_url'],trim($bt['bt_key']));#获取ftp数据
        
        #提取ftp数量
        $id = array_column($ftps['data'],'id','pid');
        foreach($host as $key => $item){
            $_id = array_key_exists($item['bt_id'], $id);
            $_host[$key]['id'] = $_id ? $id[$item['bt_id']] : '';
        }
        
        if(isset($_host)){
        $host_ftp_count = count($_host);
        }
        
        //dump($host_ftp_count);
        #sql数量
        $mysql = get_mysql($bt['bt_url'],trim($bt['bt_key']));
        
        $id = array_column($mysql['data'],'id','pid');
        foreach($host as $key => $item){
            $_id = array_key_exists($item['bt_id'], $id);
            $_host[$key]['id'] = $_id ? $id[$item['bt_id']] : '';
        }
        if(isset($_host)){
            $host_mysql_count = count($_host);
        }
        
        if(isset($host_count)){
            $data['count1'] = $host_count;
        }
        
        if(isset($host_ftp_count)){$data['count2'] = $host_ftp_count;}
        
        if(isset($host_mysql_count)){$data['count3'] = $host_mysql_count;}
        
        
        #获取轮播图
        $data['shuffling'] = Db::name('shuffling')->order('id desc')->limit(5)->select();
        
        #获取公告
        $data['affiche'] = Db::name('affiche')->order('id desc')->paginate(10);
        
        $data['affiche_page'] = $data['affiche']->render();

        
        $this->assign('data',$data);
        //dump($host_ftp_count,$host_mysql_count);
        
       return $this->fetch(); 
    }
    //用户配置账号密码
    public function update_user(){
       return $this->fetch();  
    }
    
    /**
     * 用户虚拟主机管理页
    */
    
    public function myhost(){
        
        
        
        $host = Db::name('host')->where(['user_id'=>$this->id])->order('id desc')->select();
        $goods = Db::name('goods')->select();
        
        $_goods = Db::name('goods')->select();
                
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        $res = WebList($bt['bt_url'],trim($bt['bt_key']));
         
        //dump($res['data']);
        
        $_res = array_column($res['data'], 'status', 'id');
        $ress = array_column($res['data'], 'name', 'id');
        //dump($_res);
        foreach($host as $key => $item) {
            $status = array_key_exists($item['bt_id'], $_res);
            $host[$key]['status'] = $status ? $_res[$item['bt_id']] : '';
            $name = array_key_exists($item['bt_id'], $ress);
            $host[$key]['name'] = $name ? $ress[$item['bt_id']] : '';
        }
        
        //dump($host);
        
        foreach($_goods as $key=>$value){
            $css = array ("blue", "red", "green", "yellow"); 
            $css_value = array_rand ($css, 2);
            $_goods[$key]['css']= $css[$css_value[0]];
        }
        // dump($host);
        // dump($goods);
        $this->assign('_goods',$_goods);
        $this->assign('goods',$goods);
        $this->assign('host',$host);
        return $this->fetch();
    }
    
    //虚拟机管理面板
    public function myhost_set(){
        
        
       $id = input('get.id');
       $webname = input('get.webname');
       
       //判断是否过期 判断权限
       $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
       
       if(!$kpl_host){
            $this->error('权限不足');
       }
       
       $time = time();
       
       $_time = $kpl_host['time'] + $kpl_host['month']*30*24*60*60;
       
       if($_time < $time){
          $update = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->update(['state'=>1]); 
          $this->error('服务器已到期');
       }
       
       $this->assign('id',$id);
       $this->assign('webname',$webname);
       return $this->fetch(); 
    }
    
    /**
     * 提交工单界面 add_work_order
     * 
    **/
    public function add_work_order(){
        return $this->fetch();
    }
    
    
    /**
     * 提交工单到数据库
     * type 问题类型
     * describe 问题描述
     * username 平台账号
     * password 平台密码
     * work_order_number 工单编号
     * time 提交时间
    **/
    
    public function add_mysql_work_order(){
        $data = input('post.');
        
        if(!$data['type'] || !$data['describe'] || !$data['username'] || !$data['password']){
            exit(json_encode(['code'=>1,'msg'=>'数据不完整，请检查!']));
        }
        
        $data['userID'] = $this->id;

        //查重
        $work_order = Db::name('work_order')->where(['userID'=>$data['userID']])->where('state','<',3)->find();
        if($work_order){
            exit(json_encode(['code'=>1,'msg'=>'您的工单还未结束！暂时无法提交新的工单']));
        }
        
        $data['work_order_number'] = time()*2;
        $data['time'] = time();
        
        $insert = Db::name('work_order')->insert($data);
        if(!$insert){
          exit(json_encode(['code'=>1,'msg'=>'失败']));  
        }
        
        $work_order = Db::name('work_order')->where(['work_order_number'=>$data['work_order_number']])->find();
        
        exit(json_encode(['code'=>0,'msg'=>'成功','won'=>$data['work_order_number'],'wkid'=>$work_order['id']]));  

    }
     
     
    /**
     * 查看工单界面
    **/
    public function look_work_order(){
        
        //输出自己创建的工单
        $work_order = Db::name('work_order')->where(['userID'=>$this->id])->order('id desc')->paginate(8);
        
        if(isset($work_order)){
        $data['work_order'] = $work_order;
        $data['page'] = $work_order->render();
        $this->assign('data',$data);
        }
        
        return $this->fetch();
    }
    
    
    /**
     * 工单详情界面
     * $id 工单ID
    **/
    
    public function work_order_page($id){
        //dump($id);
        $data['work_order'] = Db::name('work_order')->where('id',$id)->find();
        if($data['work_order']['state'] == 3){$this->error('该工单已结束');}
        
        $data['data'] = Db::name('work_order_page')->where('wk_id',$id)->select();
        
        $this->assign('data',$data);
        return $this->fetch();
    }
    
    /**
     * add_mysql_wop 用户发布工单叙述到数据库
    **/
    
    public function add_mysql_wop(){
        $data = input('post.');
        
        if(!$data['msg'] || !$data['wk_id']){exit(json_encode(['code'=>1,'msg'=>'检测数据完整性']));}
        
        $data['text'] = xss($data['msg']);
        unset($data['msg']);
        
       
        
        $data['time'] = time();
        
        $data['user_id'] = $this->id;
        //dump($data);
        $insert = Db::name('work_order_page')->insert($data);
        if(!$insert){exit(json_encode(['code'=>1,'msg'=>'回复失败']));}
        exit(json_encode(['code'=>0,'msg'=>'回复成功']));
    }
    
    
    /**
     * 订单确认页
     * @$id 商品id
    */
    
    public function shop_confirm(){
        $id = input('get.id');
        
        $goods = Db::name('goods')->where(['id'=>$id])->find();
        
        $this->assign('goods',$goods);
        
        return $this->fetch(); 
    }
    
    /**
     * create_order
     * 生成订单
     * $id 商品id
     * $pay 支付方式
     * $price 支付金额
     * $input 购买时长
    */
    
    public function create_order(){
        $data['order_gid'] = input('post.id');
        $data['order_pay'] = input('post.pay');
        $data['order_price'] = input('post.price');
        $data['order_input'] = input('post.input');
        $data1 = [
            'code'=>1,
            'msg'=>'error'
         ];

         
        if(!$data['order_gid'] || $data['order_gid']==''){
            exit(json_encode($data1));
        }
        if(!$data['order_pay'] || $data['order_pay']==''){
            exit(json_encode(['code'=>1,'msg'=>'请选择支付方式']));
        }
        if(!$data['order_price'] || $data['order_price']==''){
           exit(json_encode(['code'=>1,'msg'=>'请填写支付金额']));
        }   
        if(!$data['order_input'] || $data['order_input']==''){
            exit(json_encode($data1));
        }           
        $data['order_time'] = time();
        $data['order_id'] = time()*2;
        $user = Db::name('user')->where(['username'=>$this->_user['username']])->find();
         
        $data['user_id']=$user['id'];
        
        
        //  问题记录 2021/11/11
        // $orders = Db::name('orders')->where('create_time','<= time',$data['order_time'] - 1500)->where('order_state',1)->update(['order_state'=>3]);
        //生成订单时 清除过期订单
        $time = time();
        $time = $time - 300;
        $update = Db::name('orders')->where('order_time','<',$time)->where('order_state','1')->update(['order_state'=>2]);
        
        //用户id 订单金额 商品id同时满足
        $cx = Db::name('orders')->where(['user_id'=>$data['user_id'],'order_price'=>$data['order_price'],'order_gid'=>$data['order_gid'],'order_pay'=>$data['order_pay']])->where('order_state',1)->find();
        if($cx){
            exit(json_encode(['code'=>1,'msg'=>'订单已存在,请等待过期后重试']));
        }
        
        //写入订单到数据库
        $res = Db::name('orders')->insert($data);
        if(!$res){
          exit(json_encode(['code'=>1,'msg'=>'创建订单失败'])); 
        }
        exit(json_encode(['code'=>0,'msg'=>'创建订单成功','order_id'=>$data['order_id']])); 
    }
    
    /**获取服务绑定域名
     * 
     * $this->id 用户ID
    **/
    public function yuming_list(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));}
        
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        // $_time = $kpl_host['month']*30*24*60*60;
        // $_times = $_time + $kpl_host['time'];
        
        // if($_times<time()){exit(json_encode(['code'=>1,'msg'=>'已过期请续费']));}
        
        
        
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        
        $res = get_yuming_list($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id']);
        //dump($res);
        if(!$res){exit(json_encode(['code'=>1,'msg'=>'获取失败']));}
        
        return $res;
    }
    
    /**删除已绑定的域名
     * $id HOSTid
     * $name 删除的域名
     * $webname 删除站点名
    **/
    public function delete_yuming(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));}
        $name = input('get.name');
        if(!$name){exit(json_encode(['code'=>1,'msg'=>'name为空']));}
        $webname = input('get.webname');
        if(!$webname){exit(json_encode(['code'=>1,'msg'=>'webname为空']));}
        
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        $res = get_delete_yuming($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id'],$name,$webname);
        //dump($res);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>$res['msg']]));}
        exit(json_encode(['code'=>0,'msg'=>'删除成功']));
        
    }
    /**
     * 添加域名
     * domain
    **/
    public function add_yuming(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));}
        $domain = input('get.domain');
        if(!$domain){exit(json_encode(['code'=>1,'msg'=>'domain']));}
        $webname = input('get.webname');
        if(!$webname){exit(json_encode(['code'=>1,'msg'=>'webname为空']));}
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        $res = get_add_yuming($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id'],$webname,$domain);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>$res['msg']]));}
        exit(json_encode(['code'=>0,'msg'=>'添加成功']));
    }
    
    /**
     * 获取网站运行目录
    **/
    public function run_directory(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));} 
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        $key = get_key($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id']);
        $res = get_run_directory($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id'],$key);
        return $res['runPath'];
        
    }
    
    /**
     * 保存站点运行目录
     *
     **/
    public function add_run_directory(){
        $id = input('get.id');
        $path = input('get.path');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));} 
        if(!$path){exit(json_encode(['code'=>1,'msg'=>'path为空']));} 
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        $res = add_runDirectory($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id'],$path);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>$res['msg']]));}
        exit(json_encode(['code'=>0,'msg'=>'保存成功']));        
    }
    /**
     * 获取伪静态列表
    **/
    
    public function web_rewrite(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));} 
        $webname = input('get.webname');
        if(!$webname){exit(json_encode(['code'=>1,'msg'=>'webname为空']));}
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        $res = get_rewrite_list($bt['bt_url'],trim($bt['bt_key']),$webname);
        if(!$res){exit(json_encode(['code'=>1,'msg'=>'获取失败']));}
        
        return $res;
    }
    /**
     * 获取当前网站伪静态内容
    **/
    
    public function rewrite_config(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));} 
        $webname = input('get.webname'); 
        if(!$webname){exit(json_encode(['code'=>1,'msg'=>'webname为空']));}
        
        
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        $res = get_rewrite_config($bt['bt_url'],trim($bt['bt_key']),$webname);
        if(!$res){exit(json_encode(['code'=>1,'msg'=>'获取失败']));}
        
        return $res;
    }
    
    /**
     * 获取更换的伪静态内容
    **/
    
    public function rewrites_config(){
        $rewrite_id = input('get.rewrite_id'); 
        if(!$rewrite_id){exit(json_encode(['code'=>1,'msg'=>'rewrite_id为空']));} 
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        $res = get_rewrites_config($bt['bt_url'],trim($bt['bt_key']),$rewrite_id);
        if(!$res){exit(json_encode(['code'=>1,'msg'=>'获取失败']));}
        
        return $res;
        
    }
    
    //保存伪静态
    public function add_rewrite(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));} 
        $rewrite_desc = input('get.rewrite_desc'); 
        if(!$rewrite_desc){exit(json_encode(['code'=>1,'msg'=>'rewrite_desc为空']));} 
        $webname = input('get.webname'); 
        if(!$webname){exit(json_encode(['code'=>1,'msg'=>'webname为空']));}
        
        $_index = $this->permissions($id);
        $bt = json_decode($_index['value'],true);
        
        $res = get_add_rewrite($bt['bt_url'],trim($bt['bt_key']),$webname,$rewrite_desc);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>'保存失败']));}
        exit(json_encode(['code'=>0,'msg'=>'保存成功']));
    }
    
    //关机
    public function web_off($id){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));}
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        
        $web_name = $this->web_name($kpl_host['bt_id']);
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        
        $res = SiteStop($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id'],$web_name);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>'关闭失败']));}
        exit(json_encode(['code'=>0,'msg'=>'关闭成功']));        
    }
    
    //开机
    public function web_on(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'Id为空']));}
        $kpl_host = Db::name('host')->where(['id'=>$id,'user_id'=>$this->id])->find();
        
        if(!$kpl_host){exit(json_encode(['code'=>1,'msg'=>'权限验证失败']));}
        
        $timek = $kpl_host['time'] + $kpl_host['month']*30*24*60*60;
        if($timek < time()){
          exit(json_encode(['code'=>1,'msg'=>'服务器已到期']));  
        }
        
        $web_name = $this->web_name($kpl_host['bt_id']);
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        
        $res = SiteStart($bt['bt_url'],trim($bt['bt_key']),$kpl_host['bt_id'],$web_name);
        if($res['status']!=true){exit(json_encode(['code'=>1,'msg'=>'开启失败']));}
        exit(json_encode(['code'=>0,'msg'=>'开启成功']));     
    }
    
    
    // 修改登录密码
    public function user_password(){
        
        $this->assign('user',$this->user);
        return $this->fetch();
    }
    
    // 修改密码保存到数据库
    
    public function save_password(){
        $password = input('post.password');
        if(!$password || $password == ''){exit(json_encode(['code'=>1,'msg'=>'密码为空']));}
        $update = Db::name('user')->where('id',$this->id)->update(['password'=>md5($password)]);
        if(!$update){
            exit(json_encode(['code'=>1,'msg'=>'与原密码一致']));
        }
        exit(json_encode(['code'=>0,'msg'=>'保存成功']));
    }
}