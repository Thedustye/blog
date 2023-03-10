<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => get_option('ice_ali_app_id'),

		//商户私钥
		'merchant_private_key' => get_option('ice_ali_private_key'),
		
		//异步通知地址
		'notify_url' => constant("erphpdown")."payment/alipay/notify_new.php",
		
		//同步跳转
		'return_url' => constant("erphpdown")."payment/alipay/return_new.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => get_option('ice_ali_public_key'),
);