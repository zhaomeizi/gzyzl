<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 定义应用目录
define('APP_PATH','./Application/');

if(function_exists('saeAutoLoader')){// 自动识别SAE环境
    define('DB_NAME','app_gzyzl');
    define('DB_HOST',SAE_MYSQL_HOST_M);
    define('DB_USER',SAE_MYSQL_USER);
    define('DB_PWD',SAE_MYSQL_PASS);
    define('DB_PORT',SAE_MYSQL_PORT); 
    define('SAE_DEVELOPMENT',true);
}

define('APP_DOMAIN','http://gzyzl.applinzi.com');    //定义域名

//微信相关
define('WX_AUTH_TOKEN','gzyzlapp');
define('WX_APP_ID','wx2c2514839239f3dd');
define('WX_SECRET_ID','79d19dde27255fcd04907db5f5d3441d');
//define('WX_APP_ID','wx33c182aa7e4ad93c');
//define('WX_SECRET_ID','60e8afbf3c40b459076118a997bdcae2');
define('WX_TOKEN_STORE_TIME',7100);


// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单