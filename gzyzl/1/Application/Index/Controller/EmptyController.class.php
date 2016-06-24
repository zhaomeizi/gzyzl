<?php
namespace Index\Controller;


use Think\Controller;
class EmptyController extends Controller{
    
    
    public function __call($a,$b ){
       goHome();
    }
}//end
