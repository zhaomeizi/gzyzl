<?php
namespace Index\Controller;

use Think\Controller;
/**
 * 罩杯游戏统计专用类
 * @author ly-chengminbin
 *
 */
class BraStatisticalController extends  Controller{
    
    /*获取数据*/
    public function getData(){
        /*杯类型数*/
        $sql = "select bra_type, count(1) as bra_num from bra group by bra_type";
        
        /*pv ip uv*/
        $sql2 = "SELECT count(*) as pv_num,b.ip_num,c.uv_num,d.avg_time
                    FROM bra_record a 
                    left join (select count(distinct rec_ip) as ip_num from bra_record ) b 
                    on 1
                    left join (select count(distinct openid) as uv_num from bra_record) c 
                    on 1
                    left join (select avg(diff_time) as avg_time from (SELECT openid,max(login_time),max(rec_time), max(rec_time)-max(login_time) as diff_time FROM `bra_record` where  openid!='0' group by openid ) as e) d
                    on 1";
                            
        $pdo = new \initcore\Database('mysql');
        
        $braType = $pdo->pdo->query($sql);
        $braStat = $pdo->pdo->query($sql2);
        
        $braType = $braType->fetchAll(\PDO::FETCH_ASSOC);
        $braStat = $braStat->fetchAll(\PDO::FETCH_ASSOC);
        
        $i = 0;
        while($braType[$i]){
            switch ($braType[$i]['bra_type']){
                case moli:
                    $braType[$i]['bra_type_cn'] = '魔力杯';
                    break;
                case deepv:
                    $braType[$i]['bra_type_cn'] = '深V杯';
                    break;
                case double:
                    $braType[$i]['bra_type_cn'] = '双倍杯';
                    break;
                case elegance:
                    $braType[$i]['bra_type_cn'] = '雅美碟杯';
                    break;
                case qingxin:
                    $braType[$i]['bra_type_cn'] = '清新杯';
                    break;
                case slide:
                    $braType[$i]['bra_type_cn'] = '丝滑杯';
                    break;
                case together:
                    $braType[$i]['bra_type_cn'] = '聚拢杯';
                    break;
                default:
                    $braType[$i]['bra_type_cn'] = '未知杯';
                    break;
            }
            $i ++;
        }
        
        $braStat[0]['avg_time'] = $this->_countTime($braStat[0]['avg_time']);
        $result['braType'] = $braType;
        $result['braStat'] = $braStat[0];
        
        $this->assign('result',$result);
        $this->display('getData');
    }//end func
    
    /*时间显示*/
    private function _countTime($time){
        $oneMin = 60;
        $oneHour = 3600;
        $oneDay = $oneHour*24;
        $oneMonth = $oneDay*30;
        $oneYear = $oneMonth*12;  //年
        
        //小于1分钟 直接以秒结束
        if($time < $oneMin){
            return round($time,0).'秒';
        }
        //大于1分钟 小于 1小时 以分结束
        if($time >= $oneMin & $time < $oneHour){
            $min = intval($time/$oneMin);   //求出多少分 取整
            $sec = $time - $min*$oneMin;    //余多少秒
            $sec = round($sec,0);
            return $min.'分'.$sec.'秒';
        }
        
        //大于1小时  小于60*24分钟(1天) 以小时结束
        if($time >= $oneHour & $time < $oneDay){
            $hour = intval($time/$oneHour);   //求出多少小时  取整
            $min = intval(($time - $hour*$oneHour)/$oneMin);   //求出多少分   
            $sec = $time - $hour*$oneHour - $min*$oneMin;   //余多少秒
            $sec = round($sec,0);
            return $hour.'小时'.$min.'分'.$sec.'秒';
        }
        
        //大于1天 小于1月
        if($time >= $oneDay){
            $day = intval($time/$oneDay);  //天
            $hour = intval(($time-$day*$oneDay)/$oneHour);   //小时
            $min = intval(($time - ($day*$oneDay + $hour*$oneHour))/$oneMin); //分
            $sec = $time - $day*$oneDay - $hour*$oneHour - $min*$oneMin; //秒
            $sec = round($sec,0);
            return $day.'天'.$hour.'小时'.$min.'分'.$sec.'秒';
        }
        
        
    }//end func
}//end class
