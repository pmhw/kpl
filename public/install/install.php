<?php
//判断php版本
if (version_compare(PHP_VERSION,'5.6','<')) die(' PHP 版本必须要 > 5.6');
//判断是否安装
if (is_file('install.lock') == true || is_file('install.lock')) {

    header("location: /index");
    exit;
}
$myfile = './install';
if (is_writable ($myfile)) {
$myfile = 0;
} else {
$myfile = 1;
}

//var_dump($myfile)
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>PMHW-宝塔服务器、虚拟主机服务商系统</title>
  <meta name="renderer" content="webkit">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
  <!-- 引入 layui.css -->
  <link rel="stylesheet" href="//unpkg.com/layui@2.6.8/dist/css/layui.css">

</head>
<body>
          
<blockquote class="layui-elem-quote layui-text">
  作者QQ：<a href="http://wpa.qq.com/msgrd?v=3&uin=826979076&site=qq&menu=yes" target="_blank">32579135</a>
  遇到问题？ <a href="" target="_blank">加入qq群：811749523</a>
</blockquote>
              
<fieldset class="layui-elem-field layui-field-title" style="margin-top: 20px;">
  <legend>BT云服务器、虚拟主机 | 服务商系统 ©PMHW</legend>
</fieldset>
 
<form class="layui-form" action="">
  <div class="layui-form-item">
    <label class="layui-form-label">MySQL数据库地址</label>
    <div class="layui-input-block">
      <input type="text" id="dizhi" lay-verify="required" autocomplete="off" lay-reqtext="MySQL数据库地址是必填项，岂能为空？"  placeholder="请输入标题" class="layui-input" value="127.0.0.1">
    </div>
  </div>
  <div class="layui-form-item">
    <label class="layui-form-label">MySQL数据库名称</label>
    <div class="layui-input-block">
      <input type="text" id="name1" lay-verify="required" lay-reqtext="MySQL数据库名称是必填项，岂能为空？" placeholder="请输入" autocomplete="off" class="layui-input">
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">MySQL用户名</label>
    <div class="layui-input-block">
      <input type="text" id="name2" lay-verify="required" lay-reqtext="MySQL用户名是必填项，岂能为空？" placeholder="请输入" autocomplete="off" class="layui-input">
    </div>
  </div>
    <div class="layui-form-item">
    <label class="layui-form-label">MySQL密码</label>
    <div class="layui-input-block">
      <input type="text" id="password" lay-verify="required" lay-reqtext="MySQL密码是必填项，岂能为空？" placeholder="请输入" autocomplete="off" class="layui-input">
    </div>
  </div>
  
  <div class="layui-form-item">
    <div class="layui-inline">
      <label class="layui-form-label">数据表前缀</label>
      <div class="layui-input-inline">
        <input type="tel" id="qianzhui" lay-verify="required"  lay-reqtext="数据表前缀是必填项，岂能为空？" autocomplete="off" class="layui-input" value="kpl_">
      </div>
    </div>
    <div class="layui-inline">
      <label class="layui-form-label">MySQL端口号</label>
      <div class="layui-input-inline">
        <input type="text" id="duankou" class="layui-input" value="" placeholder="默认为空可以不填">
      </div>
    </div>
  </div>
  <br>
<div class="layui-form-item">
    <label class="layui-form-label">网站后台登录名</label>
    <div class="layui-input-block">
      <input type="text" id="adminName" lay-verify="required"  placeholder="请输入" autocomplete="off" class="layui-input" value="admin">
    </div>
  </div>
  
      <div class="layui-form-item">
    <label class="layui-form-label">网站后台登录密码</label>
    <div class="layui-input-block">
      <input type="text" id="adminPassword" lay-verify="required" lay-reqtext="" placeholder="请输入" autocomplete="off" class="layui-input">
    </div>
  </div>

</form>
 
  <div class="layui-form-item">
    <div class="layui-input-block">
        
      <button type="reset" class="layui-btn layui-btn-primary">重置</button>
      <button onclick="installation()" class="layui-btn layui-bg-red" >立即安装</button>

    </div>
  </div>
<!-- 引入 layui.js -->
<script src="//unpkg.com/layui@2.6.8/dist/layui.js"></script>
<!-- 注意：如果你直接复制所有代码到本地，上述 JS 路径需要改成你本地的 -->
<!-- jQuery -->
<script src="https://cdn.staticfile.org/jquery/2.1.4/jquery.min.js"></script>
<script>
layui.use(['form', 'layedit', 'laydate'], function(){
  var form = layui.form
  ,layer = layui.layer
  ,layedit = layui.layedit
  ,laydate = layui.laydate;
  
});


function installation(){
    var dizhi = $('#dizhi').val();
    if(!dizhi){layer.msg("MySQL数据库地址是必填项",{icon:2}); return;}
    var name1 = $('#name1').val();
    if(!name1){layer.msg("MySQL数据库名称是必填项",{icon:2}); return;}
    var name2 = $('#name2').val();
    if(!name2){layer.msg("MySQL用户名是必填项",{icon:2}); return;}
    var password = $('#password').val();
    if(!password){layer.msg("MySQL密码是必填项",{icon:2}); return;}
    var qianzhui = $('#qianzhui').val();
    if(!qianzhui){layer.msg("数据表前缀是必填项",{icon:2}); return;}
    var duankou = $('#duankou').val();
    var adminName = $('#adminName').val();
    if(!adminName){layer.msg("网站后台登录名是必填项",{icon:2}); return;}
    var adminPassword = $('#adminPassword').val();
    if(!adminName){layer.msg("网站后台登录密码是必填项",{icon:2}); return;}
    
    $.post('/install/installation.php',{'dizhi':dizhi,'name1':name1,'name2':name2,'password':password,'qianzhui':qianzhui,'duankou':duankou},function(res){
        if(res.code>0){
            layer.msg(res.msg,{icon:3})
        }else{
            layer.msg('安装成功',{icon:1})
            layer.open({
              type: 1, 
              content: '请拍照或截图保存<br><br>前端：您的域名/index <br> 管理员后端：您的域名/admin_login <br>账号：admin 密码：admin <br>用户后端：您的域名/console' //这里content是一个普通的String
            });
        }

    },'json');
}
</script>

</body>
</html>

