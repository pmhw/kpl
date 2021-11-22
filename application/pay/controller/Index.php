<?php
namespace app\pay\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Config;
use think\Cookie;

/**
 * Pay-订单处理
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/

class Index extends Controller
{
    /**
     * 发起支付
     * 
     * create_order
     * 生成订单
     * $id 商品id
     * $pay 支付方式
     * $price 支付金额
     * $input 购买时长
     * $_pay 支付平台
     * $order_id 订单号
    */
    
    public function index(){
        $id = input('id');
        $pay = input('pay');
        $price = input('price');
        $input = input('input');
        $order_id = input('order_id');
        $_pay = Config::get($pay);
        
        $this->$_pay($pay,$price,$order_id);
    }

    /**
     * jpay 捷支付 生成订单
     * $pay =1 支付宝 =2微信支付
    */
    public function jpay($pay,$price,$order_id){
        if($pay=='alipay'){
            $pay=1;
        }else{
            $pay=2;
        }
        
        $index = Db::name('index')->where('key','jpay')->find();
        if($index){
            $_index = json_decode($index['value'],true);
            $appid = $_index['appid']; //APPID
            $apptoken = $_index['apptoken']; //apptoken
            if(!$apptoken){
                exit(json_encode(['code'=>1,'msg'=>'apptoken不存在']));
            }
            
        }
        
      
        $is_ok = "true"; //false=返回参数 true=返回支付扫码界面
        $diy = "1"; //自定义参数，可以使用用户id之类的配合回调使用 
        
        $sign = md5($appid.$apptoken);//生成订单时的签名
        
        $data = "http://pay.joo.life/Corder?appid=".$appid."&code=".$pay."&order_id=".$order_id."&order_rmb=".$price."&sign=".$sign."&is_ok=".$is_ok."&diy=".$diy;
        
        echo "<script>window.location.href = '" . $data . "'</script>";//向官方API发送请求
    }
    
    
     /**
     * jpay 捷支付通知
     * 异步回调
     */
    public function jpay_notify(){
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        
        
        
        $index = Db::name('index')->where('key','jpay')->find();
        if($index){
            $_index = json_decode($index['value'],true);
            $appid = $_index['appid']; //APPID
            $apptoken = $_index['apptoken']; //apptoken
            
        }else{
            exit(json_encode(['code'=>1,'msg'=>'商家未填写对接数据'],JSON_UNESCAPED_UNICODE));
        }
        
        
        
        $code = input('code'); //支付方式 ：支付宝支付为1 微信支付为2
        $order_id = input('order_id'); //订单号
        $order_rmb = input('order_rmb'); //支付金额
        $diy = input('diy'); //自定义参数
        
        $sign = input('sign'); //接收签名
        
        $_sign = md5($appid.$apptoken.$code.$order_id.$order_rmb.$diy);//制作签名
        
        if ($_sign != $sign) {
            echo "error";//sign校验不通过
            exit();
        }
        
        $this->process_orders($order_id);
        
        echo "success";
    }
    
    
    /**
     * Vpay V免签 生成订单
     * $pay =1 微信支付  =2支付宝
    **/
    
    public function vpay($pay,$price,$order_id){
        if($pay=='alipay'){
            $pay=2;
        }else{
            $pay=1;
        }
        
        $index = Db::name('index')->where('key','vpay')->find();
        
        if($index){
            $_index = json_decode($index['value'],true);
            $url = $_index['url']; //链接
            $token = $_index['token']; //token
            if(!$token){
                exit(json_encode(['code'=>1,'msg'=>'未设置Vpay密钥']));
            }
            
        }
        
        $param='kpl';
        
        $_url = $url . "/createOrder";
        
        $sign = md5($order_id.$param.$pay.$price.$token);
        $p = "payId=" . $order_id . '&param=' . $param . '&type=' . $pay . "&price=". $price . '&sign=' . $sign . '&isHtml=1';
        
        echo "<script>window.location.href = '". $_url ."?" . $p ."'</script>";

        
        
    }
    
    /**
     * V免签异步回调
    **/
    
    public function vpay_notify(){
        
        ini_set("error_reporting","E_ALL & ~E_NOTICE");
        
        $index = Db::name('index')->where('key','vpay')->find();
        
        if($index){
            $_index = json_decode($index['value'],true);
            $url = $_index['url']; //链接
            $token = $_index['token']; //token
            if(!$token){
                exit(json_encode(['code'=>1,'msg'=>'未设置Vpay密钥']));
            }
            
        }
        
        $payId = $_GET['payId'];//商户订单号
        $param = $_GET['param'];//创建订单的时候传入的参数
        $type = $_GET['type'];//支付方式 ：微信支付为1 支付宝支付为2
        $price = $_GET['price'];//订单金额
        $reallyPrice = $_GET['reallyPrice'];//实际支付金额
        $sign = $_GET['sign'];//校验签名，计算方式 = md5(payId + param + type + price + reallyPrice + 通讯密钥)
        //开始校验签名
        $_sign =  md5($payId . $param . $type . $price . $reallyPrice . $token);
        if ($_sign != $sign) {
            echo "error_sign";//sign校验不通过
            exit();
        }
        
        $this->process_orders($payId);
        
        
        echo "success";
    }
    
    
// +----------------------------------------------------------------------
// |分割线 程序作者QQ32579135
// +----------------------------------------------------------------------    
    
    
    /**
     * 处理订单
     * 
     * 
    **/
    
    public function process_orders($order_id){
        
        #获取下单数据
       $orders = Db::name('orders')->where(['order_id'=>$order_id])->find();
       if(!$orders){
         exit(json_encode(['code'=>1,'msg'=>'订单不存在'],JSON_UNESCAPED_UNICODE));  
       }
       
       #修改订单状态
       $_orders = Db::name('orders')->where(['order_id'=>$order_id])->update(['order_state'=>0]);
       
       #查询服务器配置
       $kpl_goods = Db::name('goods')->where(['id'=>$orders['order_gid']])->find();
       
       
       $day = $orders['order_input']*30;//购买天数
       
           
        $url   = $_SERVER['HTTP_HOST'];
        $data = explode('.', $url);
        $co_ta = count($data);
        $zi_tow = true;
        $host_cn = 'com.cn,net.cn,org.cn,gov.cn';
        $host_cn = explode(',', $host_cn);
        foreach($host_cn as $host){
            if(strpos($url,$host)){
                $zi_tow = false;
            }
        }
        if($zi_tow == true){
            $host = $data[$co_ta-2].'.'.$data[$co_ta-1];
        }else{
            $host = $data[$co_ta-3].'.'.$data[$co_ta-2].'.'.$data[$co_ta-1];
        }
        
        
        $_host = $order_id.'.'.$host; #二级域名
        
        
        $webname = ['domain'=>$_host,'domainlist'=>[],'count'=>0];
        $path = '/www/wwwroot/' + $_host;
        $type_id = 0; 
        $type = 'PHP';
        
        $version = 70; #默认70版本
        
        $port = 80; #默认80端口
        
        $ps = $kpl_goods['title'] . '：' . $orders['order_id'];
        
        // 创建FTP
        $ftp = 'true';
        $ftp_username = str_replace(".","_",$_host);
        $ftp_password = Random_password(12);
        
        // 创建数据库
        $sql = 'true';
        $codeing = 'utf8';
        $datauser =  $ftp_username;
        $datapassword = $ftp_password;
        
        $index = Db::name('index')->where('key','bt')->find();
        $bt = json_decode($index['value'],true);
        $res = AddSite($bt['bt_url'],trim($bt['bt_key']),$webname,$path,$type_id,$type,$version,$port,$ps,$ftp,$ftp_username,$ftp_password,$sql,$codeing,$datauser,$datapassword);
        
        $_res = $res;
        if($_res){
            if($_res['ftpStatus'] == true){
                #创建成功 $_host
                $weblist = WebList($bt['bt_url'],trim($bt['bt_key']));
                $data_weblist = array_column($weblist['data'], 'id', 'name');
                
                #检查二级域名是否存在
                $bt_host = array_key_exists($_host, $data_weblist);
                
                #获取宝塔ID
                $bt_id = $bt_host ? $data_weblist[$_host] : '';
    
            }else{
               exit(json_encode(['code'=>1,'msg'=>'服务器创建失败','error'=>$_res],JSON_UNESCAPED_UNICODE));  
            }  
        }
        
        #设置到期时间
        $time = time();
        $etime = $day*86400;
        $etime = $etime+$time;
        $etime = date('Y-m-d', $etime);
        
        SetEdate($bt['bt_url'],trim($bt['bt_key']),$bt_id,$etime);
        
        #写入数据库
        $datas['goods_id'] = $kpl_goods['id'];
        $datas['user_id'] = $orders['user_id'];
        if(!$datas['user_id']){
           exit(json_encode(['code'=>1,'msg'=>'用户名为空，请联系管理员补单'],JSON_UNESCAPED_UNICODE)); 
        }
        $datas['time'] = time();
        $datas['month'] = $orders['order_input'];
        $datas['bt_id'] = $bt_id;;
        $insert_host = Db::name('host')->insert($datas);
        if(!$insert_host){
           exit(json_encode(['code'=>1,'msg'=>'服务器创建失败'],JSON_UNESCAPED_UNICODE)); 
        }
    }
    
}