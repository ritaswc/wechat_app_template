<?php
/**
 * ALIPAY API: alipay.zdatafront.datatransfered.fileupload request
 *
 * @author auto create
 *
 * @since  1.0, 2017-05-02 14:41:11
 */

namespace Alipay\Request;

class AlipayZdatafrontDatatransferedFileuploadRequest extends AbstractAlipayRequest
{
    /**
     * 合作伙伴上传文件中的各字段,使用英文半角","分隔，file_type为json_data时必选
     **/
    private $columns;
    /**
     * 二进制字节数组，由文件转出，最大支持50M文件的上传
     **/
    private $file;
    /**
     * 文件描述信息，非解析数据类型必选
     **/
    private $fileDescription;
    /**
     * 文件摘要，算法SHA
     **/
    private $fileDigest;
    /**
     * 描述上传文件的类型
     **/
    private $fileType;
    /**
     * 上传数据文件的主键字段，注意该pk若出现重复则后入数据会覆盖前面的，file_type为json_data时必选
     **/
    private $primaryKey;
    /**
     * 上传数据文件包含的记录数，file_type为json_data时必选
     **/
    private $records;
    /**
     * 外部公司的数据源标识信息，由联接网络分配
     **/
    private $typeId;

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

    public function setFileDescription($fileDescription)
    {
        $this->fileDescription = $fileDescription;
        $this->apiParams['file_description'] = $fileDescription;
    }

    public function getFileDescription()
    {
        return $this->fileDescription;
    }

    public function setFileDigest($fileDigest)
    {
        $this->fileDigest = $fileDigest;
        $this->apiParams['file_digest'] = $fileDigest;
    }

    public function getFileDigest()
    {
        return $this->fileDigest;
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

    public function setPrimaryKey($primaryKey)
    {
        $this->primaryKey = $primaryKey;
        $this->apiParams['primary_key'] = $primaryKey;
    }

    public function getPrimaryKey()
    {
        return $this->primaryKey;
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

    public function setTypeId($typeId)
    {
        $this->typeId = $typeId;
        $this->apiParams['type_id'] = $typeId;
    }

    public function getTypeId()
    {
        return $this->typeId;
    }
}
