<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
define('PI',3.1415926535898);
define('EARTH_RADIUS',6378.137);

function getrandstr1($length){
    $str='abcdefghijklmnopqrstuvwxyz0123456789';
    $len=strlen($str)-1;
    $randstr='';
    for($i=0;$i<$length;$i++){
        $num=mt_rand(0,$len);
        $randstr .= $str[$num];
    }
    return $randstr;
}
function getrandstr2(){
    $str='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
    $randStr = str_shuffle($str);//打乱字符串
    $rands= substr($randStr,0,4);//substr(string,start,length);返回字符串的一部分
    return $rands;
}
//地图计算范围，可以做搜索用户
function GetRange($lat,$lon,$raidus){
    //计算纬度
    $degree = (24901 * 1609) / 360.0;
    $dpmLat = 1 / $degree;
    $radiusLat = $dpmLat * $raidus;
    $minLat = $lat - $radiusLat; //得到最小纬度
    $maxLat = $lat + $radiusLat; //得到最大纬度
    //计算经度
    $mpdLng = $degree * cos($lat * (PI / 180));
    $dpmLng = 1 / $mpdLng;
    $radiusLng = $dpmLng * $raidus;
    $minLng = $lon - $radiusLng; //得到最小经度
    $maxLng = $lon + $radiusLng; //得到最大经度
    //范围
    $range = array(
        'minLat' => $minLat,
        'maxLat' => $maxLat,
        'minLon' => $minLng,
        'maxLon' => $maxLng
    );
    return $range;
}
//获取地图2点之间的距离
function GetDistance($lat1, $lng1, $lat2, $lng2){
    $radLat1 = $lat1 * (PI / 180);
    $radLat2 = $lat2 * (PI / 180);
    $a = $radLat1 - $radLat2;
    $b = ($lng1 * (PI / 180)) - ($lng2 * (PI / 180));
    $s = 2 * asin(sqrt(pow(sin($a/2),2) + cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)));
    $s = $s * EARTH_RADIUS;
    $s = round($s * 10000) / 10000;
    return $s;
}

function getLatLng($address='',$city='')
{
    $result = array();
    $ak = 'BvjaP9G4yYMN14AoTx3WiW9X';//您的百度地图ak，可以去百度开发者中心去免费申请
    $url ="http://api.map.baidu.com/geocoder/v2/?callback=renderOption&output=json&address=".$address."&city=".$city."&ak=".$ak;
    $data = file_get_contents($url);
    $data = str_replace('renderOption&&renderOption(', '', $data);
    $data = str_replace(')', '', $data);
    $data = json_decode($data,true);
    if (!empty($data) && $data['status'] == 0) {
        $result['lat'] = $data['result']['location']['lat'];
        $result['lng'] = $data['result']['location']['lng'];
        return $result;//返回经纬度结果
    }else{
        return null;
    }
}

function query_address($key_words){
    $header[] = 'Referer: http://lbs.qq.com/webservice_v1/guide-suggestion.html';
    $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36';
    $url ="http://apis.map.qq.com/ws/place/v1/suggestion/?&region=&key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77&keyword=".$key_words;

    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    // print_r($output);die;
    //释放curl句柄
    curl_close($ch);
    // return $output;
    $result = json_decode($output,true);
    // print_r($result);
    // $res = $result['data'][0];
    return $result;
    //echo json_encode(['error_code'=>'SUCCESS','reason'=>'查询成功','result'=>$result);
}

/**
 * 搜索地址，查询周边的位置 （）
 */
function query_address2($key_words){
    $header[] = 'Referer: http://lbs.qq.com/webservice_v1/guide-suggestion.html';
    $header[] = 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.139 Safari/537.36';
    $url ="http://apis.map.qq.com/ws/place/v1/suggestion/?&region=&key=OB4BZ-D4W3U-B7VVO-4PJWW-6TKDJ-WPB77&keyword=".$key_words;

    $ch = curl_init();
    //设置选项，包括URL
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);

    //执行并获取HTML文档内容
    $output = curl_exec($ch);
    // print_r($output);die;
    //释放curl句柄
    curl_close($ch);
    // return $output;
    $result = json_decode($output,true);
    // print_r($result);
    // $res = $result['data'][0];
    return $result;
    //echo json_encode(['error_code'=>'SUCCESS','reason'=>'查询成功','result'=>$result);
}

// 应用公共文件
