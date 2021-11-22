<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Config;

/**
 * 后端-站长后台
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/

class Index extends Base
{
    /**
     * 后台公共页
    */
    public function index1()
    {
        
        
        return $this->fetch();
    }
    
    /**
     * 后台首页
    */
    public function admin()
    {
        
        
        //判断是否执行ajax
        $index = Db::name('index')->where('key','bt')->find();
        if(!$index){
            $data['bt_is'] = 1;
        }else{
            $data['bt_is'] = 0;
        }
        
        $data['info'] = info();
        
        $data['orders_day'] = Db::name('orders')->where('order_time','today')->sum('order_price');
        //dump( $data['orders_day']);
        $data['orders_count'] = Db::name('orders')->where(['order_state'=>0])->sum('order_price');
        $data['orders'] = Db::name('orders')->where(['order_state'=>0])->count();
        
        $data['host'] = Db::name('host')->where(['state'=>0])->count();
        
        $this->assign('data',$data);
        return $this->fetch();
    }    

    /**
     * 一级菜单
    */
    public function menu1()
    {
        return $this->fetch();
    } 
    
    /**
     * 二级菜单
    */
    public function menu2()
    {
        return $this->fetch();
    } 
    
    
    //网站配置
    public function web_setting(){
        return $this->fetch();
    }
    
    /**
     * 前端配置
    */
    public function frontend(){
        
        $index = Db::name('index')->where('key','frontend')->find();
        //dump($index["value"]);
        
        $frontend = json_decode($index["value"],true);
        if($frontend){$this->assign('frontend',$frontend);}
        
        //dump($frontend);
        
        return $this->fetch();
    }
    
    
    /**
     * 主题界面
    */
    public function theme(){
        $theme = Db::name('index')->where('key','theme')->find();
        
        $theme = $theme['value'];
        //dump($theme);
        if($theme){
            $this->assign('theme',$theme);
        }
        
        return $this->fetch();
    }
    

    
    //创建商品界面
    public function add_shop(){
        $index = Db::name('index')->where('key','bt')->find();
        if($index){
            return $this->fetch();
        }else{
             $this->error('请先配置您的宝塔API哦！');
        }  
    }


// +----------------------------------------------------------------------
// | BT宝塔相关界面
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2021 作者红牛 qq32579135.
// +----------------------------------------------------------------------    
    /**
     * bt接口配置界面
    */
    
    public function bt(){
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        if($bt){$this->assign('bt',$bt);}
        
        return $this->fetch();        
    }
    
    public function bt_log(){
        //取面板日志
        $index = Db::name('index')->where('key','bt')->find();
        if($index){
            $bt = json_decode($index['value'],true);
            $res =  GetLogs($bt['bt_url'],trim($bt['bt_key']));
            //dump($res);
            foreach ($res['data'] as $key => $value) {
                    echo "<div  style='margin:0px 0px 0px 80px;padding-top:30px;'>";
					echo "<label>".$value['username']."</label>";
					echo "<label>".$value['type']."</label><br>";
					echo "<label>".$value['log']."</label>";
					echo "<label>".$value['addtime']."</label><br><br>";
					echo "</div>";
			}
        }else{
             $this->error('请先配置您的宝塔API哦！');
        }
    }
    
    //网站列表界面
    public function bt_webList(){
        
        $index = Db::name('index')->where('key','bt')->find();
        if($index){
            return $this->fetch();
        }else{
             $this->error('请先配置您的宝塔API哦！');
        }  
    }


// +----------------------------------------------------------------------
// | Console支付管理相关界面
// +----------------------------------------------------------------------
// | Copyright (c) 2015-2021 作者红牛 qq32579135.
// +----------------------------------------------------------------------     
    
    /**
     * 收款方式配置界面
    */
    public function pay_way(){
        
        return $this->fetch();
    }
    
    /**
     * 捷支付
    */
    public function jpay(){
        $index = Db::name('index')->where('key','jpay')->find();
        if($index){
            $_index = $index['value'];
            $this->assign('jpay',json_decode($_index,true));
        }
        
       return $this->fetch();  
    }
    
    /**
     * 保存捷支付
    */
    public function add_jpay(){
        $data = input('post.');
        $data['0']=0;
        
        if(!$data){
            exit(json_encode(['code'=>1,'msg'=>'请检测是否填写完整'])); 
        }
        
        $index = Db::name('index')->where('key','jpay')->find();
        if(!$index){
            $index = Db::name('index')->insert(['key'=>'jpay','value'=>json_encode($data)]);
            if(!$index){
                exit(json_encode(['code'=>1,'msg'=>'保存失败']));
            }
            exit(json_encode(['code'=>0,'msg'=>'保存成功']));
        }
        $index = Db::name('index')->where('key','jpay')->update(['value'=>json_encode($data)]);
        if(!$index){
            exit(json_encode(['code'=>1,'msg'=>'更新失败']));
        }
    }
    
    
    /**
     * V免签
     * 
    **/
    public function vpay(){
        
        $index = Db::name('index')->where('key','vpay')->find();
        if($index){
            $_index = $index['value'];
            $this->assign('vpay',json_decode($_index,true));
        }
        return $this->fetch('pay/vpay');
    }
    
    
    /**
     * 保存V免签
    */
    public function add_vpay(){
        $data = input('post.');
        $data['0']=0;
        
        if(!$data){
            exit(json_encode(['code'=>1,'msg'=>'请检测是否填写完整'])); 
        }
        
        $index = Db::name('index')->where('key','vpay')->find();
        if(!$index){
            $index = Db::name('index')->insert(['key'=>'vpay','value'=>json_encode($data)]);
            if(!$index){
                exit(json_encode(['code'=>1,'msg'=>'保存失败']));
            }
            exit(json_encode(['code'=>0,'msg'=>'保存成功']));
        }
        $index = Db::name('index')->where('key','vpay')->update(['value'=>json_encode($data)]);
        if(!$index){
            exit(json_encode(['code'=>1,'msg'=>'更新失败']));
        }
    }
    
    
    // 商品列表
    public function shop_list(){
        
        return $this->fetch();
    }
    
    //订单列表
    public function order_list(){
        
        return $this->fetch();
    }    
    
    
    
    /**
     * 工单管理界面
     * work_order_list
    **/
    
    
    public function work_order_list(){
        return $this->fetch();
    }
    
    
    
    /**
     * 处理工单详情界面
     * 
     * $id 工单id
    **/
    
    public function wkop($id){

        $data['work_order'] = Db::name('work_order')->where('id',$id)->find();
        $data['data'] = Db::name('work_order_page')->where('wk_id',$id)->select();
        
        
        $this->assign('data',$data);
        
        return $this->fetch(); 
    }
    
    
    //回复工单接口
    public function add_wkop(){
        $data = input('post.');
        
        if(!$data['msg'] || !$data['wk_id']){exit(json_encode(['code'=>1,'msg'=>'检测数据完整性']));}
        
        $data['text'] = xss($data['msg']);
        unset($data['msg']);
        
       
        
        $data['time'] = time();
        
        $data['user_id'] = '';
        //dump($data);
        $insert = Db::name('work_order_page')->insert($data);
        if(!$insert){exit(json_encode(['code'=>1,'msg'=>'回复失败']));}
        exit(json_encode(['code'=>0,'msg'=>'回复成功']));
    }
    
    /**
     * console轮播图 console_imgs
     * id   
     * imgSrc 图片地址
     * text 图片描述
     * addtime 保存时间
    **/
    public function console_imgs(){
        
        return $this->fetch();
    }
    
    /**
     * console公告 console_affiche
    **/
    public function console_affiche(){
       return $this->fetch(); 
    }
}