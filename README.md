# 开普勒IDC-宝塔分销面板分销系统

#### 介绍
宝塔分销系统、BT分销系统、服务器分销系统、IDC

#### 图片展示

##### 前端

![输入图片说明](https://images.gitee.com/uploads/images/2021/1122/150931_ccf8d943_4920524.png "屏幕截图.png")
![输入图片说明](https://images.gitee.com/uploads/images/2021/1122/151003_1dbf903d_4920524.png "屏幕截图.png")


##### 管理端

![输入图片说明](https://images.gitee.com/uploads/images/2021/1122/151048_57bf859a_4920524.png "屏幕截图.png")
![输入图片说明](https://images.gitee.com/uploads/images/2021/1122/151124_7bd8c0c0_4920524.png "屏幕截图.png")
![输入图片说明](https://images.gitee.com/uploads/images/2021/1122/151141_410d9878_4920524.png "屏幕截图.png")

##### 用户端

![输入图片说明](https://images.gitee.com/uploads/images/2021/1122/160741_78d3b083_4920524.png "屏幕截图.png")
![输入图片说明](https://images.gitee.com/uploads/images/2021/1122/160726_d9d1ac1f_4920524.png "屏幕截图.png")



#### 软件架构
原生       首页
layuiMini  前端
Thinkphp   后端


#### 安装教程

1.  上传服务器
2.  设置运行目录为 public
3.  配置伪静态为 Thinkphp
伪静态：
```
location / {
	if (!-e $request_filename){
		rewrite  ^(.*)$  /index.php?s=$1  last;   break;
	}
}
```


#### 使用说明

1.  安装完成进入后台
2.  配置宝塔面板API
3.  配置支付接口

#### 参与贡献

作者：RedBull qq32579135

