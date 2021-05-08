<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/9
 * Time: 13:45
 */

namespace app\utils;

use app\models\Goods;
use app\models\Order;
use app\models\OrderDetail;
use app\models\Printer;
use app\models\PrinterSetting;
use app\models\PtGoods;
use app\models\PtOrder;
use app\models\PtOrderDetail;
use app\models\Store;

class PrinterPtOrder
{

    public $store_id;
    public $store;
    public $order_id;
    public $setting;
    public $printer;
    public $printer_setting;


    public function __construct($store_id, $order_id)
    {
        $this->store_id = $store_id;
        $this->store = Store::findOne($store_id);
        $this->order_id = $order_id;
    }

    public function print_order()
    {
        $this->setting = PrinterSetting::findOne(['store_id' => $this->store_id]);//打印设置
        if (!$this->setting) {
            return false;
        }
        $this->printer = Printer::findOne(['id' => $this->setting->printer_id]);
        if (!$this->printer) {
            return false;
        }
        $this->printer_setting = json_decode($this->printer->printer_setting, true);//打印机配置
        if ($this->printer->printer_type == 'kdt2') {
            return $this->printer_1();
        }
        if ($this->printer->printer_type == 'yilianyun-k4') {
            return $this->printer_2();
        }
        if ($this->printer->printer_type == 'feie') {
            return $this->printer_3();
        }
    }

    /**
     * 365打印机
     */
    private function printer_1()
    {
        $order = PtOrder::findOne(['id' => $this->order_id, 'is_delete' => 0]);
        $addtime = date('Y-m-d H:i:s', $order->addtime);
        $goods_list = $this->getPtOrderGoodsList($order->id);
        header("Content-Type: text/html;charset=utf-8");
        $DEVICE_NO = $this->printer_setting['name'];
        $key = $this->printer_setting['key'];
        $content = "";
        $content .= "<CB>{$this->store->name}</CB><BR>";
        $content .= "订单号：{$order->order_no}<BR>";
        $content .= "下单时间：{$addtime}<BR>";
        $content .= "--------------------------------<BR>";
        $content .= "名称　　　　　 单价  数量 金额<BR>";
        $content .= "--------------------------------<BR>";
        foreach ($goods_list as $k => $v) {
            $price = round($v['total_price'] / $v['num'], 2);
            $content .= $this->r_str_pad($v['name']) . " " . str_pad($price, 5) . " " . str_pad($v['num'], 4) . " " . round($v['total_price'], 2) . '<BR>';
        }
        if ($order->content) {
            $content .= "备注：{$order->content}<BR>";
        }
        $content .= "--------------------------------<BR>";
        $content .= "运费：{$order->express_price}元<BR>";
        $content .= "总计：{$order->total_price}元<BR>";
        $content .= "实付：{$order->pay_price}元<BR>";
        $content .= "收货人：{$order->name}<BR>";
        $content .= "收货地址：{$order->address}<BR>";
        $content .= "收货人电话：{$order->mobile}<BR>";

//        $content .= "<QR>http://open.printcenter.cn</QR><BR>";
        $result = $this->sendSelfFormatOrderInfo($DEVICE_NO, $key, 1, $content);
        \Yii::warning('==>>' . $result);
        return $result;
    }

    /**
     * 365打印机
     */
    private function sendSelfFormatOrderInfo($device_no, $key, $times, $orderInfo)
    {
 // $times打印次数
        $selfMessage = array(
            'deviceNo' => $device_no,
            'printContent' => $orderInfo,
            'key' => $key,
            'times' => $times
        );
        $url = "http://open.printcenter.cn:8080/addOrder";
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded ",
                'method' => 'POST',
                'content' => http_build_query($selfMessage),
            ),
        );
        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        return $result;
    }

    /**
     * @param $order_id
     * @return mixed
     * 订单商品详情
     */
    public function getPtOrderGoodsList($order_id)
    {
        $order_detail_list = PtOrderDetail::find()->alias('od')
            ->leftJoin(['g' => PtGoods::tableName()], 'od.goods_id=g.id')
            ->where([
                'od.is_delete' => 0,
                'od.order_id' => $order_id,
            ])->select('od.*,g.name')->asArray()->all();
        return $order_detail_list;
    }

    /**
     * 补齐空格
     * 截取七个中文字符长度
     */
    private function r_str_pad($input)
    {
        $string = "";
        $count = 0;
        $c_count = 0;
        for ($i = 0; $i < mb_strlen($input, 'UTF-8'); $i++) {
            $char = mb_substr($input, $i, 1, 'UTF-8');
            $string .= $char;
            if (strlen($char) == 3) {
                $count += 2;
                $c_count++;
            } else {
                $count += 1;
            }
            if ($count >= 14) {
                break;
            }
        }
        if ($count < 14) {
            $string = str_pad($string, 14 + $c_count);
        }
        return $string;
    }

    /**
     * 易联云打印机-k4
     */
    private function printer_2()
    {
        $machine_code = $this->printer_setting['machine_code'];//终端编号
        $key = $this->printer_setting['key'];//终端密钥
        $client_id = $this->printer_setting['client_id'];//用户ID
        $client_key = $this->printer_setting['client_key'];//用户密钥
        $time = $this->printer_setting['time'];//打印联数

        $order = PtOrder::findOne(['id' => $this->order_id, 'is_delete' => 0]);
        $addtime = date('Y-m-d H:i:s', $order->addtime);
        $goods_list = $this->getPtOrderGoodsList($order->id);
        $print = new Yprint();
        $content = "<MN>{$time}</MN>";
        $content .= "<FB><center>{$this->store->name}</center></FB>\n";
        $content .= "订单号：{$order->order_no}\n";
        $content .= "下单时间：{$addtime}\n";
        $content .= "--------------------------------\n";
        $content .= "<table><tr><td>名称</td><td>数量</td><td>单价</td></tr>";
        foreach ($goods_list as $k => $v) {
            $price = round($v['total_price'] / $v['num'], 2);
            $v['name'] = str_replace('，', ',', $v['name']);
            $content .= "<tr><td>" . $this->r_mb_str($v['name'], 8) . "</td><td>" . $v['num'] . "</td><td>" . $price . "</td></tr>";
        }
        $content .= "</table>";
        if ($order->content) {
            $content .= "备注：{$order->content}\n";
        }
        $content .= "--------------------------------\n";
        $content .= "运费：{$order->express_price}元\n";
        $content .= "总计：{$order->total_price}元\n";
        $content .= "实付：{$order->pay_price}元\n";
        $content .= "收货人：{$order->name}\n";
        $content .= "收货地址：{$order->address}\n";
        $content .= "收货人电话：{$order->mobile}\n";


        return $print->action_print($client_id, $machine_code, $content, $client_key, $key);
    }

    /**
     * 补齐空格
     * 截取$n个中文字符长度
     */
    private function r_mb_str($input, $n)
    {
        $string = "";
        $count = 0;
        $c_count = 0;
        for ($i = 0; $i < mb_strlen($input, 'UTF-8'); $i++) {
            $char = mb_substr($input, $i, 1, 'UTF-8');
            $string .= $char;
            if (strlen($char) == 3) {
                $count += 2;
                $c_count++;
            } else {
                $count += 1;
            }
            if ($count >= 2 * $n) {
                break;
            }
        }
        if ($count < 2 * $n) {
            $string = str_pad($string, 2 * $n + $c_count);
        }
        return $string;
    }

    //飞鹅打印机
    public function printer_3()
    {
        $order = PtOrder::findOne(['id' => $this->order_id, 'is_delete' => 0]);
        $addtime = date('Y-m-d H:i:s', $order->addtime);
        $time = $this->printer_setting['time'];//打印联数
        $goods_list = $this->getPtOrderGoodsList($order->id);
        $content = "";
        $content .= "<CB>{$this->store->name}</CB><BR>";
        $content .= "订单号：{$order->order_no}<BR>";
        $content .= "下单时间：{$addtime}<BR>";
        $content .= "--------------------------------<BR>";
        $content .= "名称　　　　　 单价  数量 金额<BR>";
        $content .= "--------------------------------<BR>";
        foreach ($goods_list as $k => $v) {
            $price = round($v['total_price'] / $v['num'], 2);
            $content .= $this->r_str_pad($v['name']) . " " . str_pad($price, 5) . " " . str_pad($v['num'], 4) . " " . round($v['total_price'], 2) . '<BR>';
        }
        if ($order->content) {
            $content .= "备注：{$order->content}<BR>";
        }
        $content .= "--------------------------------<BR>";
        $content .= "运费：{$order->express_price}元<BR>";
        $content .= "总计：{$order->total_price}元<BR>";
        $content .= "实付：{$order->pay_price}元<BR>";
        if ($order->offline != 1) {
            $content .= "收货人：{$order->name}<BR>";
            $content .= "收货地址：{$order->address}<BR>";
            $content .= "收货人电话：{$order->mobile}<BR>";
        }
        return $this->wp_print($this->printer_setting['sn'], $content, $time);
    }

    /*
 *  方法1
    拼凑订单内容时可参考如下格式
    根据打印纸张的宽度，自行调整内容的格式，可参考下面的样例格式
*/
    private function wp_print($printer_sn, $orderInfo, $times)
    {
        $user = $this->printer_setting['user'];
        $ukey = $this->printer_setting['ukey'];
        $time = time();
        $ip = 'api.feieyun.cn';
        $path = '/Api/Open/';
        $content = array(
            'user' => $user,
            'stime' => $time,
            'sig' => sha1($user . $ukey . $time),
            'apiname' => 'Open_printMsg',

            'sn' => $printer_sn,
            'content' => $orderInfo,
            'times' => $times//打印次数
        );

        $client = new FeieYun($ip, 80);
        if (!$client->post($path, $content)) {
            return 'error';
        } else {
            //服务器返回的JSON字符串，建议要当做日志记录起来
            return $client->getContent();
        }
    }
}
