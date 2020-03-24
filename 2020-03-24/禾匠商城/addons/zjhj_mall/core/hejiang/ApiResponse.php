<?php

namespace app\hejiang;

/**
 * Structured HTTP API Response Class
 * 
 * @property int $code
 * @property string $msg
 * @property array|object $data
 */
class ApiResponse extends BaseApiResponse
{
    public function __construct($code, $msg = '', $data = []){
        parent::__construct();
        $this->code = $code;
        $this->msg  = $msg;
        $this->data = $data;
    }

    public function getCode(){
        return $this->raw['code'];
    }

    public function getMsg(){
        return $this->raw['msg'];
    }

    public function getData(){
        return $this->raw['data'];
    }

    protected function setCode($v){
        $this->raw['code'] = $v;
    }

    protected function setMsg($v){
        $this->raw['msg'] = $v;
    }

    protected function setData($v){
        $this->raw['data'] = $v;
    }
}