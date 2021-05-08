<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/22
 * Time: 13:38
 */

namespace app\utils;

use luweiss\wechat\DataTransform;

class GetInfo
{
    //获取视频播放地址
    public static function getVideoInfo1($url)
    {
        preg_match("/\/([0-9a-zA-Z]+).html/", $url, $match);
        if (empty($match)) {
            return [
                'code' => 0,
                'msg' => 'success',
                'url' => $url
            ];
        }
        $vid = $match[1];//视频ID
        try {
            set_time_limit(0);
            $getinfo = "http://vv.video.qq.com/getinfo?vids={$vid}&platform=11&charge=0&otype=xml";
            $info = self::normal_curl($getinfo);
            $info_arr = DataTransform::xmlToArray($info);
            if ($info_arr['msg'] == 'vid is wrong') {
                return [
                    'code' => 1,
                    'msg' => '视频出错',
                    'url' => $url
                ];
            }
            $fi = $info_arr['fl']['fi'];
//            if(isset($fi[1])){
//                $format_id = $fi[1]['id'];
//                $fmt = $fi[1]['name'];
//                $format = 'p'.substr($format_id,-3,3);
//                $key = $info_arr['vl']['vi']['fvkey'];
//                $vid = $info_arr['vl']['vi']['vid'];
//                $url = $info_arr['vl']['vi']['ul']['ui'][0]['url'];
//                if(strlen($format_id)>=5){
//                    $mp4 = $vid.'.'.$format.'.1.mp4';
//                }else{
//                    $mp4 = $vid.'.mp4';
//                }
//                $video_url = $url . $mp4 .'?vkey='.$key.'&fmt='.$fmt;
//
//            }else{
                $getinfo = "http://vv.video.qq.com/getinfo?vids={$vid}&platform=101001&charge=0&otype=xml";
                $info = self::http_get($getinfo);
            $info_arr = \Yii::$app->serializer->decode(\Yii::$app->serializer->encode(DataTransform::xmlToArray($info)));
            if (isset($info_arr['msg']) && $info_arr['msg'] == 'vid is wrong') {
                return [
                    'code' => 0,
                    'msg' => '视频出错',
                    'url' => $url
                ];
            }
                $filename = $info_arr['vl']['vi']['fn'];
                $key = $info_arr['vl']['vi']['fvkey'];
                $url = $info_arr['vl']['vi']['ul']['ui'][0]['url'];
                $video_url = $url . $filename . '?vkey=' . $key;
//            }
            return [
                'code' => 0,
                'msg' => 'success',
                'url' => $video_url
            ];
        } catch (\Exception $e) {
            return [
                'code' => 0,
                'msg' => 'success',
                'url' => $url
            ];
        }
    }

    //通过vid参数换取源地址
    public static function getVideoInfo($url){
        preg_match("/\/([0-9a-zA-Z]+).html/", $url, $vids);
        if(empty($vids) || !is_array($vids)){
            return [
                'code' => 0,
                'msg' => 'success',
                'url' => $url
            ];
        }
        $getUrl = 'https://h5vv.video.qq.com/getinfo?';
        $realUrl = 'http://ugcws.video.gtimg.com/%s?vkey=%s&br=56&platform=2&fmt=auto&level=0&sdtfrom=v5010&guid=a3527bbc8888951591bc3a67c2bc9c50';
        $newVideo = array();
        foreach($vids as $key => $value){
            if(!empty($value) && $key == 1){
                $vid = $value;
                //获取真正的视频源地址
                $data = array(
                    'platform' => 11001,
                    'charge' => 0,
                    'otype' => 'json',
                    'ehost' => 'https://v.qq.com',
                    'sphls' => 1,
                    'sb' => 1,
                    'nocache' => 0,
                    '_rnd' => time(),
                    'guid' => 'a3527bbc8888951591bc3a67c2bc9c50',
                    'appVer' => 'V2.0Build9496',
                    'vids' => $vid,
                    'defaultfmt' => 'auto',
                    '_qv_rmt' => 'jJPtBTyoA12993HPU=',
                    '_qv_rmt2' => 'pS3QdOqZ150285Jdg=',
                    'sdtfrom' => 'v5010'
                );
                $result = self::http_get($getUrl.http_build_query($data));
                if(!empty($result)){
                    $result = explode('=', $result);
                    if(!empty($result) && !empty($result[1])){
                        $json = substr($result[1], 0, strlen($result[1])-1);
                        $json = json_decode($json, true);
                        if(json_last_error() == 'JSON_ERROR_NONE'){
                            if(!empty($json['vl']['vi'][0]['fn']) && !empty($json['vl']['vi'][0]['fvkey'])){
                                $fn = $json['vl']['vi'][0]['fn'];
                                $fvkey = $json['vl']['vi'][0]['fvkey'];
                                $returnUrl = sprintf($realUrl, $fn, $fvkey);
                                $newVideo['url'] = $returnUrl;
                            }
                        }
                    }
                }
            }
        }
        return [
            'code' => 0,
            'msg' => 'success',
            'url' => $newVideo['url']
        ];
    }

    public static function http_get($url) {
        $oCurl = curl_init ();
        if (stripos ( $url, "https://" ) !== FALSE) {
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
            curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, FALSE );
        }
        curl_setopt ( $oCurl, CURLOPT_URL, $url );
        curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
        $sContent = curl_exec ( $oCurl );
        $aStatus = curl_getinfo ( $oCurl );
        curl_close ( $oCurl );
        if (intval ( $aStatus ["http_code"] ) == 200) {
            return $sContent;
        } else {
            return false;
        }
    }
}
