<?php
session_start();
if(isset($_GET['redirect_url'])){
    $_COOKIE['erphpdown_return'] = urldecode($_GET['redirect_url']);
    setcookie('erphpdown_return',urldecode($_GET['redirect_url']),0,'/');
}else{
    $_COOKIE['erphpdown_return'] = '';
    setcookie('erphpdown_return','',0,'/');
}
header("Content-type:text/html;character=utf-8");
require_once('../../../../wp-load.php');
date_default_timezone_set('Asia/Shanghai');
//if(!is_user_logged_in()){wp_die('请先登录！');}

require_once('paypal/CallerService.php');
require_once("paypal/APIError.php");

/*  www.mobantu.com  */

/* An express checkout transaction starts with a token, that
identifies to PayPal your transaction
In this example, when the script sees a token, the script
knows that the buyer has already authorized payment through
paypal.  If no token was found, the action is to send the buyer
to PayPal to first authorize payment
*/

if(!isset($_REQUEST['token'])){

	$post_id   = isset($_GET['ice_post']) && is_numeric($_GET['ice_post']) ?$_GET['ice_post'] :0;
	$user_type   = isset($_GET['ice_type']) && is_numeric($_GET['ice_type']) ?$_GET['ice_type'] :'';
	$index   = isset($_GET['index']) && is_numeric($_GET['index']) ?$_GET['index'] :'';
	$index_vip = '';
	if($post_id){
	    if($index){
	        $urls = get_post_meta($post_id, 'down_urls', true);
	        if($urls){
	            $cnt = count($urls['index']);
	            if($cnt){
	                for($i=0; $i<$cnt;$i++){
	                    if($urls['index'][$i] == $index){
	                        $index_name = $urls['name'][$i];
	                        $price = $urls['price'][$i];
	                        $index_vip = $urls['vip'][$i];
	                        break;
	                    }
	                }
	            }
	        }
	    }else{
	        $price=get_post_meta($post_id, 'down_price', true);
	    }
	    $start_down2 = get_post_meta($post_id, 'start_down2',TRUE);
		if(!$start_down2){
		    $price = $price / get_option("ice_proportion_alipay");
		}
	    $price = $price / get_option('ice_payapl_api_rmb');
	    $price = sprintf("%.2f",$price);
	    $memberDown=get_post_meta($post_id, 'member_down',TRUE);
	    if($index_vip){
			$memberDown = $index_vip;
		}
	    $userType=getUsreMemberType();
	    if($memberDown==4 || $memberDown==15 || $memberDown==8 || $memberDown==9 || (($memberDown == 10 || $memberDown == 11 || $memberDown == 12) && !$userType)){
	        wp_die('您无权购买此资源！','友情提示');
	    }

	    if($userType && ($memberDown==2 || $memberDown==13)){
	        $price=sprintf("%.2f",$price*0.5);
	    }elseif($userType && ($memberDown==5 || $memberDown==14)){
	        $price=sprintf("%.2f",$price*0.8);
	    }elseif($userType>=9 && $memberDown==11){
	        $price=sprintf("%.2f",$price*0.5);
	    }elseif($userType>=9 && $memberDown==12){
	        $price=sprintf("%.2f",$price*0.8);
	    }
	}elseif($user_type){
	    $erphp_life_price    = get_option('ciphp_life_price');
	    $erphp_year_price    = get_option('ciphp_year_price');
	    $erphp_quarter_price = get_option('ciphp_quarter_price');
	    $erphp_month_price  = get_option('ciphp_month_price');
	    $erphp_day_price  = get_option('ciphp_day_price');
	    if($user_type == 6){
	        $price = $erphp_day_price/get_option('ice_proportion_alipay');
	    }elseif($user_type == 7){
	        $price = $erphp_month_price/get_option('ice_proportion_alipay');
	    }elseif($user_type == 8){
	        $price = $erphp_quarter_price/get_option('ice_proportion_alipay');
	    }elseif($user_type == 9){
	        $price = $erphp_year_price/get_option('ice_proportion_alipay');
	    }elseif($user_type == 10){
	        $price = $erphp_life_price/get_option('ice_proportion_alipay');
	    }

	    $vip_update_pay = 0;$oldUserType = 0;
	    if(get_option('vip_update_pay') && is_user_logged_in()){
	        global $current_user;
	        $oldUserType = getUsreMemberTypeById($current_user->ID);

	        if($user_type == 7){
	            if($oldUserType == 6){
	                $price = ($erphp_month_price - $erphp_day_price)/get_option('ice_proportion_alipay');
	            }
	        }elseif($user_type == 8){
	            if($oldUserType == 6){
	                $price = ($erphp_quarter_price - $erphp_day_price)/get_option('ice_proportion_alipay');
	            }elseif($oldUserType == 7){
	                $price = ($erphp_quarter_price - $erphp_month_price)/get_option('ice_proportion_alipay');
	            }
	        }elseif($user_type == 9){
	            if($oldUserType == 6){
	                $price = ($erphp_year_price - $erphp_day_price)/get_option('ice_proportion_alipay');
	            }elseif($oldUserType == 7){
	                $price = ($erphp_year_price - $erphp_month_price)/get_option('ice_proportion_alipay');
	            }elseif($oldUserType == 8){
	                $price = ($erphp_year_price - $erphp_quarter_price)/get_option('ice_proportion_alipay');
	            }
	        }elseif($user_type == 10){
	            if($oldUserType == 6){
	                $price = ($erphp_life_price - $erphp_day_price)/get_option('ice_proportion_alipay');
	            }elseif($oldUserType == 7){
	                $price = ($erphp_life_price - $erphp_month_price)/get_option('ice_proportion_alipay');
	            }elseif($oldUserType == 8){
	                $price = ($erphp_life_price - $erphp_quarter_price)/get_option('ice_proportion_alipay');
	            }elseif($oldUserType == 9){
	                $price = ($erphp_life_price - $erphp_year_price)/get_option('ice_proportion_alipay');
	            }
	        }
	    }
	    $price = $price / get_option('ice_payapl_api_rmb');
	    $price = sprintf("%.2f",$price);
	}else{
		$price = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
		$price = $price / get_option('ice_payapl_api_rmb');
		$price = sprintf("%.2f",$price);
	}

	$currencyCodeType='USD';
	$paymentType='Sale';

	$personName        = 'erphpdown';
	$SHIPTOSTREET      = 'wuhan';
	$SHIPTOCITY        = 'wuhan';
	$SHIPTOSTATE          = 'wuhan';
	$SHIPTOCOUNTRYCODE = '86';
	$SHIPTOZIP         = '430000';
	$L_NAME0           = '网站充值';
	$L_AMT0            = $price;
	$L_QTY0            = 1;

	$returnURL =urlencode(ERPHPDOWN_URL.'/payment/paypal.php?currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType);
	$cancelURL =urlencode(get_bloginfo('url'));

	
	$itemamt = 0.00;
	$itemamt = $L_QTY0*$L_AMT0;
	$amt = $itemamt;
	$maxamt= $amt+25.00;
	$nvpstr="";
	$nvpHeader="";

	/*
	* Setting up the Shipping address details
	*/
	$shiptoAddress = "&SHIPTONAME=$personName&SHIPTOSTREET=$SHIPTOSTREET&SHIPTOCITY=$SHIPTOCITY&SHIPTOSTATE=$SHIPTOSTATE&SHIPTOCOUNTRYCODE=$SHIPTOCOUNTRYCODE&SHIPTOZIP=$SHIPTOZIP";

	$num = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
    $ice_aff = '';
    if(!is_user_logged_in()){
        $num = 'MD'.$num;
        if(isset($_COOKIE["erphprefid"]) && is_numeric($_COOKIE["erphprefid"])){
            $ice_aff = $_COOKIE["erphprefid"];
        }
    }

	if($price > 0){
		$user_Info   = wp_get_current_user();

		$ice_data = '';
	    $erphp_down=get_post_meta($post_id, 'erphp_down',TRUE);
	    if($erphp_down == 6){
	    	if(function_exists('getErphpActLeft')){
	            $ErphpActLeft = getErphpActLeft($post_id);
	            if($ErphpActLeft < 1){
	                wp_die('抱歉，库存不足!','友情提示');
	            }
	        }else{
	            wp_die('抱歉，网站未启用【激活码发放】扩展（Erphpdown-基础设置 里的免费扩展）!','友情提示');
	        }
        
	        $num2 = isset($_GET['num']) && is_numeric($_GET['num']) ?$_GET['num'] : 1;
	        $email = isset($_GET['data']) && is_email($_GET['data']) ?$_GET['data'] : '';
	        if(!$email){
	            wp_die('请填写一个接收卡密的邮箱!');
	        }
	        $ice_data = $email.'|'.$num2;
	        $price = $price*$num2;

	        $num = str_replace('MD','',$num);
        	$num = 'FK'.$num;
	        $_SESSION['ice_num'] = $num;
	    }

		$rmbPrice=round(get_option('ice_payapl_api_rmb')*$price,2);
		$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_user_type,ice_post_id,ice_post_index,ice_time,ice_success,ice_note,ice_success_time,ice_alipay,ice_aff,ice_ip,ice_data) VALUES ('$rmbPrice','$num','".$user_Info->ID."','".$user_type."','".$post_id."','".$index."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','paypal','".$ice_aff."','".erphpGetIP()."','".$ice_data."')";
		$a=$wpdb->query($sql);
		if(!$a){
			wp_die('系统发生错误，请稍后重试!');
		}
	}
	$_SESSION["paypal_num"]=$num;
	$nvpstr="&L_AMT0=".$price."&SHIPPINGAMT=0.00&L_NAME0=".$L_NAME0."&L_NUMBER0=1000&L_QTY0=1&CURRENCYCODE=USD&NOSHIPPING=1&INVNUM=".$num."&AMT=".$price."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType."&PAYMENTACTION=".$paymentType;

	$nvpstr = $nvpHeader.$nvpstr;

	$resArray=hash_call("SetExpressCheckout",$nvpstr);
	$_SESSION['reshash']=$resArray;

	$ack = strtoupper($resArray["ACK"]);

	if($ack=="SUCCESS"){
		// Redirect to paypal.com here
		$token = urldecode($resArray["TOKEN"]);
		$payPalURL = PAYPAL_URL.$token;
		header("Location: ".$payPalURL);
	} 
	else
	{
		echo showPaypalError($resArray);
		exit;
	}
}
else
{
	/* At this point, the buyer has completed in authorizing payment
	at PayPal.  The script will now call PayPal with the details
	of the authorization, incuding any shipping information of the
	buyer.  Remember, the authorization is not a completed transaction
	at this state - the buyer still needs an additional step to finalize
	the transaction
	*/

	$token =urlencode( $_REQUEST['token']);

	/* Build a second API request to PayPal, using the token as the
	ID to get the details on the payment authorization
	*/
	$nvpstr="&TOKEN=".$token;

	$nvpstr = $nvpHeader.$nvpstr;
	/* Make the API call and store the results in an array.  If the
	call was a success, show the authorization details, and provide
	an action to complete the payment.  If failed, show the error
	*/
	$resArray=hash_call("GetExpressCheckoutDetails",$nvpstr);
	$_SESSION['reshash']=$resArray;
	$ack = strtoupper($resArray["ACK"]);

	if(($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING') && get_option('ice_payapl_api_md5'))
	{

		$_SESSION['token']=$_REQUEST['token'];
		$_SESSION['payer_id'] = $_REQUEST['PayerID'];

		$_SESSION['paymentAmount']=$_REQUEST['paymentAmount'];
		$_SESSION['currCodeType']=$_REQUEST['currencyCodeType'];
		$_SESSION['paymentType']=$_REQUEST['paymentType'];

		$resArray=$_SESSION['reshash'];
		$_SESSION['TotalAmount']= $resArray['AMT'] + $resArray['SHIPDISCAMT'];

		ini_set('session.bug_compat_42',0);
		ini_set('session.bug_compat_warn',0);

		$token =urlencode( $_SESSION['token']);
		$paymentAmount =urlencode ($_SESSION['TotalAmount']);
		$paymentType = urlencode($_SESSION['paymentType']);
		$currCodeType = urlencode($_SESSION['currCodeType']);
		$payerID = urlencode($_SESSION['payer_id']);
		$serverName = urlencode($_SERVER['SERVER_NAME']);

		$nvpstr='&TOKEN='.$token.'&PAYERID='.$payerID.'&PAYMENTACTION='.$paymentType.'&AMT='.$paymentAmount.'&CURRENCYCODE='.$currCodeType.'&IPADDRESS='.$serverName ;
		/* Make the call to PayPal to finalize payment
		If an error occured, show the resulting errors
		*/
		$resArray=hash_call("DoExpressCheckoutPayment",$nvpstr);

		/* Display the API response back to the browser.
		If the response from PayPal was a success, display the response parameters'
		If the response was an error, display the errors received using APIError.php.
		*/
		$ack = strtoupper($resArray["ACK"]);
		if($ack != 'SUCCESS' && $ack != 'SUCCESSWITHWARNING')
		{
			echo showPaypalError($resArray);
			exit;
		}
		else
		{
			$num=$_SESSION["paypal_num"];
			$money=$_SESSION['TotalAmount']*get_option('ice_payapl_api_rmb');
			$money=round($money,2);
			if(strstr($num,'MD') || strstr($num,'FK')){
				epd_set_wppay_success($num,$money,'paypal');
			}else{
				epd_set_order_success($num,$money,'paypal');
			}
			$re = get_option('erphp_url_front_success');
			if(isset($_COOKIE['erphpdown_return']) && $_COOKIE['erphpdown_return']){
			    $re = $_COOKIE['erphpdown_return'];
			}
			if($re)
				wp_redirect($re);
			else{
				wp_die('充值成功！', '友情提示');
			}
			
		}
	}
	else
	{
		wp_die(showPaypalError($resArray));
	}
}
