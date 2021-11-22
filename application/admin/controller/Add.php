<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
/**
 * 后端-请求板块
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/
class Add extends Base 
{
    
    /**
     * 保存 编辑 菜单栏
     * @ $id 1=>一级菜单 2=>二级菜单
     * @ $event 1=>添加  2=>编辑
     * @ $menu_id 编辑的菜单id
    */
    public function add_menu(){
        $id = input('get.id');
        $event = input('get.event');
        $menu_id = input('get.menu_id');
        if($menu_id){
            $data['menu_id']=$menu_id;
            if($id == 1){
             $menu = Db::name('menu1')->where(['gid'=>$menu_id])->find();
            }else{
             $menu = Db::name('menu2')->where(['id'=>$menu_id])->find();
            }
            $this->assign('menu',$menu);
        }else{
            $data['menu_id']=0;
        }
        
        
        $data['title']=1;
        
        if($event == 1){
            $data['event'] = '添加';
            $data['event_id'] = 1;
        }else{
            $data['event'] = '编辑';
            $data['event_id'] = 2;
        }
        
        if($id == 1){
            $data['id']=1;
        }else{
            $data['id']=2;
        }
        
        
        $this->assign('data',$data);
        return $this->fetch();
    }
    
    /**
     * 添加菜单
     * $id 1=>一级菜单 2=>二级菜单 
    */
    public function addMenu(){
        $id=input('post.id');
        $data['name'] = input('post.name');
        $data['url'] = input('post.url');
        if(!$data['name']){
            exit(json_encode(['code'=>1,'msg'=>'请检测所填内容是否完整']));
        }
        
        if($id==1){
            //添加一级菜单
            $res = Db::name('menu1')->insert($data);
            if(!$res){
               exit(json_encode(['code'=>1,'msg'=>'添加失败'])); 
            }
            exit(json_encode(['code'=>0,'msg'=>'添加成功'])); 
        }else{
            $data['gid'] = input('post.gid');
            if(!$data['gid']){
               exit(json_encode(['code'=>1,'msg'=>'请检测所填内容是否完整']));
            }
            
            $res = Db::name('menu2')->insert($data);
            if(!$res){
               exit(json_encode(['code'=>1,'msg'=>'失败'])); 
            }
            exit(json_encode(['code'=>0,'msg'=>'成功']));             
        }
    }
    
    /**
     * 编辑菜单栏
     *  $id  1=>一级菜单 2=>二级菜单 
     *  $aid 菜单ID
    */
    public function editorMenu(){
        $id = input('post.id');
        $data['name'] = input('post.name');
        $data['url'] = input('post.url');
        if(!$data['name']){
            exit(json_encode(['code'=>1,'msg'=>'请检测所填内容是否完整']));
        }
        $aid = input('post.aid');
        if($id==1){
            //编辑一级菜单
            $res = Db::name('menu1')->where(['gid'=>$aid])->update($data);
            if(!$res){
               exit(json_encode(['code'=>1,'msg'=>'添加失败'])); 
            }
            exit(json_encode(['code'=>0,'msg'=>'添加成功'])); 
        }else{
            $data['gid'] = input('post.gid');
            $menu1 = Db::name('menu1')->where(['gid'=>$data['gid']])->find();
            if($menu1['url']){
                exit(json_encode(['code'=>1,'msg'=>'请先删除该一级菜单下的url']));
            }
            
            if(!$data['gid']){
               exit(json_encode(['code'=>1,'msg'=>'请检测所填内容是否完整']));
            }
            
            $res = Db::name('menu2')->where(['id'=>$aid])->update($data);
            if(!$res){
               exit(json_encode(['code'=>1,'msg'=>'失败'])); 
            }
            exit(json_encode(['code'=>0,'msg'=>'成功']));             
        }
        
    }
    
    
    /**
     * 删除菜单栏
     *  $id  1=>一级菜单 2=>二级菜单 id
     *  $aid 菜单ID
    */
    public function DeleteMenu(){
        $id = input('get.id');
        $aid = input('get.aid');
        
        if($id==1){
            $_res = Db::name('menu2')->where(['gid'=>$aid])->delete();
            $res = Db::name('menu1')->where(['gid'=>$aid])->delete();
            if(!$res){
                exit(json_encode(['code'=>1,'msg'=>'失败'])); 
            }
            exit(json_encode(['code'=>0,'msg'=>'成功'])); 
            
        }else{
            $_res = Db::name('menu2')->where(['id'=>$aid])->delete();
            if(!$_res){
                exit(json_encode(['code'=>1,'msg'=>'失败'])); 
            }
            exit(json_encode(['code'=>0,'msg'=>'成功'])); 
        }
    }
    
    
    /**
     * 保存前端配置到数据库
     * $data 前端配置json数组
    */
    
    public function add_frontend(){
        $data = input('post.');
        //dump($data);
        $data['0']=0;//出现了'未定义数组下标: 0'的错误解决方法
        
        $index = Db::name('index')->where('key','frontend')->find();
        if(!$index){
            $index = Db::name('index')->insert(['key'=>'frontend','value'=>json_encode($data)]);
            if(!$index){
                exit(json_encode(['code'=>1,'msg'=>'保存失败']));
            }
            exit(json_encode(['code'=>0,'msg'=>'保存成功']));
        }
        $index = Db::name('index')->where('key','frontend')->update(['value'=>json_encode($data)]);
            if(!$index){
                exit(json_encode(['code'=>1,'msg'=>'更新失败']));
            }
            exit(json_encode(['code'=>0,'msg'=>'更新成功']));        
    }
    
    /**
     * 保存宝塔接口数据
    */
    
    public function add_bt(){
        $data = input('post.');
        $data['0']=0;
        
        if(!$data){
            exit(json_encode(['code'=>1,'msg'=>'请检测是否填写完整'])); 
        }
        
        $index = Db::name('index')->where('key','bt')->find();
        if(!$index){
            $index = Db::name('index')->insert(['key'=>'bt','value'=>json_encode($data)]);
            if(!$index){
                exit(json_encode(['code'=>1,'msg'=>'保存失败']));
            }
            exit(json_encode(['code'=>0,'msg'=>'保存成功']));
        }
        $index = Db::name('index')->where('key','bt')->update(['value'=>json_encode($data)]);
        if(!$index){
            exit(json_encode(['code'=>1,'msg'=>'更新失败']));
        }
    }
    
    
}