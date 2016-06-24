<?php
namespace Upload;
use sinacloud\sae\Storage as Storage;
/**
 * sae 专用上传类
 * @author ly-chengminbin
 *
 */
class SaeUpload{
    
    /*upload file*/
    public function upload($savename,$content,$rootpath,$subpath = '', $base64 = false){
        $s = new Storage();
        // 获取Bucket列表
        $bkList = $s->listBuckets(true);
        if($base64){
            $content = base64_decode($content);
        }
        if($subpath!=''){
            $savepath = $rootpath . '/';
        }else{
            $savepath = $rootpath;
        }
        
        $f = file_put_contents(SAE_TMP_PATH.$savename, $content);
        
        // 把$_FILES全局变量中的缓存文件上传到test这个Bucket，设置此Object名为1.txt
        $s->putObjectFile(SAE_TMP_PATH.$savename, $savepath.$subpath, $savename);
        
        if($subpath!=''){
            $uri = 'http://gzyzl-'.$rootpath.'.stor.sinaapp.com/'.$subpath.'/'.$savename;
        }else{
            $uri = 'http://gzyzl-'.$rootpath.'.stor.sinaapp.com/'.$savename;
        }
         $uri_sina = $s->getUrl($rootpath,$subpath.'/'.$savename);
        
        $return['fileSize'] = $f;
        $return['fileUrl'] = $uri_sina;
        $return['file'] = base64_encode($content);
        //$return['ssss'] = $uri_sina;
        return $return;
        
    }//end func
}//end class