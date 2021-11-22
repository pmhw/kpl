<?php
namespace app\index\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Model;
use think\Request;
/**
 * 首页
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/

class Index extends Controller
{
    protected $template_path = ROOT_PATH . 'public/template/';//模板路径
    
    /**
     * ## 首页 ##
     * @ $template 模板名称
    */
    public function index()
    {
     $theme = Db::name('index')->where('key','theme')->find();
     
     
     $template = $theme['value'];//模板
    
    
     $templates = 'template/' . $template;
     $this->assign('template',$templates);//js css引入路径

     //导航栏
     $menu1 = Db::name('menu1')->select();
     $menu2 = Db::name('menu2')->select();
     
     
     //获取网站信息
     $index = Db::name('index')->where('key','frontend')->find();
     //dump($index["value"]);
     $frontend = json_decode($index["value"],true);
     //dump($frontend);
     $this->assign('frontend',$frontend);
     
     //获取商品信息
     $goods = Db::name('goods')->limit(3)->select();
     if($goods){
      $this->assign('goods',$goods); 
     }
     $bw = Db::name('goods')->limit(3)->select();
     if($bw){
        $this->assign('bw',$bw);  
     }
     //
     $mq = Db::name('goods')->limit(3)->select();
     //dump($res);
     if($mq){
        $this->assign('mq',$mq);  
     }
     
     $web = Db::name('goods')->limit(3)->select();
     if($web){
        $this->assign('web',$web); 
     }
     $ips = Db::name('goods')->limit(3)->select();
     
     if($ips){
        $this->assign('ips',$ips); 
     }  
     
     //判断是否登录
     if(session('user')){
       $this->assign('session',0);  
     }else{
       $this->assign('session',1);  
     }
     
     $this->assign('menu1',$menu1);
     $this->assign('menu2',$menu2);
     return view($this->template_path . $template . "/index.html");
    }
}
