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



#### 特技

1.  使用 Readme\_XXX.md 来支持不同的语言，例如 Readme\_en.md, Readme\_zh.md
2.  Gitee 官方博客 [blog.gitee.com](https://blog.gitee.com)
3.  你可以 [https://gitee.com/explore](https://gitee.com/explore) 这个地址来了解 Gitee 上的优秀开源项目
4.  [GVP](https://gitee.com/gvp) 全称是 Gitee 最有价值开源项目，是综合评定出的优秀开源项目
5.  Gitee 官方提供的使用手册 [https://gitee.com/help](https://gitee.com/help)
6.  Gitee 封面人物是一档用来展示 Gitee 会员风采的栏目 [https://gitee.com/gitee-stars/](https://gitee.com/gitee-stars/)
