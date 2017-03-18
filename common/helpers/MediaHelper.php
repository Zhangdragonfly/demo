<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 5/19/16 10:52 AM
 */

namespace common\helpers;

use common\models\AdWeixinPlan;
use common\models\AdWeixinOrder;
use common\models\AdVideoOrder;
use common\models\UserAccount;
use common\models\VideoVendorBind;
use yii;

/**
 * Class MediaHelper
 * @package common\helpers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class MediaHelper
{
    /**
     * 获取微信信息
     * @return mixed
     */
    public static function getWeixinInfo()
    {
        return Yii::$app->params['media.weixin'];
    }

    /**
     * 获取微博信息
     * @return mixed
     */
    public static function getWeiboInfo()
    {
        return Yii::$app->params['media.weibo'];
    }

    /**
     * 获取视频信息
     * @return mixed
     */
    public static function getVideoInfo()
    {
        return Yii::$app->params['media.video'];
    }

    /**
     * 视频资源的默认头像
     * @return mixed
     */
    public static function getMediaVideoDefaultAvatar()
    {
        return Yii::$app->params['media.video.default_avatar'];
    }

    /**
     * 获取视频平台信息
     * @return mixed
     */
    public static function getVideoPlatformType()
    {
        return Yii::$app->params['media.video.platform'];
    }

    /**
     * 资源
     * @return mixed
     */
    public static function getMediaOwnershipList()
    {
        return Yii::$app->params['media.ownership'];
    }

    /**
     * 微信自媒体所属类型
     * @return mixed
     */
    public static function getMediaWeixinBelongType()
    {
        return Yii::$app->params['media.weixin.belong-type'];
    }

    // ========= 视频资源 =========
    /**
     * 视频资源的分类
     * @return mixed
     */
    public static function getMediaVideoCateList()
    {
        return Yii::$app->params['media.video.category'];
    }

    /**
     * 获取视频平台种类列表
     * @return mixed
     */
    public static function getMediaVideoPlatformList()
    {
        return Yii::$app->params['media.video.platform'];
    }

    /**
     * 获取视频平台网红头像路径
     * @return mixed
     */
    public static function getMediaVideoAvatarPath()
    {
        return Yii::$app->params['video.avatar.path'];
    }

    /**
     * 获取文件上传路径
     * @return mixed
     */
    public static function getMediaUploadFilePath()
    {
        return Yii::$app->params['media.upload.file.path'];
    }

    /**
     * 获取图片上传路径
     * @return mixed
     */
    public static function getMediaUploadImagePath()
    {
        return Yii::$app->params['media.upload.image.path'];
    }

    /**
     * 媒体资源分类(适用微信和微博)
     * @return mixed
     * 返回示例:
     * [1: '新闻资讯', 2: '生活']
     */
    public static function getMediaCateList()
    {
        return Yii::$app->params['media.category'];
    }

    /**
     * 媒体资源分类选中与否
     * @param $mediaCateSetting 示例 #1#2#3#
     * @return array 1 表示已经选择 0 表示未选择
     * 示例
     * [1=> 1, 2 => 0, 3 => 1]
     */
    public static function mediaCateSetted($mediaCateSetting)
    {
        $mediaCateList = static::getMediaCateList();

        $mediaCateSetting = substr($mediaCateSetting, 1, strlen($mediaCateSetting) - 2);
        $mediaCateSettingList = explode('#', $mediaCateSetting);

        $mediaCateSettedList = [];
        foreach ($mediaCateList as $code => $cate) {
            if (in_array($code, $mediaCateSettingList)) {
                $mediaCateSettedList[$code] = 1;
            } else {
                $mediaCateSettedList[$code] = 0;
            }
        }
        return $mediaCateSettedList;
    }

    /**
     * 解析资源的媒体类型
     * @param $mediaCateSetting 示例 #1#2#
     * @return string json格式
     * 返回值示例
     * [1: "abc", 2: "efg"]
     */
    public static function parseMediaCate($mediaCateSetting)
    {
        if (empty($mediaCateSetting)) {
            return '';
        }

        $_cateCodeList = explode('#', $mediaCateSetting);
        if (empty($_cateCodeList)) {
            return '';
        }

        // 媒体资源分类code => 媒体资源分类name
        $allMediaCateMap = Yii::$app->params['media.category'];

        $mediaCateList = [];
        foreach ($_cateCodeList as $_code) {
            if ($_code == '')
                continue;
            if (array_key_exists($_code, $allMediaCateMap)) {
                $mediaCateList[$_code] = $allMediaCateMap[$_code];
            } else {
                continue;
            }
        }

        return json_encode($mediaCateList);
    }

    /**
     * 解析视频资源的媒体类型
     * @param $mediaCateSetting 示例 #1#2#
     * @return string json格式
     * 返回值示例
     * [1: "abc", 2: "efg"]
     */
    public static function parseVideoMediaCate($mediaCateSetting)
    {
        if (empty($mediaCateSetting)) {
            return '';
        }

        $_cateCodeList = explode('#', $mediaCateSetting);
        if (empty($_cateCodeList)) {
            return '';
        }

        // 媒体资源分类code => 媒体资源分类name
        $allMediaCateMap = Yii::$app->params['media.video.category'];

        $mediaCateList = [];
        foreach ($_cateCodeList as $_code) {
            if ($_code == '')
                continue;
            if (array_key_exists($_code, $allMediaCateMap)) {
                $mediaCateList[$_code] = $allMediaCateMap[$_code];
            } else {
                continue;
            }
        }

        return json_encode($mediaCateList);
    }

    /**
     * 解析粉丝地域
     * @param $citySetting 示例 #1#2#
     * @return string json格式
     * 返回值示例:
     * [1: "北京", 2: "天津"]
     */
    public static function parseCity($citySetting){
        if (empty($citySetting)) {
            return '';
        }
        $_cityCodeList = explode('#', $citySetting);
        if (empty($_cityCodeList)) {
            return '';
        }
        // 城市code => 城市name
        $allCityMap = Yii::$app->params['common.city'];
        $cityList = [];
        foreach ($_cityCodeList as $_code) {
            if ($_code == '')
                continue;
            if (array_key_exists($_code, $allCityMap)) {
                $cityList[$_code] = $allCityMap[$_code];
            } else {
                continue;
            }
        }
        return json_encode($cityList);
    }

    /**
     * 获取城市列表
     */
    public static function getCityList()
    {
        return Yii::$app->params['common.city'];
    }

    /**
     * 获取微信发布位置名称
     * @param $posCode 位置的code
     * @return string 名称
     */
    public static function getWeixinPubPosLabel($posCode)
    {
        $posConfig = Yii::$app->params['media.weixin.pub-pos'];
        if (array_key_exists($posCode, $posConfig)) {
            return $posConfig[$posCode];
        } else {
            return '未知';
        }
    }

    /**
     * 获取微信活动订单支付数量
     */
    public static function getPlanPayStatus($planUuid){
        $orderCount = AdWeixinOrder::find()->where(['plan_uuid'=>$planUuid])->count();
        $payCount = AdWeixinOrder::find()->where(['plan_uuid'=>$planUuid,'status'=>AdWeixinOrder::ORDER_STATUS_TO_ACCEPT])->count();
        return ['orderCount'=>$orderCount,'payCount'=>$payCount];
    }

    /**
     * 获取微信订单状态
     * @param $role 角色(广告主 or 媒体主)
     * @param $statusCode (订单状态code)
     * @param $pubType (发布类型)
     * @return string
     */
    public static function getWeixinOrderStatusLabel($role, $statusCode, $pubType){
        if ($pubType == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
            if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT) {
                return '待提交';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_PAY) {
                return '待支付';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT) {
                return '待接单';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_REFUSE) {
                return '已拒单';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FlOW) {
                return '已流单';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_CANCElED) {
                return '已取消';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FINISHED) {
                return '已完成';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_LINK) {
                return '待执行链接';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT) {
                return '待效果截图';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_CONFIRM_LINK) {
                return '待效果截图';
            }else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_CONFTRM_FEEDBACK) {
                return '待效果截图';
            } else {
                return '/';
            }
        } else if ($pubType == AdWeixinOrder::ORDER_TYPE_ORIGIN_PUB) {
            if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT) {
                return '待提交';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT) {
                return '待审核';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_REFUSE) {
                return '审核失败';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_CANCElED) {
                return '已取消';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FINISHED) {
                return '已完成';
            } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT) {
                return '执行中';
            } else {
                return '/';
            }
        } else {
            return '/';
        }
    }

    /**
     * 获取微信订单操作列表
     * @param $statusCode
     * @param $pubType
     * @return array
     */
//    public static function getWeixinOrderOperationList($role, $statusCode, $pubType)
//    {
//        if ($role == UserAccount::ACCOUNT_TYPE_AD_OWNER) {
//            // 广告主
//            if ($pubType == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
//                if ($statusCode == AdWeixinOrder::ORDER_STATUS_PLAN_PUB_NOT_YET) {
//                    return ['update_order', 'cancel_order'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_PLAN_NOT_PAY) {
//                    return ['pay_order', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT_DEAL) {
//                    return ['direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_REFUSE) {
//                    return ['invalid_order_info', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_NOT_RESPONSE) {
//                    return ['invalid_order_info', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FORCE_STOP) {
//                    return ['invalid_order_info', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FINISHED) {
//                    return ['show_report', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_PUB) {
//                    return ['direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_FEEDBACK_EXECUTE_LINK) {
//                    return ['to_verify_execute_link', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT) {
//                    return ['direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_CONFIRM_RESULT) {
//                    return ['direct_order_detail'];
//                } else {
//                    return [];
//                }
//            } else if ($pubType == AdWeixinOrder::ORDER_TYPE_ORIGIN_PUB) {
//                if ($statusCode == AdWeixinOrder::ORDER_STATUS_PLAN_PUB_NOT_YET) {
//                    return ['update_order', 'cancel_order'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT_DEAL) {
//                    return ['arrange_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_REFUSE) {
//                    return ['invalid_order_info', 'arrange_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FORCE_STOP) {
//                    return ['invalid_order_info', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_PUB) {
//                    return ['arrange_order_more_detail'];
//                } else {
//                    return [];
//                }
//            } else {
//                return [];
//            }
//        } else if ($role == UserAccount::ACCOUNT_TYPE_MEDIA_VENDOR) {
//            // 媒体主
//            if ($pubType == AdWeixinOrder::ORDER_TYPE_DIRECT_PUB) {
//                if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT_DEAL) {
//                    return ['accept_order', 'direct_order_detail', 'refuse_order'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_REFUSE) {
//                    return ['invalid_order_info', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_NOT_RESPONSE) {
//                    return ['invalid_order_info', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FORCE_STOP) {
//                    return ['invalid_order_info', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_PUB) {
//                    return ['direct_order_detail', 'submit_execute_link'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_FEEDBACK_EXECUTE_LINK) {
//                    return ['direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_SUBMIT_RESULT) {
//                    return ['submit_effect_shots', 'direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_CONFIRM_RESULT) {
//                    return ['direct_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FINISHED) {
//                    return ['view_report', 'direct_order_detail'];
//                } else {
//                    return [];
//                }
//            } else if ($pubType == AdWeixinOrder::ORDER_TYPE_ORIGIN_PUB) {
//                if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_ACCEPT_DEAL) {
//                    return ['arrange_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_REFUSE) {
//                    return ['invalid_order_info', 'arrange_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_FORCE_STOP) {
//                    return ['invalid_order_info', 'arrange_order_detail'];
//                } else if ($statusCode == AdWeixinOrder::ORDER_STATUS_TO_PUB) {
//                    return ['arrange_order_more_detail'];
//                } else {
//                    return [];
//                }
//            } else {
//                return [];
//            }
//        } else {
//            return [];
//        }
//    }

    /**
     * 获取视频订单状态
     * @param $statusCode 视频订单状态code
     * @return string 状态label
     */
    public static function getVideoOrderStatusLabel($statusCode)
    {
        if ($statusCode == AdVideoOrder::ORDER_STATUS_DEFAULT) {
            return '订单未填写完成';
        } else if ($statusCode == AdVideoOrder::ORDER_STATUS_EXIST_SUBMIT) {
            return '已提交';
        } else if ($statusCode == AdVideoOrder::ORDER_STATUS_EXECUTE) {
            return '执行中';
        } else if ($statusCode == AdVideoOrder::ORDER_STATUS_FINISH) {
            return '已完成';
        } else if ($statusCode == AdVideoOrder::ORDER_STATUS_CANCEL) {
            return '已取消';
        }
    }

    /**
     * 获取微信资源的前端账号中心展示价格
     * @param $pub_config () (json格式字符串)
     * 例:{"pos_s":{"pub_type":2,"orig_price_min":5000,"orig_price_max":8000,"retail_price_min":4000,"retail_price_max":7000,"execute_price":3500},"pos_m_1":{"pub_type":1,"orig_price_min":2000,"orig_price_max":3000,"retail_price_min":1000,"retail_price_max":2000,"execute_price":800},"pos_m_2":{"pub_type":1,"orig_price_min":1000,"orig_price_max":2000,"retail_price_min":900,"retail_price_max":1900,"execute_price":700},"pos_m_3":{"pub_type":0,"orig_price_min":0,"orig_price_max":0,"retail_price_min":0,"retail_price_max":0,"execute_price":0}}
     * @param $pos (int类型) 账号位置 例如:1:单图文,2:多图文第一条,3:多图文第二条,4:多图文第三~N条,
     *
     * @return string
     */
    public static function getMediaWeixinPrice($pub_config_json, $pos)
    {
        $pub_config = json_decode($pub_config_json, true);
        switch ($pos) {
            case 1:
                $pub_type = $pub_config['pos_s']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //单图文最低价
                    $price_min = intval($pub_config['pos_s']['retail_price_min']);
                    //单图文最高价
                    $price_max = intval($pub_config['pos_s']['retail_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;
            case 2:
                $pub_type = $pub_config['pos_m_1']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //多图文第1条最低价
                    $price_min = intval($pub_config['pos_m_1']['retail_price_min']);
                    //多图文第2条最低价
                    $price_max = intval($pub_config['pos_m_1']['retail_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;
            case 3:
                $pub_type = $pub_config['pos_m_2']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //多图文第2条最低价
                    $price_min = intval($pub_config['pos_m_2']['retail_price_min']);
                    //多图文第2条最高价
                    $price_max = intval($pub_config['pos_m_2']['retail_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;
            case 4:
                $pub_type = $pub_config['pos_m_3']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //多图文第3~N条最低价
                    $price_min = intval($pub_config['pos_m_3']['retail_price_min']);
                    //多图文第3~N条最高价
                    $price_max = intval($pub_config['pos_m_3']['retail_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;

            default :
                $price_str = '不接单';
                break;
        }
        return $price_str;
    }

    /**
     * 获取微信资源的前端供应商报价价格
     * @param $pub_config () (json格式字符串)
     * 例:{"pos_s":{"pub_type":2,"orig_price_min":5000,"orig_price_max":8000,"retail_price_min":4000,"retail_price_max":7000,"execute_price":3500},"pos_m_1":{"pub_type":1,"orig_price_min":2000,"orig_price_max":3000,"retail_price_min":1000,"retail_price_max":2000,"execute_price":800},"pos_m_2":{"pub_type":1,"orig_price_min":1000,"orig_price_max":2000,"retail_price_min":900,"retail_price_max":1900,"execute_price":700},"pos_m_3":{"pub_type":0,"orig_price_min":0,"orig_price_max":0,"retail_price_min":0,"retail_price_max":0,"execute_price":0}}
     *
     * @param $pos (int类型) 账号位置 例如:1:单图文,2:多图文第1条,3:多图文第2条,4:多图文第3~N条,
     *
     * @return string
     *
     */
    public static function getMediaWeixinOrigPrice($pub_config_json, $pos)
    {
        $pub_config = json_decode($pub_config_json, true);
        switch ($pos) {
            case 1:
                $pub_type = $pub_config['pos_s']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //单图文最低价
                    $price_min = intval($pub_config['pos_s']['orig_price_min']);
                    //单图文最高价
                    $price_max = intval($pub_config['pos_s']['orig_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;
            case 2:
                $pub_type = $pub_config['pos_m_1']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //多图文第1条最低价
                    $price_min = intval($pub_config['pos_m_1']['orig_price_min']);
                    //多图文第1条最高价
                    $price_max = intval($pub_config['pos_m_1']['orig_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;
            case 3:
                $pub_type = $pub_config['pos_m_2']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //多图文第2条最低价
                    $price_min = intval($pub_config['pos_m_2']['orig_price_min']);
                    //多图文第2条最高价
                    $price_max = intval($pub_config['pos_m_2']['orig_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;
            case 4:
                $pub_type = $pub_config['pos_m_3']['pub_type'];
                if ($pub_type == 0) {
                    $price_str = '不接单';
                } else {
                    //多图文第3条最低价
                    $price_min = intval($pub_config['pos_m_3']['orig_price_min']);
                    //多图文第3条最高价
                    $price_max = intval($pub_config['pos_m_3']['orig_price_max']);
                    //判断是否最低价和最高价相等
                    if ($price_min == $price_max) {
                        $price_str = $price_min;
                    } else {
                        $price_str = $price_min . '-' . $price_max;
                    }
                }
                break;
            default :
                $price_str = '不接单';
                break;
        }
        return $price_str;
    }

    /**
     * 解析微信媒体的零售价
     * @param $pub_config_json
     * @return array
     * ['s': [pos_label: '单图文', pub_type: 0, pub_type_label: '不接单', price_label: '不接单', retail_price_min: 0, retail_price_max: 0],
     * 'm_1': [pos_label: '多图文头条', pub_type: 1, pub_type_label: '纯发布', price_label: '2222', retail_price_min: xxx, retail_price_max: yyy],
     * 'm_2': [pos_label: '多图文2条', pub_type: 2, pub_type_label: '原创+发布', price_label: '3333', retail_price_min: xxx, retail_price_max: yyy],
     * 'm_3': [pos_label: '多图文3-N条', pub_type: 2, pub_type_label: '原创+发布', price_label: '4444', retail_price_min: xxx, retail_price_max: yyy]]
     */
    public static function parseMediaWeixinRetailPrice($pub_config_json)
    {
        $pubConfig = json_decode($pub_config_json, true);

        // 单图文
        $priceArray['s']['pos_label'] = "单图文";
        $priceArray['s']['pub_type'] = $pubConfig['pos_s']['pub_type'];
        if ($priceArray['s']['pub_type'] == 0) {
            $priceArray['s']['pub_type_label'] = "不接单";
        } else if ($priceArray['s']['pub_type'] == 1) {
            $priceArray['s']['pub_type_label'] = "只发布";
        } else if ($priceArray['s']['pub_type'] == 2) {
            $priceArray['s']['pub_type_label'] = "只原创";
        } else {
            $priceArray['s']['pub_type_label'] = "未知";
        }
        $priceArray['s']['retail_price_min'] = ceil($pubConfig['pos_s']['retail_price_min']);
        $priceArray['s']['retail_price_max'] = ceil($pubConfig['pos_s']['retail_price_max']);
        if ($priceArray['s']['retail_price_min'] == 0) {
            $priceArray['s']['price_label'] = "不接单";
        } else {
            $priceArray['s']['price_label'] = ceil($priceArray['s']['retail_price_min']);
        }
        $priceArray['s']['orig_price_min'] = ceil($pubConfig['pos_s']['orig_price_min']);
        $priceArray['s']['orig_price_max'] = ceil($pubConfig['pos_s']['orig_price_max']);
        $priceArray['s']['orig_label'] = ceil($priceArray['s']['orig_price_min']);

        // 多图文头条
        $priceArray['m_1']['pos_label'] = "多图文头条";
        $priceArray['m_1']['pub_type'] = $pubConfig['pos_m_1']['pub_type'];
        if ($priceArray['m_1']['pub_type'] == 0) {
            $priceArray['m_1']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_1']['pub_type'] == 1) {
            $priceArray['m_1']['pub_type_label'] = "只发布";
        } else if ($priceArray['m_1']['pub_type'] == 2) {
            $priceArray['m_1']['pub_type_label'] = "只原创";
        } else {
            $priceArray['m_1']['pub_type_label'] = "未知";
        }
        $priceArray['m_1']['retail_price_min'] = ceil($pubConfig['pos_m_1']['retail_price_min']);
        $priceArray['m_1']['retail_price_max'] = ceil($pubConfig['pos_m_1']['retail_price_max']);
        if ($priceArray['m_1']['retail_price_min'] == 0) {
            $priceArray['m_1']['price_label'] = "不接单";
        } else {
            $priceArray['m_1']['price_label'] = ceil($priceArray['m_1']['retail_price_min']);
        }
        $priceArray['m_1']['orig_price_min'] = ceil($pubConfig['pos_m_1']['orig_price_min']);
        $priceArray['m_1']['orig_price_max'] = ceil($pubConfig['pos_m_1']['orig_price_max']);
        $priceArray['m_1']['orig_label'] = ceil($priceArray['m_1']['orig_price_min']);

        // 多图文2条
        $priceArray['m_2']['pos_label'] = "多图文2条";
        $priceArray['m_2']['pub_type'] = $pubConfig['pos_m_2']['pub_type'];
        if ($priceArray['m_2']['pub_type'] == 0) {
            $priceArray['m_2']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_2']['pub_type'] == 1) {
            $priceArray['m_2']['pub_type_label'] = "只发布";
        } else if ($priceArray['m_2']['pub_type'] == 2) {
            $priceArray['m_2']['pub_type_label'] = "只原创";
        } else {
            $priceArray['m_2']['pub_type_label'] = "未知";
        }
        $priceArray['m_2']['retail_price_min'] = ceil($pubConfig['pos_m_2']['retail_price_min']);
        $priceArray['m_2']['retail_price_max'] = ceil($pubConfig['pos_m_2']['retail_price_max']);
        if ($priceArray['m_2']['retail_price_min'] == 0) {
            $priceArray['m_2']['price_label'] = "不接单";
        } else {
            $priceArray['m_2']['price_label'] = ceil($priceArray['m_2']['retail_price_min']);
        }
        $priceArray['m_2']['orig_price_min'] = ceil($pubConfig['pos_m_2']['orig_price_min']);
        $priceArray['m_2']['orig_price_max'] = ceil($pubConfig['pos_m_2']['orig_price_max']);
        $priceArray['m_2']['orig_label'] = ceil($priceArray['m_2']['orig_price_min']);

        // 多图文3-N条
        $priceArray['m_3']['pos_label'] = "多图文3-N条";
        $priceArray['m_3']['pub_type'] = $pubConfig['pos_m_3']['pub_type'];
        if ($priceArray['m_3']['pub_type'] == 0) {
            $priceArray['m_3']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_3']['pub_type'] == 1) {
            $priceArray['m_3']['pub_type_label'] = "只发布";
        } else if ($priceArray['m_3']['pub_type'] == 2) {
            $priceArray['m_3']['pub_type_label'] = "只原创";
        } else {
            $priceArray['m_3']['pub_type_label'] = "未知";
        }
        $priceArray['m_3']['retail_price_min'] = ceil($pubConfig['pos_m_3']['retail_price_min']);
        $priceArray['m_3']['retail_price_max'] = ceil($pubConfig['pos_m_3']['retail_price_max']);
        if ($priceArray['m_3']['retail_price_min'] == 0) {
            $priceArray['m_3']['price_label'] = "不接单";
        } else {
            $priceArray['m_3']['price_label'] = ceil($priceArray['m_3']['retail_price_min']);
        }
        $priceArray['m_3']['orig_price_min'] = ceil($pubConfig['pos_m_3']['orig_price_min']);
        $priceArray['m_3']['orig_price_max'] = ceil($pubConfig['pos_m_3']['orig_price_max']);
        $priceArray['m_3']['orig_label'] = ceil($priceArray['m_3']['orig_price_min']);
        return $priceArray;
    }

    /**
     * 解析微信媒体的执行价
     * @param $pub_config_json
     * @return array
     * ['s': [pos_label: '单图文', pub_type: 0, pub_type_label: '不接单', price_label: '不接单', execute_price: 0],
     * 'm_1': [pos_label: '多图文头条', pub_type: 1, pub_type_label: '纯发布', price_label: '2222', execute_price: xxx],
     * 'm_2': [pos_label: '多图文2条', pub_type: 2, pub_type_label: '原创+发布', price_label: '3333', execute_price: xxx],
     * 'm_3': [pos_label: '多图文3-N条', pub_type: 2, pub_type_label: '原创+发布', price_label: '4444', execute_price: xxx]]
     */
    public static function parseMediaWeixinExecutePrice($pub_config_json)
    {
        $pubConfig = json_decode($pub_config_json, true);

        // 单图文
        $priceArray['s']['pos_label'] = "单图文";
        $priceArray['s']['pub_type'] = $pubConfig['pos_s']['pub_type'];
        if ($priceArray['s']['pub_type'] == 0) {
            $priceArray['s']['pub_type_label'] = "不接单";
        } else if ($priceArray['s']['pub_type'] == 1) {
            $priceArray['s']['pub_type_label'] = "纯发布";
        } else if ($priceArray['s']['pub_type'] == 2) {
            $priceArray['s']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['s']['pub_type_label'] = "未知";
        }
        $priceArray['s']['execute_price'] = ceil($pubConfig['pos_s']['execute_price']);
        $priceArray['s']['price_label'] = ceil($priceArray['s']['execute_price']);

        // 多图文头条
        $priceArray['m_1']['pos_label'] = "多图文头条";
        $priceArray['m_1']['pub_type'] = $pubConfig['pos_m_1']['pub_type'];
        if ($priceArray['m_1']['pub_type'] == 0) {
            $priceArray['m_1']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_1']['pub_type'] == 1) {
            $priceArray['m_1']['pub_type_label'] = "纯发布";
        } else if ($priceArray['m_1']['pub_type'] == 2) {
            $priceArray['m_1']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['m_1']['pub_type_label'] = "未知";
        }
        $priceArray['m_1']['execute_price'] = ceil($pubConfig['pos_m_1']['execute_price']);
        $priceArray['m_1']['price_label'] = ceil($priceArray['m_1']['execute_price']);

        // 多图文2条
        $priceArray['m_2']['pos_label'] = "多图文2条";
        $priceArray['m_2']['pub_type'] = $pubConfig['pos_m_2']['pub_type'];
        if ($priceArray['m_2']['pub_type'] == 0) {
            $priceArray['m_2']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_2']['pub_type'] == 1) {
            $priceArray['m_2']['pub_type_label'] = "纯发布";
        } else if ($priceArray['m_2']['pub_type'] == 2) {
            $priceArray['m_2']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['m_2']['pub_type_label'] = "未知";
        }
        $priceArray['m_2']['execute_price'] = ceil($pubConfig['pos_m_2']['execute_price']);
        $priceArray['m_2']['price_label'] = ceil($priceArray['m_2']['execute_price']);

        // 多图文3-N条
        $priceArray['m_3']['pos_label'] = "多图文3-N条";
        $priceArray['m_3']['pub_type'] = $pubConfig['pos_m_3']['pub_type'];
        if ($priceArray['m_3']['pub_type'] == 0) {
            $priceArray['m_3']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_3']['pub_type'] == 1) {
            $priceArray['m_3']['pub_type_label'] = "纯发布";
        } else if ($priceArray['m_3']['pub_type'] == 2) {
            $priceArray['m_3']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['m_3']['pub_type_label'] = "未知";
        }
        $priceArray['m_3']['execute_price'] = ceil($pubConfig['pos_m_3']['execute_price']);
        $priceArray['m_3']['price_label'] = ceil($priceArray['m_3']['execute_price']);

        return $priceArray;
    }

    /**
     * 解析微信媒体的平台合作价
     * @param $pub_config_json
     * @return array
     * ['s': [pos_label: '单图文', pub_type: 0, pub_type_label: '不接单', price_label: '不接单', orig_price_min: 0, orig_price_max: 0],
     * 'm_1': [pos_label: '多图文头条', pub_type: 1, pub_type_label: '纯发布', price_label: '2222', orig_price_min: xxx, orig_price_max: yyy],
     * 'm_2': [pos_label: '多图文2条', pub_type: 2, pub_type_label: '原创+发布', price_label: '3333', orig_price_min: xxx, orig_price_max: yyy],
     * 'm_3': [pos_label: '多图文3-N条', pub_type: 2, pub_type_label: '原创+发布', price_label: '4444', orig_price_min: xxx, orig_price_max: yyy]]
     */
    public static function parseMediaWeixinOriginPrice($pub_config_json)
    {
        $pubConfig = json_decode($pub_config_json, true);

        // 单图文
        $priceArray['s']['pos_label'] = "单图文";
        $priceArray['s']['pub_type'] = $pubConfig['pos_s']['pub_type'];
        if ($priceArray['s']['pub_type'] == 0) {
            $priceArray['s']['pub_type_label'] = "不接单";
        } else if ($priceArray['s']['pub_type'] == 1) {
            $priceArray['s']['pub_type_label'] = "纯发布";
        } else if ($priceArray['s']['pub_type'] == 2) {
            $priceArray['s']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['s']['pub_type_label'] = "未知";
        }
        $priceArray['s']['orig_price_min'] = ceil($pubConfig['pos_s']['orig_price_min']);
        $priceArray['s']['orig_price_max'] = ceil($pubConfig['pos_s']['orig_price_max']);
        $priceArray['s']['price_label'] = ceil($priceArray['s']['orig_price_min']);

        // 多图文头条
        $priceArray['m_1']['pos_label'] = "多图文头条";
        $priceArray['m_1']['pub_type'] = $pubConfig['pos_m_1']['pub_type'];
        if ($priceArray['m_1']['pub_type'] == 0) {
            $priceArray['m_1']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_1']['pub_type'] == 1) {
            $priceArray['m_1']['pub_type_label'] = "纯发布";
        } else if ($priceArray['m_1']['pub_type'] == 2) {
            $priceArray['m_1']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['m_1']['pub_type_label'] = "未知";
        }
        $priceArray['m_1']['orig_price_min'] = ceil($pubConfig['pos_m_1']['orig_price_min']);
        $priceArray['m_1']['orig_price_max'] = ceil($pubConfig['pos_m_1']['orig_price_max']);
        $priceArray['m_1']['price_label'] = ceil($priceArray['m_1']['orig_price_min']);

        // 多图文2条
        $priceArray['m_2']['pos_label'] = "多图文2条";
        $priceArray['m_2']['pub_type'] = $pubConfig['pos_m_2']['pub_type'];
        if ($priceArray['m_2']['pub_type'] == 0) {
            $priceArray['m_2']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_2']['pub_type'] == 1) {
            $priceArray['m_2']['pub_type_label'] = "纯发布";
        } else if ($priceArray['m_2']['pub_type'] == 2) {
            $priceArray['m_2']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['m_2']['pub_type_label'] = "未知";
        }
        $priceArray['m_2']['orig_price_min'] = ceil($pubConfig['pos_m_2']['orig_price_min']);
        $priceArray['m_2']['orig_price_max'] = ceil($pubConfig['pos_m_2']['orig_price_max']);
        $priceArray['m_2']['price_label'] = ceil($priceArray['m_2']['orig_price_min']);

        // 多图文3-N条
        $priceArray['m_3']['pos_label'] = "多图文3-N条";
        $priceArray['m_3']['pub_type'] = $pubConfig['pos_m_3']['pub_type'];
        if ($priceArray['m_3']['pub_type'] == 0) {
            $priceArray['m_3']['pub_type_label'] = "不接单";
        } else if ($priceArray['m_3']['pub_type'] == 1) {
            $priceArray['m_3']['pub_type_label'] = "纯发布";
        } else if ($priceArray['m_3']['pub_type'] == 2) {
            $priceArray['m_3']['pub_type_label'] = "原创+发布";
        } else {
            $priceArray['m_3']['pub_type_label'] = "未知";
        }
        $priceArray['m_3']['orig_price_min'] = ceil($pubConfig['pos_m_3']['orig_price_min']);
        $priceArray['m_3']['orig_price_max'] = ceil($pubConfig['pos_m_3']['orig_price_max']);
        $priceArray['m_3']['price_label'] = ceil($priceArray['m_3']['orig_price_min']);

        return $priceArray;
    }

    /**
     * 获取微信账号的发布形式
     * @param $pub_config_json (json格式字符串)
     * 例:{"pos_s":{"pub_type":2,"orig_price_min":5000,"orig_price_max":8000,"retail_price_min":4000,"retail_price_max":7000,"execute_price":3500},"pos_m_1":{"pub_type":1,"orig_price_min":2000,"orig_price_max":3000,"retail_price_min":1000,"retail_price_max":2000,"execute_price":800},"pos_m_2":{"pub_type":1,"orig_price_min":1000,"orig_price_max":2000,"retail_price_min":900,"retail_price_max":1900,"execute_price":700},"pos_m_3":{"pub_type":0,"orig_price_min":0,"orig_price_max":0,"retail_price_min":0,"retail_price_max":0,"execute_price":0}}
     * @param $pos (int类型) 账号位置 例如:1:单图文,2:多图文第一条,3:多图文第二条,4:多图文第三~N条,
     * @return string
     */
    public static function getMediaPubType($pub_config_json, $pos)
    {
        $pub_config = json_decode($pub_config_json, true);
        switch ($pos) {
            case 1:
                $pub_type = $pub_config['pos_s']['pub_type'];
                if ($pub_type == 0) {
                    $pub_type_str = '不接单';
                } else if ($pub_type == 1) {
                    $pub_type_str = '直接发布';
                } else if ($pub_type == 2) {
                    $pub_type_str = '原创约稿';
                } else {
                    $pub_type_str = '暂无';
                }
                break;
            case 2:
                $pub_type = $pub_config['pos_m_1']['pub_type'];
                if ($pub_type == 0) {
                    $pub_type_str = '不接单';
                } else if ($pub_type == 1) {
                    $pub_type_str = '直接发布';
                } else if ($pub_type == 2) {
                    $pub_type_str = '原创约稿';
                } else {
                    $pub_type_str = '暂无';
                }
                break;
            case 3:
                $pub_type = $pub_config['pos_m_2']['pub_type'];
                if ($pub_type == 0) {
                    $pub_type_str = '不接单';
                } else if ($pub_type == 1) {
                    $pub_type_str = '直接发布';
                } else if ($pub_type == 2) {
                    $pub_type_str = '原创约稿';
                } else {
                    $pub_type_str = '暂无';
                }
                break;
            case 4:
                $pub_type = $pub_config['pos_m_3']['pub_type'];
                if ($pub_type == 0) {
                    $pub_type_str = '不接单';
                } else if ($pub_type == 1) {
                    $pub_type_str = '直接发布';
                } else if ($pub_type == 2) {
                    $pub_type_str = '原创约稿';
                } else {
                    $pub_type_str = '暂无';
                }
                break;
            default :
                $pub_type_str = '暂无';
        }

        return $pub_type_str;
    }

    /**
     * 获取视频资源的前端供应商零售价价格
     * @param $platform_conf () (json格式字符串)
     * 例:{"4":{"biz_coop_1":{"orig_price":"1","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_2":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_3":{"orig_price":"2000","orig_price_end_time":"1472313600","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_5":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470973732SMOnb"},"5":{"biz_coop_3":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"2000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470974197PEosU"}}
     * @return Array
     *
     */
    public static function getMediaVideoRetailPrice($platform_conf, $main_platform)
    {
        if (empty($platform_conf)) {
            return [];
        }
        if (empty($main_platform)) {
            return [];
        }
        $mainPlatform = 'platform' . $main_platform;
        $pub_config = json_decode($platform_conf, true);
        $platform = $pub_config[$mainPlatform];
        $num = 0;
        $coefficient = 1.3;
        foreach ($platform as $k => $v) {
            switch ($k) {
                case 'biz_coop_1':
                    $price[$num]['name'] = '线上直播';
                    $price[$num]['val'] = empty($v['retail_price']) ? '询价' : $v['retail_price'] * $coefficient;
                    break;
                case 'biz_coop_2':
                    $price[$num]['name'] = '线下直播';
                    $price[$num]['val'] = empty($v['retail_price']) ? '询价' : $v['retail_price'] * $coefficient;
                    break;
                case 'biz_coop_3':
                    $price[$num]['name'] = '视频原创+发布';
                    $price[$num]['val'] = empty($v['retail_price']) ? '询价' : $v['retail_price'] * $coefficient;
                    break;
                case 'biz_coop_4':
                    $price[$num]['name'] = '视频直发';
                    $price[$num]['val'] = empty($v['retail_price']) ? '询价' : $v['retail_price'] * $coefficient;
                    break;
                case 'biz_coop_5':
                    $price[$num]['name'] = '视频转发';
                    $price[$num]['val'] = empty($v['retail_price']) ? '询价' : $v['retail_price'] * $coefficient;
                    break;
            }
            $num++;
        }
        return $price;
    }

    /**
     * 获取视频资源的前端供应商报价价格
     * @param $platform_conf () (json格式字符串)
     * 例:{"4":{"biz_coop_1":{"orig_price":"1","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_2":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_3":{"orig_price":"2000","orig_price_end_time":"1472313600","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_5":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470973732SMOnb"},"5":{"biz_coop_3":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"2000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470974197PEosU"}}
     * @return Array
     *
     */
    public static function getMediaVideoOrigPrice($platform_conf, $main_platform)
    {
        if (empty($platform_conf)) {
            return [];
        }
        if (empty($main_platform)) {
            return [];
        }
        $mainPlatform = 'platform' . $main_platform;
        $pub_config = json_decode($platform_conf, true);
        $platform = $pub_config[$mainPlatform];
        $num = 0;
        foreach ($platform as $k => $v) {
            switch ($k) {
                case 'biz_coop_1':
                    $price[$num]['name'] = '线上直播';
                    $price[$num]['val'] = empty($v['orig_price']) ? '不接单' : $v['orig_price'];
                    break;
                case 'biz_coop_2':
                    $price[$num]['name'] = '线下直播';
                    $price[$num]['val'] = empty($v['orig_price']) ? '不接单' : $v['orig_price'];
                    break;
                case 'biz_coop_3':
                    $price[$num]['name'] = '视频原创+发布';
                    $price[$num]['val'] = empty($v['orig_price']) ? '不接单' : $v['orig_price'];
                    break;
                case 'biz_coop_4':
                    $price[$num]['name'] = '视频直发';
                    $price[$num]['val'] = empty($v['orig_price']) ? '不接单' : $v['orig_price'];
                    break;
                case 'biz_coop_5':
                    $price[$num]['name'] = '视频转发';
                    $price[$num]['val'] = empty($v['orig_price']) ? '不接单' : $v['orig_price'];
                    break;
            }
            $num++;
        }
        return $price;
    }

    /**
     * 获取视频资源的前端供应商零售价价格的有效期
     * @param $platform_conf () (json格式字符串)
     * 例:{"4":{"biz_coop_1":{"orig_price":"1","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_2":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_3":{"orig_price":"2000","orig_price_end_time":"1472313600","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_5":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470973732SMOnb"},"5":{"biz_coop_3":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"2000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470974197PEosU"}}
     * @return Array
     *
     */
    public static function getMediaVideoRetailPriceTime($platform_conf, $main_platform)
    {
        $mainPlatform = 'platform' . $main_platform;
        $pub_config = json_decode($platform_conf, true);
        $platform = $pub_config[$mainPlatform];
        $timeNum = 0;
        foreach ($platform as $k => $v) {
            switch ($k) {
                case 'biz_coop_1':
                    $price_time[$timeNum]['val'] = empty($v['retail_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['retail_price_end_time']);
                    break;
                case 'biz_coop_2':
                    $price_time[$timeNum]['val'] = empty($v['retail_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['retail_price_end_time']);
                    break;
                case 'biz_coop_3':
                    $price_time[$timeNum]['val'] = empty($v['retail_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['retail_price_end_time']);
                    break;
                case 'biz_coop_4':
                    $price_time[$timeNum]['val'] = empty($v['retail_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['retail_price_end_time']);
                    break;
                case 'biz_coop_5':
                    $price_time[$timeNum]['val'] = empty($v['retail_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['retail_price_end_time']);
                    break;
            }
            $timeNum++;
        }
        return $price_time;
    }

    /**
     * 获取视频资源的前端供应商报价价格的有效期
     * @param $platform_conf () (json格式字符串)
     * 例:{"4":{"biz_coop_1":{"orig_price":"1","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_2":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_3":{"orig_price":"2000","orig_price_end_time":"1472313600","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_5":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470973732SMOnb"},"5":{"biz_coop_3":{"orig_price":"1000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"biz_coop_4":{"orig_price":"2000","orig_price_end_time":"","retail_price":"","retail_price_end_time":"","coop_price":"","prmt_price":""},"uuid":"1470974197PEosU"}}
     * @return Array
     *
     */
    public static function getMediaVideoOrigPriceTime($platform_conf, $main_platform)
    {
        $mainPlatform = 'platform' . $main_platform;
        $pub_config = json_decode($platform_conf, true);
        $platform = $pub_config[$mainPlatform];
        $timeNum = 0;
        foreach ($platform as $k => $v) {
            switch ($k) {
                case 'biz_coop_1':
                    $price_time[$timeNum]['val'] = empty($v['orig_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['orig_price_end_time']);
                    break;
                case 'biz_coop_2':
                    $price_time[$timeNum]['val'] = empty($v['orig_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['orig_price_end_time']);
                    break;
                case 'biz_coop_3':
                    $price_time[$timeNum]['val'] = empty($v['orig_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['orig_price_end_time']);
                    break;
                case 'biz_coop_4':
                    $price_time[$timeNum]['val'] = empty($v['orig_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['orig_price_end_time']);
                    break;
                case 'biz_coop_5':
                    $price_time[$timeNum]['val'] = empty($v['orig_price_end_time']) ? '-' : DateTimeHelper::getFormattedDateTime($v['orig_price_end_time']);
                    break;
            }
            $timeNum++;
        }
        return $price_time;
    }
//
//    /**
//     * 生成视频直播资源的价格信息
//     * @param $priceInfoArray (价格信息的array数据)
//     * @param $platformType (平台类型 ['1' => '花椒', '2' => '熊猫', '3' => '哈你', '4' => '美拍', '5' => '秒拍', '6' => '斗鱼', '7' => '映客', '8' => '淘宝'])
//     * @param $platformUuid (需要存储平台的uuid)
//     * @param $bindPlatformConf (video_media_bind表的platform_conf字段,该字段存储的是该网红各个平台的价格信息和uuid)
//     * @return string (如果返回值为空字符则表示添加失败,如果返回json格式字符串则表示成功)
//     */
//    public static function generateJsonFormattedVideoMediaPriceInfo($priceInfoArray, $platformType, $platformUuid, $bindPlatformConf)
//    {
//        $conf = [];
//        $platformJsonToArr = [];
//        if ($platformType == VideoMediaBaseInfo::ACCOUNT_TYPE_MEIPAI) {
//            ///////////////价格//////////////
//            $bizOrigPrice1 = $priceInfoArray['biz_orig_price_1'];//线上直播报价
//            $bizOrigPrice2 = $priceInfoArray['biz_orig_price_2'];//线下直播报价
//            $bizOrigPrice3 = $priceInfoArray['biz_orig_price_3'];//视频原创+发布报价
//            $bizOrigPrice4 = $priceInfoArray['biz_orig_price_4'];//视频直发报价
//            $bizOrigPrice5 = $priceInfoArray['biz_orig_price_5'];//视频转发报价
//            $bizRetailPrice1 = $priceInfoArray['biz_retail_price_1'];//线上直播零售价
//            $bizRetailPrice2 = $priceInfoArray['biz_retail_price_2'];//线下直播零售价
//            $bizRetailPrice3 = $priceInfoArray['biz_retail_price_3'];//视频原创+发布零售价
//            $bizRetailPrice4 = $priceInfoArray['biz_retail_price_4'];//视频直发零售价
//            $bizRetailPrice5 = $priceInfoArray['biz_retail_price_5'];//视频转发零售价
//            $bizCoopPrice1 = $priceInfoArray['biz_coop_price_1'];//线上直播折扣价
//            $bizCoopPrice2 = $priceInfoArray['biz_coop_price_2'];//线下直播折扣价
//            $bizCoopPrice3 = $priceInfoArray['biz_coop_price_3'];//视频原创+发布折扣价
//            $bizCoopPrice4 = $priceInfoArray['biz_coop_price_4'];//视频直发折扣价
//            $bizCoopPrice5 = $priceInfoArray['biz_coop_price_5'];//视频转发折扣价
//            ///////////////有效期//////////////
//            $orig_price1_end_time = $priceInfoArray['orig_price1_end_time'];
//            $origPrice1EndTime = empty($orig_price1_end_time) ? '' : strtotime($orig_price1_end_time);
//            //线上直播报价价格有效期
//            $orig_price2_end_time = $priceInfoArray['orig_price2_end_time'];
//            $origPrice2EndTime = empty($orig_price2_end_time) ? '' : strtotime($orig_price2_end_time);
//            //线下直播报价价格有效期
//            $orig_price3_end_time = $priceInfoArray['orig_price3_end_time'];
//            $origPrice3EndTime = empty($orig_price3_end_time) ? '' : strtotime($orig_price3_end_time);
//            //视频原创+发布报价价格有效期
//            $orig_price4_end_time = $priceInfoArray['orig_price4_end_time'];
//            $origPrice4EndTime = empty($orig_price4_end_time) ? '' : strtotime($orig_price4_end_time);
//            //视频直发报价价格有效期
//            $orig_price5_end_time = $priceInfoArray['orig_price5_end_time'];
//            $origPrice5EndTime = empty($orig_price5_end_time) ? '' : strtotime($orig_price5_end_time);
//            //视频转发报价价格有效期
//            $retail_price1_end_time = $priceInfoArray['retail_price1_end_time'];
//            $retailPrice1EndTime = empty($retail_price1_end_time) ? '' : strtotime($retail_price1_end_time);
//            //线上直播零售价价格有效期
//            $retail_price2_end_time = $priceInfoArray['retail_price2_end_time'];
//            $retailPrice2EndTime = empty($retail_price2_end_time) ? '' : strtotime($retail_price2_end_time);
//            //线下直播零售价价格有效期
//            $retail_price3_end_time = $priceInfoArray['retail_price3_end_time'];
//            $retailPrice3EndTime = empty($retail_price3_end_time) ? '' : strtotime($retail_price3_end_time);
//            //视频原创+发布零售价价格有效期
//            $retail_price4_end_time = $priceInfoArray['retail_price4_end_time'];
//            $retailPrice4EndTime = empty($retail_price4_end_time) ? '' : strtotime($retail_price4_end_time);
//            //视频直发零售价价格有效期
//            $retail_price5_end_time = $priceInfoArray['retail_price5_end_time'];
//            $retailPrice5EndTime = empty($retail_price5_end_time) ? '' : strtotime($retail_price5_end_time);
//            //视频转发零售价价格有效期
//            $conf = [
//                "biz_coop_1" => [
//                    "orig_price" => $bizOrigPrice1,
//                    "orig_price_end_time" => $origPrice1EndTime,
//                    "retail_price" => $bizRetailPrice1,
//                    "retail_price_end_time" => $retailPrice1EndTime,
//                    "coop_price" => $bizCoopPrice1,
//                    "prmt_price" => '',
//                ],
//                "biz_coop_2" => [
//                    "orig_price" => $bizOrigPrice2,
//                    "orig_price_end_time" => $origPrice2EndTime,
//                    "retail_price" => $bizRetailPrice2,
//                    "retail_price_end_time" => $retailPrice2EndTime,
//                    "coop_price" => $bizCoopPrice2,
//                    "prmt_price" => '',
//                ],
//                "biz_coop_3" => [
//                    "orig_price" => $bizOrigPrice3,
//                    "orig_price_end_time" => $origPrice3EndTime,
//                    "retail_price" => $bizRetailPrice3,
//                    "retail_price_end_time" => $retailPrice3EndTime,
//                    "coop_price" => $bizCoopPrice3,
//                    "prmt_price" => '',
//                ],
//                "biz_coop_4" => [
//                    "orig_price" => $bizOrigPrice4,
//                    "orig_price_end_time" => $origPrice4EndTime,
//                    "retail_price" => $bizRetailPrice4,
//                    "retail_price_end_time" => $retailPrice4EndTime,
//                    "coop_price" => $bizCoopPrice4,
//                    "prmt_price" => '',
//                ],
//                "biz_coop_5" => [
//                    "orig_price" => $bizOrigPrice5,
//                    "orig_price_end_time" => $origPrice5EndTime,
//                    "retail_price" => $bizRetailPrice5,
//                    "retail_price_end_time" => $retailPrice5EndTime,
//                    "coop_price" => $bizCoopPrice5,
//                    "prmt_price" => '',
//                ],
//                'uuid' => $platformUuid
//            ];
//        } elseif ($platformType == VideoMediaBaseInfo::ACCOUNT_TYPE_MIAOPAI) {
//            ///////////////价格//////////////
//            $bizOrigPrice3 = $priceInfoArray['biz_orig_price_3'];//视频原创+发布报价
//            $bizOrigPrice4 = $priceInfoArray['biz_orig_price_4'];//视频直发报价
//            $bizOrigPrice5 = $priceInfoArray['biz_orig_price_5'];//视频直发报价
//            $bizRetailPrice3 = $priceInfoArray['biz_retail_price_3'];//视频原创+发布零售价
//            $bizRetailPrice4 = $priceInfoArray['biz_retail_price_4'];//视频直发零售价
//            $bizRetailPrice5 = $priceInfoArray['biz_retail_price_5'];//视频直发零售价
//            $bizCoopPrice3 = $priceInfoArray['biz_coop_price_3'];//视频原创+发布折扣价
//            $bizCoopPrice4 = $priceInfoArray['biz_coop_price_4'];//视频直发折扣价
//            $bizCoopPrice5 = $priceInfoArray['biz_coop_price_5'];//视频直发折扣价
//            ///////////////有效期//////////////
//            $orig_price3_end_time = $priceInfoArray['orig_price3_end_time'];
//            $origPrice3EndTime = empty($orig_price3_end_time) ? '' : strtotime($orig_price3_end_time);
//            //视频原创+发布报价价格有效期
//            $orig_price4_end_time = $priceInfoArray['orig_price4_end_time'];
//            $origPrice4EndTime = empty($orig_price4_end_time) ? '' : strtotime($orig_price4_end_time);
//            //视频转发报价价格有效期
//            $orig_price5_end_time = $priceInfoArray['orig_price5_end_time'];
//            $origPrice5EndTime = empty($orig_price5_end_time) ? '' : strtotime($orig_price5_end_time);
//            //视频直发报价价格有效期
//            $retail_price3_end_time = $priceInfoArray['retail_price3_end_time'];
//            $retailPrice3EndTime = empty($retail_price3_end_time) ? '' : strtotime($retail_price3_end_time);
//            //视频原创+发布零售价价格有效期
//            $retail_price4_end_time = $priceInfoArray['retail_price4_end_time'];
//            $retailPrice4EndTime = empty($retail_price4_end_time) ? '' : strtotime($retail_price4_end_time);
//            //视频转发报价价格有效期
//            $retail_price5_end_time = $priceInfoArray['retail_price5_end_time'];
//            $retailPrice5EndTime = empty($retail_price5_end_time) ? '' : strtotime($retail_price5_end_time);
//            //视频直发零售价价格有效期
//            $conf = [
//                "biz_coop_3" => [
//                    "orig_price" => $bizOrigPrice3,
//                    "orig_price_end_time" => $origPrice3EndTime,
//                    "retail_price" => $bizRetailPrice3,
//                    "retail_price_end_time" => $retailPrice3EndTime,
//                    "coop_price" => $bizCoopPrice3,
//                    "prmt_price" => '',
//                ],
//                "biz_coop_4" => [
//                    "orig_price" => $bizOrigPrice4,
//                    "orig_price_end_time" => $origPrice4EndTime,
//                    "retail_price" => $bizRetailPrice4,
//                    "retail_price_end_time" => $retailPrice4EndTime,
//                    "coop_price" => $bizCoopPrice4,
//                    "prmt_price" => '',
//                ],
//                "biz_coop_5" => [
//                    "orig_price" => $bizOrigPrice5,
//                    "orig_price_end_time" => $origPrice5EndTime,
//                    "retail_price" => $bizRetailPrice5,
//                    "retail_price_end_time" => $retailPrice5EndTime,
//                    "coop_price" => $bizCoopPrice5,
//                    "prmt_price" => '',
//                ],
//                'uuid' => $platformUuid
//            ];
//        } else {
//            ///////////////价格//////////////
//            $bizOrigPrice1 = $priceInfoArray['biz_orig_price_1'];//线上直播报价
//            $bizOrigPrice2 = $priceInfoArray['biz_orig_price_2'];//线下直播报价
//            $bizRetailPrice1 = $priceInfoArray['biz_retail_price_1'];//线上直播零售价
//            $bizRetailPrice2 = $priceInfoArray['biz_retail_price_2'];//线下直播零售价
//            $bizCoopPrice1 = $priceInfoArray['biz_coop_price_1'];//线上直播折扣价
//            $bizCoopPrice2 = $priceInfoArray['biz_coop_price_2'];//线下直播折扣价
//            ///////////////有效期//////////////
//            $orig_price1_end_time = $priceInfoArray['orig_price1_end_time'];
//            $origPrice1EndTime = empty($orig_price1_end_time) ? '' : strtotime($orig_price1_end_time);
//            //线上直播报价价格有效期
//            $orig_price2_end_time = $priceInfoArray['orig_price2_end_time'];
//            $origPrice2EndTime = empty($orig_price2_end_time) ? '' : strtotime($orig_price2_end_time);
//            //线下直播报价价格有效期
//            $retail_price1_end_time = $priceInfoArray['retail_price1_end_time'];
//            $retailPrice1EndTime = empty($retail_price1_end_time) ? '' : strtotime($retail_price1_end_time);
//            //线上直播零售价价格有效期
//            $retail_price2_end_time = $priceInfoArray['retail_price2_end_time'];
//            $retailPrice2EndTime = empty($retail_price2_end_time) ? '' : strtotime($retail_price2_end_time);
//            //线下直播零售价价格有效期
//            $conf = [
//                "biz_coop_1" => [
//                    "orig_price" => $bizOrigPrice1,
//                    "orig_price_end_time" => $origPrice1EndTime,
//                    "retail_price" => $bizRetailPrice1,
//                    "retail_price_end_time" => $retailPrice1EndTime,
//                    "coop_price" => $bizCoopPrice1,
//                    "prmt_price" => '',
//                ],
//                "biz_coop_2" => [
//                    "orig_price" => $bizOrigPrice2,
//                    "orig_price_end_time" => $origPrice2EndTime,
//                    "retail_price" => $bizRetailPrice2,
//                    "retail_price_end_time" => $retailPrice2EndTime,
//                    "coop_price" => $bizCoopPrice2,
//                    "prmt_price" => '',
//                ],
//                'uuid' => $platformUuid
//            ];
//        }
//
//        //判断bind是否已经录入平台
//        if (empty($bindPlatformConf)) {
//            $platformJsonToArr['platform' . $platformType] = $conf;
//        } else {
//            $platformJsonToArr = json_decode($bindPlatformConf, true);
//            //判断是否已经存在该平台的价格信息
//            if (isset($platformJsonToArr['platform' . $platformType])) {
//                $uuid = $platformJsonToArr['platform' . $platformType]['uuid'];
//                //判断已经存储的平台账号是否是该账号
//                if ($uuid == $platformUuid) {
//                    $platformJsonToArr['platform' . $platformType] = $conf;
//                } else {
//                    return ['uuid' => $uuid, 'platformUuid' => $platformUuid, 'conf' => $conf];
//                }
//            } else {
//                $platformJsonToArr['platform' . $platformType] = $conf;
//            }
//        }
//        //返回json格式的字符串
//        return json_encode($platformJsonToArr);
//    }

    /**
     * 解析视频直播资源的价格信息
     * @param $priceInfoJson
     * @return array
     */
    public static function parseJsonFormattedVideoMediaPriceInfo($priceInfoJson)
    {
        return [];
    }

    /**
     * 针对某个供应商,检查其录入的网红之前是否入驻了某直播平台
     * @param $vendorUuid (供应商的uuid)
     * @param $platformType (平台类型 ['1' => '花椒', '2' => '熊猫', '3' => '哈你', '4' => '美拍', '5' => '秒拍', '6' => '斗鱼', '7' => '映客', '8' => '淘宝'])
     * @param $platformUuid (平台的uuid)
     * @return int  (返回值为0或1,0代拍不存在,1代表存在,)
     */
    public static function checkExistOfVideoPlatformOfMedia($vendorUuid, $bindUuid, $platformType, $platformUuid)
    {
        $vendorAllBinds = VideoVendorBind::findAll(['vendor_uuid' => $vendorUuid]);
        //判断是否存在该供应商录入的网红
        if (empty($vendorAllBinds)) {
            return 0;
        }
        foreach ($vendorAllBinds as $v) {
            if (!empty($v->platform_conf) && $v->uuid != $bindUuid) {
                $platformConf = $v->platform_conf;
                $platformConfJsonToArr = json_decode($platformConf);
                foreach ($platformConfJsonToArr as $key => $item) {
                    if ($key == 'platform' . $platformType) {
                        if ($item->uuid == $platformUuid) {
                            return 1;
                        }
                    }
                }
            }
        }
        return 0;
    }


    //数字国际化显示
    public static function formatMoney($number,$fractional=false){
        setlocale(LC_MONETARY,"en_US");
        if($fractional){
            // return money_format("%!i",$number);
        }else{
            // return money_format("%!.0i",$number);
        }
    }


}
