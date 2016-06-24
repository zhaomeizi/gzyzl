<?php
namespace Weixin;

/**
 * 微信公众号相关
 * 专用父类
 * 
 * @author ly-chengminbin
 *        
 */
class Weixin
{

    protected $P_accessToken;
 // 微信接口令牌
                              // 图片下载URL
    protected $P_imageDownloadURL = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=';
    // 获取 ACCESS_TOKE URL
    protected $P_accessTokenURL = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential';

    public function __construct ()
    {
        $this->P_accessToken = $this->_getAccessToken();
    }

    /**
     * 获取 access_token
     *
     * @return {"access_token":"ACCESS_TOKEN","expires_in":7200}
     */
    private function _getAccessToken ()
    {
        // 如果 缓存 access_token 未过期 就直接返回值
        if (S('wx_access_token')) {
            return S('wx_access_token');
            exit();
        }
        // session 过期了 就重新 获取 access_token
        $url = "$this->P_accessTokenURL&appid=" . WX_APP_ID . "&secret=" .
                 WX_SECRET_ID;
        $ret = file_get_contents($url);
        $ret = json_decode($ret); // 解释JSON数据
        S('wx_access_token', $ret->access_token, WX_TOKEN_STORE_TIME); // 保存access_token缓存保存 时间 7100秒
        
        return $ret->access_token;
    }
    
    /**
     * 发送HTTP请求方法，目前只支持CURL发送请求
     *
     * @param string $url
     *            请求URL
     * @param array $params
     *            请求参数
     * @param string $method
     *            请求方法GET/POST
     * @param boolean $ssl
     *            是否进行SSL双向认证
     * @return json $data 响应数据
     * @author
     *
     */
    protected function P_httpsend ($url, $params = array(), $method = 'GET', $ssl = false)
    {
        $opts = array(
                CURLOPT_TIMEOUT => 30,
                CURLOPT_RETURNTRANSFER => 1, // 配置信息 取回结果
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false
        );
        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET':
            default:
                $opts[CURLOPT_URL] = $url;
                break;
            case 'POST':
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = http_build_query($params);
                break;
        }
        if ($ssl) {
            $pemPath = dirname(__FILE__) . '/Wechat/';
            $pemCret = $pemPath . $this->pem . '_cert.pem';
            $pemKey = $pemPath . $this->pem . '_key.pem';
            if (! file_exists($pemCret)) {
                $this->error = '证书不存在';
                return false;
            }
            if (! file_exists($pemKey)) {
                $this->error = '密钥不存在';
                return false;
            }
            $opts[CURLOPT_SSLCERTTYPE] = 'PEM';
            $opts[CURLOPT_SSLCERT] = $pemCret;
            $opts[CURLOPT_SSLKEYTYPE] = 'PEM';
            $opts[CURLOPT_SSLKEY] = $pemKey;
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $err = curl_errno($ch); // 错误编码
        $errmsg = curl_error($ch); // 错误信息
        curl_close($ch);
        if ($err > 0) {
            $this->error = $errmsg;
            return false;
        } else {
            return $data;
        }
    }   
    
}//end class