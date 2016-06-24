<?php
namespace Weixin;

/**
 * 公众号菜单专用类
 * @author ly-chengminbin
 *
 */
class WeixinMenu extends Weixin{
    /* 菜单相关URL */
    const MENU_CREATE_URL         = 'https://api.weixin.qq.com/cgi-bin/menu/create';
    const MENU_GET_URL            = 'https://api.weixin.qq.com/cgi-bin/menu/get';
    const OPENQQ_URL               = 'https://open.weixin.qq.com/connect/oauth2/authorize?';
    
    /**
     * 菜单获取
     * @return array  $data
     */
    public  function getMenu(){
        $url = self::MENU_GET_URL;
    
        $param = array('access_token' => $this->P_accessToken);
        $menu = $this->P_httpsend($url,$param,'get');
    
        return $menu;
    }

    
    /**
     * 创建菜单
     */
    public function createMenu(){
        $url = self::MENU_CREATE_URL . '?access_token=' . $this->P_accessToken;
        $redirect_url = urlencode(APP_DOMAIN);     //应用的URL 域名
        $openUrl = self::OPENQQ_URL . 'appid=' . WX_APP_ID . '&redirect_uri='. $redirect_url .'&response_type=code&scope=snsapi_base&state={state}#wechat_redirect';
        
        
        
    }//end create menu
    
    /**
     * 不转义中文字符和\/的 json 编码方法
     * @param  array $array
     * @return json
     * @author 、小陈叔叔 <cjango@163.com>
     */
    private function jsonEncode($array = array()) {
        $array = str_replace("\\/", "/", json_encode($array));
        //$array = json_encode($array);
        $search = '#\\\u([0-9a-f]+)#ie';
        if (strpos(strtoupper(PHP_OS), 'WIN') === false) {
            $replace = "iconv('UCS-2BE', 'UTF-8', pack('H4', '\\1'))";//LINUX
        } else {
            $replace = "iconv('UCS-2', 'UTF-8', pack('H4', '\\1'))";//WINDOWS
        }
        return preg_replace($search, $replace, $array);
    }//end json   
    
}//end class