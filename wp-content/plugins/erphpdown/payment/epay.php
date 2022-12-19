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
    $sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_user_type,ice_post_id,ice_post_index,ice_time,ice_success,ice_note,ice_success_time,ice_alipay,ice_aff,ice_ip,ice_data) VALUES ('$price','$out_trade_no','".$user_Info->ID."','".$user_type."','".$post_id."','".$index."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','epay','".$ice_aff."','".erphpGetIP()."','".$ice_data."')";
    $a=$wpdb->query($sql);
    if(!$a){
        wp_die('系统发生错误，请稍后重试!');
    }else{
        $money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$out_trade_no."'");
    }

    require_once("epay/epay.config.php");
    require_once("epay/lib/epay_submit.class.php");

    /**************************请求参数**************************/
    $notify_url = ERPHPDOWN_URL.'/payment/epay/notify_url.php';
    //需http://格式的完整路径，不能加?id=123这类自定义参数

    //页面跳转同步通知页面路径
    $return_url = ERPHPDOWN_URL.'/payment/epay/return_url.php';
    //需http://格式的完整路径，不能加?id=123这类自定义参数，不能写成http://localhost/


	//支付方式
    $type='alipay';
    if(isset($_GET['type']) && $_GET['type']) $type = $_GET['type'];
    //商品名称
    $name = $subject;
	//付款金额
    $money = $price;
	//站点名称
    $sitename = get_bloginfo('name');
    //必填

    //返回方式
    $data_type = 'html';
    if(get_option('erphpdown_epay_alipay_json') && $type == 'alipay'){
        $data_type = 'json';
    }elseif(get_option('erphpdown_epay_wxpay_json') && $type == 'wxpay'){
        $data_type = 'json';
    }elseif(get_option('erphpdown_epay_qqpay_json') && $type == 'qqpay'){
        $data_type = 'json';
    }

    /************************************************************/

    if($data_type == 'json' && !(wp_is_mobile() || erphpdown_is_mobile())){
        $parameter = array(
    		"pid" => trim($alipay_config['partner']),
    		"type" => $type,
    		"notify_url"	=> $notify_url,
    		"return_url"	=> $return_url,
    		"out_trade_no"	=> $out_trade_no,
    		"name"	=> $name,
    		"money"	=> $money,
    		"sitename"	=> $sitename,
            "clientip" => erphpGetIP(),
            //"device" => 'pc'
        );
    }else{
        $parameter = array(
            "pid" => trim($alipay_config['partner']),
            "type" => $type,
            "notify_url"    => $notify_url,
            "return_url"    => $return_url,
            "out_trade_no"  => $out_trade_no,
            "name"  => $name,
            "money" => $money,
            "sitename"  => $sitename
        );
    }

    //建立请求
    $alipaySubmit = new AlipaySubmit($alipay_config);
    if($data_type == 'json' && !(wp_is_mobile() || erphpdown_is_mobile())){
        $resultArray = $alipaySubmit->buildRequestJson($parameter);
        //var_dump($resultArray);exit;
        if(is_array($resultArray) && $resultArray['code'] == '1'){
?>
        <html>
        <head>
            <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1" /> 
            <title><?php if($type == 'qqpay') echo 'QQ钱包'; else echo ($type=='alipay')?'支付宝':'微信';?>支付</title>
            <link rel='stylesheet'  href='../static/erphpdown.css' type='text/css' media='all' />
        </head>
        <body<?php if(!isset($_GET['iframe'])){echo ' class="erphpdown-page-pay"';}?>>
            <div class="wppay-custom-modal-box mobantu-wppay erphpdown-custom-modal-box">
                <section class="wppay-modal">  
                    <section class="erphp-wppay-qrcode mobantu-wppay">
                        <section class="tab">
                            <a href="javascript:;" class="active"><div class="payment"><img src="<?php echo constant("erphpdown");?>static/images/<?php if($type == 'qqpay') echo 'payment-qqpay'; else echo ($type=='alipay')?'payment-alipay':'payment-weixin';?>.png"></div>￥<?php echo sprintf("%.2f",$price);?></a>
                                   </section>
                        <section class="tab-list" style="background-color: <?php echo ($type=='alipay')?'#00a3ee':'#21ab36';?>;">
                            <section class="item">
                                <section class="qr-code">
                                    <img src="<?php echo constant("erphpdown").'includes/qrcode.php?data='.urlencode($resultArray['qrcode']);?>" class="img" alt="">
                                </section>
                                <p class="account">支付完成后请等待5秒左右</p>
                                <p id="time" class="desc"></p>
                                <?php if(wp_is_mobile() || erphpdown_is_mobile()){
                                    if($type=='alipay'){
                                        echo '<p class="wap"><a id="erphp-wap-link" href="'.$resultArray['qrcode'].'" target="_blank"><span>启动支付宝APP支付</span></a></p>';
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
                                    parent.layer.msg('充值成功！');
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

                var m = 5, s = 0;  
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
        }else{
            echo '创建订单二维码失败！请检查接口配置！';
        }
    }else{
        $html_text = $alipaySubmit->buildRequestForm($parameter);
?>
        <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
        <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <title>正在跳转...</title>
                <style>input{display:none}</style>
        </head>
<?php
        echo $html_text;
    }
}
?>
</body>
</html>