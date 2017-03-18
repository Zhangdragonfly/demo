<?php
/**
 * @copyright Copyright (c) 2016 沃米优选
 * @create: 6/15/16 2:16 PM
 */

namespace common\helpers;

/**
 * Class StringHelper
 * @package common\helpers
 * @author Pony Gu <pony@51wom.com>
 * @since 1.0
 */
class StringHelper
{
    /**
     * 截取UTF-8编码下字符串
     * @param $str
     * @param int $length 截取长度
     * @param bool $append 是否添加 ...
     * @return string
     */
    public static function sub_str($str, $length = 0, $append = true)
    {
        if($str === null) {
            return '';
        }
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str; //截取长度等于0或大于等于本字符串的长度，返回字符串本身
        } elseif ($length < 0) //如果截取长度为负数
        {
            $length = $strlength + $length; //那么截取长度就等于字符串长度减去截取长度
            if ($length < 0) {
                $length = $strlength; //如果截取长度的绝对值大于字符串本身长度，则截取长度取字符串本身的长度
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            //$newstr = trim_right(substr($str, 0, $length));
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return $newstr;
    }

    //地区转换字符串
    public static function areaToStr($area=""){
        $str_arr = explode("#",$area);
        $area = "";
        $area_str = "";
        foreach(array_filter($str_arr) as $k=>$v){
            if($v == 1){$area_str ="北京";}
            if($v == 9){$area_str ="上海";}
            if($v == 175){$area_str ="杭州";}
            if($v == 385){$area_str ="成都";}
            if($v == 0){$area_str ="未知";}
            $area.=$area_str."/";
        }
        return substr($area,0,-1);
    }


}