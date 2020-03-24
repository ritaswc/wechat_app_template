<?php
/**
 * ALIPAY API: zhima.data.batch.feedback request
 *
 * @author auto create
 *
 * @since  1.0, 2017-05-02 14:40:53
 */

namespace Alipay\Request;

class ZhimaDataBatchFeedbackRequest extends AbstractAlipayRequest
{
    /**
     * 扩展参数
     **/
    private $bizExtParams;
    /**
     * 单条数据的数据列，多个列以逗号隔开
     **/
    private $columns;
    /**
     * 反馈的json格式文件，其中{"records":  是每个文件的固定开头，文件中的字段名请使用数据反馈模板（该模板是通过“获取数据反馈模板”接口获得）中字段编码列的英文字段来组装
     **/
    private $file;
    /**
     * 是反馈文件的数据编码，如果文件格式是UTF-8，则填写UTF-8，如果文件格式是GBK，则填写GBK
     **/
    private $fileCharset;
    /**
     * 文件描述信息
     **/
    private $fileDescription;
    /**
     * 反馈的数据类型
     **/
    private $fileType;
    /**
     * 主键列使用反馈字段进行组合，也可以使用反馈的某个单字段（确保主键稳定，而且可以很好的区分不同的数据）。例如order_no,pay_month或者order_no,bill_month组合，对于一个order_no只会有一条数据的情况，直接使用order_no作为主键列
     **/
    private $primaryKeyColumns;
    /**
     * 文件数据记录条数
     **/
    private $records;

    public function setBizExtParams($bizExtParams)
    {
        $this->bizExtParams = $bizExtParams;
        $this->apiParams['biz_ext_params'] = $bizExtParams;
    }

    public function getBizExtParams()
    {
        return $this->bizExtParams;
    }

    public function setColumns($columns)
    {
        $this->columns = $columns;
        $this->apiParams['columns'] = $columns;
    }

    public function getColumns()
    {
        return $this->columns;
    }

    public function setFile($file)
    {
        $this->file = $file;
        $this->apiParams['file'] = $file;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFileCharset($fileCharset)
    {
        $this->fileCharset = $fileCharset;
        $this->apiParams['file_charset'] = $fileCharset;
    }

    public function getFileCharset()
    {
        return $this->fileCharset;
    }

    public function setFileDescription($fileDescription)
    {
        $this->fileDescription = $fileDescription;
        $this->apiParams['file_description'] = $fileDescription;
    }

    public function getFileDescription()
    {
        return $this->fileDescription;
    }

    public function setFileType($fileType)
    {
        $this->fileType = $fileType;
        $this->apiParams['file_type'] = $fileType;
    }

    public function getFileType()
    {
        return $this->fileType;
    }

    public function setPrimaryKeyColumns($primaryKeyColumns)
    {
        $this->primaryKeyColumns = $primaryKeyColumns;
        $this->apiParams['primary_key_columns'] = $primaryKeyColumns;
    }

    public function getPrimaryKeyColumns()
    {
        return $this->primaryKeyColumns;
    }

    public function setRecords($records)
    {
        $this->records = $records;
        $this->apiParams['records'] = $records;
    }

    public function getRecords()
    {
        return $this->records;
    }
}
