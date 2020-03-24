<?php
namespace Qcloud_cos;
date_default_timezone_set('PRC');
class Cosapi
{
    //计算sign签名的时间参数
    const EXPIRED_SECONDS = 180;
    //512K
    const SLICE_SIZE_512K = 524288;
    //1M
    const SLICE_SIZE_1M = 1048576;
    //2M
    const SLICE_SIZE_2M = 2097152;
	//3M
    const SLICE_SIZE_3M = 3145728;
    //20M 大于20M的文件需要进行分片传输
    const MAX_UNSLICE_FILE_SIZE = 20971520;
	//失败尝试次数
    const MAX_RETRY_TIMES = 3;
	//返回的错误码
    const COSAPI_PARAMS_ERROR = -1;
    const COSAPI_NETWORK_ERROR = -2;
	//HTTP请求超时时间
    private static $timeout = 60;

	/*
     * 设置HTTP请求超时时间
     * @param  int  $timeout  超时时长
     */	
    public static function setTimeout($timeout = 60) {
        if (!is_int($timeout) || $timeout < 0) {
            return false;
        }

        self::$timeout = $timeout;
        return true;
    }

    /**
     * 上传文件,自动判断文件大小,如果小于20M则使用普通文件上传,大于20M则使用分片上传
     * @param  string  $bucketName   bucket名称
     * @param  string  $srcPath      本地文件路径
     * @param  string  $dstPath      上传的文件路径
	 * @param  string  $bizAttr      文件属性
	 * @param  string  $slicesize    分片大小(512k,1m,2m,3m)，默认:1m
	 * @param  string  $insertOnly   同名文件是否覆盖
     * @return [type]                [description]
     */
    public static function upload($bucketName, $srcPath, $dstPath,
               $bizAttr = null, $slicesize = null, $insertOnly = null)
    {

        if (!file_exists($srcPath)) {
            return array(
                    'code' => self::COSAPI_PARAMS_ERROR, 
                    'message' => 'file '.$srcPath.' not exists', 
                    'data' => array());
        }

        //文件大于20M则使用分片传输
	    if (filesize($srcPath) < self::MAX_UNSLICE_FILE_SIZE ) {
            return self::uploadfile($bucketName, $srcPath, $dstPath, $bizAttr, $insertOnly);
        } else {
			$sliceSize = self::getSliceSize($slicesize);
		    return self::upload_slice($bucketName, $srcPath, $dstPath, $bizAttr, $sliceSize, $insertOnly);
	    }
    }

    /*
     * 创建目录
     * @param  string  $bucketName bucket名称
     * @param  string  $path       目录路径
	 * @param  string  $bizAttr    目录属性
     */
    public static function createFolder($bucketName, $path, $bizAttr = null) {
        $path = self::normalizerPath($path, True);
        $path = self::cosUrlEncode($path);
        $expired = time() + self::EXPIRED_SECONDS;
        $url = self::generateResUrl($bucketName, $path);
        $sign = Auth::appSign($expired, $bucketName);

        $data = array(
            'op' => 'create',
            'biz_attr' => (isset($bizAttr) ? $bizAttr : ''),
        );
        
        $data = json_encode($data);

        $req = array(
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => $data,
            'header' => array(
                'Authorization:'.$sign,
                'Content-Type: application/json',
            ),
        );

        return self::sendRequest($req);
    }

    /*
     * 目录列表
     * @param  string  $bucketName bucket名称
     * @param  string  $path     目录路径，sdk会补齐末尾的 '/'
     * @param  int     $num      拉取的总数
     * @param  string  $pattern  eListBoth,ListDirOnly,eListFileOnly  默认both
     * @param  int     $order    默认正序(=0), 填1为反序,
     * @param  string  $offset   透传字段,用于翻页,前端不需理解,需要往前/往后翻页则透传回来
     */
    public static function listFolder(
                    $bucketName, $path, $num = 20, 
                    $pattern = 'eListBoth', $order = 0, 
                    $context = null) {
        $path = self::normalizerPath($path,True);

        return self::listBase($bucketName, $path, $num,
                $pattern, $order, $context);
    }

    /*
     * 目录列表(前缀搜索)
     * @param  string  $bucketName bucket名称
     * @param  string  $prefix   列出含此前缀的所有文件
     * @param  int     $num      拉取的总数
     * @param  string  $pattern  eListBoth(默认),ListDirOnly,eListFileOnly
     * @param  int     $order    默认正序(=0), 填1为反序,
     * @param  string  $offset   透传字段,用于翻页,前端不需理解,需要往前/往后翻页则透传回来
     */
    public static function prefixSearch(
                    $bucketName, $prefix, $num = 20, 
                    $pattern = 'eListBoth', $order = 0, 
                    $context = null) {
		$path = self::normalizerPath($prefix);

        return self::listBase($bucketName, $prefix, $num,
                $pattern, $order, $context);
    }
	
    /*
     * 目录更新
     * @param  string  $bucketName bucket名称
     * @param  string  $path      文件夹路径,SDK会补齐末尾的 '/'
     * @param  string  $bizAttr   目录属性
     */
    public static function updateFolder($bucketName, $path, $bizAttr = null) {
        $path = self::normalizerPath($path, True);

        return self::updateBase($bucketName, $path, $bizAttr);
    }

   /*
     * 查询目录信息
     * @param  string  $bucketName bucket名称
     * @param  string  $path       目录路径
     */
    public static function statFolder($bucketName, $path) {
        $path = self::normalizerPath($path, True);	

        return self::statBase($bucketName, $path);
    }

    /*
     * 删除目录
     * @param  string  $bucketName bucket名称
     * @param  string  $path       目录路径
	 *  注意不能删除bucket下根目录/
     */
    public static function delFolder($bucketName, $path) {
        $path = self::normalizerPath($path, True);

        return self::delBase($bucketName, $path);
    }

    /*
     * 更新文件
     * @param  string  $bucketName  bucket名称
     * @param  string  $path        文件路径
     * @param  string  $authority:  eInvalid(继承Bucket的读写权限)/eWRPrivate(私有读写)/eWPrivateRPublic(公有读私有写)
	 * @param  array   $customer_headers_array 携带的用户自定义头域,包括
     * 'Cache-Control' => '*'
     * 'Content-Type' => '*'
     * 'Content-Disposition' => '*'
     * 'Content-Language' => '*'
     * 'x-cos-meta-自定义内容' => '*'
     */
    public static function update($bucketName, $path, 
                  $bizAttr = null, $authority=null,$customer_headers_array=null) {
        $path = self::normalizerPath($path);

        return self::updateBase($bucketName, $path, $bizAttr, $authority, $customer_headers_array);
    }

    /*
     * 移动(重命名)文件
     * @param  string  $bucketName  bucket名称
     * @param  string  $srcPath     源文件路径
     * @param  string  $dstPath     目的文件名(可以是单独文件名也可以是带目录的文件名)
     * @param  string  $toOverWrite 是否覆盖(当目的文件名已经存在同名文件时是否覆盖)
     */
	public static function move($bucketName, $srcPath, $dstPath, $toOverWrite = 0)
	{
		$srcPath = self::cosUrlEncode($srcPath);
		$url = self::generateResUrl($bucketName,$srcPath);		
		$sign = Auth::appSign_once($srcPath, $bucketName);
		$expired = time() + self::EXPIRED_SECONDS;

		$data = array(
			'op' => 'move',
			'dest_fileid' => $dstPath,
			'to_over_write' => $toOverWrite,
		);
		
		$data = json_encode($data);
	
		$req = array(
			'url' => $url,
			'method' => 'post',
			'timeout' => self::$timeout,
			'data' => $data,
			'header' => array(
					'Authorization: '.$sign,
					'Content-Type: application/json',
			),	
		);	

		return self::sendRequest($req);
	}
	
    /*
     * 查询文件信息
     * @param  string  $bucketName  bucket名称
     * @param  string  $path        文件路径
     */
    public static function stat($bucketName, $path) {
        $path = self::normalizerPath($path);

        return self::statBase($bucketName, $path);
    }

    /*
     * 删除文件
     * @param  string  $bucketName
     * @param  string  $path      文件路径
     */
    public static function delFile($bucketName, $path) {
		$path = self::normalizerPath($path);

        return self::delBase($bucketName, $path);
    }
	
    /**
     * 内部方法, 上传文件
     * @param  string  $bucketName  bucket名称
     * @param  string  $srcPath     本地文件路径
     * @param  string  $dstPath     上传的文件路径
	 * @param  string  $bizAttr     文件属性
	 * @param  int     $insertOnly  是否覆盖同名文件:0 覆盖,1:不覆盖
     * @return [type]               [description]
     */
    private static function uploadfile($bucketName, $srcPath, $dstPath, $bizAttr = null, $insertOnly = null)
    {
        $srcPath = realpath($srcPath);
	    $dstPath = self::cosUrlEncode($dstPath);

	    if (filesize($srcPath) >= self::MAX_UNSLICE_FILE_SIZE )
	    {
		    return array(
                   'code' => self::COSAPI_PARAMS_ERROR, 
                   'message' => 'file '.$srcPath.' larger then 20M, please use upload_slice interface', 
                   'data' => array());
	    }
		
        $expired = time() + self::EXPIRED_SECONDS;
        $url = self::generateResUrl($bucketName, $dstPath);
        $sign = Auth::appSign($expired, $bucketName);
        $sha1 = hash_file('sha1', $srcPath);

        $data = array(
            'op' => 'upload',
            'sha' => $sha1,
            'biz_attr' => (isset($bizAttr) ? $bizAttr : ''),
        );
		
        if (function_exists('curl_file_create')) {
            $data['filecontent'] = curl_file_create($srcPath);
        } else {
            $data['filecontent'] = '@'.$srcPath;
        }
		
	    if (isset($insertOnly) && strlen($insertOnly) > 0)
		     $data['insertOnly'] = (($insertOnly == 0 || $insertOnly == '0' ) ? 0 : 1);

        $req = array(
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => $data,
            'header' => array(
                'Authorization:'.$sign,
            ),
        );

        return self::sendRequest($req);
    }

    /**
     * 内部方法,上传文件
     * @param  string  $bucketName  bucket名称
     * @param  string  $srcPath     本地文件路径
     * @param  string  $dstPath     上传的文件路径
	 * @param  string  $bizAttr     文件属性
	 * @param  string  $sliceSize   分片大小
	 * @param  int     $insertOnly  是否覆盖同名文件:0 覆盖,1:不覆盖
     * @return [type]                [description]
     */
    private static function upload_slice(
        $bucketName, $srcPath,  $dstPath, 
        $bizAttr = null, $sliceSize = null, $insertOnly=null) {

        $srcPath = realpath($srcPath);
        $fileSize = filesize($srcPath);
        $dstPath = self::cosUrlEncode($dstPath);

        $expired = time() + self::EXPIRED_SECONDS;
        $url = self::generateResUrl($bucketName, $dstPath);
        $sign = Auth::appSign($expired, $bucketName);
        $sha1 = hash_file('sha1', $srcPath);

        $ret = self::upload_prepare(
                $fileSize, $sha1, $sliceSize, 
                $sign, $url, $bizAttr, $insertOnly);

        if($ret['code'] != 0) {
            return $ret;
        }

        if(isset($ret['data']) 
                && isset($ret['data']['url'])) {
				//秒传命中，直接返回了url
            return $ret;
        }

        $sliceSize = $ret['data']['slice_size'];
        if ($sliceSize > self::SLICE_SIZE_3M ||
            $sliceSize <= 0) {
            $ret['code'] = self::COSAPI_PARAMS_ERROR;
            $ret['message'] = 'illegal slice size';
            return $ret;
        }

        $session = $ret['data']['session'];
        $offset = $ret['data']['offset'];

        $sliceCnt = ceil($fileSize / $sliceSize);
        // expired seconds for one slice mutiply by slice count 
        // will be the expired seconds for whole file
        $expired = time() + (self::EXPIRED_SECONDS * $sliceCnt);
        $sign = Auth::appSign($expired, $bucketName);

        $ret = self::upload_data(
                $fileSize, $sha1, $sliceSize,
                $sign, $url, $srcPath,
                $offset, $session);
        return $ret;
    }

    /**
     * 第一个分片控制消息
     * @param  string  $fileSize  文件大小
     * @param  string  $sha1      文件sha值
     * @param  string  $sliceSize 分片大小
	 * @param  string  $sign      签名
	 * @param  string  $url       URL
	 * @param  string  $bizAttr   文件属性
	 * @param  string  $insertOnly 同名文件是否覆盖
     * @return [type]    
     */
    private static function upload_prepare(
        $fileSize, $sha1, $sliceSize,
        $sign, $url, $bizAttr = null, $insertOnly = null) {

        $data = array(
            'op' => 'upload_slice',
            'filesize' => $fileSize,
            'sha' => $sha1,
        );
        
		if (isset($bizAttr) && strlen($bizAttr))
            $data['biz_attr'] = $bizAttr;

	    if (isset($insertOnly))
			$data['insertOnly'] = (($insertOnly == 0) ? 0 : 1);
	
        if ($sliceSize <= self::SLICE_SIZE_3M) {
            $data['slice_size'] = $sliceSize;
        } else {
            $data['slice_size'] = self::SLICE_SIZE_3M;
        }

        $req = array(
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => $data,
            'header' => array(
                'Authorization:'.$sign,
            ),
        );

        $ret = self::sendRequest($req);
        return $ret;
    }
	
    /**
     * 分片上传
     * @param  int $fileSize   文件大小
     * @param  string $sha1    文件sha值
     * @param  int $sliceSize  文件分片大小
     * @param  string $sign    签名
     * @param  string $url     url
     * @param  string $srcPath 源文件路径
     * @param  int $offset     文件偏移offset
     * @param  string $session  session
     * @return [type] array     
     */
    private static function upload_data(
            $fileSize, $sha1, $sliceSize,
            $sign, $url, $srcPath, 
            $offset, $session) {

        while ($fileSize > $offset) {
            $filecontent = file_get_contents(
                    $srcPath, false, null,
                    $offset, $sliceSize);

            if ($filecontent === false) {
                return array(
                    'code' => self::COSAPI_PARAMS_ERROR,
                    'message' => 'read file '.$srcPath.' error', 
                    'data' => array(),
                );
            }

            $boundary = '---------------------------' . substr(md5(mt_rand()), 0, 10); 
            $data = self::generateSliceBody(
                    $filecontent, $offset, $sha1,
                    $session, basename($srcPath), $boundary);

            $req = array(
                'url' => $url,
                'method' => 'post',
                'timeout' => self::$timeout,
                'data' => $data,
                'header' => array(
                    'Authorization:'.$sign,
                    'Content-Type: multipart/form-data; boundary=' . $boundary,
                ),
            );

            $retry_times = 0;
            do {
                $ret = self::sendRequest($req);
                if ($ret['code'] == 0) {
                    break;
                }
                $retry_times++;
            } while($retry_times < self::MAX_RETRY_TIMES);

            if($ret['code'] != 0) {
                return $ret;
            }

            if ($ret['data']['session']) {
                $session = $ret['data']['session'];
            }

            $offset += $sliceSize;
        }

        return $ret;
    }


    /**
     * 构造分片body体
     * @param  string  $fileContent  文件内容
     * @param  string  $offset       文件偏移
     * @param  string  $sha          文件sha值 
	 * @param  string  $session      session
	 * @param  string  $fileName     文件名
	 * @param  string  $boundary     分隔符
     * @return [type]  
     */
    private static function generateSliceBody(
            $fileContent, $offset, $sha, 
            $session, $fileName, $boundary) {
        $formdata = '';

        $formdata .= '--' . $boundary . "\r\n";
        $formdata .= "content-disposition: form-data; name=\"op\"\r\n\r\nupload_slice\r\n";

        $formdata .= '--' . $boundary . "\r\n";
        $formdata .= "content-disposition: form-data; name=\"offset\"\r\n\r\n" . $offset. "\r\n";

        $formdata .= '--' . $boundary . "\r\n";
        $formdata .= "content-disposition: form-data; name=\"session\"\r\n\r\n" . $session . "\r\n";

        $formdata .= '--' . $boundary . "\r\n";
        $formdata .= "content-disposition: form-data; name=\"fileContent\"; filename=\"" . $fileName . "\"\r\n"; 
        $formdata .= "content-type: application/octet-stream\r\n\r\n";

        $data = $formdata . $fileContent . "\r\n--" . $boundary . "--\r\n";

        return $data;
    }

    /*
     * 内部公共函数
     * @param  string  $bucketName bucket名称
     * @param  string  $path       文件夹路径
     * @param  int     $num        拉取的总数
     * @param  string  $pattern    eListBoth(默认),ListDirOnly,eListFileOnly
     * @param  int     $order      默认正序(=0), 填1为反序,
     * @param  string  $context    在翻页查询时候用到
     */	
    private static function listBase(
                    $bucketName, $path, $num = 20, 
                    $pattern = 'eListBoth', $order = 0, $context = null) {

        $path = self::cosUrlEncode($path);
        $expired = time() + self::EXPIRED_SECONDS;
        $url = self::generateResUrl($bucketName, $path);
        $sign = Auth::appSign($expired, $bucketName);

        $data = array(
            'op' => 'list',
        );
        
		if (self::isPatternValid($pattern) == false)
		{
			return array(
                    'code' => self::COSAPI_PARAMS_ERROR,
                    'message' => 'parameter pattern invalid', 
                );
		}
		$data['pattern'] = $pattern;
		
		if ($order != 0 && $order != 1)
		{
			return array(
                    'code' => self::COSAPI_PARAMS_ERROR,
                    'message' => 'parameter order invalid', 
            );
		}
		$data['order'] = $order;
		
		if ($num < 0 || $num > 199)
		{
			return array(
                    'code' => self::COSAPI_PARAMS_ERROR,
                    'message' => 'parameter num invalid, num need less then 200', 
            );
		}
		$data['num'] = $num;
		
		if (isset($context))
		{
			$data['context'] = $context;
		}
		
        $url = $url . '?' . http_build_query($data);

        $req = array(
            'url' => $url,
            'method' => 'get',
            'timeout' => self::$timeout,
            'header' => array(
                'Authorization:'.$sign,
            ),
        );

        return self::sendRequest($req);
    } 

    /*
     * 内部公共方法(更新文件和更新文件夹)
     * @param  string  $bucketName  bucket名称
     * @param  string  $path        路径
     * @param  string  $bizAttr     文件/目录属性	 
     * @param  string  $authority:  eInvalid/eWRPrivate(私有)/eWPrivateRPublic(公有读写)
	 * @param  array   $customer_headers_array 携带的用户自定义头域,包括
     * 'Cache-Control' => '*'
     * 'Content-Type' => '*'
     * 'Content-Disposition' => '*'
     * 'Content-Language' => '*'
     * 'x-cos-meta-自定义内容' => '*'
     */
    private static function updateBase($bucketName, $path, 
                  $bizAttr = null, $authority = null, $custom_headers_array = null) {

        $path = self::cosUrlEncode($path);
        $expired = time() + self::EXPIRED_SECONDS;
        $url = self::generateResUrl($bucketName, $path);
        $sign = Auth::appSign_once(
                $path, $bucketName);

        $data = array(
            'op' => 'update',
        );
        
		$flag = 0;
	    if (isset($bizAttr))
	    {
	        $data['biz_attr'] = $bizAttr;
	        $flag = $flag | 0x01;
	    }
		
	    if (isset($authority) && strlen($authority) > 0)
	    {
			if(self::isAuthorityValid($authority) == false)
			{
				return array(
                    'code' => self::COSAPI_PARAMS_ERROR,
                    'message' => 'parameter authority invalid',
                    );
			}

	        $data['authority'] = $authority;
	        $flag = $flag | 0x80;
	    }
		
	    if (isset($custom_headers_array))
	    {
	        $data['custom_headers'] = array();
	        self::add_customer_header($data['custom_headers'], $custom_headers_array);	
	        $flag = $flag | 0x40;
	    }

		if ($flag != 0 && $flag != 1)
		{
			$data['flag'] = $flag;
		}

        $data = json_encode($data);

        $req = array(
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => $data,
            'header' => array(
                'Authorization:'.$sign,
                'Content-Type: application/json',
            ),
        );
		
		return self::sendRequest($req);
    }


    /*
     * 内部方法 
     * @param  string  $bucketName  bucket名称
     * @param  string  $path        文件/目录路径
     */
    private static function statBase($bucketName, $path) {

        $path = self::cosUrlEncode($path);
        $expired = time() + self::EXPIRED_SECONDS;
        $url = self::generateResUrl($bucketName, $path);
        $sign = Auth::appSign($expired, $bucketName);

        $data = array(
            'op' => 'stat',
        );

        $url = $url . '?' . http_build_query($data);

        $req = array(
            'url' => $url,
            'method' => 'get',
            'timeout' => self::$timeout,
            'header' => array(
                'Authorization:'.$sign,
            ),
        );

        return self::sendRequest($req);
    } 

    /*
     * 内部私有方法 
     * @param  string  $bucketName  bucket名称
     * @param  string  $path        文件/目录路径路径
     */
    private static function delBase($bucketName, $path) {
        if ($path == "/") {
            return array(
                    'code' => self::COSAPI_PARAMS_ERROR,
                    'message' => 'can not delete bucket using api! go to http://console.qcloud.com/cos to operate bucket',
                    );
        }

        $path = self::cosUrlEncode($path);
        $expired = time() + self::EXPIRED_SECONDS;
        $url = self::generateResUrl($bucketName, $path);
        $sign = Auth::appSign_once(
                $path, $bucketName);

        $data = array(
            'op' => 'delete',
        );
        
        $data = json_encode($data);

        $req = array(
            'url' => $url,
            'method' => 'post',
            'timeout' => self::$timeout,
            'data' => $data,
            'header' => array(
                'Authorization:'.$sign,
                'Content-Type: application/json',
            ),
        );

        return self::sendRequest($req);
    }
    
    /*
     * 内部公共方法, 路径编码
     * @param  string  $path 待编码路径
     */
	private static function cosUrlEncode($path) {
        return str_replace('%2F', '/',  rawurlencode($path));
    }
    
    /*
     * 内部公共方法, 构造URL
     * @param  string  $bucketName
     * @param  string  $dstPath
     */
    private static function generateResUrl($bucketName, $dstPath) {
        return Conf::API_COSAPI_END_POINT . Conf::APPID . '/' . $bucketName . $dstPath;
    }
    
	/*
     * 内部公共方法, 发送消息
     * @param  string  $req
     */
    private static function sendRequest($req) {
        $rsp = Http::send($req);
        $info = Http::info();
        $ret = json_decode($rsp, true);

        if ($ret) {
            if (0 === $ret['code']) {
                return $ret;
            } else {
                return array(
                    'code' => $ret['code'], 
                    'message' => $ret['message'], 
                    'data' => array()
                );
            }
        } else {
            return array(
                'code' => self::COSAPI_NETWORK_ERROR, 
                'message' => $rsp, 
                'data' => array()
            );
        }
    }
    
    /**
     * 设置分片大小
     * @param  string  $sliceSize 
     * @return [type] int       
     */
	private static function getSliceSize($sliceSize)
	{
		$size = self::SLICE_SIZE_1M;
		if (!isset($sliceSize))
		{
			return $size;
		}
		
		if ($sliceSize <= self::SLICE_SIZE_512K) 
		{
			$size = self::SLICE_SIZE_512K;
		}
		else if ($sliceSize <= self::SLICE_SIZE_1M)
		{
			$size = self::SLICE_SIZE_1M;
		}
		else if ($sliceSize <= self::SLICE_SIZE_2M)
		{
			$size = self::SLICE_SIZE_2M;
		}
		else 
		{
			$size = self::SLICE_SIZE_3M;
		}
		
		return $size;
	}
	
    /*
     * 内部方法, 规整文件路径
     * @param  string  $path      文件路径
     * @param  string  $isfolder  是否为文件夹
     */
	private static function normalizerPath($path, $isfolder = False) {

		if (preg_match('/^\//', $path) == 0) {
            $path = '/' . $path;
        }
		
		if ($isfolder == True)
		{
			if (preg_match('/\/$/', $path) == 0) {
				$path = $path . '/';
			}
		}

		return $path;
	}

    /**
     * 判断authority值是否正确
     * @param  string  $authority 
     * @return [type]  bool         
     */
    private static function isAuthorityValid($authority)
    {
        if ($authority == 'eInvalid' 
		|| $authority == 'eWRPrivate' 
		|| $authority == 'eWPrivateRPublic')
	    {
            return true;
	    }
	    return false;
    }
	
    /**
     * 判断pattern值是否正确
     * @param  string  $authority 
     * @return [type]  bool         
     */
    private static function isPatternValid($pattern)
    {
        if ($pattern == 'eListBoth' 
		|| $pattern == 'eListDirOnly' 
		|| $pattern == 'eListFileOnly')
	    {
            return true;
	    }
	    return false;
    }
	
    /**
     * 判断是否符合自定义属性
     * @param  string  $key 
     * @return [type]  bool         
     */
    private static function isCustomer_header($key)
    {
        if ($key == 'Cache-Control' 
		|| $key == 'Content-Type' 
		|| $key == 'Content-Disposition' 
		|| $key == 'Content-Language'
		|| substr($key,0,strlen('x-cos-meta-')) == 'x-cos-meta-')
	    {
            return true;
	    }
	    return false;
    }
	
	/**
     * 增加自定义属性到data中
     * @param  array  $data 
	 * @param  array  $customer_headers_array 
     * @return [type]  void         
     */
    private static function add_customer_header(&$data, &$customer_headers_array)
    {
        if (count($customer_headers_array) < 1) return;
	    foreach($customer_headers_array as $key=>$value)
	    {
            if(self::isCustomer_header($key))
	        { 
	            $data[$key] = $value;
            }
	    }
    }
}

