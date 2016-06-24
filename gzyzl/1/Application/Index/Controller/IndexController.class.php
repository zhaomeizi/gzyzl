<?php
namespace Index\Controller;



use initcore\InitCore;
/**
 * 默认 主页类
 * @author ly-chengminbin
 *
 */
class IndexController extends InitCore{
    
    /**
     * 默认主页
     */
    public function index(){
        
        redirect('/cloth');
        exit();
        
        $this->display('index');
    }//end 
    
    
    
}//enc class