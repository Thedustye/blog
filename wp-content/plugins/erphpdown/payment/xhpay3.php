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
header("Content-Type: text/html;charset=utf-8");
date_default_timezone_set('Asia/Shanghai');

$erphpdown_xhpay_admin = get_option('erphpdown_xhpay_admin');
if($erphpdown_xhpay_admin){
    require_once 'xhpay/api4.php';
}else{
    require_once 'xhpay/api3.php';
}

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
            wp_die('请填写一个接收卡密的邮箱!');
        }
        $ice_data = $email.'|'.$num;
        $price = $price*$num;

        $trade_order_id = str_replace('MD','',$trade_order_id);
        $trade_order_id = 'FK'.$trade_order_id;
        $_SESSION['ice_num'] = $trade_order_id;
    }

    $user_Info   = wp_get_current_user();
    $sql="INSERT INTO $wpdb->icemoney (ice_money,ice_num,ice_user_id,ice_user_type,ice_post_id,ice_post_index,ice_time,ice_success,ice_note,ice_success_time,ice_alipay,ice_aff,ice_ip,ice_data)
    VALUES ('$price','$trade_order_id','".$user_Info->ID."','".$user_type."','".$post_id."','".$index."','".date("Y-m-d H:i:s")."',0,'0','".date("Y-m-d H:i:s")."','xhpay','".$ice_aff."','".erphpGetIP()."','".$ice_data."')";
    $a=$wpdb->query($sql);
    if(!$a){
        wp_die('系统发生错误，请稍后重试!');
    }else{
        $money_info=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$trade_order_id."'");
    }
}else{
    wp_die('请输入您要充值的金额');
}

$appid              = get_option('erphpdown_xhpay_appid32');
$appsecret          = get_option('erphpdown_xhpay_appsecret32');
if($erphpdown_xhpay_admin){
    $url                = get_option('erphpdown_xhpay_api32')?get_option('erphpdown_xhpay_api32'):"https://admin.xunhuweb.com";
    $notify = constant("erphpdown").'payment/xhpay/notify34.php'; 
}else{
    $url                = get_option('erphpdown_xhpay_api32')?get_option('erphpdown_xhpay_api32'):"https://api.xunhupay.com/payment/do.html";
    $notify = constant("erphpdown").'payment/xhpay/notify32.php'; 
}
$payment = 'alipay';
$payType = 1;

if(isset($_GET['type']) && $_GET['type'] == '2'){
    $appid              = get_option('erphpdown_xhpay_appid31');
    $appsecret          = get_option('erphpdown_xhpay_appsecret31'); 
    if($erphpdown_xhpay_admin){
        $url              = get_option('erphpdown_xhpay_api31')?get_option('erphpdown_xhpay_api31'):"https://admin.xunhuweb.com";
        $notify = constant("erphpdown").'payment/xhpay/notify33.php';
    }else{
        $url              = get_option('erphpdown_xhpay_api31')?get_option('erphpdown_xhpay_api31'):"https://api.xunhupay.com/payment/do.html";
        $notify = constant("erphpdown").'payment/xhpay/notify31.php';
    }
    $payment = 'wechat';
    $payType = 2;
    if($erphpdown_xhpay_admin){
        $trade_order_id=$trade_order_id.'@'.time();
    }
}

$mob = 'N';
if(wp_is_mobile() || erphpdown_is_mobile()){
    $mob = 'Y';
}

if($erphpdown_xhpay_admin){//迅虎支付
    $data=array(
        'mchid'     => $appid, //必须的，APPID
        'out_trade_no'=> $trade_order_id, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+ 
        'type'   => $payment,//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
        'total_fee' => $price*100,//人民币，单位精确到分(测试账户只支持0.1元内付款)
        'body'     => $subject, //必须的，订单标题，长度32或以内
        'notify_url'=>  $notify, //必须的，支付成功异步回调接口
        'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
    );
    $hashkey =$appsecret;
    if($mob == 'Y'){
        if($payment=='alipay'){
            $data['redirect_url']=get_option('erphp_url_front_success');
            $data['sign']     = XunHu_Payment_Api::generate_xh_hash($data,$hashkey);
            $pay_url          = XunHu_Payment_Api::data_link($url.'/alipaycashier', $data);
            header("Location:". htmlspecialchars_decode($pay_url,ENT_NOQUOTES));
            exit;
        }else{
            if(XunHu_Payment_Api::is_wechat_app()){
                 $data['redirect_url']=get_option('erphp_url_front_success');
                 $data['sign']     = XunHu_Payment_Api::generate_xh_hash($data,$hashkey);
                 $pay_url     = XunHu_Payment_Api::data_link($url.'/pay/cashier', $data);
                 header("Location:". htmlspecialchars_decode($pay_url,ENT_NOQUOTES));
                 exit;
            }
            $redirect_url=get_option('erphp_url_front_success');
            $data['trade_type'] = 'WAP';
            $data['wap_url']    = $http_type.$_SERVER['SERVER_NAME'];//h5支付域名必须备案，然后找服务商绑定
            $data['wap_name']   = '迅虎网络';
            $data['sign']       = XunHu_Payment_Api::generate_xh_hash($data,$hashkey);
            $response            = XunHu_Payment_Api::http_post_json($url.'/pay/payment', json_encode($data));
            $result              = $response?json_decode($response,true):null;
            if(!$result){
               throw new Exception('Internal server error',500);
            }
            $sign             = XunHu_Payment_Api::generate_xh_hash($result,$hashkey);
            if(!isset( $result['sign'])|| $sign!=$result['sign']){
                throw new Exception('Invalid sign!',40029);
            }
            if($result['return_code']!='SUCCESS'){
                throw new Exception($result['err_msg'],$result['err_code']);
            }
            $pay_url =$result['mweb_url'].'&redirect_url='.urlencode($redirect_url);
            ?>
                <html>
                <head>
                <meta charset="UTF-8">
                <title>收银台付款</title>
                <meta id="viewport" name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
                <meta name="format-detection" content="telephone=no">
                <link rel="stylesheet" href="../static/pay.css">
                </head>
                <body ontouchstart="" class="bggrey">
                    <div class="xh-title"><img src="<?php print ERPHPDOWN_URL ;?>/static/images/wechat-s.png" alt="" style="vertical-align: middle"> 微信支付收银台</div>

                        <div class="xhpay ">
                           <img class="logo" alt="" src="<?php print ERPHPDOWN_URL ;?>/static/images/img_14.png">

                            <span class="price">￥<?php echo $price ?></span>
                        </div>
                        <div class="xhpaybt">
                            <a href="<?php print $pay_url ?>" class="xunhu-btn xunhu-btn-green" >微信支付</a>
                        </div>
                        <div class="xhpaybt">
                            <a href="<?php echo $redirect_url;?>" class="xunhu-btn xunhu-btn-border-green" >取消支付</a>
                        </div>
                        <div class="xhtext" align="center">支付完成后，如需售后服务请联系客服</div>
                        <script src="<?php echo ERPHPDOWN_URL;?>/static/jquery-1.7.min.js"></script>
                        <script type="text/javascript">
                         (function($){
                                window.view={
                                    query:function () {
                                    $.ajax({
                                        type: 'POST',  
                                        url: '<?php echo ERPHPDOWN_URL;?>/admin/action/order.php',  
                                        data: {
                                            do: 'checkOrder',
                                            order: '<?php echo $money_info->ice_id;?>',
                                            token: '<?php echo $_SESSION['erphpdown_token'];?>'
                                        },  
                                        dataType: 'text',
                                            success:function(data){
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
                                                
                                                setTimeout(function(){window.view.query();}, 2000);
                                            },
                                            error:function(){
                                                 setTimeout(function(){window.view.query();}, 2000);
                                            }
                                        });
                                    }
                                };                              
                                  window.view.query();                              
                            })(jQuery);
                            </script>
                </body>
                </html>
             <?php
                 exit;
        }
    }
    $data['sign']     = XunHu_Payment_Api::generate_xh_hash($data,$hashkey);

    try {
        $response     = XunHu_Payment_Api::http_post_json($url.'/pay/payment', json_encode($data));
        $result       = $response?json_decode($response,true):null;
        if(!$result){
            throw new Exception('Internal server error',500);
        }
        $sign         = XunHu_Payment_Api::generate_xh_hash($result,$hashkey);
        if(!isset( $result['sign'])|| $sign!=$result['sign']){
            throw new Exception('Invalid sign!',40029);
        }
        if($result['return_code']!='SUCCESS'){
            throw new Exception($result['err_msg'],$result['err_code']);
        }
        //var_dump($result['code_url']);exit;
        /**
         * 支付回调数据
         * @var array
         *  array(
         *      order_id,//支付系统订单ID
         *      url,//支付跳转地址
         *      url_qrcode//二维码
         *  )
         */
    ?>
    <html>
        <head>
            <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1" /> 
            <title><?php echo ($payType==1)?'支付宝':'微信';?>支付</title>
            <link rel='stylesheet'  href='../static/erphpdown.css' type='text/css' media='all' />
        </head>
        <body<?php if(!isset($_GET['iframe'])){echo ' class="erphpdown-page-pay"';}?>>
            <div class="wppay-custom-modal-box mobantu-wppay erphpdown-custom-modal-box">
                <section class="wppay-modal">
                            
                    <section class="erphp-wppay-qrcode mobantu-wppay">
                        <section class="tab">
                            <a href="javascript:;" class="active"><div class="payment"><img src="<?php echo constant("erphpdown");?>static/images/<?php echo ($payType==1)?'payment-alipay':'payment-weixin';?>.png"></div>￥<?php echo sprintf("%.2f",$price);?></a>
                                   </section>
                        <section class="tab-list" style="background-color: <?php echo ($payType==1)?'#00a3ee':'#21ab36';?>;">
                            <section class="item">
                                <section class="qr-code">
                                    <img src="<?php echo ERPHPDOWN_URL.'/includes/qrcode.php?data='.urlencode($result['code_url']);?>" class="img" alt="">
                                </section>
                                <p class="account">支付完成后请等待5秒左右</p>
                                <p id="time" class="desc"></p>
                                <?php if(wp_is_mobile() || erphpdown_is_mobile()){
                                    if($payType=='1'){
                                        echo '<p class="wap"><a id="erphp-wap-link" href="'.urldecode($result['code_url']).'" target="_blank"><span>启动支付宝APP支付</span></a></p>';
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

    } catch (Exception $e) {
        echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
        //TODO:处理支付调用异常的情况
    }
}else{//虎皮椒V3
    $data=array(
        'version'   => '1.1',//固定值，api 版本，目前暂时是1.1
        'lang'       => 'zh-cn', //必须的，zh-cn或en-us 或其他，根据语言显示页面
        'plugins'   => 'erphpdown-xhpay3',//必须的，根据自己需要自定义插件ID，唯一的，匹配[a-zA-Z\d\-_]+ 
        'appid'     => $appid, //必须的，APPID
        'trade_order_id'=> $trade_order_id, //必须的，网站订单ID，唯一的，匹配[a-zA-Z\d\-_]+ 
        'payment'   => $payment,//必须的，支付接口标识：wechat(微信接口)|alipay(支付宝接口)
        'is_app'    => $mob, //必须的，Y|N 是否是移动端
        'total_fee' => $price,//人民币，单位精确到分(测试账户只支持0.1元内付款)
        'title'     => $subject, //必须的，订单标题，长度32或以内
        'description'=> '',//可选，订单描述，长度5000或以内
        'time'      => time(),//必须的，当前时间戳，根据此字段判断订单请求是否已超时，防止第三方攻击服务器
        'notify_url'=>  $notify, //必须的，支付成功异步回调接口
        'return_url'=> get_option('erphp_url_front_success'),//必须的，支付成功后的跳转地址
        'callback_url'=>get_option('erphp_url_front_success'),//必须的，支付发起地址（未支付或支付失败，系统会会跳到这个地址让用户修改支付信息）
        'nonce_str' => str_shuffle(time())//必须的，随机字符串，作用：1.避免服务器缓存，2.防止安全密钥被猜测出来
    );

    if ((wp_is_mobile() || erphpdown_is_mobile()) && $payment == "wechat") {
        $data['type']='WAP';
        $data['wap_url']=home_url();
        $data['wap_name']=home_url();
    }

    $hashkey =$appsecret;
    $data['hash']     = XH_Payment_Api::generate_xh_hash($data,$hashkey);

    try {
        $response     = XH_Payment_Api::http_post($url, json_encode($data));
        $result       = $response?json_decode($response,true):null;
        if(!$result){
            throw new Exception('Internal server error',500);
        }
         
        $hash         = XH_Payment_Api::generate_xh_hash($result,$hashkey);
        if(!isset( $result['hash'])|| $hash!=$result['hash']){
            throw new Exception(__('Invalid sign!',XH_Wechat_Payment),40029);
        }

        if($result['errcode']!=0){
            throw new Exception($result['errmsg'],$result['errcode']);
        }
        
        /**
         * 支付回调数据
         * @var array
         *  array(
         *      order_id,//支付系统订单ID
         *      url,//支付跳转地址
         *      url_qrcode//二维码
         *  )
         */
        if(wp_is_mobile() || erphpdown_is_mobile()){
            $pay_url =$result['url'];
            header("Location: $pay_url");
            exit;
        }
    ?>
    <html>
    	<head>
    	    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    	    <meta name="viewport" content="width=device-width, initial-scale=1" /> 
    	    <title><?php echo ($payType==1)?'支付宝':'微信';?>支付</title>
    	    <link rel='stylesheet'  href='../static/erphpdown.css' type='text/css' media='all' />
    	</head>
    	<body<?php if(!isset($_GET['iframe'])){echo ' class="erphpdown-page-pay"';}?>>
    		<div class="wppay-custom-modal-box mobantu-wppay erphpdown-custom-modal-box">
    			<section class="wppay-modal">
    	                    
    	            <section class="erphp-wppay-qrcode mobantu-wppay">
    	                <section class="tab">
    	                    <a href="javascript:;" class="active"><div class="payment"><img src="<?php echo constant("erphpdown");?>static/images/<?php echo ($payType==1)?'payment-alipay':'payment-weixin';?>.png"></div>￥<?php echo sprintf("%.2f",$price);?></a>
    	                           </section>
    	                <section class="tab-list" style="background-color: <?php echo ($payType==1)?'#00a3ee':'#21ab36';?>;">
    	                    <section class="item">
    	                        <section class="qr-code">
    	                            <img src="<?php echo $result['url_qrcode'];?>" class="img" alt="">
    	                        </section>
    	                        <p class="account">支付完成后请等待5秒左右</p>
    	                        <p id="time" class="desc"></p>
                                <?php if(wp_is_mobile() || erphpdown_is_mobile()){
                                    if($payType=='1'){
                                        echo '<p class="wap"><a id="erphp-wap-link" href="'.urldecode($result['url_qrcode']).'" target="_blank"><span>启动支付宝APP支付</span></a></p>';
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

    } catch (Exception $e) {
        echo "errcode:{$e->getCode()},errmsg:{$e->getMessage()}";
        //TODO:处理支付调用异常的情况
    }
}