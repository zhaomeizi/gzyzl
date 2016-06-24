<?php
return array(
	//'配置项'=>'配置值'
	'URL_MODEL'=>2,
        
        'URL_ROUTER_ON'   => true,   //开启路由
        'URL_ROUTE_RULES'=>array(
               
                //微信公众号相关
                '/^wc/'           =>   'Weixin/Weixin/response',    //公众号接口认证
        
                '/^map$/'                   =>     'Index/Map/index',   //地图小游戏
                '/^cloth$/'                 =>     'Index/Cloth/index',   //换衣小游戏--首面
                '/^cloth\/game/'          =>     'Index/Cloth/game',    //换衣小游戏--开搞
                '/^cloth\/share-(\d+)/'           =>     'Index/Cloth/share?id=:1',   //换衣- 分享页面
                '/^cloth\/gencard/'         =>    'Index/Cloth/genCard',   //生成贺卡,点击生成贺卡时调用
                
                '/^bra$/'              =>   'Index/Bra/index',  //bra 首页 小游戏  七大杯
                '/^bra\/portrait/'       =>   'Index/Bra/choosePortrait',   //七大杯选择头像  "点 我要玩" 进来 先返头像
                '/^bra\/game/'          =>   'Index/Bra/game',  //七大杯 开始玩游戏  选择 罩杯  与 选择  粘纸类型
                '/^bra\/saveGame/'      =>    'Index/Bra/saveGame', //七大杯  保存游戏
                '/^bra\/share(\d+)/'    =>    'Index/Bra/share?id=:1',   //七大杯游戏  分享链接
                '/^bra\/stat/'          =>   'Index/BraStatistical/getData',  //七大杯 游戏统计

        		'/^father$/'              =>   'Index/Father/index',  //bra 首页 锋尚爸爸
        		'/^father\/portrait/'       =>   'Index/Father/choosePortrait',   //锋尚爸爸选择头像  "点 我要玩" 进来 先返头像
        		'/^father\/game/'          =>   'Index/Father/game',  //锋尚爸爸 开始玩游戏  选择 罩杯  与 选择  粘纸类型
        		'/^father\/saveGame/'      =>    'Index/Father/saveGame', //锋尚爸爸  保存游戏
        		'/^father\/share(\d+)/'    =>    'Index/Father/share?id=:1',   //锋尚爸爸  分享链接
                '/^father\/savePortrait/'    =>   'Index/Father/savePortrait',   //保存用户上传的图片  这是第二步 需要 base64_decode
        		'/^father\/phf/'              =>   'Index/Father/phf',  //phf个人测试页面
                        		
                '/^wx\/saveImg/'        =>   'Weixin/Upload/saveImg',  //调用微信接口保存用户上传图片  第一步
                '/^bra\/savePortrait/'    =>   'Index/Bra/savePortrait',   //保存用户上传的图片  这是第二步 需要 base64_decode
                
                '/^upload/'      =>  'Index/Test/t',
                '/^test$/'                 =>    'Index/Cloth/testSQL',   //测试SQL 语句 
                '/^ddd\/aaa$/'          =>   'Index/Test/aa',  //测试
                '/^surl\/gen(.*)/'          =>     'Weixin/Shorturl/gen?id=:1',   //短链接
                
                '/^(.*)/'          =>   '/',   //狙击任何其它链接访问
                
        ),	
        
        
        
        
/* 数据库设置 */

    'DB_PARAMS'          	=>  array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL), // 数据库连接参数    
    'DB_DEBUG'  			=>  TRUE, // 数据库调试模式 开启后可以记录SQL日志
    'DB_FIELDS_CACHE'       =>  false,        // 启用字段缓存
    'DB_CHARSET'            =>  'utf8',      // 数据库编码默认采用utf8
	'DEFAULT_MODULE'        =>  'Index',  // 默认模块
	'DEFAULT_CONTROLLER'    =>  'Index', // 默认控制器名称
	'DEFAULT_ACTION'        =>  'index', // 默认操作名称	
	'MODULE_ALLOW_LIST'    =>    array('Index','Weixin'),   //允许访问的模块列表
	'URL_HTML_SUFFIX'       =>  'html',  // URL伪静态后缀设置
	'URL_CASE_INSENSITIVE'  =>  true,  // URL区分大小写
	'TMPL_ACTION_ERROR'     =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认错误跳转对应的模板文件
	'TMPL_ACTION_SUCCESS'   =>  THINK_PATH.'Tpl/dispatch_jump.tpl', // 默认成功跳转对应的模板文件
	//'TMPL_EXCEPTION_FILE'   =>  THINK_PATH.'Tpl/404.html',// 异常页面的模板文件	
	'TMPL_EXCEPTION_404'   =>  THINK_PATH.'Tpl/404.html',// 异常页面的模板文件
	//	'SHOW_ERROR_MSG'        =>  true,    // 显示错误信息
	'SHOW_ERROR_MSG'        =>  false,    // 显示错误信息
	

		
);