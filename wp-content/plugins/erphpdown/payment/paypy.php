<?php 
session_start();
$_SESSION['erphpdown_token']=md5(time().rand(100,999));
if(isset($_GET['redirect_url'])){
    $_COOKIE['erphpdown_return'] = urldecode($_GET['redirect_url']);
    setcookie('erphpdown_return',urldecode($_GET['redirect_url']),0,'/');
}else{
    $_COOKIE['erphpdown_return'] = '';
    setcookie('erphpdown_return','',0,'/');
}
require_once('../../../../wp-load.php');
$secretkey = get_option('erphpdown_paypy_key');
$api = get_option('erphpdown_paypy_api').'api/order/';

header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

$post_id   = isset($_GET['ice_post']) && is_numeric($_GET['ice_post']) ?$_GET['ice_post'] :0;
$user_type   = isset($_GET['ice_type']) && is_numeric($_GET['ice_type']) ?$_GET['ice_type'] :'';
$index   = isset($_GET['index']) && is_numeric($_GET['index']) ?$_GET['index'] :'';
$index_vip = '';

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

if($price > 0){
    $trade_order_id = date("ymdhis").mt_rand(100,999).mt_rand(100,999).mt_rand(100,999);
    $ice_aff = '';
    if(is_user_logged_in()){
        $subject = get_bloginfo('name').'订单['.get_the_author_meta( 'user_login', wp_get_current_user()->ID ).']';
    }else{
        $trade_order_id = 'MD'.$trade_order_id;
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
            wp_die('请填写一个接收卡密的邮箱!','友情提示');
        }
        $ice_data = $email.'|'.$num;
        $price = $price*$num;

        $trade_order_id = str_replace('MD','',$trade_order_id);
        $trade_order_id = 'FK'.$trade_order_id;
        $_SESSION['ice_num'] = $trade_order_id;
    }

    $user_Info   = wp_get_current_user();
    $sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_user_type,ice_post_id,ice_post_index,ice_time,ice_success,ice_note,ice_success_time,ice_alipay,ice_aff,ice_ip,ice_data) VALUES ('$price','$trade_order_id','".$user_Info->ID."','".$user_type."','".$post_id."','".$index."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','paypy','".$ice_aff."','".erphpGetIP()."','".$ice_data."')";
    $a=$wpdb->query($sql);
    if(!$a){
        wp_die('系统发生错误，请稍后重试!','友情提示');
    }else{
		$money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$trade_order_id."'");
	}
}else{
    wp_die('请输入您要充值的金额！','友情提示');
}

$order_type = 'wechat';
if(isset($_GET['type']) && $_GET['type'] == 'alipay') $order_type = 'alipay';

$sign = md5(md5($trade_order_id.$price).$secretkey);
$logged_ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? esc_sql($_SERVER['HTTP_X_FORWARDED_FOR']) : esc_sql($_SERVER['REMOTE_ADDR']);
if(function_exists('paypy_install') && $api == (PAYPY_URL.'/api/order/')){
    $minute = get_option("paypy_minute");
    $secretkey = get_option("paypy_key");
    $max = get_option("paypy_max");
    $paypy_fresh = get_option("paypy_fresh");
    $paypy_alipay_trans = get_option("paypy_alipay_trans");
    $alipayUid = get_option("paypy_alipayUid");
    $paypy_method = get_option("paypy_method");
    $order_id = $trade_order_id;
    $order_price = $price;
    $order_name = $subject;
    $order_ip = $logged_ip;
    $redirect_url = constant("erphpdown")."payment/paypy/notify.php";
    $extension = "erphpdown-".$order_type;

    $code = '-1';
    $msg = '';
    $can = 0;
    $qr_price = $order_price;
    $qr_url = '';
    if($sign == md5(md5($order_id.$order_price).$secretkey) && paypy_active()){
        if($paypy_method){ //随意金额
            if($order_ip && $paypy_fresh){
                $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and qr_ip = '".$order_ip."' and pay_status='未支付' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                if($check_or){ //有这个IP的价格
                    $del_ord = $wpdb->query("delete from ".$wpdb->prefix."paypy_orders where id='".$check_or."'");
                    if($del_ord){ //删除这个IP的价格
                        if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                            $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                            $can = 1;
                        }else{
                            $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='0.00' and qr_type='".$order_type."'");
                            if($qr_url){
                                $can = 1;
                            }
                        }
                    }else{ //没删成功 正常减免
                        for($i = 1;$i <= $max;$i ++){
                            $qr_price = $order_price - 0.01*$i;
                            if($qr_price > 0){
                                $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                                if($check_or){
                                    continue;
                                }else{
                                    if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                        $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                        $can = 1;
                                        break;
                                    }else{
                                        $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='0.00' and qr_type='".$order_type."'");
                                        if($qr_url){
                                            $can = 1;
                                            break;
                                        }else{
                                            continue;
                                        }
                                    }
                                }
                            }
                        }
                        if($i == $max+1) $can = 3;
                    }
                }else{ //没有的话得判断全局有没有这个价格
                    $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                    if($check_or){
                        for($i = 1;$i <= $max;$i ++){
                            $qr_price = $order_price - 0.01*$i;
                            if($qr_price > 0){
                                $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and qr_ip = '".$order_ip."' and pay_status='未支付' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                                if($check_or){ //检查是否有该IP的减免价格
                                    $del_ord = $wpdb->query("delete from ".$wpdb->prefix."paypy_orders where id='".$check_or."'");
                                    if($del_ord){
                                        if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                            $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                            $can = 1;
                                            break;
                                        }else{
                                            $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='0.00' and qr_type='".$order_type."'");
                                            if($qr_url){
                                                $can = 1;
                                                break;
                                            }else{
                                                continue;
                                            }
                                        }
                                    }else{
                                        continue;
                                    }
                                }else{
                                    $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                                    if($check_or){
                                        continue;
                                    }else{
                                        if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                            $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                            $can = 1;
                                            break;
                                        }else{
                                            $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='0.00' and qr_type='".$order_type."'");
                                            if($qr_url){
                                                $can = 1;
                                                break;
                                            }else{
                                                continue;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if($i == $max+1) $can = 3;
                    }else{
                        if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                            $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                            $can = 1;
                        }else{
                            $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='0.00' and qr_type='".$order_type."'");
                            if($qr_url){
                                $can = 1;
                            }
                        }
                    }
                }
            }else{
                $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                if($check_or){
                    for($i = 1;$i <= $max;$i ++){
                        $qr_price = $order_price - 0.01*$i;
                        if($qr_price > 0){
                            $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                            if($check_or){
                                continue;
                            }else{
                                if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                    $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                    $can = 1;
                                    break;
                                }else{
                                    $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='0.00' and qr_type='".$order_type."'");
                                    if($qr_url){
                                        $can = 1;
                                        break;
                                    }else{
                                        continue;
                                    }
                                }
                            }
                        }
                    }
                    if($i == $max+1) $can = 3;
                }else{
                    if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                        $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                        $can = 1;
                    }else{
                        $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='0.00' and qr_type='".$order_type."'");
                        if($qr_url){
                            $can = 1;
                        }
                    }
                }
            }
        }else{ //固定金额
            if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                $can = 1;
            }else{
                $check_qr = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                if($check_qr){
                    if($order_ip && $paypy_fresh){
                        $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and qr_ip = '".$order_ip."' and pay_status='未支付' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                        if($check_or){ //有这个IP的价格  
                            $del_ord = $wpdb->query("delete from ".$wpdb->prefix."paypy_orders where id='".$check_or."'");
                            if($del_ord){ //删除这个IP的价格
                                if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                    $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                    $can = 1;
                                }else{
                                    $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                    if($qr_url){
                                        $can = 1;
                                    }else{
                                        $can = 2;
                                    }
                                }
                            }else{ //没删成功 正常减免
                                for($i = 1;$i <= $max;$i ++){
                                    $qr_price = $order_price - 0.01*$i;
                                    if($qr_price > 0){
                                        $check_qr = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                        if($check_qr){
                                            $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                                            if($check_or){
                                                continue;
                                            }else{
                                                if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                                    $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                                    $can = 1;
                                                    break;
                                                }else{
                                                    $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                                    if($qr_url){
                                                        $can = 1;
                                                        break;
                                                    }else{
                                                        continue;
                                                    }
                                                }
                                            }
                                        }else{
                                            continue;
                                        }
                                    }
                                }
                                if($i == $max+1) $can = 3;
                            }
                        }else{ //没有的话得判断全局有没有这个价格
                            $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                            if($check_or){
                                for($i = 1;$i <= $max;$i ++){
                                    $qr_price = $order_price - 0.01*$i;
                                    if($qr_price > 0){
                                        $check_qr = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                        if($check_qr){
                                            $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and qr_ip = '".$order_ip."' and pay_status='未支付' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                                            if($check_or){ //检查是否有该IP的减免价格
                                                $del_ord = $wpdb->query("delete from ".$wpdb->prefix."paypy_orders where id='".$check_or."'");
                                                if($del_ord){
                                                    if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                                        $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                                        $can = 1;
                                                        break;
                                                    }else{
                                                        $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                                        if($qr_url){
                                                            $can = 1;
                                                            break;
                                                        }else{
                                                            continue;
                                                        }
                                                    }
                                                }else{
                                                    continue;
                                                }
                                            }else{
                                                $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                                                if($check_or){
                                                    continue;
                                                }else{
                                                    if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                                        $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                                        $can = 1;
                                                        break;
                                                    }else{
                                                        $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                                        if($qr_url){
                                                            $can = 1;
                                                            break;
                                                        }else{
                                                            continue;
                                                        }
                                                    }
                                                }
                                            }
                                        }else{
                                            continue;
                                        }
                                    }
                                }
                                if($i == $max+1) $can = 3;
                            }else{
                                if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                    $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                    $can = 1;
                                }else{
                                    $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                    if($qr_url){
                                        $can = 1;
                                    }else{
                                        $can = 2;
                                    }
                                }
                            }
                        }
                    }else{
                        $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                        if($check_or){
                            for($i = 1;$i <= $max;$i ++){
                                $qr_price = $order_price - 0.01*$i;
                                if($qr_price > 0){
                                    $check_qr = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                    if($check_qr){
                                        $check_or = $wpdb->get_var("select id from ".$wpdb->prefix."paypy_orders where qr_price='".$qr_price."' and order_type='".$order_type."' and created_at >= SUBDATE(NOW(), INTERVAL ".$minute." MINUTE)");
                                        if($check_or){
                                            continue;
                                        }else{
                                            if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                                $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                                $can = 1;
                                                break;
                                            }else{
                                                $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                                if($qr_url){
                                                    $can = 1;
                                                    break;
                                                }else{
                                                    continue;
                                                }
                                            }
                                        }
                                    }else{
                                        continue;
                                    }
                                }
                            }
                            if($i == $max+1) $can = 3;
                        }else{
                            if($order_type == 'alipay' && $paypy_alipay_trans && $alipayUid){
                                $qr_url = 'alipays%3A%2F%2Fplatformapi%2Fstartapp%3FappId%3D20000123%26actionType%3Dscan%26biz_data%3D%7B"s"%3A+"money"%2C+"u"%3A+"'.$alipayUid.'"%2C+"a"%3A+"'.$qr_price.'"%2C+"m"%3A+"'.$order_id.'"%7D';
                                $can = 1;
                            }else{
                                $qr_url = $wpdb->get_var("select qr_url from ".$wpdb->prefix."paypy_qrcodes where qr_price='".$qr_price."' and qr_type='".$order_type."'");
                                if($qr_url){
                                    $can = 1;
                                }else{
                                    $can = 2;
                                }
                            }
                        }
                    }
                }
            }
        }
    }else{
        $can = 4;
    }


    if($can == 1){
        if($order_ip && $paypy_fresh){
            $re = $wpdb->query("insert into ".$wpdb->prefix."paypy_orders(order_id,order_type,order_price,order_name,qr_ip,qr_url,qr_price,redirect_url,extension,created_at,updated_at) values('".$order_id."','".$order_type."','".$order_price."','".$order_name."','".$order_ip."','".$qr_url."','".$qr_price."','".$redirect_url."','".$extension."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')");
        }else{
            $re = $wpdb->query("insert into ".$wpdb->prefix."paypy_orders(order_id,order_type,order_price,order_name,qr_url,qr_price,redirect_url,extension,created_at,updated_at) values('".$order_id."','".$order_type."','".$order_price."','".$order_name."','".$qr_url."','".$qr_price."','".$redirect_url."','".$extension."','".date("Y-m-d H:i:s")."','".date("Y-m-d H:i:s")."')");
        }

        if($re){
            $code = '1';
        }else{
            $msg = '系统超时，请稍后重试！';
        }
    }elseif($can == 2){
        $msg = '系统超时，请稍后重试！';
    }elseif($can == 3){
        $msg = '操作太快啦，请您等待'.$minute.'分钟后再来！';
    }elseif($can == 4){
        $msg = '请求失败，请检查配置是否正确！';
    }else{
        $msg = '此商户未上传此价格的收款二维码！';
    }

    $resultArray = array(
        'code' => $code,
        'qr_price' => sprintf("%.2f",$qr_price),
        'qr_url' => $qr_url?urlencode($qr_url):$qr_url,
        'qr_minute' => $minute,
        'msg' => $msg
    );
}else{
    if(get_option('erphpdown_paypy_curl')){
        $result = erphpdown_curl_post($api,"order_id=".$trade_order_id."&order_type=".$order_type."&order_price=".$price."&order_ip=".$logged_ip."&order_name=".$subject."&sign=".$sign."&redirect_url=".constant("erphpdown")."payment/paypy/notify.php"."&extension=erphpdown-".$order_type);
        $result = trim($result, "\xEF\xBB\xBF");
        $resultArray = json_decode($result,true);
    }else{
        $body = array("order_id"=>$trade_order_id, "order_type"=>$order_type, "order_price"=>$price, "order_ip"=>$logged_ip, "order_name"=>$subject, "sign"=>$sign, "redirect_url"=>constant("erphpdown")."payment/paypy/notify.php", "extension"=>"erphpdown-".$order_type);
        $result = wp_remote_request($api, array("method" => "POST", "body"=>$body));
        $resultArray = json_decode($result['body'],true);
        //var_dump($resultArray);exit;
    }
}

if($resultArray['code'] != '1'){
	echo '获取支付失败：'.$resultArray['msg'];
}else{
?>
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    <title><?php echo ($order_type=='alipay')?'支付宝':'微信';?>支付</title>
    <link rel='stylesheet'  href='../static/erphpdown.css' type='text/css' media='all' />
</head>
<body<?php if(!isset($_GET['iframe'])){echo ' class="erphpdown-page-pay"';}?>>

	<div class="wppay-custom-modal-box mobantu-wppay erphpdown-custom-modal-box">
		<section class="wppay-modal">
            <section class="erphp-wppay-qrcode mobantu-wppay">
                <section class="tab">
                    <a href="javascript:;" class="active"><div class="payment"><img src="<?php echo constant("erphpdown");?>static/images/<?php echo ($order_type=='alipay')?'payment-alipay':'payment-weixin';?>.png"></div>￥<?php echo sprintf("%.2f",$resultArray['qr_price']);?></a>
                    <?php if($resultArray['qr_price']<$price) echo '<div class="warning">随机减免，请务必支付金额￥'.$resultArray['qr_price'].'</div>';?>
                </section>
                <section class="tab-list" style="background-color: <?php echo ($order_type=='alipay')?'#00a3ee':'#21ab36';?>;">
                    <section class="item">
                        <section class="qr-code">
                            <img src='<?php echo constant("erphpdown").'includes/qrcode.php?data='.urldecode($resultArray['qr_url']);?>' class="img" alt="">
                        </section>
                        <p class="account">支付完成后请等待5秒左右，期间请勿刷新</p>
                        <p id="time" class="desc"></p>
                        <?php if(wp_is_mobile() || erphpdown_is_mobile()){
                            if($order_type=='alipay'){
                        ?>
                            <p class="wap"><a id="erphp-wap-link" href='<?php echo str_replace(' ', '%20', str_replace('"', '%22', urldecode(urldecode($resultArray['qr_url']))));?>' target="_blank"><span>启动支付宝APP支付</span></a></p>
                        <?php
                            }else{
                                echo '<p class="wap">请截屏后，打开微信扫一扫，从相册选择二维码图片</p>';
                            }
                        }?>
                    </section>
                </section>
            </section>
    	</section>
    </div>

    <script src="<?php echo ERPHPDOWN_URL;?>/static/jquery-1.7.min.js"></script>
	<script>
        <?php if(wp_is_mobile() || erphpdown_is_mobile()){?>
            $(function(){$("#erphp-wap-link").find("span").trigger("click");});
        <?php }?>
		erphpOrder = setInterval(function() {
			$.ajax({  
	            type: 'POST',  
	            url: '<?php echo ERPHPDOWN_URL;?>/admin/action/order.php',  
	            data: {
	            	do: 'checkOrder',
	            	order: '<?php echo $money_info->ice_id;?>',
                    token: '<?php echo $_SESSION['erphpdown_token'];?>'
	            },  
	            dataType: 'text',
	            success: function(data){  
	                if( $.trim(data) == '1' ){
	                    clearInterval(erphpOrder);
                        <?php if(isset($_GET['iframe'])){?>
                            var mylayer= parent.layer.getFrameIndex(window.name);
                            parent.layer.close(mylayer);
                            parent.layer.msg('支付成功！');
                            parent.location.reload();  
                        <?php }else{?>
    	                    alert('支付成功！');
                            <?php if(isset($_COOKIE['erphpdown_return']) && $_COOKIE['erphpdown_return']){?>
                            location.href="<?php echo $_COOKIE['erphpdown_return'];?>";
    	                    <?php }elseif(get_option('erphp_url_front_success')){?>
    	                    location.href="<?php echo get_option('erphp_url_front_success');?>";
    	                    <?php }else{?>
    	                    window.close();
    	                	<?php }?>
                        <?php }?>
	                }  
	            },
	            error: function(XMLHttpRequest, textStatus, errorThrown){
	            	//alert(errorThrown);
	            }
	        });
		}, 5000);

        var m = <?php echo $resultArray['qr_minute'];?>, s = 0;  
        var Timer = document.getElementById("time");
        wppayCountdown();
        erphpTimer = setInterval(function(){ wppayCountdown() },1000);
        function wppayCountdown (){
            Timer.innerHTML = "支付倒计时：<span>0"+m+"分"+s+"秒</span>";
            if( m == 0 && s == 0 ){
                clearInterval(erphpOrder);
                clearInterval(erphpTimer);
                $(".qr-code").append('<div class="expired"></div>');
                m = 4;
                s = 59;
            }else if( m >= 0 ){
                if( s > 0 ){
                    s--;
                }else if( s == 0 ){
                    m--;
                    s = 59;
                }
            }
        }

	</script>
</body>
</html>
<?php
}