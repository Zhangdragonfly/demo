<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 12/30/16 4:40 PM
 */

namespace wom\helpers;


class StatHelper
{
    /**
     * 处理微信详情页json数据
     * @return array
     */
    public static function dealWeixinStatisticDate($data = array(),$day = 30,$data_type = 'one'){

        $data_array = array();
        foreach($data as $k=>$v){
            $time_key = strtotime($k);
            $data_array[$time_key] = $v;

        }
        krsort($data_array);
        $last_time = key($data_array);//近默认($day=30)天最后抓取的时间
        if($data_type =="one"){//发稿量
            for($i=0 ; $i<$day ; $i++){
                $sort = "-".$i." day";
                $before_time = strtotime($sort,$last_time);
                if(!array_key_exists($before_time,$data_array)){
                    $data_array[$before_time] = 0;
                }
            }
        }
        if($data_type =="two"){//点赞数、阅读数
            for($i=0 ; $i<$day ; $i++){
                $sort = "-".$i." day";
                $before_time = strtotime($sort,$last_time);
                if(!array_key_exists($before_time,$data_array)){
                    $data_array[$before_time] = array('read_num'=>0,'like_num'=>0);
                }
            }
        }
        return array_slice($data_array,0,$day,true);

    }

}