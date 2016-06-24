<?php
namespace Weixin;

/**
 * 微信公众号JS-SDK 认证专用类
 *
 * @author ly-chengminbin
 *        
 */
class WeixinJsApi extends Weixin
{

    const GET_JSAPI_KEY_URL = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=';

    public function getSignPackage ()
    {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ||
                 $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        
        $timestamp = time();
        $nonceStr = $this->createNonceStr();
        
        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
        
        $signature = sha1($string);
        
        $signPackage = array(
                "appId" => WX_APP_ID,
                "nonceStr" => $nonceStr,
                "timeStamp" => $timestamp,
                "url" => $url,
                "signature" => $signature,
                "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr ($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i ++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket ()
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        // $data = json_decode(file_get_contents("jsapi_ticket.json"));
        $data = S('wx_jsapi_ticket');
        
        if (! $data) {
            // $accessToken = $this->getAccessToken();
            $accessToken = $this->P_accessToken;
            // 如果是企业号用以下 URL 获取 ticket
            // $url =
            // "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = self::GET_JSAPI_KEY_URL . $accessToken;
            $res = json_decode($this->P_httpsend($url,'','get'));
            // dump($res);
            $ticket = $res->ticket;
            if ($ticket) {
                /**
                 * $data->expire_time = time() + 7000;
                 * $data->jsapi_ticket = $ticket;
                 * $fp = fopen("jsapi_ticket.json", "w");
                 * fwrite($fp, json_encode($data));
                 * fclose($fp);
                 */
                S('wx_jsapi_ticket', $ticket, WX_TOKEN_STORE_TIME);
            }
        } else {
            
            $ticket = $data;
        }
        
        return $ticket;
    }
}//end class