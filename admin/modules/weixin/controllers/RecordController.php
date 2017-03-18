<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/6/16 2:31 PM
 */

namespace admin\modules\weixin\controllers;

use common\helpers\DateTimeHelper;
use Yii;
use admin\controllers\BaseAppController;
use yii\data\ActiveDataProvider;
use yii\db\Query;

/**
 * Class OrderController
 * @package admin\modules\weixin\controllers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class RecordController extends BaseAppController
{

    //价格记录趋势图
    public function actionChart()
    {
        $rows = (new Query())
            ->select(['*'])
            ->from('media_weixin_price_record')
            ->all();

        return $this->render('chart');
    }


    public function actionChartInfo(){
        $rows = (new Query())
            ->select(['*'])
            ->from('media_weixin_price_record')
            ->all();
        $arrlength=count($rows);
        $time_x = array();
        $orig_price_s = array();/*平台合作价 单图文*/
        $retail_price_s = array();/*零售价 单图文*/
        for($x=0;$x<$arrlength;$x++){
            $time=date("Y-m-d h:i:s", $rows[$x]['create_time']);
            $time_theDay = substr($time,0,10);
            if ($x == $arrlength-1){
                $time_theAfter=substr(date("Y-m-d h:i:s", $rows[$x-1]['create_time']),0,10);
            }else{
                $time_theAfter=substr(date("Y-m-d h:i:s", $rows[$x+1]['create_time']),0,10);
            }
            //要求:取每个日期的最后一天
            //思路:当天日期与后一天不相等时,判断当天为所需取出来的日期
            if($time_theDay != $time_theAfter){
                array_push($time_x,$time_theDay);
                array_push($orig_price_s,$rows[$x]['orig_price_s']);
                array_push($retail_price_s,$rows[$x]['retail_price_s']);
            }
        }

        return json_encode(array('err_code'=>0,'time_x'=>$time_x,'orig_price_s'=>$orig_price_s,'retail_price_s'=>$retail_price_s));


    }


}