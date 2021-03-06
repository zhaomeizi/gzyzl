<?php
namespace applog;

/**
 * 日志专用类  公用
 * @author ly-chengminbin
 *
 */
class AppLog{
    
    /**
     * 日志方法
     * @param string $dir   日志目录
     * @param array $data   日志内容 
     * 
     */
   static public  function appLog($dir,$data){

       $rootPath = APP_LOG_PATH . '/' .$dir;
       if(!is_dir($rootPath)){
           @mkdir($rootPath,0777,true);
       }
       
       $file = $rootPath .'/' .date("Y-m-d");
       $res = fopen($file, 'a');
       
       $content = self::_rebuildArray($data);
       fwrite($res,$content);
/*        if(!is_array($data)){
           fwrite($res, '日志:' . $data .  PHP_EOL);
       }else{
           foreach ($data as $k => $v){
               fwrite($res, $k .':'.$v.  PHP_EOL);
           }
       } */
       fwrite($res, '记录时间:' . date("Y-m-d H:i:s") .  PHP_EOL);
       fwrite($res, '-------------------------------------------' . PHP_EOL);
       fclose($res);
        
    }//end function
    /**
     * 把数组重组成字符串
     * @param unknown $data
     */
    static private function _rebuildArray($data){
        
        if(!is_array($data)){
            return $data . PHP_EOL;
        }else{
            $str = '';
            foreach ($data as $k => $v){
                if(!is_array($v)){
                    $str .= $k . ':' .$v . PHP_EOL;;
                }else{
                    $str .= $k .'=>{' . PHP_EOL;
                    $str .= self::_rebuildArray($v);
                    $str .= '}' . PHP_EOL;
                }
            }
        }//end else if       
        
        return $str;
    }//end function
}//end class
