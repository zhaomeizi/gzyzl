<?php
namespace initcore;

/**
 * oracel数据库连接类
 * 
 * @author ly-chengminbin
 *        
 */
class Database
{

    public $pdo;

    public $table; // 要操作的表
    public $field_prefix = ''; // 表字段的前缀 默认为空
    public $sid = "
	        (DESCRIPTION =
    (ADDRESS_LIST =
      (ADDRESS = (PROTOCOL = TCP)(HOST = 172.26.136.199)(PORT = 1521))
    )
    (CONNECT_DATA =
      (SERVICE_NAME = KFYCXMS)
    )
  )
       ";

    //根据 $type 分别连接数据库
    public function __construct ($type = 'mysql')
    {
        $type = strtolower($type);
        if($type == 'mysql'){
            $this->_connectMysqlDb();
        }
        if($type == 'oracle'){
            $this->connectDb();
        }
    }

    /**
     * 连接数据库 oracel
     */
    private function connectDb ()
    {
        
        $pdo = new \PDO("oci:dbname=$this->sid;charset=AL32UTF8",'test', 'test');
        $this->pdo = $pdo;
    }
    
    /**
     * 连接 mysql 数据库
     */
    private function _connectMysqlDb(){
        $user = DB_USER;
        $pas = DB_PWD;
        $host = DB_HOST;
        $dbname = DB_NAME;
        $port = DB_PORT;
        
        $pdo = new \PDO("mysql:host=$host;port=$port;dbname=$dbname",$user,$pas);
       $pdo->query("set names utf8");
        $this->pdo = $pdo;
    }
    /**
     * 增加记录   MYISAM 表  不包含事务操作
     * @param array $data    要添加数据库的数组
     * 
     */
    public function normalAdd($data){
        
        $table = $this->table;
        if ($table == '') {
            return;
        }

        $i = 0;
        foreach ($data as $k => $v) {      //  准备预处理SQL 变量
            $str .= $k . "=:" . $k . ",";
            // $str .= $k . "=?" . ",";
            $param[$i]['k'] = ':' . $k;
            $param[$i]['v'] = $v;
            $i ++;
        }//end foreach        
        
        $str = rtrim($str, ',');
        $pre_str = "insert into $table" . " set " . " $str";
        $act_pre = $this->pdo->prepare($pre_str);    //生成预处理SQL 语句 
        
        $i = 0;
        while ($param[$i]) {      //替换 SQL 预处理语句
            $act_pre->bindParam($param[$i]['k'], $param[$i]['v']);
            $i ++;
        }
        
        //@return bool true on success or false on failure
        $add_ret = $act_pre->execute();   
        
        if($add_ret){
            $lastId = $this->pdo->lastInsertId();
            return $lastId;
        }else{
            $return['error'] = $this->pdo->errorCode();
            $return['errormessage'] = $this->pdo->errorInfo();
            return $return;
        }
        
    }//end normal add
    
    
    /**
     * 增加记录   + 事务
     * 
     * @param string $table
     *            要操作的表
     * @param array $data
     *            插入的数据 数组
     * @return int lastID if true else return false;
     */
    public function add ($data)
    {
       // $this->connectDb();
        $table = $this->table;
        $field_prefix = $this->field_prefix; // 字段前缀
        
        if ($table == '') {
            return;
        }
        $i = 0;
        foreach ($data as $k => $v) {
            $str .= $field_prefix . $k . "=:" . $k . ",";
            // $str .= $k . "=?" . ",";
            $param[$i]['k'] = ':' . $k;
            $param[$i]['v'] = $v;
            $i ++;
        }
        $str = rtrim($str, ',');
        $pre_str = "insert into $table" . " set " . " $str";
        // echo $pre_str;
        // dump($param);
        $act_pre = $this->pdo->prepare($pre_str);
        
        $i = 0;
        while ($param[$i]) {
            $act_pre->bindParam($param[$i]['k'], $param[$i]['v']);
            $i ++;
        }
        try {
            $trans = $this->pdo->beginTransaction();
            
            $add_ret = $act_pre->execute();
            
            $commit = $this->pdo->commit();
        } catch (\PDOException $e) {
            $this->saveLogFile($act_pre->errorCode(), $act_pre->errorInfo());
            
            return 3;
        }
        
        $data_debug['trans'] = $trans;
        $data_debug['commit'] = $commit;
        
        if ($act_pre->errorCode() != '00000') {
            // 出错
            try {
                if (! $commit || ! $trans) {
                    $data_debug['roll'] = $this->pdo->rollBack();
                }
            } catch (\PDOException $e) {
                $this->saveLogFile($act_pre->errorCode(), $act_pre->errorInfo());
                
                return $e->getMessage();
            }
            $data_debug['errinfo'] = $act_pre->errorInfo();
            // dump($data_debug);
            $this->saveLogFile($act_pre->errorCode(), $act_pre->errorInfo());
            
            return $act_pre->errorCode();
            // return $act_pre->errorInfo();
        } else {
            $this->saveLogFile($act_pre->errorCode(), $act_pre->errorInfo());
            
            return 1;
        }
    }//end add
    
    /**
     * 数据库查询语句  输入sql 语句
     * @param string $sql
     */
    public function query($sql = ''){
        
        $query = $this->pdo->query($sql);
        if(!$query){
            return false;
        }
        $result = $query->fetchAll(\PDO::FETCH_ASSOC);
        return $result;
    }

    /**
     * 记录 错误 日志 在文件
     */
    private function saveLogFile ($errCode, $errInfo)
    {
        if ($errCode != '00000') {
            $filename = LOG_PATH . '/err_logs_' . date("Y-m-d");
            $res = fopen($filename, 'a');
            fwrite($res, 'error:' . $errCode . PHP_EOL);
            foreach ($errInfo as $k => $v) {
                fwrite($res, $k . 'err:' . $v . PHP_EOL);
            }
            
            fwrite($res, 'sql_lastid:' . $this->pdo->lastInsertId() . PHP_EOL);
            fwrite($res, 'time:' . date("Y-m-d H:i:s") . PHP_EOL);
            fwrite($res, 
                    '-------------------------------------------' . PHP_EOL);
            fclose($res);
        }
    }

    public function __destruct ()
    {
        $this->pdo = null;
    }
}//end class