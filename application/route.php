<?php
// +----------------------------------------------------------------------
think\Route::rule('adminKPL','admin/Index/index1');//请不要设置admin 否则会导致部分接口失效
think\Route::rule('admin_admin','admin/Index/admin');
think\Route::rule('menu1','admin/Index/menu1');
think\Route::rule('frontend','admin/Index/frontend');
think\Route::rule('menu2','admin/Index/menu2');
think\Route::rule('bt','admin/Index/bt');
think\Route::rule('bt_log','admin/Index/bt_log');
think\Route::rule('webList','admin/Index/bt_webList');
think\Route::rule('theme','admin/Index/theme');
think\Route::rule('pay_way','admin/Index/pay_way');
think\Route::rule('shop_list','admin/Index/shop_list');
think\Route::rule('add_shop','admin/Index/add_shop');
think\Route::rule('web_setting','admin/Index/web_setting');
think\Route::rule('jpay','admin/Index/jpay');
think\Route::rule('vpay','admin/Index/vpay');
think\Route::rule('add_jpay','admin/Index/add_jpay');
think\Route::rule('add_vpay','admin/Index/add_vpay');
think\Route::rule('work_order_list','admin/Index/work_order_list');
think\Route::rule('wkop','admin/Index/wkop');
think\Route::rule('order_list','admin/Index/order_list');
think\Route::rule('console_imgs','admin/Index/console_imgs');
think\Route::rule('console_affiche','admin/Index/console_affiche');

think\Route::rule('admin_login','admin/Login/login');//后台地址 建议自行修改防止被渗透
think\Route::rule('admin_dulogin','admin/Login/dulogin');
think\Route::rule('out_adminlogin','admin/Login/outlogin');

think\Route::rule('api_menu1','admin/Api/menu1');
think\Route::rule('api_menu2','admin/Api/menu2');
think\Route::rule('bt_GetNetWork','admin/Api/bt_GetNetWork');
think\Route::rule('bt_GetSystemTotal','admin/Api/bt_GetSystemTotal');
think\Route::rule('bt_webList','admin/Api/bt_webList');
think\Route::rule('bt_SiteStart','admin/Api/bt_SiteStart');
think\Route::rule('bt_SiteStop','admin/Api/bt_SiteStop');
think\Route::rule('theme_josn','admin/Api/theme_josn');
think\Route::rule('theme_setting','admin/Api/theme_setting');
think\Route::rule('pay_setting','admin/Api/pay_setting');
think\Route::rule('get_path_size','admin/Api/get_path_size');
think\Route::rule('api_app_shop','admin/Api/api_app_shop');
think\Route::rule('api_goods','admin/Api/api_goods');
think\Route::rule('betch_goods','admin/Api/betch_goods');
think\Route::rule('clear','admin/Api/clear');
think\Route::rule('api_work_order_list','admin/Api/api_work_order_list');
think\Route::rule('batch_work_order_state','admin/Api/batch_work_order_state');
think\Route::rule('work_order_state','admin/Api/work_order_state');
think\Route::rule('api_order_list','admin/Api/api_order_list');
think\Route::rule('delete_shuffling','admin/Api/delete_shuffling');
think\Route::rule('upload_shuffling','admin/Api/upload_shuffling');
think\Route::rule('mysql_shuffling','admin/Api/mysql_shuffling');
think\Route::rule('api_logo_img','admin/Api/api_logo_img');


think\Route::rule('add_menu','admin/Add/add_menu');
think\Route::rule('addMenu','admin/Add/addMenu');
think\Route::rule('editorMenu','admin/Add/editorMenu');
think\Route::rule('DeleteMenu','admin/Add/DeleteMenu');
think\Route::rule('add_frontend','admin/Add/add_frontend');
think\Route::rule('add_bt','admin/Add/add_bt');

// +----------------------------------------------------------------------

think\Route::rule('login','console/Login/login');
think\Route::rule('add_login','console/Login/add_login');
think\Route::rule('register','console/Login/register');
think\Route::rule('add_register','console/Login/add_register');
think\Route::rule('outlogin','console/Login/outlogin');
think\Route::rule('sendEmail','console/Login/sendEmail');
think\Route::rule('back','console/Login/back');
think\Route::rule('add_back','console/Login/add_back');

// +----------------------------------------------------------------------

think\Route::rule('consoles','console/Index/consoles');
think\Route::rule('myhost','console/Index/myhost');
think\Route::rule('shop_confirm','console/Index/shop_confirm');
think\Route::rule('create_order','console/Index/create_order');
think\Route::rule('update_user','console/Index/update_user');
think\Route::rule('yuming_list','console/Index/yuming_list');
think\Route::rule('delete_yuming','console/Index/delete_yuming');
think\Route::rule('add_yuming','console/Index/add_yuming');
think\Route::rule('run_directory','console/Index/run_directory');
think\Route::rule('add_run_directory','console/Index/add_run_directory');
think\Route::rule('web_rewrite','console/Index/web_rewrite');
think\Route::rule('rewrite_config','console/Index/rewrite_config');
think\Route::rule('rewrites_config','console/Index/rewrites_config');
think\Route::rule('add_rewrite','console/Index/add_rewrite');
think\Route::rule('web_off','console/Index/web_off');
think\Route::rule('web_on','console/Index/web_on');
think\Route::rule('add_work_order','console/Index/add_work_order');
think\Route::rule('add_mysql_work_order','console/Index/add_mysql_work_order');
think\Route::rule('look_work_order','console/Index/look_work_order');
think\Route::rule('work_order_page','console/Index/work_order_page');
think\Route::rule('add_mysql_wop','console/Index/add_mysql_wop');
think\Route::rule('myhost_set','console/Index/myhost_set');
think\Route::rule('user_password','console/Index/user_password');


think\Route::rule('ftp','console/Ftp/index');
think\Route::rule('ftp_api','console/Ftp/ftp_api');
think\Route::rule('add_password_ftp','console/Ftp/add_password_ftp');
think\Route::rule('add_passwordFtp','console/Ftp/add_passwordFtp');

think\Route::rule('mysql','console/Mysql/index');
think\Route::rule('mysql_api','console/Mysql/mysql_api');
think\Route::rule('add_password_mysql','console/Mysql/add_password_mysql');
think\Route::rule('add_passwordMysql','console/Mysql/add_passwordMysql');

// +----------------------------------------------------------------------

think\Route::rule('pay','pay/Index/index');
think\Route::rule('jpay_notify','pay/Index/jpay_notify');
think\Route::rule('vpay_notify','pay/Index/vpay_notify');



return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],
    
    
    

];
