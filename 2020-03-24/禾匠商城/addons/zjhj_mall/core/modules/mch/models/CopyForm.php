<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/13
 * Time: 16:58
 */

namespace app\modules\mch\models;

use app\hejiang\Cloud;

class CopyForm extends MchModel
{
    public $url;
    public $level = 2;

    public function rules()
    {
        return [
            [['url'], 'trim'],
            [['url'], 'string']
        ];
    }

    public function copy()
    {
        if (!$this->validate()) {
            return $this->errorResponse;
        }
        if (strpos($this->url, 'item.jd')) {
//            $id = $this->str_substr('item.jd.com/', '.html', $this->url);
            $id = $this->preg_substr('/item.jd.[a-z]+\//', '/.html/', $this->url);
            $data = $this->j_copy($id[0]);
            if ($data['code'] == 1) {
                try{
                    $data = $this->j_copy_2($id[0]);
                }catch(\Exception $e){
                    return [
                        'code'=>1,
                        'msg'=>$e->getMessage()
                    ];
                }
            }
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => $data
            ];
        }
        $arr = explode('?', $this->url);
        if (count($arr) < 2) {
            return [
                'code' => 1,
                'msg' => '链接错误，请检查链接'
            ];
        }

        if (strpos($arr[0], 'taobao') || strpos($arr[0], 'tmall')) {
//            $id = 550718798356;
//            $id = $this->preg_substr('/&?id=/', '/&/', $arr[1]);
            $id = $this->preg_substr('/(\?id=|&id=)/', '/&/', $this->url);
            $data = $this->t_copy($id[0]);
            if ($data['code'] == 1) {
                return $data;
            }
            return [
                'code' => 0,
                'msg' => 'success',
                'data' => $data
            ];
        }
        return [
            'code' => 1,
            'msg' => '链接错误，请检查链接'
        ];
    }

    /**
     * @param $id
     * @return array
     * 淘宝&&天猫抓取
     */
    public function t_copy($id)
    {
        $url_tianmao = "http://hws.m.taobao.com/cache/wdetail/5.0/?id=" . $id;
//        $url_tianmao = "http://hws.m.taobao.com/cache/wdetail/5.0/?id=550718798356";
//        $url_tianmao = "http://cje.tunnel.qydev.com/test/test.php?vid=".$id;
//        try {
//            $html = $this->getHTTPS($url_tianmao);
//        } catch (\Exception $e) {
//            try {
//                $html = $this->normal_curl($url_tianmao);
//            } catch (\Exception $e) {
//                var_dump($e->getMessage());
//                exit();
//            }
//        }
        $api = "https://auth.zjhejiang.com/mall/copy/index";
        $curl = \app\hejiang\cloud\HttpClient::get($api, ['vid'=>$id]);
        $res = json_decode($curl, true);
        if ($res && isset($res['code'])) {
            if ($res['code'] != 0) {
                return [
                    'code'=>1,
                    'msg'=>$res['msg']
                ];
            }
            $html = $res['data'];
            $coding = mb_detect_encoding($html, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5', 'ISO-8859-1'));
            $html = mb_convert_encoding($html, 'UTF-8', $coding);
//        $content_ori = strip_tags($html);
            $html = json_decode($html, true);
            return self::data($html);
        } else {
            return [
                'code'=>1,
                'msg'=>'操作失败，请重试'
            ];
        }
    }
    public function t_copy_2($html = null)
    {
        $data = self::data($html);
        return [
            'code'=>0,
            'data'=>$data,
            'msg'=>''
        ];
    }
    private function data($html = null)
    {
        $content_arr = $html;
        if ($content_arr['ret'][0] != 'SUCCESS::调用成功') {
            return [
                'code' => 1,
                'msg' => $content_arr['ret'][0],
                'data'=>$html
            ];
        }
        $info = $content_arr['data']['itemInfoModel'];
        //标题
        $title = $info['title'];
        //缩略图
        $picsPath = $info['picsPath'];

        $pro_detail = json_decode($content_arr['data']['apiStack']['0']['value'], true);
        $pro_data = $pro_detail['data'];
        $pro_detail_info = $pro_data['itemInfoModel'];
        //商品价格
        $price = isset($pro_detail_info['priceUnits'][1]) ? $pro_detail_info['priceUnits'][1]['price'] : $pro_detail_info['priceUnits'][0]['price'];
        $price_arr = explode('-', $price);
        $goods_price = $price_arr[0];//原价
        $sale_price = $pro_detail_info['priceUnits'][0]['price'];
        $sale_price_arr = explode('-', $sale_price);
        $goods_price_sale = $sale_price_arr[0];//售价
        //销量
        $sale_count = $pro_detail_info['totalSoldQuantity'];
        //库存
        $quantity = $pro_detail_info['quantity'];

        $cat_model = $content_arr['data']['skuModel'];
        $quantity_model = $pro_data['skuModel'];
        $quantity_list = [];
        if (isset($quantity_model['skus'])) {
            $quantity_list = $quantity_model['skus'];
        }
        //规格列表
        $attr_group_list = [];
        //规格库存列表
        $checked_attr_list = [];

        if (isset($cat_model['skuProps'])) {
            foreach ($cat_model['skuProps'] as $index => $value) {
                if ($index > $this->level) {
                    break;
                }
                $attr_group_list[$index]['attr_group_name'] = $value['propName'];
                $attr_group_list[$index]['attr_list'] = [];
                foreach ($value['values'] as $key => $item) {
                    $attr_group_list[$index]['attr_list'][$key]['attr_name'] = $item['name'];
                    $attr_group_list[$index]['attr_list'][$key]['attr_group_name'] = $value['propName'];
                    $attr_group_list[$index]['attr_list'][$key]['imgUrl'] = isset($item['imgUrl']) ? $item['imgUrl'] : "";
                }
            }
            $ppathIdmap = $cat_model['ppathIdmap'];
            $data = $quantity_list;
//            $data = $pro_data['skuModel']['skus'];
            $checked_attr_list = $this->get_attr_list($cat_model['skuProps'], 0);
            foreach ($checked_attr_list as $index => $value) {
                try {
                    $dd = $data[$ppathIdmap[$value['dataId']]];
                    $num = $dd['quantity'];
                    $price = $dd['priceUnits'][0]['price'];
                } catch (\Exception $e) {
                    $num = 0;
                    $price = 0;
                }
                if ($num) {
                    $checked_attr_list[$index]['num'] = $num;
                    $checked_attr_list[$index]['price'] = $price;
                } else {
                    $checked_attr_list[$index]['num'] = 0;
                    $checked_attr_list[$index]['price'] = 0;
                }
            }
        }
        //图文详情
        $detail_info = isset($content_arr['data']['descInfo']) ? $content_arr['data']['descInfo'] : "";
//        if (isset($content_arr['data']['descInfo'])) {
//            $detail_info = $this->detail_info($content_arr['data']['descInfo']['fullDescUrl']);
//        }
//        VarDumper::dump($checked_attr_list,6,1);
//        VarDumper::dump($pro_detail['data'], 9, 1);
//        VarDumper::dump($content_arr['data']['skuModel'], 6, 1);
//        exit();
        return [
            'title' => $title,//标题
            'picsPath' => $picsPath,//缩略图
            'price' => $goods_price,//原价
            'sale_price' => $goods_price_sale,//售价
            'sale_count' => $sale_count,//销量
            'quantity' => $quantity,//库存
            'attr_group_list' => $attr_group_list,//规格列表
            'checked_attr_list' => $checked_attr_list,//规格库存列表
            'detail_info' => $detail_info//图文详情
        ];
    }

    /**
     * @param $id
     * @return array
     * 京东抓取
     */
    public function j_copy($id)
    {
        $appkey = '166398de4092e4dcbe0275ca4fe728dc';
        //图文详情
        try {
            $detail_api = "https://way.jd.com/JDCloud/mobilebigfield?skuid={$id}&appkey={$appkey}";
            $html = $this->getHTTPS($detail_api);
            $detail_json = json_decode($html, true);
            if ($detail_json['code'] != '10000') {
                return [
                    'code' => 1,
                    'msg' => $detail_json['msg']
                ];
            }
            if ($detail_json['result']['error_response']) {
                return [
                    'code' => 1,
                    'msg' => '链接错误_1'
                ];
            }
            $detail_info = $detail_json['result']['jingdong_new_ware_mobilebigfield_get_responce']['result'];
        } catch (\Exception $e) {
            $detail_info = "";
        }
        //商品基本信息
        $title = '';
        $goods_price_sale = '';
        $goods_price = '';
        $sale_count = '';
        $quantity = '';
        $attr_group_list = [];
        $checked_attr_list = [];
        $picsPath = [];
        try {
            $basefields = "pname,size,color,weight,imagePath";
            $baseproduct_api = "https://way.jd.com/JDCloud/baseproduct?ids={$id}&appkey={$appkey}&basefields={$basefields}";
            $html = $this->getHTTPS($baseproduct_api);
            $base_json = json_decode($html, true);
            if ($base_json['code'] != '10000') {
                return [
                    'code' => 1,
                    'msg' => $base_json['msg']
                ];
            }
            if ($base_json['result']['error_response']) {
                return [
                    'code' => 1,
                    'msg' => '链接错误_2'
                ];
            }
            $arr = $base_json['result']['jingdong_new_ware_baseproduct_get_responce']['listproductbase_result'];
            $title = $arr[0]['pname'];
            if ($arr[0]['size']) {
                $attr_group_list[] = [
                    'attr_group_name' => '规格',
                    'attr_list' => [
                        [
                            'attr_name' => $arr[0]['size']
                        ]
                    ]
                ];
                $checked_attr_list[0]['attr_list'][] = [
                    'attr_group_name' => '规格',
                    'attr_name' => $arr[0]['size']
                ];
            }
            if ($arr[0]['color']) {
                $attr_group_list[] = [
                    'attr_group_name' => '颜色',
                    'attr_list' => [
                        [
                            'attr_name' => $arr[0]['color']
                        ]
                    ]
                ];
                $checked_attr_list[0]['attr_list'][] = [
                    'attr_group_name' => '颜色',
                    'attr_name' => $arr[0]['color']
                ];
            }
            if ($arr[0]['imagePath']) {
                $picsPath[] = preg_replace('/n0/', 'n12', $arr[0]['imagePath'], 1);
            }
        } catch (\Exception $e) {
        }
        try {
            $img_api = "https://way.jd.com/JDCloud/productimage?sku_id={$id}&appkey={$appkey}";
            $html = $this->getHTTPS($img_api);
            $img_json = json_decode($html, true);
            if ($img_json['code'] != '10000') {
                return [
                    'code' => 1,
                    'msg' => $img_json['msg']
                ];
            }
            if ($img_json['result']['error_response']) {
                return [
                    'code' => 1,
                    'msg' => '链接错误_3'
                ];
            }
            $arr = $img_json['result']['jingdong_ware_productimage_get_responce']['image_path_list'][0]['image_list'];
            foreach ($arr as $index => $value) {
                $path = preg_replace('/n5/', 'n12', $value['path'], 1);
                $picsPath[] = $path;
            }
        } catch (\Exception $e) {
        }

        return [
            'title' => $title,//标题
            'picsPath' => $picsPath,//缩略图
            'price' => $goods_price,//原价
            'sale_price' => $goods_price_sale,//售价
            'sale_count' => $sale_count,//销量
            'quantity' => $quantity,//库存
            'attr_group_list' => $attr_group_list,//规格列表
            'checked_attr_list' => $checked_attr_list,//规格库存列表
            'detail_info' => $detail_info//图文详情
        ];
    }

    /**
     * @param $list //规格列表
     * @param $level //规格列表下标
     * @param array $attr_list
     * @param string $n
     * @param array $new_list
     * @return array
     * 规格列表数组重组
     */
    public function get_attr_list($list, $level, $attr_list = array(), $n = '', $new_list = array())
    {
        if(isset($list[$level]['values'])) {
            foreach ($list[$level]['values'] as $key => $item) {
                $attr_list_new = $attr_list;
                $n_n = $n;
                $a = [];
                $n_n .= $list[$level]['propId'] . ':' . $item['valueId'];
                $a['attr_group_name'] = $list[$level]['propName'];
                $a['attr_id'] = null;
                $a['attr_name'] = $item['name'];
                array_push($attr_list_new, $a);
                if ($level < count($list) - 1 && $level < $this->level) {
                    $new_list = $this->get_attr_list($list, $level + 1, $attr_list_new, $n_n . ';', $new_list);
                } else {
                    $new_list_new['attr_list'] = $attr_list_new;
                    $new_list_new['dataId'] = $n_n;
                    $new_list[] = $new_list_new;
                }
            }
        }
        return $new_list;
    }

    /**
     * 图文详情
     */
    public function detail_info($url)
    {
        try {
            $html = $this->getHTTPS($url);
        } catch (\Exception $e) {
            try {
                $html = $this->normal_curl($url);
            } catch (\Exception $e) {
                \Yii::warning('图文详情出错');
                return "";
//                var_dump($e->getMessage());
//                exit();
            }
        }
        $coding = mb_detect_encoding($html, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5', 'ISO-8859-1'));
        $html = mb_convert_encoding($html, 'utf-8', $coding);
//        $content_ori = strip_tags($html);
        $content_arr = json_decode($html, true);
        try {
            $info = $this->preg_substr('/<body>[^>]*>/', '/<\/body>/', $content_arr['data']['desc']);
        } catch (\Exception $e) {
            \Yii::warning('图文详情错误：' . $e->getMessage());
            $info[0] = '';
        }
        return $info[0];
    }

    /**
     * @param $start
     * @param $end
     * @param $str
     * @return array
     * 正则截取函数
     */
    public function preg_substr($start, $end, $str) // 正则截取函数
    {
        try {
            $temp = preg_split($start, $str);
        } catch (\Exception $e) {
            var_dump($str);
            exit();
        }
        $result = [];
        foreach ($temp as $index => $value) {
            if ($index == 0) {
                continue;
            }
            $content = preg_split($end, $value);
            array_push($result, $content[0]);
        }
        return $result;
    }

    /**
     * @param $start
     * @param $end
     * @param $str
     * @return array
     * 字符串截取函数
     */
    function str_substr($start, $end, $str) // 字符串截取函数
    {
        $temp = explode($start, $str);
        try {
            $result = [];
            foreach ($temp as $index => $value) {
                if ($index == 0) {
                    continue;
                }
                $content = explode($end, $value);
                array_push($result, $content[0]);
            }
            return $result;
        } catch (\Exception $e) {
            var_dump($temp);
            return [];
        }
    }

    /**
     * @param $url
     * @return mixed
     * curl访问https
     */
    public function getHTTPS($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_REFERER, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /**
     * @param $url
     * @return mixed
     * curl访问http
     */
    public function normal_curl($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        //错误提示
        if (curl_exec($curl) === false) {
            die(curl_error($curl));
        }
        // 检查是否有错误发生
        if (curl_errno($curl)) {
            echo 'Curl error: ' . curl_error($curl);
        }
        curl_close($curl);
        return $data;
    }

    public function ihttp_build_curl($url, $extra = array(), $timeout = 60)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        @curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        @curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSLVERSION, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:9.0.1) Gecko/20100101 Firefox/9.0.1');

        $data = curl_exec($ch);
        curl_close($ch);
        return self::ihttp_response_parse($data);
    }
    public function ihttp_response_parse($data, $chunked = false)
    {
        $rlt = array();
        $headermeta = explode('HTTP/', $data);
        if (count($headermeta) > 2) {
            $data = 'HTTP/' . array_pop($headermeta);
        }
        $pos = strpos($data, "\r\n\r\n");
        $split1[0] = substr($data, 0, $pos);
        $split1[1] = substr($data, $pos + 4, strlen($data));

        $split2 = explode("\r\n", $split1[0], 2);
        preg_match('/^(\S+) (\S+) (.*)$/', $split2[0], $matches);
        $rlt['code'] = $matches[2];
        $rlt['status'] = $matches[3];
        $rlt['responseline'] = $split2[0];
        $header = explode("\r\n", $split2[1]);
        $isgzip = false;
        $ischunk = false;
        foreach ($header as $v) {
            $pos = strpos($v, ':');
            $key = substr($v, 0, $pos);
            $value = trim(substr($v, $pos + 1));
            if (is_array($rlt['headers'][$key])) {
                $rlt['headers'][$key][] = $value;
            } elseif (!empty($rlt['headers'][$key])) {
                $temp = $rlt['headers'][$key];
                unset($rlt['headers'][$key]);
                $rlt['headers'][$key][] = $temp;
                $rlt['headers'][$key][] = $value;
            } else {
                $rlt['headers'][$key] = $value;
            }
            if (!$isgzip && strtolower($key) == 'content-encoding' && strtolower($value) == 'gzip') {
                $isgzip = true;
            }
            if (!$ischunk && strtolower($key) == 'transfer-encoding' && strtolower($value) == 'chunked') {
                $ischunk = true;
            }
        }
        $rlt['content'] = $split1[1];
        if ($isgzip && function_exists('gzdecode')) {
            $rlt['content'] = gzdecode($rlt['content']);
        }

        $rlt['meta'] = $data;
        return $rlt;
    }

    private function j_copy_2($id)
    {
        // 售价
        $priceUrl = "https://pe.3.cn/prices/mgets?skuids=" . $id;
        $responsePrice = $this->ihttp_build_curl($priceUrl);
        $responsePrice = json_decode($responsePrice['content'], true);
        if(empty($responsePrice)){
            throw new \Exception('未从京东获取到商品信息',1);
        }
        $goodsPrice = $responsePrice[0]['p'];
        $goodsPriceSale = $responsePrice[0]['p'];

        $url = "https://item.m.jd.com/ware/detail.json?wareId=".$id;
        $html = $this->ihttp_build_curl($url);
        $details = json_decode($html['content'], true);
        if( empty($details) ) {
            throw new \Exception('未从京东获取到商品信息',1);
        }
        $title = $details['ware']['wname'];// 标题；

        // 商品图片
        $picsPath = array( );
        $imgUrls = $details["ware"]["images"];
        foreach( $imgUrls as $imgUrl ) {
            $path = preg_replace('/n[0-9]+/', 'n12', $imgUrl['bigpath'], 1);
            $picsPath[] = $path;
        }

        // 商品详情
        $prodectContent = $details["wdis"];
        $prodectContent = strval($prodectContent);

        // 规格
        $skuColorSize = $details['ware']['skuColorSize'];
        $attrGroupList = [];
        $checkAttrList = [];
//        if(!empty($skuColorSize['colorSizeTitle'])){
//            foreach($skuColorSize['colorSizeTitle'] as $key => $value){
//                $attrGroup['attr_group_name'] = $value;
//                $attrGroup['attr_list'] = [];
//                foreach($skuColorSize['colorSize'] as $k => $v){
//                    $attrList['attr_name'] = $v[substr($key,0,strlen($key)-4)];
//                    $ok = true;
//                    foreach($attrGroup['attr_list'] as $k1 => $v1){
//                        if(count(array_diff($v1,$attrList)) > 0){
//                            $ok = false;
//                        }
//                    }
//                    if($ok){
//                        $attrGroup['attr_list'][] = $attrList;
//                    }
//                }
//                $attrGroupList[] = $attrGroup;
//            }
//        }

        $saleCount = 0;
        $quantity = 0;
        $data = [
            'title' => $title,//标题
            'picsPath' => $picsPath,//缩略图
            'price' => $goodsPrice,//原价
            'sale_price' => $goodsPriceSale,//售价
            'sale_count' => $saleCount,//销量
            'quantity' => $quantity,//库存
            'attr_group_list' => $attrGroupList,//规格列表
            'checked_attr_list' => $checkAttrList,//规格库存列表
            'detail_info' => $prodectContent//图文详情
        ];
        return $data;
    }
}
