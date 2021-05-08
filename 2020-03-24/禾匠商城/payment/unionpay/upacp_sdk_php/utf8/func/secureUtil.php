<?php

/**
 * [WeEngine System] Copyright (c) 2014 WE7.CC
 * WeEngine is NOT a free software, it under the license terms, visited http://www.we7.cc/ for more details.
 */$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );

function sign(&$params) {
	global $log;
	$log->LogInfo ( '=====签名报文开始======' );
	if(isset($params['transTempUrl'])){
		unset($params['transTempUrl']);
	}
		$params_str = coverParamsToString ( $params );
	$log->LogInfo ( "签名key=val&...串 >" . $params_str );
	
	$params_sha1x16 = sha1 ( $params_str, FALSE );
	$log->LogInfo ( "摘要sha1x16 >" . $params_sha1x16 );
		$cert_path = SDK_SIGN_CERT_PATH;
	$private_key = getPrivateKey ( $cert_path );
		$sign_falg = openssl_sign ( $params_sha1x16, $signature, $private_key, OPENSSL_ALGO_SHA1 );
	if ($sign_falg) {
		$signature_base64 = base64_encode ( $signature );
		$log->LogInfo ( "签名串为 >" . $signature_base64 );
		$params ['signature'] = $signature_base64;
	} else {
		$log->LogInfo ( ">>>>>签名失败<<<<<<<" );
	}
	$log->LogInfo ( '=====签名报文结束======' );
}


function verify($params) {
	global $log;
		$public_key = getPulbicKeyByCertId ( $params ['certId'] );	
		$signature_str = $params ['signature'];
	unset ( $params ['signature'] );
	$params_str = coverParamsToString ( $params );
	$log->LogInfo ( '报文去[signature] key=val&串>' . $params_str );
	$signature = base64_decode ( $signature_str );
	$params_sha1x16 = sha1 ( $params_str, FALSE );
	$log->LogInfo ( '摘要shax16>' . $params_sha1x16 );	
	$isSuccess = openssl_verify ( $params_sha1x16, $signature,$public_key, OPENSSL_ALGO_SHA1 );
	$log->LogInfo ( $isSuccess ? '验签成功' : '验签失败' );
	return $isSuccess;
}


function getPulbicKeyByCertId($certId) {
	global $log;
	$log->LogInfo ( '报文返回的证书ID>' . $certId );
		$cert_dir = SDK_VERIFY_CERT_DIR;
	$log->LogInfo ( '验证签名证书目录 :>' . $cert_dir );
	$handle = opendir ( $cert_dir );
	if ($handle) {
		while ( $file = readdir ( $handle ) ) {
			clearstatcache ();
			$filePath = $cert_dir . '/' . $file;
			if (is_file ( $filePath )) {
				if (pathinfo ( $file, PATHINFO_EXTENSION ) == 'cer') {
					if (getCertIdByCerPath ( $filePath ) == $certId) {
						closedir ( $handle );
						$log->LogInfo ( '加载验签证书成功' );
						return getPublicKey ( $filePath );
					}
				}
			}
		}
		$log->LogInfo ( '没有找到证书ID为[' . $certId . ']的证书' );
	} else {
		$log->LogInfo ( '证书目录 ' . $cert_dir . '不正确' );
	}
	closedir ( $handle );
	return null;
}


function getCertId($cert_path) {
	$pkcs12certdata = file_get_contents ( $cert_path );

	openssl_pkcs12_read ( $pkcs12certdata, $certs, SDK_SIGN_CERT_PWD );
	$x509data = $certs ['cert'];
	openssl_x509_read ( $x509data );
	$certdata = openssl_x509_parse ( $x509data );
	$cert_id = $certdata ['serialNumber'];
	return $cert_id;
}


function getCertIdByCerPath($cert_path) {
	$x509data = file_get_contents ( $cert_path );
	openssl_x509_read ( $x509data );
	$certdata = openssl_x509_parse ( $x509data );
	$cert_id = $certdata ['serialNumber'];
	return $cert_id;
}


function getSignCertId() {
		
	return getCertId ( SDK_SIGN_CERT_PATH );
}
function getEncryptCertId() {
		return getCertIdByCerPath ( SDK_ENCRYPT_CERT_PATH );
}


function getPublicKey($cert_path) {
	return file_get_contents ( $cert_path );
}

function getPrivateKey($cert_path) {
	$pkcs12 = file_get_contents ( $cert_path );
	openssl_pkcs12_read ( $pkcs12, $certs, SDK_SIGN_CERT_PWD );
	return $certs ['pkey'];
}


function encryptPan($pan) {
	$cert_path = MPI_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );
	
	openssl_public_encrypt ( $pan, $cryptPan, $public_key );
	return base64_encode ( $cryptPan );
}

function encryptPin($pan, $pwd) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );

	return EncryptedPin ( $pwd, $pan, $public_key );
}

function encryptCvn2($cvn2) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );
	
	openssl_public_encrypt ( $cvn2, $crypted, $public_key );
	
	return base64_encode ( $crypted );
}

function encryptDate($certDate) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );
	
	openssl_public_encrypt ( $certDate, $crypted, $public_key );
	
	return base64_encode ( $crypted );
}


function encryptDateType($certDataType) {
	$cert_path = SDK_ENCRYPT_CERT_PATH;
	$public_key = getPublicKey ( $cert_path );

	openssl_public_encrypt ( $certDataType, $crypted, $public_key );

	return base64_encode ( $crypted );
}

?>