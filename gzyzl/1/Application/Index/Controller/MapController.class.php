<?php
namespace Index\Controller;



use initcore\InitCore;
/**
 * 地图小游戏
 * @author ly-chengminbin
 *
 */
class MapController extends InitCore{
    
    
    /**
     * 小游戏主页
     */
    public function index(){
        
        $this->display('index');
    }
}//end class