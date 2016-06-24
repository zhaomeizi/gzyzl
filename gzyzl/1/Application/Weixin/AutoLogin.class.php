<?php
namespace Weixin;

/**
 * 微信自动登录专用类
 * @author ly-chengminbin
 *
 */
class AutoLogin extends Weixin{
    
    const LOGIN_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid={1}&redirect_uri={2}&response_type=code&scope=snsapi_base&state={3}#wechat_redirect';
    const OPEN_USER_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token?';
    /**
     * 用户点击菜单或链接直接登录
     */
    public function login(){
        
        $url = self::OPEN_USER_URL . 'appid='.WX_APP_ID.'&secret='.WX_SECRET_ID.'&code='.session('wx_autologin_code').'&grant_type=authorization_code';
        $info = file_get_contents($url);
        $ret  = json_decode($info);
        $openid = $ret->openid;
        if($openid){
            session('wx_openid',$openid);
            session('wx_autologin',true);
        }
    }//end func
    
}//end class