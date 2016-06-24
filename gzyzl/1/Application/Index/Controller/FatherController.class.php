<?php
namespace Index\Controller;



use initcore\InitCore;
/**
 * 锋尚爸爸专用类
 * @author ly-chengminbin
 *
 */
class FatherController extends InitCore{
    
    public function _initialize(){
        parent::_initialize();
        //这里写个 PV UV 统计函数
            //$this->_braRecord();
    }
    
    public function __call($method, $args){
        goHome();
    }
    
    /*首页*/
    public function index(){
        

    	$this->assign('wxkey',$this->getWxJsAPI());
        $this->display('index');
    }
    
    /*选择头像*/
    public function choosePortrait(){
        
        
        $this->assign('wxkey',$this->getWxJsAPI());
        $this->display('game');
    }
    
    /*上传头像  保存  第二步*/
    /*post base64encode image   post angle 图片角度*/
    public function savePortrait(){
        if(!IS_AJAX || !IS_POST){
            goHome();
        }
        
        //接收图片的角度 
        $angle = I('post.angle','0');
        
        
        //接收图片  base64encode的
        $img = $_POST['portrait'];
        $image_file = preg_replace('/data:.*;base64,/i', '', $img);
        
        $image_file = base64_decode($image_file);
        $name = createGuid(); // 取一个唯一的名称
        
        $path = 'upload';
        $subpath = date("Ym");
        $file = $name . '.jpg'; // 保存文件名称 

        if($image_file == false){
            $return['error'] = '43';
            $return['errormessage'] = '图片解码错误';
            exit(json_encode($return));
        }       
        
        $storage = new \Upload\SaeUpload();
        $info = $storage->upload($file, $image_file, $path,$subpath);   //保存进新浪云 
        
        /*下一步先临时保存在session 缓存  最后一起保存进数据库*/
        if($info['fileSize'] > 0){
            session('temp_portrait_url',$info['fileUrl']);    //保存上存图片的URL  新浪云的
            session('temp_portrait_angle',$angle);           //图片旋转角度
            $return['error'] = '0';
            $return['fileUrl'] = $info['fileUrl'];
            $return['sss'] = $info['ssss'];
            $return['errormessage'] = '图片保存成功';
            $return['shareUrl'] = $this->saveGame();;
            exit(json_encode($return));
        }else{
            $return['error'] = '13';
            $return['errormessage'] = '图片上传失败';
            exit(json_encode($return));
        }
        
    }//end func
    
    /**
     * 开始玩游戏
     */
    public function game(){
        
        $this->assign('wxkey',$this->getWxJsAPI());
        $this->display('game');
    }//end func

    /**
     * phf
     */
    public function phf(){
    
    	$this->assign('wxkey',$this->getWxJsAPI());
    	$this->display('phf');
    }//end func
    
    /**
     * 保存游戏
     * @post string  pose杯 url
     * @post string  粘纸url
     */
    public function saveGame(){
        if(!IS_POST || !IS_AJAX){
            goHome();
        }
        
        $pose_url = I('post.pose_girl_url');
        $sticker_url = I('post.sticker_url');    //贴纸 url
      //  $pose_font_url = I('post.pose_font_url');   //字体 url
        $top = I('post.sticker_top',0);
        $left = I('post.sticker_left',0);
        $bra_type = I('post.bra_type','moli');
        
        //保存的用户上传图片的url
        $data['portrait_url'] = session('temp_portrait_url');    //  图片url
        $data['img_angle'] = session('temp_portrait_angle');    //角度
        $pdo = new \initcore\Database('mysql');
        $pdo->table = 'fathers_day';
        $action = $pdo->normalAdd($data);
        if(is_numeric($action)){
            $return['error'] = '0';
            $return['url'] = '/father/share' . $action;
            return APP_DOMAIN.'/father/share' . $action;
            //exit(json_encode($return));
        }
        return '';
    }//end save game
    
    
    /**
     * 分享链接
     */
    public function share(){
        
        $id = I('get.id',0,'int');
        if($id == 0){
            goHome();
        }
        
        $pdo = new \initcore\Database('mysql');
        $pre_sql = "select * from fathers_day where id=:id";
        $pre_action = $pdo->pdo->prepare($pre_sql);   
        
        $pre_action->bindParam(":id",$id,\PDO::PARAM_INT);
        
        $action = $pre_action->execute();    //执行操作
        
        if($action){
            $result = $pre_action->fetchAll(\PDO::FETCH_ASSOC);            
        }else{
            redirect('/father');
        }       
        if(empty($result)){
            redirect('/father');
        }
        
        $this->assign('wxkey',$this->getWxJsAPI());
       // $this->assign('braClass',$result[0]['bra_type']);
        $this->assign('key',json_encode($result[0]));
        $this->display('share');
    }//end share
    
    /**
     * 用户访问登录记录
     */
    private function _pvRecord($log_out = false){
        $ip = get_client_ip();
        $openid = session('wx_openid') ? session('wx_openid'):0;
        if(session('wx_autologin')){
            $data['login_time'] = date("Y-m-d H:i:s");
            session('wx_autologin',null);
        }
        if($log_out){
            $data['logout_time'] = date("Y-m-d H:i:s");
        }
        $data['rec_ip'] = $ip;
        $data['openid'] = $openid;
        $data['rec_url'] = __SELF__;
        $pdo = new \initcore\Database('mysql');
        $pdo->table = 'fathers_day_record';
        $pdo->normalAdd($data);
    }
}//end class