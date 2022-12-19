<?php
if(isset($_GET['redirect_url'])){
    $_COOKIE['erphpdown_return'] = urldecode($_GET['redirect_url']);
    setcookie('erphpdown_return',urldecode($_GET['redirect_url']),0,'/');
}else{
    $_COOKIE['erphpdown_return'] = '';
    setcookie('erphpdown_return','',0,'/');
}
require_once('../../../../wp-load.php');
header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

if(erphpdown_is_weixin()){
    echo '<img src="'.ERPHPDOWN_URL.'/static/images/browser.gif" />';
    echo '<div style="margin:100px auto 0;text-align:center;font-size:16px;padding:0 30px;">'.erphpdown_selfurl().'</div>';
    exit;
}

$post_id   = isset($_GET['ice_post']) && is_numeric($_GET['ice_post']) ?$_GET['ice_post'] :0;
$user_type   = isset($_GET['ice_type']) && is_numeric($_GET['ice_type']) ?$_GET['ice_type'] :'';
$index   = isset($_GET['index']) && is_numeric($_GET['index']) ?$_GET['index'] :'';
$index_vip = '';
$ice_ali_app  = get_option('ice_ali_app');

if(!$post_id && !is_user_logged_in()){
    $erphp_url_front_login = wp_login_url();
    if(get_option('erphp_url_front_login')){
        $erphp_url_front_login = get_option('erphp_url_front_login');
    }
    wp_die("请先<a href='".$erphp_url_front_login."'>登录</a>！",'提示');
}

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

}else{
	$price   = isset($_GET['ice_money']) && is_numeric($_GET['ice_money']) ?$_GET['ice_money'] :0;
	$price = esc_sql($price);
	$erphpdown_min_price    = get_option('erphpdown_min_price');
	if($erphpdown_min_price > 0){
		if($price < $erphpdown_min_price){
			wp_die('您最低需充值'.$erphpdown_min_price.'元','提示');
		}
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>正在前往支付宝...</title>
    <style>input{display:none}</style>
</head>
<?php
if($price > 0){
    $out_trade_no = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
    $ice_aff = '';
    if(is_user_logged_in()){
        $subject = get_bloginfo('name').'订单['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';
    }else{
        $out_trade_no = 'MD'.$out_trade_no;
        $subject = get_bloginfo('name').'订单';
        if(isset($_COOKIE["erphprefid"]) && is_numeric($_COOKIE["erphprefid"])){
            $ice_aff = $_COOKIE["erphprefid"];
        }
    } 
    $erphp_order_title = get_option('erphp_order_title');
    if($erphp_order_title){
        $subject = $erphp_order_title;
    }

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
        
        $num = isset($_GET['num']) && is_numeric($_GET['num']) ?$_GET['num'] : 1;
        $email = isset($_GET['data']) && is_email($_GET['data']) ?$_GET['data'] : '';
        if(!$email){
            wp_die('请填写一个接收卡密的邮箱!');
        }
        $ice_data = $email.'|'.$num;
        $price = $price*$num;

        $out_trade_no = str_replace('MD','',$out_trade_no);
        $out_trade_no = 'FK'.$out_trade_no;
        $_SESSION['ice_num'] = $out_trade_no;
    }
	
	$user_Info   = wp_get_current_user();
	$sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_post_id,ice_post_index,ice_user_type,ice_time,ice_success,ice_note,ice_success_time,ice_alipay,ice_aff,ice_ip,ice_data)
	VALUES ('$price','$out_trade_no','".$user_Info->ID."','".$post_id."','".$index."','".$user_type."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','alipay','".$ice_aff."','".erphpGetIP()."','".$ice_data."')";
	$a=$wpdb->query($sql);
	if(!$a){
		wp_die('系统发生错误，请稍后重试!','提示');
	}

    $erphpdown_alipay_type = get_option('erphpdown_alipay_type');

    if($ice_ali_app && erphpdown_is_mobile()){
        require_once("alipay-h5/wappay/service/AlipayTradeService.php");
        require_once("alipay-h5/wappay/buildermodel/AlipayTradeWapPayContentBuilder.php");
        require_once("alipay-h5/config.php");

        $payRequestBuilder = new AlipayTradeWapPayContentBuilder();
        $payRequestBuilder->setBody('');
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setOutTradeNo($out_trade_no);
        $payRequestBuilder->setTotalAmount($price);
        $payRequestBuilder->setTimeExpress("1m");
        $payResponse = new AlipayTradeService($config);
        $result=$payResponse->wapPay($payRequestBuilder,$config['return_url'],$config['notify_url']);
        return ;
    }elseif($erphpdown_alipay_type == 'create_trade_pay_by_user'){
        require_once 'alipay/config.php';
        require_once 'alipay/pagepay/service/AlipayTradeService.php';
        require_once 'alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';

        //付款金额，必填
        $total_amount = $price;

        //商品描述，可空
        $body = "";

        //构造参数
        $payRequestBuilder = new AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        var_dump($response);
        exit;
    }else{
	
        require_once("alipay/alipay.config.php");
        require_once("alipay/lib/alipay_submit.class.php");
    	/**************************请求参数**************************/

            //支付类型
            $payment_type = "1";
            //必填，不能修改
            //服务器异步通知页面路径
            $notify_url = constant("erphpdown").'payment/alipay/notify_url.php';
            //需http://格式的完整路径，不能加?id=123这类自定义参数

            //页面跳转同步通知页面路径
            $return_url = constant("erphpdown").'payment/alipay/return_url.php';
            //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/

            //商品数量
            $quantity = "1";
            //必填，建议默认为1，不改变值，把一次交易看成是一次下订单而非购买一件商品
            //物流费用
            $logistics_fee = "0.00";
            //必填，即运费
            //物流类型
            $logistics_type = "EXPRESS";
            //必填，三个值可选：EXPRESS（快递）、POST（平邮）、EMS（EMS）
            //物流支付方式
            $logistics_payment = "SELLER_PAY";
            //必填，两个值可选：SELLER_PAY（卖家承担运费）、BUYER_PAY（买家承担运费）
            //订单描述

            $body = '';
            //商品展示地址
            $show_url = '';
            //需以http://开头的完整路径，如：http://www.商户网站.com/myorder.html

            $receive_name= "张三"; //收货人姓名，如：张三
    		$receive_address= "XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号"; //收货人地址，如：XX省XXX市XXX区XXX路XXX小区XXX栋XXX单元XXX号
    		$receive_zip= "123456"; //收货人邮编，如：123456
    		$receive_phone= "0571-81234567"; //收货人电话号码，如：0571-81234567
    		$receive_mobile= "13312341234"; //收货人手机号码，如：13312341234


    	/************************************************************/
    	
    	//构造要请求的参数数组，无需改动
    	$parameter = array(
    			"service" => "create_direct_pay_by_user",
    			"partner" => trim($alipay_config['partner']),
    			"seller_email" => trim($alipay_config['seller_email']),
    			"payment_type"	=> $payment_type,
    			"notify_url"	=> $notify_url,
    			"return_url"	=> $return_url,
    			"out_trade_no"	=> $out_trade_no,
    			"subject"	=> $subject,
    			"price"	=> $price,
    			"quantity"	=> $quantity,
    			"logistics_fee"	=> $logistics_fee,
    			"logistics_type"	=> $logistics_type,
    			"logistics_payment"	=> $logistics_payment,
    			"body"	=> $body,
    			"show_url"	=> $show_url,
    			"receive_name"	=> $receive_name,
    			"receive_address"	=> $receive_address,
    			"receive_zip"	=> $receive_zip,
    			"receive_phone"	=> $receive_phone,
    			"receive_mobile"	=> $receive_mobile,
    			"_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
    	);
    	
    	//建立请求
    	$alipaySubmit = new AlipaySubmit($alipay_config);
    	$html_text = $alipaySubmit->buildRequestForm($parameter,"get", "确认");
    	echo $html_text;
    }

}

?>
</body>
</html>