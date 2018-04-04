<?php
namespace app\modules\admin\components;

use Yii;
use yii\base\Object;

class Tools extends Object{
    /**
     * 获取用户IP地址
     */
    public static function getClientIp(){
        $ip_address = '0.0.0.0';

        if(isset($_SERVER['HTTP_SNAIL_IP']) && !empty($_SERVER['HTTP_SNAIL_IP'])){
            $ip_address = $_SERVER['HTTP_SNAIL_IP'];
        }elseif(isset($_SERVER['HTTP_CDN_SRC_IP']) && !empty($_SERVER['HTTP_CDN_SRC_IP'])){
            $ip_address = $_SERVER['HTTP_CDN_SRC_IP'];
        }elseif(isset($_SERVER['SNAIL_IP']) && !empty($_SERVER['SNAIL_IP'])){
            $ip_address = $_SERVER['SNAIL_IP'];
        }elseif(isset($_SERVER['http_Snail_Ip']) && !empty($_SERVER['http_Snail_Ip'])){
            $ip_address = $_SERVER['http_Snail_Ip'];
        }elseif(isset($_SERVER['X_FORWARDED_FOR']) && !empty($_SERVER['X_FORWARDED_FOR'])){
            $ip_address = $_SERVER['X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['X-Forwarded-For']) && !empty($_SERVER['X-Forwarded-For'])){
            $ip_address = $_SERVER['X-Forwarded-For'];
        }elseif(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }elseif(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])){
            $ip_address = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR'])){
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        if ($ip_address === 'Unknown'){
            $ip_address = '0.0.0.0';
            return $ip_address;
        }

        if (strpos($ip_address, ',') !== 'Unknown'){
            $x = explode(',', $ip_address);
            $ip_address = trim(end($x));
        }
        return $ip_address;
    }

    /**
     * 获取随机字符串
     */
    public static function getUUID($length, $a = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') {
        $l = strlen($a) - 1;
        $r = '';
        while ($length-- > 0)
            $r .= $a{mt_rand(0, $l)};

        return $r;
    }

    /**
     * 验证字母数字组合
     */
    public static function checkLetterAndNumber($value,$preg = null){
        if($preg === null){
            $preg = "/^[0-9a-zA-Z]+$/";
        }
        if(preg_match($preg,$value)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证字母
     */
    public static function checkLetter($value,$preg = null){
        if($preg === null){
            $preg = "/^[a-zA-Z]+$/";
        }
        if(preg_match($preg,$value)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证数字
     */
    public static function checkNumber($value,$preg = null){
        if($preg === null){
            $preg = "/^[0-9]+$/";
        }
        if(preg_match($preg,$value)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证URL地址
     */
    public static function checkUrl($value,$preg = null){
        if($preg === null){
            $preg = '/(http:|https:)\/\/[a-z]+[.]*[a-z\d\-]+[.]{1}[a-z\d]*[\/]*[A-Za-z\d]*[\/]*[A-Za-z\d]*/';
        }
        if(preg_match($preg,$value)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 非法字符替换指定字符
     */
    public static function replaceString($value,$preg = null,$replace = ''){
        if($preg === null){
            $preg = '/[^\x{00}-\x{ff}A-Za-z0-9| |,|，|。|！|.|!|]/';
        }
        return preg_replace($preg,$replace,$value);
    }

    /**
     * 验证邮箱地址
     */
    public static function checkEmail($value,$preg = null){
        if($preg === null){
            $preg = '/^[-a-zA-Z0-9_\.]+\@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/';
        }
        if(preg_match($preg,$value)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证日期时间格式
     */
    public static function checkDateTime($value,$preg = null){
        if($preg === null){
            $preg = '/^\d{4}[\-](0?[1-9]|1[012])[\-](0?[1-9]|[12][0-9]|3[01])(\s+(0?[0-9]|1[0-9]|2[0-3])\:(0?[0-9]|[1-5][0-9])\:(0?[0-9]|[1-5][0-9]))?$/';
        }
        if(preg_match($preg,$value)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 验证身份证号码
     */
    public static function checkIdcard($value,$preg = null){
        if($preg === null){
            $preg = '/(^\d{15}$)|(^\d{17}(\d|X|x)$)/';
        }
        if(preg_match($preg,$value)){
            return true;
        }else{
            return false;
        }
    }
}