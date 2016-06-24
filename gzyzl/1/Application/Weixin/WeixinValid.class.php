<?php
namespace Weixin;

/**
 * 微信公众号接口认证类
 * @author ly-chengminbin
 *
 */

class WeixinValid extends Weixin{
    
    public function __construct(){
        
    }
    
    public function valid(){
        $echoStr = $_GET ["echostr"];
        
        // valid signature , option
        if ($this->_checkSignature ()) {
            echo $echoStr;
            exit ();
        }
     
       exit('what thing?'); 
    }
    
    /**
     * 验证URL有效性第二个方法
     *
     * @throws Exception
     * @return boolean
     */
    private function _checkSignature() {
        // you must define TOKEN by yourself
    
        $signature = $_GET ["signature"];
        $timestamp = $_GET ["timestamp"];
        $nonce = $_GET ["nonce"];
    
        $token = WX_AUTH_TOKEN;
        $tmpArr = array (
                $token,
                $timestamp,
                $nonce
        );
        // use SORT_STRING rule
        sort ( $tmpArr, SORT_STRING ); // 按大到小排序，按字符串
        $tmpStr = implode ( $tmpArr );
        $tmpStr = sha1 ( $tmpStr );
    
        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
        
}//end class