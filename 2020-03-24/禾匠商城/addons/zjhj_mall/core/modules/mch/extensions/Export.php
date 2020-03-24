<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/21
 * Time: 14:55
 */

namespace app\modules\mch\extensions;

use app\models\Order;
use app\models\OrderForm;
use app\models\OrderRefund;
use app\models\Shop;
use app\models\User;

class Export
{
    /**
     * excel导出
     */
    const EXPORT = 'EXPORT';

    //导出  header
    public static function exportHeader($EXCEL_OUT)
    {
        header("Content-type:text/csv");
        header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
        header('Expires:0');
        header('Pragma:public');
        echo $EXCEL_OUT;
    }

    //判断是否含有英文逗号，英文引号
    public static function Check($str)
    {
        $str = str_replace(array("\r\n", "\r", "\n"), "", $str);
        if (strpos($str, ',')) {
            if (strpos($str, "\"") || strpos($str, "\"") == 0) {
                $str = str_replace("\"", "\"\"", $str);
            }
            $str = "\"" . $str . "\"";
        } else {
            $str = "\"\t" . $str . "\"";
        }
        return $str;
    }

    /**
     * @param $info
     * 导出订单
     */
    public static function order($info)
    {
        $title = "序号,订单号,用户,商品名,商品信息,收件人,电话,地址,总金额（含运费）,运费,实际付款,付款状态,申请状态,发货状态,收货状态,快递单号,快递公司,下单时间";
        $title .= "\n";
        $EXCEL_OUT = mb_convert_encoding($title, 'GBK', 'UTF-8');
        foreach ($info as $index => $value) {
            $out = array();
            $out[] = $index + 1;
            $out[] = trim("\"\t" . $value['order_no'] . "\"");
            $out[] = trim(self::Check($value['nickname']));

            $goodsName = "";//商品名
            $goods_str = "";//商品信息
            foreach ($value['goods_list'] as $i => $v) {
                $goodsName .= "商品名：" . $v['name'];
                $attr_list = json_decode($v['attr']);
                if (is_array($attr_list)) {
                    foreach ($attr_list as $attr) {
                        $goods_str .= $attr->attr_group_name . "：" . $attr->attr_name . "，";
                    }
                }
                $goods_str .= "数量：" . $v['num'] . $v['unit'] . "，";
                $goods_str .= "小计：" . $v['total_price'] . "元";
                $goods_str .= "；";
            }
            $out[] = self::Check($goodsName);
            $out[] = self::Check($goods_str);

            $out[] = self::Check($value['name']);
            $out[] = trim("\"\t" . $value['mobile'] . "\"");
            $out[] = self::Check($value['address']);
            $out[] = $value['total_price'] . "元";
            $out[] = $value['express_price'] . "元";
            $out[] = $value['pay_price'] . "元";
            $out[] = ($value['is_pay'] == 1) ? "已付款" : "未付款";
            $out[] = ($value['apply_delete'] == 1) ? "取消中" : "无";
            $out[] = ($value['is_send'] == 1) ? "已发货" : "未发货";
            $out[] = ($value['is_confirm'] == 1) ? "已收货" : "未收货";
            $out[] = self::Check($value['express_no']);
            $out[] = self::Check($value['express']);


            $out[] = trim("\"\t" . date('Y-m-d H:i', $value['addtime']) . "\"");
//            $EXCEL_OUT .= iconv('UTF-8', 'GB2312', implode($out,',')."\n");
            $EXCEL_OUT .= mb_convert_encoding(implode(',', $out) . "\n", 'GB2312', 'UTF-8');//需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
        }
        $name = "订单导出-" . date('YmdHis', time());//导出文件名称
        header("Content-Disposition:attachment;filename={$name}.csv"); //“生成文件名称”=自定义
        self::exportHeader($EXCEL_OUT);
        exit();
    }

    /**
     * 约定字段名称
     * =============
     * num          编号(编号后端添加、前端不需要传)
     * platform     所属平台(编号后端添加、前端不需要传)
     * good_name    商品名称
     * attr         商品规格
     * good_num     商品数量
     * clerk_name   核销员名称
     * shop_name    核销门店名称
     * pay_price    实际付款
     * ==============
     * csv导出
     * @param array $data 导出的数据
     * @param array $fields 表头字段名
     */
    public static function order_3(array $data, array $fields)
    {
        $fields = array_merge(['num' => '序号', 'platform' => '所属平台'], $fields);
        $fieldVals = implode(',', array_values($fields)) . "\n";
        $EXCEL_OUT = mb_convert_encoding($fieldVals, 'GBK', 'UTF-8');
        $num = 1;
        foreach ($data as $dataK => $dataV) {
            $export = new Export();
            $arr = ['good_name', 'attr', 'good_num', 'good_no'];

            if ($export->arrayExists($arr, $fields)) {
                foreach ($dataV['goods_list'] as $goodK => $goodV) {
                    $outData = [];

                    $goods_str = "";//商品规格
                    $dataV['num'] = $num;
                    $num++;

                    $dataV['good_name'] = $goodV['name'];
                    if (isset($goodV['total_price'])) {
                        $dataV['pay_price'] = round($goodV['total_price'], 2);
                    }
                    if (isset($goodV['attr'])) {
                        $attrList = json_decode($goodV['attr']);
                        if (is_array($attrList)) {
                            foreach ($attrList as $item) {
                                $goods_str .= $item->attr_group_name . "：" . $item->attr_name . '，';
                            }
                        }
                        $dataV['attr'] = rtrim($goods_str);
                        $dataV['good_num'] = $goodV['num'];
                        $dataV['good_no'] = $goodV['good_no'];
                    }

                    foreach ($fields as $fieldK => $fieldV) {
                        if (in_array($fieldK, ['pay_price', 'total_price', 'express_price'])) {
                            $outData[] = $dataV[$fieldK];
                        } else {
                            $outData[] = trim(self::Check($dataV[$fieldK]));
                        }
                    }

                    //需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
                    $EXCEL_OUT .= mb_convert_encoding(implode(',', $outData) . "\n", 'GBK', 'UTF-8');
                }
            } else {
                $outData = [];
                $dataV['num'] = $num;
                $num++;

                foreach ($fields as $fieldK => $fieldV) {
                    $outData[] = trim(self::Check($dataV[$fieldK]));
                }

                //需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
                $EXCEL_OUT .= mb_convert_encoding(implode(',', $outData) . "\n", 'GBK', 'UTF-8');
            }
        }
        $name = date('YmdHis', time());//导出文件名称
        header("Content-Disposition:attachment;filename={$name}.csv"); //“生成文件名称”=自定义
        self::exportHeader($EXCEL_OUT);
        exit();
    }


    /**
     * @param array $data 需要比对的字段数组
     * @param array $fields
     * @return bool
     */
    private function arrayExists(array $data, array $fields)
    {
        foreach ($data as $datum) {
            if (array_key_exists($datum, $fields)) {
                return true;
            }
        }

        return false;
    }

    public static function order_2($info, $is_offline = null, $order_type = 0)
    {
        $title = "序号,订单号,用户,商品名,规格,数量,收件人,电话,地址";
        if ($is_offline) {
            $title .= ",总金额,核销人,核销门店";
        } else {
            $title .= ",总金额（含运费）,运费,运费快递单号,快递公司";
        }
        $title .= ",实际付款,付款状态,申请状态,发货状态,收货状态,下单时间,备注";
        if ($order_type == 2) {
            $title .= ",拼团状态";
        }
        $title .= "\n";
        $EXCEL_OUT = mb_convert_encoding($title, 'GBK', 'UTF-8');
        $count = 1;
        foreach ($info as $index => $value) {
            $order_form = OrderForm::findAll(['store_id' => $value['store_id'], 'order_id' => $value['id'], 'is_delete' => 0]);
            if ($is_offline) {
                $clerk = User::findOne(['id' => $value['clerk_id']]);
                $shop = Shop::findOne(['id' => $value['shop_id']]);
            } else {
                $clerk = new User();
                $shop = new Shop();
            }
            foreach ($value['goods_list'] as $i => $v) {
                //  $price = round($v['total_price'] * $value['pay_price'] / ($value['total_price'] - $value['express_price']), 2);
                $goods_str = "";//规格
                $out = array();
                $out[] = $count;
                $count++;
                $out[] = trim("\"\t" . $value['order_no'] . "\"");
                $out[] = trim(self::Check($value['nickname']));
                $out[] = trim(self::Check($v['name']));
                $attr_list = json_decode($v['attr']);
                if (is_array($attr_list)) {
                    foreach ($attr_list as $attr) {
                        $goods_str .= $attr->attr_group_name . "：" . $attr->attr_name . '，';
                    }
                }
                $out[] = self::Check($goods_str);
                $out[] = $v['num'] . $v['unit'];

                $out[] = self::Check($value['name']);
                $out[] = trim("\"\t" . $value['mobile'] . "\"");
                $out[] = self::Check($value['address']);
                $out[] = $value['total_price'] . "元";
                if ($is_offline) {
                    $out[] = $clerk->nickname;
                    $out[] = $shop->name;
                } else {
                    $out[] = $value['express_price'] . "元";
                    $out[] = trim(self::Check($value['express_no']));
                    $out[] = self::Check($value['express']);
                }
                $out[] = round(min($v['total_price'], ($value['pay_price'] - $value['express_price'])), 2) . "元";
                $out[] = ($value['is_pay'] == 1) ? "已付款" : "未付款";
                $out[] = ($value['apply_delete'] == 1) ? "取消中" : "无";
                $out[] = ($value['is_send'] == 1) ? "已发货" : "未发货";
                $out[] = ($value['is_confirm'] == 1) ? "已收货" : "未收货";
                $out[] = trim("\"\t" . date('Y-m-d H:i', $value['addtime']) . "\"");
                if ($order_form) {
                    $str = '';
                    foreach ($order_form as $key => $item) {
                        $str .= $item['key'] . ':' . $item['value'] . ',';
                    }
                    $content = self::Check($str);
                } else {
                    $content = self::Check($value['content']);
                }
                $out[] = $content;
                if ($order_type == 2) {
                    $status = ['', '待付款', '拼团中', '拼团成功', '拼团失败'];
                    $out[] = $status[$value['status']];
                }
                $EXCEL_OUT .= mb_convert_encoding(implode(',', $out) . "\n", 'GBK', 'UTF-8');//需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
            }
//            $EXCEL_OUT .= iconv('UTF-8', 'GB2312', implode($out,',')."\n");
        }
        $name = "订单导出-" . date('YmdHis', time());//导出文件名称
        header("Content-Disposition:attachment;filename={$name}.csv"); //“生成文件名称”=自定义
        self::exportHeader($EXCEL_OUT);
        exit();
    }

    /**
     * @param $info
     * 导出售后订单
     */
    public static function refund($info)
    {
        $title = "序号,订单号,用户,商品信息,收件人,电话,地址,售后类型,退款金额,申请理由,状态,售后申请时间";
        $title .= "\n";
        $EXCEL_OUT = mb_convert_encoding($title, 'GBK', 'UTF-8');
        foreach ($info as $index => $value) {
            $out = array();
            $out[] = $index + 1;
            $out[] = trim("\"\t" . $value['order_no'] . "\"");
            $out[] = trim("\"\t" . $value['nickname'] . "\"");

            $goods_str = "";//商品信息
            $goods_str .= "商品名：" . $value['goods_name'];
            $attr_list = json_decode($value['attr']);
            if (is_array($attr_list)) {
                foreach ($attr_list as $attr) {
                    $goods_str .= "，" . $attr->attr_group_name . "：" . $attr->attr_name;
                }
            }
            $goods_str .= "，数量：" . $value['num'] . "件，";
            $goods_str .= "金额：" . $value['total_price'] . "元";
            $out[] = self::Check($goods_str);

            $out[] = self::Check($value['name']);
            $out[] = trim("\"\t" . $value['mobile'] . "\"");
            $out[] = self::Check($value['address']);
            if ($value['refund_type'] == 1) {
                $out[] = "退货退款";
                $out[] = $value['refund_price'] . "元";
                $out[] = self::check($value['refund_desc']);
            } elseif ($value['refund_type'] == 2) {
                $out[] = "换货";
                $out[] = $value['refund_price'] . "元";
                $out[] = self::check($value['refund_desc']);
            }

            if ($value['refund_status'] == 0) {
                $out[] = "待处理";
            } elseif ($value['refund_status'] == 1) {
                $out[] = "已同意退款退货";
            } elseif ($value['refund_status'] == 2) {
                $out[] = "已同意换";
            } elseif ($value['refund_status'] == 3) {
                if ($value['refund_type'] == 1) {
                    $str = "已拒绝退货退款";
                } else {
                    $str = "已拒换货";
                }
                $out[] = self::Check($str . "，拒绝理由：" . $value['refund_refuse_desc']);
            }


            $out[] = trim("\"\t" . date('Y-m-d H:i', $value['addtime']) . "\"");
//            $EXCEL_OUT .= iconv('UTF-8', 'GB2312', implode($out,',')."\n");
            $EXCEL_OUT .= mb_convert_encoding(implode(',', $out) . "\n", 'GB2312', 'UTF-8');
        }


        $name = "售后订单导出-" . date('YmdHis', time());//导出文件名称
        header("Content-Disposition:attachment;filename={$name}.csv"); //“生成文件名称”=自定义
        self::exportHeader($EXCEL_OUT);
        exit();
    }

    /**
     * @param $info
     * 导出预约订单
     */
    public static function expBook($info)
    {
        $title = "序号,订单号,用户,商品名,表单信息,总金额,实际付款,付款状态,使用状态,下单时间";
        $title .= "\n";
        $EXCEL_OUT = mb_convert_encoding($title, 'GBK', 'UTF-8');
        foreach ($info as $index => $value) {
            $out = array();
            $out[] = $index + 1;
            $out[] = trim("\"\t" . $value['order_no'] . "\"");
            $out[] = trim(self::Check($value['nickname']));

            $goodsName = $value['goods_list']['name'];//商品名
            $goods_str = "";//商品信息
            if (is_array($value['goods_list']['form'])) {
                foreach ($value['goods_list']['form'] as $k => $form) {
                    $goods_str .= $form['key'] . "：" . $form['value'] . "，";
                }
            }

            $out[] = self::Check($goodsName);
            $out[] = self::Check($goods_str);

            $out[] = $value['total_price'] . "元";
            $out[] = $value['pay_price'] . "元";
            $out[] = ($value['is_pay'] == 1) ? "已付款" : "未付款";
            $out[] = ($value['is_use'] == 1) ? "已使用" : "未使用";


            $out[] = trim("\"\t" . date('Y-m-d H:i', $value['addtime']) . "\"");
//            $EXCEL_OUT .= iconv('UTF-8', 'GB2312', implode($out,',')."\n");
            $EXCEL_OUT .= mb_convert_encoding(implode(',', $out) . "\n", 'GB2312', 'UTF-8');//需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
        }
        $name = "预约订单导出-" . date('YmdHis', time());//导出文件名称
        header("Content-Disposition:attachment;filename={$name}.csv"); //“生成文件名称”=自定义
        self::exportHeader($EXCEL_OUT);
        exit();
    }

    /**
     * @param $info
     * 导出模板订单
     */
    public static function shipModel()
    {
        $title = "序号(可不填),订单号,快递单号";
        $title .= "\n";
        $EXCEL_OUT = iconv('UTF-8', 'GB2312', $title);
        $info = array();

        foreach ($info as $index => $value) {
            $out = array();
            $out[] = $index + 1;
            $out[] = trim("\"\t" . $value['order_no'] . "\"");
            $out[] = trim("\"\t" . $value['express_no'] . "\"");
            $EXCEL_OUT .= mb_convert_encoding(implode(',', $out) . "\n", 'GB2312', 'UTF-8'); //需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
        }
        $name = "" . date('YmdHis', time()); //导出文件名称
        header("Content-Disposition:attachment;filename={$name}.csv"); //“生成文件名称”=自定义
        self::exportHeader($EXCEL_OUT);
        exit();
    }

    public static function order_title(array $fields)
    {
        $fields = array_merge(['num' => '序号', 'platform' => '所属平台'], $fields);
        $fieldVals = implode(',', array_values($fields)) . "\n";
        $EXCEL_OUT = mb_convert_encoding($fieldVals, 'GBK', 'UTF-8');
        return $EXCEL_OUT;
    }

    /**
     * 约定字段名称
     * =============
     * num          编号(编号后端添加、前端不需要传)
     * good_name    商品名称
     * attr         商品规格
     * good_num     商品数量
     * clerk_name   核销员名称
     * shop_name    核销门店名称
     * pay_price    实际付款
     * ==============
     * csv导出
     * @param array $data 导出的数据
     * @param array $fields 表头字段名
     */
    public static function order_new(array &$data, array &$fields)
    {
        $fields = array_merge(['num' => '序号', 'platform' => '所属平台'], $fields);
        $EXCEL_OUT = "";
        $num = 1;

        foreach ($data as $dataK => &$dataV) {
            $export = new Export();
            $arr = ['good_name', 'attr', 'good_num', 'good_no'];

            if ($export->arrayExists($arr, $fields)) {
                foreach ($dataV['goods_list'] as $goodK => &$goodV) {
                    $outData = [];

                    $goods_str = "";//商品规格
                    $dataV['num'] = $num;
                    $num++;

                    $dataV['good_name'] = $goodV['name'];
                    $dataV['cost_price'] = $goodV['cost_price'];
                    if (isset($goodV['total_price'])) {
                        $dataV['pay_price'] = round($goodV['total_price'], 2);
                    }
                    if (isset($goodV['attr'])) {
                        $attrList = json_decode($goodV['attr']);
                        if (is_array($attrList)) {
                            foreach ($attrList as $item) {
                                $goods_str .= $item->attr_group_name . "：" . $item->attr_name . '，';
                            }
                        }
                        $dataV['attr'] = rtrim($goods_str);
                        $dataV['good_num'] = $goodV['num'];
                        $dataV['good_no'] = $goodV['good_no'];
                    }

                    foreach ($fields as $fieldK => &$fieldV) {
                        if (in_array($fieldK, ['pay_price', 'total_price', 'express_price', 'good_num', 'cost_price', 'rebate'])) {
                            $outData[] = $dataV[$fieldK];
                        } else {
                            $outData[] = trim(self::Check($dataV[$fieldK]));
                        }
                    }

                    //需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
                    $EXCEL_OUT .= mb_convert_encoding(implode(',', $outData) . "\n", 'GBK', 'UTF-8');
                }
            } else {
                $outData = [];
                $dataV['num'] = $num;
                $num++;

                foreach ($fields as $fieldK => $fieldV) {
                    $outData[] = trim(self::Check($dataV[$fieldK]));
                }

                //需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
                $EXCEL_OUT .= mb_convert_encoding(implode(',', $outData) . "\n", 'GBK', 'UTF-8');
            }
        }
        return $EXCEL_OUT;
    }

    /**
     * 通用
     * @param array $data
     * @param array $fields
     * @return string
     */
    public static function dataNew(array &$data, array &$fields)
    {
        $fields = array_merge(['num' => '序号'], $fields);
        $fieldVals = implode(',', array_values($fields)) . "\n";
        $EXCEL_OUT = mb_convert_encoding($fieldVals, 'GBK', 'UTF-8');
        $num = 1;
        foreach ($data as $dataK => &$dataV) {

            $outData = [];
            $dataV['num'] = $num;
            $num++;

            foreach ($fields as $fieldK => $fieldV) {
                $outData[] = trim(self::Check($dataV[$fieldK]));
            }

            //需要先启用 mbstring 扩展库，在 php.ini里将; extension=php_mbstring.dll 前面的 ; 去掉
            $EXCEL_OUT .= mb_convert_encoding(implode(',', $outData) . "\n", 'GBK', 'UTF-8');
        }

        $name = date('YmdHis', time());//导出文件名称
        header("Content-Disposition:attachment;filename={$name}.csv"); //“生成文件名称”=自定义
        self::exportHeader($EXCEL_OUT);
        exit();
    }
}
