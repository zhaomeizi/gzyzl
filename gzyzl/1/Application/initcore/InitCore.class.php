<?php
namespace initcore;



use Think\Controller;
/**
 * 核心类
 * @author ly-chengminbin
 *
 */
class InitCore extends Controller{
    
    //初始化方法
    public function _initialize(){
        //这里写个微信自动登录
        $this->_wxAutoLogin();
    }
    
    /**
     * 获取微信JS-SDK 配置
     */
    public function getWxJsAPI(){
        $wx = new \Weixin\WeixinJsApi();
        return $wx->getSignPackage();
    }
    
    /**
     * 微信自动登录
     */
    private function _wxAutoLogin(){
        if(isset($_GET['code']) && isset($_GET['state'])){
            session('wx_autologin_code',$_GET['code']);
            session('wx_autologin_state',$_GET['state']);
            $wx = new \Weixin\AutoLogin();
            $wx->login();
        }
        
    }//end func
    
}//end class