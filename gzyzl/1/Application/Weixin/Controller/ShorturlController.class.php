<?php
namespace Weixin\Controller;


use Weixin\Weixin;
class ShorturlController extends Weixin{
    
    /**
     * 生成短链接
     */
    public function gen(){
        
        $id = $_GET['id'];
        if($id != '110110122'){
            goHome();
        }
        
        $genUrl = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token=';
        $genUrl = $genUrl . $this->P_accessToken;
        
        $long_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx33c182aa7e4ad93c&redirect_uri=http%3A%2F%2Fwww.kohian.com%2Fbra&response_type=code&scope=snsapi_base&state=aaabcd#wechat_redirect';
        
        $data['access_token'] = $this->P_accessToken;
        $data['action'] = 'long2short';
        $data['long_url'] = $long_url;
        
        $ret = appCurlSend($genUrl,$data,'POST');
        dump($genUrl);
        dump($ret);
        
    }//end function
    
}//end class