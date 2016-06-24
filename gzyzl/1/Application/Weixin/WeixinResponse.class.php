<?php
namespace Weixin;


/**
 * 微信回复消息 专用类
 * @author ly-chengminbin
 *
 */
class WeixinResponse extends Weixin{
    
    private $_postObj;
    
    /**
     * 根据用户交互回复 相关信息
     */
    public function responseMsg ()
    {
        // get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (empty($postStr)) {
            $postStr = file_get_contents("php://input");
        }
        // extract post data
        if (! empty($postStr)) {
            /*
             * libxml_disable_entity_loader is to prevent XML eXternal Entity
             * Injection,
             * the best way is to check the validity of xml by yourself
             */
    
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement',
                    LIBXML_NOCDATA);
            $this->_postObj = $postObj;
    
            switch ($postObj->MsgType) {
                case 'text':
                    $this->text($keyword);
                    break;
    
                case 'event':
                    $this->event();
                    break;
            }
        } // end if empty
    }//end function
    
    private function text(){
        
    }
    
    private function event(){
        
    }
    
}//end class