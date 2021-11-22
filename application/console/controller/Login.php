<?php
namespace app\console\controller;

use phpmailer\PHPMailer;
use think\Controller;
use think\Db;
use think\Session;
use think\Model;
use think\Request;
/**
 * 控制台登录 注册控制器
 * 
 * 作者 pmhw
 * qq   32579135
 * 如遇到任何问题请联系我
*/

class Login extends Controller
{
    
    //登录界面
    public function login(){
        
        if(session('user')){
            $this->success('您已登录', '/console');
        }
        
        $index = Db::name('index')->where('key','frontend')->find();
        
        $_index = json_decode($index['value'],true);
        
        $frontend = $_index['title'];
        
        $this->assign('frontend',$frontend);
        return $this->fetch();
    }
    
    //注册界面
    public function register(){
        
        if(session('user')){
            $this->success('您已登录', '/console');
        }
        
        $codeTime = session('codeTime');
        if(isset($codeTime)){
           $this->assign('codeTime',$codeTime);
        }
        
        $index = Db::name('index')->where('key','frontend')->find();
        
        $_index = json_decode($index['value'],true);
        $frontend = $_index['title'];       
        
        $this->assign('frontend',$frontend);
        
        return $this->fetch();
    }
    
    public function add_login(){
        $username = trim(input('post.username'));
        $password = trim(input('post.password'));
        $captcha = input('post.captcha');
        if(!$username || $username==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写用户名']));
        }
        if(!$password || $password==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写密码']));
        }
        if(!$captcha || $captcha==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写验证码']));
        }
        
        if(!captcha_check($captcha)){
           exit(json_encode(['code'=>1,'msg'=>'验证码错误']));
        }
        
        $user = Db::name('user')->where(['username'=>$username])->find();
        if(!$user){
             exit(json_encode(['code'=>1,'msg'=>'账号或密码错误！']));
        }
        
        if(md5($password) != $user['password']){
           exit(json_encode(['code'=>1,'msg'=>'账号或密码错误！'])); 
        }
        
        if($user['state']>0){
            exit(json_encode(array('code'=>1,'msg'=>'该帐号已被封禁')));
        }
        
        session('user',$user);
        exit(json_encode(array('code'=>0,'msg'=>'登录成功,欢迎回来')));
        
    }
    
    public function add_register(){
        $data['username'] = trim(input('post.username'));
        $data['password'] = trim(input('post.password'));
        $captcha = input('post.captcha');
        if(!$data['username'] || $data['username']==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写用户名']));
        }
        if(!$data['password'] || $data['password']==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写密码']));
        }
        if(!$captcha || $captcha==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写验证码']));
        }
        $code = session("qqcode");
        if($code != $captcha){
          exit(json_encode(['code'=>1,'msg'=>'验证码错误']));  
        }
        $data['add_time'] = time();
        $data['password'] = md5($data['password']);
        $insert = Db::name('user')->insert($data);
        if(!$insert){
            exit(json_encode(['code'=>1,'msg'=>'注册失败']));
        }
        exit(json_encode(['code'=>0,'msg'=>'注册成功']));
    }
    
    //找回界面
    public function back(){
        if(session('user')){
            $this->success('您已登录', '/console');
        }
        
        $codeTime = session('codeTime');
        if(isset($codeTime)){
           $this->assign('codeTime',$codeTime);
        }
        
        $index = Db::name('index')->where('key','frontend')->find();
        
        $_index = json_decode($index['value'],true);
        
        $frontend = $_index['title'];
        
        $this->assign('frontend',$frontend);
        
        return $this->fetch();
    }
    
    public function add_back(){
        $data['username'] = trim(input('post.username'));
        $data['password'] = trim(input('post.password'));
        $captcha = input('post.captcha');
        if(!$data['username'] || $data['username']==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写邮箱']));
        }
        if(!$data['password'] || $data['password']==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写密码']));
        }
        if(!$captcha || $captcha==''){
            exit(json_encode(['code'=>1,'msg'=>'请填写验证码']));
        }
        
        $code = session("qqcode");
        if($code != $captcha){
          exit(json_encode(['code'=>1,'msg'=>'验证码错误']));  
        }
        $data['password'] = md5($data['password']);
        $user = Db::name('user')->where(['username'=>$data['username']])->update(['password'=>$data['password']]);
        if(!$user){
           exit(json_encode(['code'=>1,'msg'=>'该账号未注册'])); 
        }
        exit(json_encode(['code'=>0,'msg'=>'修改成功'])); 
        

    }
    
    //退出登录
    public function outlogin(){
        Session::delete('user');
        exit(json_encode(array('code'=>0,'msg'=>'退出成功')));
    }
    
    
    //发送邮件
    public function sendEmail(){
        $email= input('get.username');
        if(!$email){
            exit(json_encode(array('code'=>1,'msg'=>'邮箱账户为空')));
        }
        //return $email;
        $sendmail = ''; //发件人邮箱 例如：xxx@qq.com
        $sendmailpswd = ""; //客户端授权密码,而不是邮箱的登录密码 不懂自行百度
        $send_name = '开普勒IDC';// 设置发件人信息，如邮件格式说明中的发件人，
        $toemail = $email;//定义收件人的邮箱
        $to_name = 'hl';//设置收件人信息，如邮件格式说明中的收件人
        $mail = new PHPMailer();
        $mail->isSMTP();// 使用SMTP服务
        $mail->CharSet = "utf8";// 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = "smtp.qq.com";// 发送方的SMTP服务器地址
        $mail->SMTPAuth = true;// 是否使用身份验证
        $mail->Username = $sendmail;
        $mail->Password = $sendmailpswd;//客户端授权密码,而不是邮箱的登录密码！
        $mail->SMTPSecure = "ssl";// 使用ssl协议方式
        $mail->Port = 465;//  qq端口465或587）
        $mail->setFrom($sendmail, $send_name);// 设置发件人信息，如邮件格式说明中的发件人，
        $mail->addAddress($toemail, $to_name);// 设置收件人信息，如邮件格式说明中的收件人，
        $mail->addReplyTo($sendmail, $send_name);// 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
        $mail->Subject = "验证消息";// 邮件标题
        $code=rand(100000,999999);
        session("qqcode",$code);
        $time = time();
        session("codeTime",$time);
        //return $code."----".session("code");
        $mail->Body = "邮件内容——您的验证码是：".$code."，如果非本人操作无需理会！";// 邮件正文
        if (!$mail->send()) { // 发送邮件
            exit(json_encode(array('code'=>1,'msg'=>'发送失败')));
            echo "Mailer Error: " . $mail->ErrorInfo;// 输出错误信息
        }else{
           exit(json_encode(array('code'=>0,'msg'=>'发送成功')));
        }  
    }
    
}