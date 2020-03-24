<?php
header ( 'Content-type:text/html;charset=utf-8' );

function sendHttpRequest($params, $url) {
	$opts = getRequestParamString ( $params );
	
	$ch = curl_init ();
	curl_setopt ( $ch, CURLOPT_URL, $url );
	curl_setopt ( $ch, CURLOPT_POST, 1 );
	curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false);    curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false);	curl_setopt ( $ch, CURLOPT_SSLVERSION, 3);
	curl_setopt ( $ch, CURLOPT_HTTPHEADER, array (
			'Content-type:application/x-www-form-urlencoded;charset=UTF-8' 
	) );
	curl_setopt ( $ch, CURLOPT_POSTFIELDS, $opts );
	
	
	curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
	
		$html = curl_exec ( $ch );
		curl_close ( $ch );
	return $html;
}


function getRequestParamString($params) {
	$params_str = '';
	foreach ( $params as $key => $value ) {
		$params_str .= ($key . '=' . (!isset ( $value ) ? '' : urlencode( $value )) . '&');
	}
	return substr ( $params_str, 0, strlen ( $params_str ) - 1 );
}