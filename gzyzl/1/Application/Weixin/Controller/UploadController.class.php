<?php
namespace Weixin\Controller;

use Weixin\Weixin;


/**
 * 微信接口上传专用类
 * @author ly-chengminbin
 *
 */

class UploadController extends Weixin{
    
    /*保存图片  第一步*/
    public function saveImg(){
        if(!IS_POST || !IS_AJAX){
            goHome();
        }
        
        $media_id = I ( 'post.wxservid' ); // 接收微信图片ID
        $guid_name = createGuid ();
        
       
        $info = $this->_downloadImg( $guid_name,$media_id ); // 调用WX 下载图片 保存进 数组
        
        exit(json_encode($info));
        
    }//end func
    
    
    /*从微信服务器下载图片*/
    private function _downloadImg($name,$media_id){
        $url = $this->P_imageDownloadURL .$this->P_accessToken . '&media_id=' . $media_id;
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $ex = curl_exec($ch); // 图片的内容 可以直接保存在图片文件中
        $http_info = curl_getinfo($ch); // 图片的各详细信息
        curl_close($ch);
        $return['img_content'] = $ex;
        $return['img_info'] = $http_info;
        
        if ($http_info['download_content_length'] <= 1000) {
            $return['error'] = '11';
            $return['errormessage'] = '图片格式错误,请重新选择!'.$media_id;
            return $return;
        }
        
        $imgtype = $http_info['content_type']; // 图片类型
        switch ($imgtype) {
            case 'image/jpeg':
            default:
                $ext = '.jpg';
                break;
            case 'image/png':
                $ext = '.png';
                break;
        }        
        
        $savename = $name . $ext;     //保存文件名
        
        /*下一步保存到 SAE服务器   (这是第一步)*/
       $ret =  $this->_uploadToSae($savename, $ex);

       if($ret['fileSize'] > 0){
           $ret['error'] = '0';
           return $ret;
       }else{
           $ret['error'] = '10';
           $ret['errormessage'] = '图片保存错误';
           return $ret;
       }
       
       
    }//end func
    
    
    /**保存进SAE服务器
     * @param string $savename  保存文件名
     * @param string ex  图片内容 
     * @param string imgtype  图片类型
     */
    
    private function _uploadToSae($savename, $ex){
        $storage = new \Upload\SaeUpload();
        
        $savepath = 'upload';
        $subpath = 'temp';
        
        
        $info = $storage->upload($savename, $ex, $savepath,$subpath);   //第一步  上传的是从微信下载的图片信息   不需要 base64 decode
        return $info;
    }//end function
    
    
    
}//end class