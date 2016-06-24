<?php
namespace Index\Controller;



use initcore\InitCore;
use initcore\Database;
/**
 * 换装游戏
 * @author ly-chengminbin
 *
 */
class ClothController extends InitCore{
    
    /**
     * 主页
     */
    public function index(){
        
        $this->display('index');
    }//end 
    
    
    /**
     * 分享--页面
     */
    public function share(){
        
        $id = I('get.id',0,'int');
        if($id == 0){
            goHome();
        }
        
        $sql = 'select * from girls where id='.$id;
        $pdo = new \initcore\Database('mysql');
        $ret = $pdo->query($sql);
        $result = $ret[0];    //获取第一次数据
        
        $p = array('.png','/Public/images/clothing/','hair_ornaments/','hair/','underwear/','grasses/',
                'pants/'
        );
            $result['hair_ornaments_class'] = str_replace($p,'',$result['hair_ornaments']);
            $result['hair_class'] = str_replace($p,'',$result['hair']);
            $result['pants_class'] = str_replace($p,'',$result['pants']);
            $result['grasses_class'] = str_replace($p,'',$result['grasses']);
            $result['underwear_class'] = str_replace($p,'',$result['underwear']);
        $this->assign('data',$result);
        $this->display('share');
    }//end share
    
    
    /**
     * 换衣 -- 游戏开始
     */
    public function game(){
        
        
        $this->display('game');
    }//end game
    
    /**
     * 生成贺卡   保存 图片路径
     * @$_GET  图片路径 
     * @param    string name 基友名称
     * @param    string greetings 祝福语
     * @param    string oranment_url  发饰url
     * @param string hair_url  头发url
     * @param string grassess_url  眼镜url
     * @param string underwear_url   内衣url
     * @param string pants_url    裤子 url
     */
    public function genCard(){
        if(!IS_POST || !IS_AJAX){
            goHome();
        }
        
        $name = I('post.name');   //基友名字
        if($name == '' || is_null($name)){
            $return['error'] = '1';
            $return['errormessage'] = '写个好基友名字呗!';
            exit(json_encode($return));
        }
        $greetings = I('post.greetings');  //祝福语
        if($greetings == '' || is_null($greetings)){
            $return['error'] = '2';
            $return['errormessage'] = '有什么对基友说说呗!';
            exit(json_encode($return));
        }       
        $oranment = I('post.oranment_url');    //发饰url
        if($oranment == '' || is_null($oranment)){
            $return['error'] = '3';
            $return['errormessage'] = '选个头饰呀';
            exit(json_encode($return));
        }       
        $hair = I('post.hair_url');   //发型
        if($hair == '' || is_null($hair)){
            $return['error'] = '4';
            $return['errormessage'] = '头呢?头呢?';
            exit(json_encode($return));
        }       
        $grasses = I('post.grasses_url');  //眼镜
        if($grasses == '' || is_null($grasses)){
            $return['error'] = '5';
            $return['errormessage'] = '选个眼镜棒棒哒!';
            exit(json_encode($return));
        }       
        $underwear = I('post.underwear_url');   //内衣
        if($underwear == '' || is_null($underwear)){
            $return['error'] = '6';
            $return['errormessage'] = '内衣呀! 不穿不好!';
            exit(json_encode($return));
        }       
        $pant = I('post.pants_url');   //裤子
        if($pant == '' || is_null($pant)){
            $return['error'] = '7';
            $return['errormessage'] = '不穿裤子不文明!!';
            exit(json_encode($return));
        }       
        
        
        $pdo = new Database();
        $pdo->table = 'girls';  //指定换装 表
        $data['friend'] = $name;
        $data['greetings'] = $greetings;
        $data['hair_ornaments'] = $oranment;
        $data['hair'] = $hair;
        $data['grasses'] = $grasses;
        $data['pants'] = $pant;
        $data['underwear'] = $underwear;
        
        $result = $pdo->normalAdd($data);
        
        if(!$result){
            $return['error'] = '10';
            $return['errormessage'] = '保存失败!';
            
        }else{
            $return['error'] = '0';
            $return['errormessage'] = '保存成功';
            $return['url'] = '/cloth/share-' . $result;
        }
        
        exit(json_encode($return));
        
    }//end 
    
    /**
     * 测试一下SQL
     */
    public function testSQL(){
        
        $pdo = new Database('mysql');
        $pre_sql = 'select * from girls where id=:id';
        $pre_action  = $pdo->pdo->prepare($pre_sql);
        $id = '3';
        $pre_action->bindParam(":id", $id,\PDO::PARAM_INT);
        $action  = $pre_action->execute();
        
        $ret = $pre_action->fetchAll(\PDO::FETCH_ASSOC);
        
        $data['aa'] = $action;
        $data['ret'] = $ret;
        dump($data);
        
        
    }//end sql
}//end