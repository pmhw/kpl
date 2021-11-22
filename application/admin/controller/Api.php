<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Request;
use think\Config;
use think\Cache;
use think\Model;

/**
 * 后端-数据接口
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/

class Api extends Base
{
// +----------------------------------------------------------------------
// | 网站数据API接口
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2021 红牛 qq32579135
// +----------------------------------------------------------------------
    
    public function pay_setting(){
        $alipay = trim(input('get.alipay'));
        $wxpay = trim(input('get.wxpay'));
        $res = 'alipay';
        $_res = 'wxpay';
        $config = setconfig($res,$alipay);
        $_config = setconfig($_res,$wxpay);
        $this->clear();
        if($config==false && $_config==false){
           exit(json_encode(['code'=>1])); 
        }
        exit(json_encode(['code'=>0]));
    }
    
    //清理缓存
    public function clear(){
      //先删除目录下的文件：
      $mulu = RUNTIME_PATH.'temp/';
         // dump($mulu);
            //Cache::clear(); 
        function deldir($path){
                  //如果是目录则继续
                  if(is_dir($path)){
                      //扫描一个文件夹内的所有文件夹和文件并返回数组
                      $p = scandir($path);
                      foreach($p as $val){
                            //排除目录中的.和..
                            if($val !="." && $val !=".."){
                                 //如果是目录则递归子目录，继续操作
                                 if(is_dir($path.$val)){
                                  //子目录中操作删除文件夹和文件
                                  deldir($path.$val.'/');
                                  //目录清空后删除空文件夹
                                  @rmdir($path.$val.'/');
                                 }else{
                                  //如果是文件直接删除
                                  unlink($path.$val);
                                 }
                            }
                      }
                  }
         }
         //调用函数，传入路径
         deldir($mulu);
         exit(json_encode(['code'=>1,'msg'=>'清理缓存成功'],JSON_UNESCAPED_UNICODE));
    }
    
    
    //设置主题
    public function theme_setting(){
        $templates = trim(input('get.templates'));
        if(!$templates){
            exit(json_encode(['code'=>1]));
        }
        
        $index = Db::name('index')->where('key','theme')->update(['value'=>$templates]);
        if(!$index){
            exit(json_encode(['code'=>1]));
        }
        exit(json_encode(['code'=>0]));
    }
    
    
    //获取主题
    public function theme_josn(){
        $dir = '../public/template';
    
        $data1 = [];
        
        $handler = opendir($dir);
        while( ($filename = readdir($handler)) !== false ) {
              if($filename != "." && $filename != ".."){
                  $json_string = file_get_contents('../public/template/'.$filename.'/theme.json'); 
                  $data = json_decode($json_string, true);
                  $data['img']= '/template/'.$filename.'/theme.jpg';
                  array_push($data1, [
                         'id'    => $data['file'],
                         'image' =>$data['img'],
                         'title'=>$data['name'],
                         'remark'=>$data['file'],
                         'zuozhe'=>$data['zuozhe'],
                         'time'=>''
                  ]);
              }
              

        }
        closedir($handler); 

        $theme = json_encode([
            'code'=>0,
            'msg'=>'...',
            'count'=>3,
            'data' =>array_values($data1)//去除键名
            ]);
        echo $theme;
    }
   
   
    public function menu1(){
        $menu1 = Db::name('menu1')->select();
        $data = [
              "code"  => 0,
              "msg"   => "",
        ];
        
        $data['data']=$menu1;
        return json($data);
    }
    
    public function menu2(){
        $menu2 = Db::name('menu2')->select();
        $data = [
              "code"  => 0,
              "msg"   => "",
        ];
        
        
        
        $data['data']=$menu2;
        return json($data);
    } 
    
    // 上传网站logo接口
    public function api_logo_img(){
        $file = request()->file('file');
        
        if($file == null){exit(json_encode(['code'=>0,'msg'=>'请选择图片']));}
        
        $info = $file->move(ROOT_PATH . 'public' . DS . 'img');
        $name = $info->getExtension();
        
        if(!in_array($name,['png'])){
            unlink(ROOT_PATH . 'public' . DS . 'img/' . $info->getSaveName());//删除临时
            exit(json_encode(['code'=>1,'msg'=>'请上传PNG格式图片']));
        }
        
        $update = rename(ROOT_PATH . 'public' . DS . 'img/' . $info->getSaveName(),ROOT_PATH . 'public' . DS .'img/logo.png');
        if(!$update){
          exit(json_encode(['code'=>1,'msg'=>'保存失败']));  
        }
        exit(json_encode(['code'=>0,'msg'=>'/img/logo.png']));
    }
    
    
    // 上传网站icon接口
    public function api_icon_img(){
        $file = request()->file('file');
        
        if($file == null){exit(json_encode(['code'=>0,'msg'=>'请选择图片']));}
        
        $info = $file->move(ROOT_PATH . 'public' . DS . 'img');
        $name = $info->getExtension();
        
        if(!in_array($name,['png'])){
            unlink(ROOT_PATH . 'public' . DS . 'img/' . $info->getSaveName());//删除临时
            exit(json_encode(['code'=>1,'msg'=>'请上传PNG格式图片']));
        }
        
        $update = rename(ROOT_PATH . 'public' . DS . 'img/' . $info->getSaveName(),ROOT_PATH . 'public' . DS .'img/favicon.png');
        if(!$update){
          exit(json_encode(['code'=>1,'msg'=>'保存失败']));  
        }
        exit(json_encode(['code'=>0,'msg'=>'/img/favicon.png']));
    }
// +----------------------------------------------------------------------
// | bt数据API接口
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2021 红牛 qq32579135
// +----------------------------------------------------------------------    
    
    //获取实时状态信息(CPU、内存、网络、负载)
    public function bt_GetNetWork(){
       $index = Db::name('index')->where('key','bt')->find();
        if($index){
            $bt = json_decode($index['value'],true);
            $res =  GetNetWork($bt['bt_url'],trim($bt['bt_key']));
            
            
            return json($res);
        }else{
             $this->error('请先配置您的宝塔API哦！');
        }
    }
    
    //获取系统基础统计
    public function bt_GetSystemTotal(){
       $index = Db::name('index')->where('key','bt')->find();
        if($index){
            $bt = json_decode($index['value'],true);
            $res =  GetSystemTotal($bt['bt_url'],trim($bt['bt_key']));
            
            
            return json($res);
        }else{
             $this->error('请先配置您的宝塔API哦！');
        }
    }
    
    
    //获取网站列表
    public function bt_webList(){
        
        $page = input('get.page');
        
        $limit = input('get.limit');
        
        
        //搜索
        $searchParams = input('get.searchParams');
        if(isset($searchParams)){
            $searchParams = json_decode($searchParams,true);
            $name = $searchParams['name'];
        }else{
            $name = '';
        }
        
        $index = Db::name('index')->where('key','bt')->find();
        
        if($index){
            $bt = json_decode($index['value'],true);
            $res = WebList($bt['bt_url'],trim($bt['bt_key']),$page,$limit,$name);
            $res1 = WebList($bt['bt_url'],trim($bt['bt_key']),'','',$name);
            //dump($bt['bt_url']);
            $count = count($res1['data']);
            
            $_res['data'] = $res['data'];
            $_res['count'] = $count;
            $_res['code']=0;    
            return json($_res);
        }else{
             $this->error('请先配置您的宝塔API哦！');
        }
    }
    
    //启用网站
    public function bt_SiteStart(){
       $id = trim(input('get.id'));
       $name = trim(input('get.name'));
       $index = Db::name('index')->where('key','bt')->find();
       $bt = json_decode($index['value'],true);
       $res = SiteStart($bt['bt_url'],trim($bt['bt_key']),$id,$name);
       
       if($res['status']==true){
           exit(json_encode(['code'=>0,'msg'=>'成功']));
       }
       exit(json_encode(['code'=>1,'msg'=>'失败']));
    }
    
    //停用网站 
    public function bt_SiteStop(){
       $id = trim(input('get.id'));
       $name = trim(input('get.name'));
       $index = Db::name('index')->where('key','bt')->find();
       $bt = json_decode($index['value'],true);
       $res = SiteStop($bt['bt_url'],trim($bt['bt_key']),$id,$name);
       
       if($res['status']==true){
           exit(json_encode(['code'=>0,'msg'=>'成功']));
       }
       exit(json_encode(['code'=>1,'msg'=>'失败']));
    } 
    
    /**
     * 获取站点占用空间
     * @ $name 站点名称
    */
    public function get_path_size(){
       $name = trim(input('get.name'));
       
       $index = Db::name('index')->where('key','bt')->find();
       $bt = json_decode($index['value'],true);
       $res = get_path_size($bt['bt_url'],trim($bt['bt_key']),$name);
       //dump($res);
       return $res;
    }

// +----------------------------------------------------------------------
// |Console财务管理API接口
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2021 红牛 qq32579135
// +----------------------------------------------------------------------    
    
    /**
     * 主机商品列表接口
    **/
    public function api_goods(){
        
        $page = input('get.page');
        $limit = input('get.limit');
        
        $searchParams = input('get.searchParams');
        if(isset($searchParams)){
            $searchParams = json_decode($searchParams,true);
            $name = $searchParams['name'];
        }
        
        
        if(isset($name)){
            
            $goods = Db::name('goods')->where('title','like',$name . '%')->page($page,$limit)->select();
            $_goods = Db::name('goods')->where('title','like',$name . '%')->count(); 
            
        }else{
                
            $goods = Db::name('goods')->page($page,$limit)->select();
            $_goods = Db::name('goods')->count();
            
        }
            
        $data = [
              "code"  => 0,
              "count" => $_goods,
              "msg"   => "",
        ];
        
        $data['data']=$goods;
        
        return json($data);
    }
    
    /**
     * 主机商品 批量删除
    **/
    
    public function betch_goods(){
        $data = input('post.data');
        $data = json_decode($data,true);
        $data = array_column($data,'id');
        
        foreach ($data as $val){
            $delete = Db::name('goods')->where(['id'=>$val])->delete();
        }
        
        
        exit(json_encode(['code'=>0,'msg'=>'批量删除成功']));
        
    }
        
    /**
     * 创建服务器商品
     * @title = 商品标题
     * @bandwidth = 带宽大小 MB
     * @mysql = 数据库大小 MB
     * @web_size = 空间大小 G
     * @ip = 独立 公共
     * @Ypricing = 月付
     * @Npricing = 年付
     * @content = 商品介绍
    */
    public function api_app_shop(){
        $data['title'] = input('post.title');
        $data['bandwidth'] = trim(input('post.bandwidth'));
        $data['mysql'] = trim(input('post.mysql'));
        $data['web_size'] = trim(input('post.web_size'));
        $data['ip'] = input('post.ip');
        $data['Ypricing'] = trim(input('post.Ypricing'));
        $data['Npricing'] = trim(input('post.Npricing'));
        $data['content'] = input('post.content');

        $goods = Db::name('goods')->insert($data);
        
        if(!$goods){
            exit(json_encode(['code'=>1]));
        }
        exit(json_encode(['code'=>0]));
        
    }
    
    
    /**
     * 工单列表接口
     * $page 第几页
     * $limit 每页显示数量
     * $searchParams 搜索数据接收
     * 
    **/
    public function api_work_order_list(){
        
        //默认参数
        $page = input('get.page');
        $limit = input('get.limit');
        
        //搜索
        $searchParams = input('get.searchParams');
        if(isset($searchParams)){
            $searchParams = json_decode($searchParams,true);
            $work_order_number = $searchParams['work_order_number'];
        }

        if(isset($work_order_number)){
            // 搜索执行
            $data = Db::name('work_order')->where('work_order_number','like',$work_order_number.'%')->where('state','<','3')->page($page,$limit)->select();
            $_data = Db::name('work_order')->where('work_order_number','like',$work_order_number.'%')->where('state','<','3')->count();
            
        }else{
            // 初始化
            $data = Db::name('work_order')->where('state','<','3')->page($page,$limit)->select();
            
            $_data = Db::name('work_order')->where('state','<','3')->count();
        }

        // $count = count($_data)/$limit;
        // $ceil = ceil($count);
        
        exit(json_encode(['code'=>0,'count'=>$_data,'data'=>$data]));
    }
    
    /**
     * 
     * 批量修改工单状态
     * 
    **/
    public function batch_work_order_state(){
        $data = input('get.');
        //dump($data);
        $data = json_decode($data['data'],true);
        $data = array_column($data,'id');
        
        foreach($data as $value){
            $state = Db::name('work_order')->where(['id'=>$value])->update(['state'=>3]);
        }

        exit(json_encode(['code'=>0,'msg'=>'结束成功']));
    }
    
    //修改单条工单状态
    public function work_order_state(){
        $id = input('get.id');
        if(!$id){exit(json_encode(['code'=>1,'msg'=>'ID为空']));}
        $state = Db::name('work_order')->where(['id'=>$id])->update(['state'=>3]);
        if(!$state){exit(json_encode(['code'=>1,'msg'=>'结束失败']));}
        exit(json_encode(['code'=>0,'msg'=>'结束成功']));
    }
    
    
    /**
     * 订单列表接口
    **/
    
    public function api_order_list(){
        //默认参数
        $page = input('get.page');
        $limit = input('get.limit');
        
        //搜索
        $searchParams = input('get.searchParams');
        if(isset($searchParams)){
            $searchParams = json_decode($searchParams,true);
            $order_id = $searchParams['order_id'];
        }
        
        if(isset($order_id)){
            // 搜索执行
            $data = Db::name('orders')->where('order_id','like',$order_id.'%')->page($page,$limit)->select();
            $_data = Db::name('orders')->where('order_id','like',$order_id.'%')->count();
            
        }else{
            // 初始化
            $data = Db::name('orders')->page($page,$limit)->select();
            
            $_data = Db::name('orders')->count();
        }     
      
      exit(json_encode(['code'=>0,'count'=>$_data,'data'=>$data]));  
    }
    
    /**
     * console轮播图 API
     * id   
     * imgSrc 图片地址
     * text 图片描述
     * addtime 保存时间
    **/
    public function api_console_imgs(){
        //默认参数
        $page = input('get.page');
        $limit = input('get.limit');
        
        $data = Db::name('shuffling')->page($page,$limit)->select();
        $count = Db::name('shuffling')->count();
        
        exit(json_encode(['code'=>0,'count'=>$count,'data'=>$data]));
    }
    
    //删除console 轮播图 delete_shuffling
    public function delete_shuffling(){
        $data = input('post.data');
        $data = json_decode($data,true);
        
        $data = array_column($data,'id');
        foreach ($data as $val){
            $delete = Db::name('shuffling')->where(['id'=>$val])->delete();
        }
        
        exit(json_encode(['code'=>0,'msg'=>'删除成功']));
    }
    
    //上传轮播图upload_shuffling
    public function upload_shuffling(){
        $file = request()->file('file');
        if($file == null){exit(json_encode(['code'=>0,'msg'=>'请选择图片']));}
        $info = $file->move(ROOT_PATH . 'public' . DS . 'img/shuffling');
        $ext = ($info->getExtension());
        if(!in_array($ext,array('jpg','png','jpeg'))){
            unlink(ROOT_PATH . 'public' . DS . 'img/shuffling/' . $info->getSaveName());//删除临时
            exit(json_encode(array('code'=>1,'msg'=>'文件格式错误')));
        }
        
        $img = '/img/shuffling/'.$info->getSaveName(); //获取文件路径
        exit(json_encode(array('code'=>0,'imgUrl'=>$img)));
        
    }
    
    //保存轮播图到数据库
    public function mysql_shuffling(){
        $data = input('get.');
        
        $data['addtime'] = time();
        $insert = Db::name('shuffling')->insert($data);
        if(!$insert){
           exit(json_encode(array('code'=>1,'msg'=>'添加失败'))); 
        }
        exit(json_encode(array('code'=>0,'msg'=>'添加成功')));
    }
    
    /**
     * console公告 API
     * id   
     * text 内容
     * time 保存时间
    **/
    public function api_console_affiche(){
        //默认参数
        $page = input('get.page');
        $limit = input('get.limit');
        
        $data = Db::name('affiche')->page($page,$limit)->select();
        $count = Db::name('affiche')->count();
        
        exit(json_encode(['code'=>0,'count'=>$count,'data'=>$data]));        
    }
    
    //删除console 公告 支持批量
    
    public function delete_affiche(){
        $data = input('post.data');
        $data = json_decode($data,true);
        
        $data = array_column($data,'id');
        foreach ($data as $val){
            $delete = Db::name('affiche')->where(['id'=>$val])->delete();
        }
        
        exit(json_encode(['code'=>0,'msg'=>'删除成功'])); 
    }
    
    //保存公到数据库
    public function mysql_affiche(){
        $data = input('get.');
        
        $data['time'] = time();
        $insert = Db::name('affiche')->insert($data);
        if(!$insert){
           exit(json_encode(array('code'=>1,'msg'=>'添加失败'))); 
        }
        exit(json_encode(array('code'=>0,'msg'=>'添加成功')));
    }    
    //清除过期订单
    
    public function clean_up_order(){
        $delete = Db::name('orders')->where('order_state',2)->delete();
        if(!$delete){
           exit(json_encode(['code'=>1,'msg'=>'当前无过期订单']));
        }
        
        exit(json_encode(['code'=>0,'msg'=>'清理成功']));
    }
    
}