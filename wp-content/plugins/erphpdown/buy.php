<?php 
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------

require_once '../../../wp-load.php';
date_default_timezone_set('Asia/Shanghai');
?>
<html lang="zh-CN">
<head>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="<?php echo constant("erphpdown"); ?>static/erphpdown.css?v=<?php echo $erphpdown_version;?>" type="text/css" />
	<script type="text/javascript" src="<?php echo ERPHPDOWN_URL;?>/static/jquery-1.7.min.js"></script>
	<script type="text/javascript" src="<?php echo ERPHPDOWN_URL;?>/static/erphpdown.js"></script>
</head>
<style>
::-webkit-scrollbar {width:6px;height:6px}
::-webkit-scrollbar-thumb {background-color: #c7c7c7;border-radius:5px;}
body{margin:10px 20px;padding: 0;}
<?php echo get_option('erphp_custom_css');?>
</style>
<body>
	<div id="erphpdown-paybox">
	<?php
	$erphp_ajaxbuy = get_option('erphp_ajaxbuy');
	$erphp_justbuy = get_option('erphp_justbuy');
	$postid=isset($_GET['postid']) && is_numeric($_GET['postid']) ?intval($_GET['postid']) :false;
	$index=isset($_GET['index']) && is_numeric($_GET['index']) ?intval($_GET['index']) : '';
	$user_type=isset($_GET['user_type']) && is_numeric($_GET['user_type']) ?intval($_GET['user_type']) : '';
	$index = esc_sql($index);
	if($postid){
		$postid = esc_sql($postid);
		$erphp_down=get_post_meta($postid, 'erphp_down',TRUE);
		if($erphp_down == 6){
			if(!function_exists('getErphpActLeft')){
				wp_die("请先在后台erphpdown-基础设置里启用激活码发放扩展！","友情提示");
			}
			$ErphpActLeft = getErphpActLeft($postid);
			$price=get_post_meta($postid, 'down_price', true);
			$price = $price / get_option('ice_proportion_alipay');
			if($price){
	?>
				<div class="erphpdown-table">
						<div class="item">
							<label>购买内容<span>（库存：<?php echo $ErphpActLeft;?>）</span></label>
							<div class="tit"><?php echo get_post($postid)->post_title;?></div>
						</div>
						<div class="item">
							<label>购买数量</label>
							<div class="tit" style="text-align:right">
								<div class="erphp-faka-num">
									<a href="javascript:;" class="erphp-faka-minus">-</a><input type="number" step="1" min="1" max="<?php echo $ErphpActLeft;?>" id="erphp_faka_num" value="1" oninput="intValidator(event)" /><a href="javascript:;" class="erphp-faka-plus">+</a>
								</div>合计 <b id="erphp_faka_total" data-price="<?php echo sprintf("%.2f",$price);?>"><?php echo sprintf("%.2f",$price);?></b> 元</div>
						</div>
						<div class="item">
							<label>接收邮箱</label>
							<div class="tit">
								<input type="email" id="erphp_faka_email" />
							</div>
						</div>
						<div class="item">
							<div style="padding-top:20px;">
							<?php echo '<div class="erphp-justbuy">';?>
								<?php if(get_option('ice_weixin_mchid')){?> 
									<?php if(erphpdown_is_weixin() && get_option('ice_weixin_app')){?>
									<a data-href="<?php echo urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_post%3D'.$postid.'%26redirect_url='.urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3 pmt-wx-app" data-prefix="<?php echo 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri=';?>" data-suffix="&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
									<?php }else{?>
									<a data-href="<?php echo constant("erphpdown")."payment/weixin.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
									<?php }?>
								<?php }?>
								<?php if((get_option('ice_ali_partner') || get_option('ice_ali_app_id')) && !erphpdown_is_weixin()){?> 
									<a data-href="<?php echo constant("erphpdown")."payment/alipay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
								<?php }?>
								<?php if(get_option('erphpdown_f2fpay_id') && !erphpdown_is_weixin() && !get_option('erphpdown_f2fpay_alipay')){?> 
									<a data-href="<?php echo constant("erphpdown")."payment/f2fpay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
								<?php }?>
								<?php if(get_option('erphpdown_payjs_appid')){?> 
									<?php if(!get_option('erphpdown_payjs_wxpay')){?><a data-href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_payjs_alipay')){?><a data-href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&type=alipay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?> 
								<?php }?>
								<?php if(get_option('erphpdown_xhpay_appid31')){?> 
									<a data-href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>   
								<?php }?>
								<?php if(get_option('erphpdown_xhpay_appid32')){?> 
									<a data-href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>  
								<?php }?>
								<?php if(get_option('erphpdown_paypy_key')){?> 
									<?php if(!get_option('erphpdown_paypy_wxpay')){?><a data-href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_paypy_alipay')){?><a data-href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
								<?php }?>
								<?php if(get_option('erphpdown_codepay_appid')){?> 
									<?php if(!get_option('erphpdown_codepay_alipay')){?><a data-href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=1"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
									<?php if(!get_option('erphpdown_codepay_wxpay')){?><a data-href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=3"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>
									<?php if(!get_option('erphpdown_codepay_qqpay')){?><a data-href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>    
								<?php }?>
								<?php if(get_option('erphpdown_epay_id')){?> 
									<?php if(!get_option('erphpdown_epay_wxpay')){?><a data-href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=wxpay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_epay_alipay')){?><a data-href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
									<?php if(!get_option('erphpdown_epay_qqpay')){?><a data-href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=qqpay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>
								<?php }?>
								<?php if(get_option('erphpdown_vpay_key')){?> 
									<?php if(!get_option('erphpdown_vpay_wxpay')){?><a data-href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_vpay_alipay')){?><a data-href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
								<?php }?>
								<?php if(get_option('erphpdown_stripe_pk') && plugin_check_stripe()){?> 
									<a data-href="<?php echo ERPHPDOWN_STRIPE_URL."/stripe.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-stripe erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-credit-card"></i> 信用卡</a>
								<?php }?>
								<?php if(get_option('ice_payapl_api_uid')){?> 
									<a data-href="<?php echo constant("erphpdown")."payment/paypal.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-pp erphpdown-jump3" target="_blank"><i class="erphp-iconfont erphp-icon-paypay"></i> Paypal</a>
								<?php }?>
							<?php echo '</div>';?>
							</div>
							<?php if(!is_user_logged_in()){
								$erphp_url_front_login = wp_login_url();
								if(get_option('erphp_url_front_login')){
									$erphp_url_front_login = get_option('erphp_url_front_login');
								}
							?><div class="login-tips"><a href="<?php echo $erphp_url_front_login;?>" target="_blank">登录</a>后购买可保存购买记录</div><?php }?>
						</div>
					</div>
					<script>
						$(".erphpdown-jump3").click(function(){
							var cmail = $("#erphp_faka_email").val(),
								cnum = Number($("#erphp_faka_num").val()),
								clink = $(this).data("href");
							if(cnum < 1){
								layer.msg('请输入购买数量！');
								return false;
							}else if(cmail == ''){
								layer.msg('请输入邮箱，用于接收卡密！');
								return false;
							}else{
								if($(this).hasClass("pmt-wx-app")){
									$(this).attr("href",$(this).data("prefix")+clink+"%26num%3D"+cnum+"%26data%3D"+cmail+$(this).data("suffix"));
								}else{
									$(this).attr("href",clink+"&num="+cnum+"&data="+cmail);
								}
								parent.layer.closeAll();
							}
						});

						$(".erphp-faka-minus").click(function(){
							var cnum = Number($("#erphp_faka_num").val()),
								cprice = $("#erphp_faka_total").data("price");
							if(cnum > 1){
								$("#erphp_faka_num").val(cnum-1);
								$("#erphp_faka_total").text(((cnum-1)*cprice).toFixed(2));
							}
						});
						
						$(".erphp-faka-plus").click(function(){
							var cnum = Number($("#erphp_faka_num").val()),
								cprice = $("#erphp_faka_total").data("price"),
								cmax = Number($("#erphp_faka_num").attr("max"));
							if(cnum < cmax){
								$("#erphp_faka_num").val(cnum+1);
								$("#erphp_faka_total").text(((cnum+1)*cprice).toFixed(2));
							}
						});

						$("#erphp_faka_num").blur(function(){
							var cnum = Number($("#erphp_faka_num").val()),
								cprice = $("#erphp_faka_total").data("price");
							if(cnum > 1){
								if(cnum > <?php echo $ErphpActLeft;?>){
									$("#erphp_faka_num").val(<?php echo $ErphpActLeft;?>);
									$("#erphp_faka_total").text((<?php echo $ErphpActLeft;?>*cprice).toFixed(2));
								}else{
									$("#erphp_faka_total").text((cnum*cprice).toFixed(2));
								}
							}else{
								$("#erphp_faka_total").text(cprice.toFixed(2));
							}
						});

						function intValidator(e){
						    var value = e.target.value;
						    value = value.replace( /\D+/, "");
						    if(value.length > 0){
						        if(value.length > 1 && value[0] == 0){
						            e.target.value = value.substring(1, value.length);
						            return;
						        }
						        //判断不要超过9位
						        if(value.length>9){
						            e.target.value=value.slice(0,9)
						        }else{
						            e.target.value = value;
						        }
						    }else{
						        e.target.value = 0;
						    };
						}
					</script>
	<?php
			}else{
				echo "获取商品价格出错!";
			}
		}else{
			$days=get_post_meta($postid, 'down_days', true);
			$down_repeat = get_post_meta($postid, 'down_repeat', true);
			$down_only_pay = get_post_meta($postid, 'down_only_pay', true);
			$memberDown=get_post_meta($postid, 'member_down',TRUE);
			$start_down2=get_post_meta($postid, 'start_down2',TRUE);

			if(!is_user_logged_in()){
				$erphp_url_front_login = wp_login_url();
				if(get_option('erphp_url_front_login')){
					$erphp_url_front_login = get_option('erphp_url_front_login');
				}
				$price=get_post_meta($postid, 'down_price', true);
				if(!$start_down2){
					$price = $price / get_option('ice_proportion_alipay');
				}
				$hidden=get_post_meta($postid, 'hidden_content', true);
				$okMoney = 0;
				if($price){
					$erphp_url_front_recharge = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-add-money-online.php';
					if(get_option('erphp_url_front_recharge')){
						$erphp_url_front_recharge = get_option('erphp_url_front_recharge');
					}
					?>
					<div class="erphpdown-table">
						<div class="item">
							<label>购买内容</label>
							<div class="tit"><?php echo get_post($postid)->post_title;?></div>
						</div>
						<div class="item">
							<label>购买结算</label>
							<div class="tit" style="text-align:right"><span>小计</span><?php echo sprintf("%.2f",$price);?> 元</div>
						</div>
						<div class="item">
							<div style="padding-top:20px;">
							<?php echo '<div class="erphp-justbuy">';?>
								<?php if(get_option('ice_weixin_mchid')){?> 
									<?php if(erphpdown_is_weixin() && get_option('ice_weixin_app')){?>
									<a href="<?php echo 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_post%3D'.$postid.'%26redirect_url='.urlencode(get_permalink($postid)).'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
									<?php }else{?>
									<a href="<?php echo constant("erphpdown")."payment/weixin.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
									<?php }?>
								<?php }?>
								<?php if((get_option('ice_ali_partner') || get_option('ice_ali_app_id')) && !erphpdown_is_weixin()){?> 
									<a href="<?php echo constant("erphpdown")."payment/alipay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
								<?php }?>
								<?php if(get_option('erphpdown_f2fpay_id') && !erphpdown_is_weixin() && !get_option('erphpdown_f2fpay_alipay')){?> 
									<a href="<?php echo constant("erphpdown")."payment/f2fpay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
								<?php }?>
								<?php if(get_option('erphpdown_payjs_appid')){?> 
									<?php if(!get_option('erphpdown_payjs_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_payjs_alipay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&type=alipay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?> 
								<?php }?>
								<?php if(get_option('erphpdown_xhpay_appid31')){?> 
									<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>   
								<?php }?>
								<?php if(get_option('erphpdown_xhpay_appid32')){?> 
									<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>  
								<?php }?>
								<?php if(get_option('erphpdown_paypy_key')){?> 
									<?php if(!get_option('erphpdown_paypy_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_paypy_alipay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
								<?php }?>
								<?php if(get_option('erphpdown_codepay_appid')){?> 
									<?php if(!get_option('erphpdown_codepay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=1"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
									<?php if(!get_option('erphpdown_codepay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=3"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>
									<?php if(!get_option('erphpdown_codepay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>    
								<?php }?>
								<?php if(get_option('erphpdown_epay_id')){?> 
									<?php if(!get_option('erphpdown_epay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=wxpay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_epay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
									<?php if(!get_option('erphpdown_epay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=qqpay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>
								<?php }?>
								<?php if(get_option('erphpdown_vpay_key')){?> 
									<?php if(!get_option('erphpdown_vpay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
									<?php if(!get_option('erphpdown_vpay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
								<?php }?>
								<?php if(get_option('erphpdown_stripe_pk') && plugin_check_stripe()){?> 
									<a href="<?php echo ERPHPDOWN_STRIPE_URL."/stripe.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-stripe erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-credit-card"></i> 信用卡</a>
								<?php }?>
								<?php if(get_option('ice_payapl_api_uid')){?> 
									<a href="<?php echo constant("erphpdown")."payment/paypal.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-pp erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-paypay"></i> Paypal</a>
								<?php }?>
							<?php echo '</div>';?>
							</div>
							<div class="login-tips"><a href="<?php echo $erphp_url_front_login;?>" target="_blank">登录</a>后购买可保存购买记录</div>
						</div>
					</div>
						<?php
				}else{
					echo "获取商品价格出错!";
				}
			}else{
				$user_info=wp_get_current_user();
				$userType=getUsreMemberType();

				if($index){
					$downInfo=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$postid."' and ice_index='".$index."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
				}else{
					$downInfo=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_user_id=".$user_info->ID ." and ice_post=".$postid." and ice_success=1 and (ice_index is null or ice_index = '') order by ice_time desc");
				}

				if($days > 0){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($downInfo->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$downInfo = null;
					}
				}

				if($downInfo && !$down_repeat){
					?>
					您已购买过，请关闭此窗口后刷新页面试试！
					<?php
				}else{

					if($index){
						$urls = get_post_meta($postid, 'down_urls', true);
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
	            				$price_old = $price;
								$hidden=get_post_meta($postid, 'hidden_content', true);
								if($price){
									$okMoney=erphpGetUserOkMoney();
									$vip=false;
									$memberDown=get_post_meta($postid, 'member_down',TRUE);
									$indexMemberDown = $memberDown;
	            					if($index_vip){
	            						$indexMemberDown = $index_vip;
	            					}

									if( $indexMemberDown==4 || $indexMemberDown==15 || $indexMemberDown==8 || $indexMemberDown==9 || (($indexMemberDown == 10 || $indexMemberDown == 11 || $indexMemberDown == 12) && !$userType))
									{
										echo "您无权购买此资源！";exit;
									}
									if($userType && ($indexMemberDown==2 || $indexMemberDown==13))
									{
										$vip=TRUE;
										$price=$price*0.5;
									}
									if($userType && ($indexMemberDown==5 || $indexMemberDown==14))
									{
										$vip=TRUE;
										$price=$price*0.8;
									}
									if($userType>=9 && $indexMemberDown==11)
									{
										$vip=TRUE;
										$price=$price*0.5;
									}
									if($userType>=9 && $indexMemberDown==12)
									{
										$vip=TRUE;
										$price=$price*0.8;
									}

									$erphp_url_front_recharge = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-add-money-online.php';
									if(get_option('erphp_url_front_recharge')){
										$erphp_url_front_recharge = get_option('erphp_url_front_recharge');
									}
									?>

									<div class="erphpdown-table">
										<div class="item">
											<label>购买内容</label>
											<div class="tit"><?php echo get_post($postid)->post_title;?> - <?php echo $index_name;?></div>
										</div>
										<div class="item">
											<label>购买结算</label>
											<div class="tit" style="text-align:right"><span>小计</span><?php echo sprintf("%.2f",$price);?><?php echo  $vip==TRUE?' <del>(原价 '.sprintf("%.2f",$price_old).')</del>' :'';?> <?php echo get_option('ice_name_alipay');?></div>
										</div>
										<?php if(!$down_only_pay){?>
										<div class="item" style="font-size:13px;color:#999;padding-left: 15px;">
											账户余额：<?php echo sprintf("%.2f",$okMoney);?> <?php echo get_option('ice_name_alipay');?>
										</div>
										<?php }?>
										<div class="item">
											<div style="padding-top:20px;">
											<?php if(sprintf("%.2f",$okMoney) >= sprintf("%.2f",$price) && !$down_only_pay) {?>
												<div style="margin-top: 30px;">
												<?php if($erphp_ajaxbuy){?>
												<a class="ss-button erphpdown-btn do-erphpdown-pay" data-href="<?php echo constant("erphpdown").'checkout.php?ajax=1&postid='.$postid;?>&index=<?php echo $index;?>" style="border:none;cursor: pointer;">使用余额支付</a>
												<?php }else{?>
												<a class="ss-button erphpdown-btn" href="<?php echo constant("erphpdown").'checkout.php?postid='.$postid; ?>&index=<?php echo $index;?>" target="_blank">使用余额支付</a>
												<?php }?>
												</div>
											<?php }else{

												if($erphp_justbuy){
													echo '<div class="erphp-justbuy">';
											?>
												<?php if(get_option('ice_weixin_mchid')){?> 
													<?php if(erphpdown_is_weixin() && get_option('ice_weixin_app')){?>
													<a href="<?php echo 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_post%3D'.$postid.'%26index='.$index.'%26redirect_url='.urlencode(get_permalink($postid)).'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
													<?php }else{?>
													<a href="<?php echo constant("erphpdown")."payment/weixin.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
													<?php }?>
												<?php }?>
												<?php if((get_option('ice_ali_partner') || get_option('ice_ali_app_id')) && !erphpdown_is_weixin()){?> 
													<a href="<?php echo constant("erphpdown")."payment/alipay.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
												<?php }?>
												<?php if(get_option('erphpdown_f2fpay_id') && !erphpdown_is_weixin() && !get_option('erphpdown_f2fpay_alipay')){?> 
													<a href="<?php echo constant("erphpdown")."payment/f2fpay.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
												<?php }?>
												<?php if(get_option('erphpdown_payjs_appid')){?> 
													<?php if(!get_option('erphpdown_payjs_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>
													<?php if(!get_option('erphpdown_payjs_alipay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&index=".$index."&type=alipay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>   
												<?php }?>
												<?php if(get_option('erphpdown_xhpay_appid31')){?> 
													<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&index=".$index."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>   
												<?php }?>
												<?php if(get_option('erphpdown_xhpay_appid32')){?> 
													<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>  
												<?php }?>
												<?php if(get_option('erphpdown_paypy_key')){?> 
													<?php if(!get_option('erphpdown_paypy_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
													<?php if(!get_option('erphpdown_paypy_alipay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&index=".$index."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
												<?php }?>
												<?php if(get_option('erphpdown_codepay_appid')){?> 
													<?php if(!get_option('erphpdown_codepay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&index=".$index."&type=1"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
													<?php if(!get_option('erphpdown_codepay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&index=".$index."&type=3"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>
													<?php if(!get_option('erphpdown_codepay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&index=".$index."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>    
												<?php }?>
												<?php if(get_option('erphpdown_epay_id')){?> 
													<?php if(!get_option('erphpdown_epay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&index=".$index."&type=wxpay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
													<?php if(!get_option('erphpdown_epay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&index=".$index."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
													<?php if(!get_option('erphpdown_epay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&index=".$index."&type=qqpay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>
												<?php }?>
												<?php if(get_option('erphpdown_vpay_key')){?> 
													<?php if(!get_option('erphpdown_vpay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
													<?php if(!get_option('erphpdown_vpay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&index=".$index."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
												<?php }?>
												<?php if(get_option('erphpdown_stripe_pk') && plugin_check_stripe()){?> 
													<a href="<?php echo ERPHPDOWN_STRIPE_URL."/stripe.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-stripe erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-credit-card"></i> 信用卡</a>
												<?php }?>
												<?php if(get_option('ice_payapl_api_uid')){?> 
													<a href="<?php echo constant("erphpdown")."payment/paypal.php?ice_post=".$postid."&index=".$index."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-pp erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-paypay"></i> Paypal</a>
												<?php }?>
											<?php	
													echo '</div>';
												}
												if(!$down_only_pay){
													echo "<div class='erphp-creditbuy'><a target=_blank class='erphpdown-btn erphpdown-jump2' href='".$erphp_url_front_recharge."'>充值后余额支付</a></div>";
												}
											}?>
											</div>
										</div>
									</div>
									<?php
								}else{
									echo "获取文章价格出错!";
								}
	            			}
	            		}
					}else{
						$price=get_post_meta($postid, 'down_price', true);
						$start_down2 = get_post_meta($postid, 'start_down2',TRUE);
						if($start_down2){
							$price = $price*get_option('ice_proportion_alipay');
						}
						$price_old = $price;
						$hidden=get_post_meta($postid, 'hidden_content', true);
						if($price){
							$okMoney=erphpGetUserOkMoney();
							$vip=false;
							$memberDown=get_post_meta($postid, 'member_down',TRUE);

							if( $memberDown==4 || $memberDown==15 || $memberDown==8 || $memberDown==9 || (($memberDown == 10 || $memberDown == 11 || $memberDown == 12) && !$userType))
							{
								echo "您无权购买此资源！";exit;
							}
							if($userType && ($memberDown==2 || $memberDown==13))
							{
								$vip=TRUE;
								$price=$price*0.5;
							}
							if($userType && ($memberDown==5 || $memberDown==14))
							{
								$vip=TRUE;
								$price=$price*0.8;
							}
							if($userType>=9 && $memberDown==11)
							{
								$vip=TRUE;
								$price=$price*0.5;
							}
							if($userType>=9 && $memberDown==12)
							{
								$vip=TRUE;
								$price=$price*0.8;
							}

							$erphp_url_front_recharge = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-add-money-online.php';
							if(get_option('erphp_url_front_recharge')){
								$erphp_url_front_recharge = get_option('erphp_url_front_recharge');
							}
							?>

							<div class="erphpdown-table">
								<div class="item">
									<label>购买内容</label>
									<div class="tit"><?php echo get_post($postid)->post_title;?></div>
								</div>
								<div class="item">
									<label>购买结算</label>
									<div class="tit" style="text-align:right"><span>小计</span><?php echo sprintf("%.2f",$price);?><?php echo $vip==TRUE?' <del>(原价 '.sprintf("%.2f",$price_old).')</del>' :'';?> <?php echo get_option('ice_name_alipay');?></div>
								</div>
								<?php if(!$down_only_pay){?>
								<div class="item" style="font-size:13px;color:#999;padding-left: 15px;">
									账户余额：<?php echo sprintf("%.2f",$okMoney);?> <?php echo get_option('ice_name_alipay');?>
								</div>
								<?php }?>
								<div class="item">
									<div style="padding-top:20px;">
									<?php if(sprintf("%.2f",$okMoney) >= sprintf("%.2f",$price) && !$down_only_pay) {?>
										<div style="margin-top: 30px;">
										<?php if($erphp_ajaxbuy){?>
										<a class="ss-button erphpdown-btn do-erphpdown-pay" data-href="<?php echo constant("erphpdown").'checkout.php?ajax=1&postid='.$postid;?>" style="border:none;cursor: pointer;">使用余额支付</a>
										<?php }else{?>
										<a class="ss-button erphpdown-btn" href="<?php echo constant("erphpdown").'checkout.php?postid='.$postid; ?>"
											target="_blank">使用余额支付</a>
										<?php }?>
										</div>
									<?php }else{

										if($erphp_justbuy){
											echo '<div class="erphp-justbuy">';
									?>
										<?php if(get_option('ice_weixin_mchid')){?> 
											<?php if(erphpdown_is_weixin() && get_option('ice_weixin_app')){?>
											<a href="<?php echo 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_post%3D'.$postid.'%26redirect_url='.urlencode(get_permalink($postid)).'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
											<?php }else{?>
											<a href="<?php echo constant("erphpdown")."payment/weixin.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
											<?php }?>
										<?php }?>
										<?php if((get_option('ice_ali_partner') || get_option('ice_ali_app_id')) && !erphpdown_is_weixin()){?> 
											<a href="<?php echo constant("erphpdown")."payment/alipay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
										<?php }?>
										<?php if(get_option('erphpdown_f2fpay_id') && !erphpdown_is_weixin() && !get_option('erphpdown_f2fpay_alipay')){?> 
											<a href="<?php echo constant("erphpdown")."payment/f2fpay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
										<?php }?>
										<?php if(get_option('erphpdown_payjs_appid')){?> 
											<?php if(!get_option('erphpdown_payjs_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
											<?php if(!get_option('erphpdown_payjs_alipay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_post=".$postid."&type=alipay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?> 
										<?php }?>
										<?php if(get_option('erphpdown_xhpay_appid31')){?> 
											<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>   
										<?php }?>
										<?php if(get_option('erphpdown_xhpay_appid32')){?> 
											<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>  
										<?php }?>
										<?php if(get_option('erphpdown_paypy_key')){?> 
											<?php if(!get_option('erphpdown_paypy_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
											<?php if(!get_option('erphpdown_paypy_alipay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_post=".$postid."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
										<?php }?>
										<?php if(get_option('erphpdown_codepay_appid')){?> 
											<?php if(!get_option('erphpdown_codepay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=1"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
											<?php if(!get_option('erphpdown_codepay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=3"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>
											<?php if(!get_option('erphpdown_codepay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>    
										<?php }?>
										<?php if(get_option('erphpdown_epay_id')){?> 
											<?php if(!get_option('erphpdown_epay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=wxpay&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>
											<?php if(!get_option('erphpdown_epay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=alipay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
											<?php if(!get_option('erphpdown_epay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_post=".$postid."&type=qqpay"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>
										<?php }?>
										<?php if(get_option('erphpdown_vpay_key')){?> 
											<?php if(!get_option('erphpdown_vpay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
											<?php if(!get_option('erphpdown_vpay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_post=".$postid."&type=2"."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
										<?php }?>
										<?php if(get_option('erphpdown_stripe_pk') && plugin_check_stripe()){?> 
											<a href="<?php echo ERPHPDOWN_STRIPE_URL."/stripe.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-stripe erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-credit-card"></i> 信用卡</a>
										<?php }?>
										<?php if(get_option('ice_payapl_api_uid')){?> 
											<a href="<?php echo constant("erphpdown")."payment/paypal.php?ice_post=".$postid."&redirect_url=".urlencode(get_permalink($postid));?>" class="pmt-pp erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-paypay"></i> Paypal</a>
										<?php }?>
									<?php	
											echo '</div>';
										}
										if(!$down_only_pay){
											echo "<div class='erphp-creditbuy'><a target=_blank class='erphpdown-btn erphpdown-jump2' href='".$erphp_url_front_recharge."'>充值后余额支付</a></div>";
										}
									}?>
									</div>
								</div>
							</div>
								<?php
						}else{
							echo "获取商品价格出错!";
						}
					}
				}
			}
		}
	}elseif($user_type){
		if($user_type >5 && $user_type < 11){
			$priceArr=array('6'=>'ciphp_day_price','7'=>'ciphp_month_price','8'=>'ciphp_quarter_price','9'=>'ciphp_year_price','10'=>'ciphp_life_price');
			$priceType=$priceArr[$user_type];
			$price=get_option($priceType);
			if($price){
				$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
				$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
				$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
				$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
				$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	?>
		<div class="erphpdown-table">
			<div class="item">
				<label>购买内容</label>
				<div class="tit"><?php 
				if($user_type == '6') echo $erphp_day_name;
				elseif($user_type == '7') echo $erphp_month_name;
				elseif($user_type == '8') echo $erphp_quarter_name;
				elseif($user_type == '9') echo $erphp_year_name;
				elseif($user_type == '10') echo $erphp_life_name;
			?></div>
			</div>
			<div class="item">
				<label>购买结算</label>
				<div class="tit" style="text-align:right"><span>小计</span><?php echo sprintf("%.2f",$price);?> <?php echo get_option('ice_name_alipay');?></div>
			</div>
			<div class="item">
				<div style="padding-top:20px;">
				<?php echo '<div class="erphp-justbuy">';?>
					<?php if(get_option('ice_weixin_mchid')){?> 
						<?php if(erphpdown_is_weixin() && get_option('ice_weixin_app')){?>
						<a href="<?php echo 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_type%3D'.$user_type.'%26redirect_url='.urlencode(home_url()).'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
						<?php }else{?>
						<a href="<?php echo constant("erphpdown")."payment/weixin.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>
						<?php }?>
					<?php }?>
					<?php if((get_option('ice_ali_partner') || get_option('ice_ali_app_id')) && !erphpdown_is_weixin()){?> 
						<a href="<?php echo constant("erphpdown")."payment/alipay.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
					<?php }?>
					<?php if(get_option('erphpdown_f2fpay_id') && !erphpdown_is_weixin() && !get_option('erphpdown_f2fpay_alipay')){?> 
						<a href="<?php echo constant("erphpdown")."payment/f2fpay.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>
					<?php }?>
					<?php if(get_option('erphpdown_payjs_appid')){?> 
						<?php if(!get_option('erphpdown_payjs_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
						<?php if(!get_option('erphpdown_payjs_alipay')){?><a href="<?php echo constant("erphpdown")."payment/payjs.php?ice_type=".$user_type."&type=alipay&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?> 
					<?php }?>
					<?php if(get_option('erphpdown_xhpay_appid31')){?> 
						<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_type=".$user_type."&type=2"."&redirect_url=".urlencode(home_url());?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a>   
					<?php }?>
					<?php if(get_option('erphpdown_xhpay_appid32')){?> 
						<a href="<?php echo constant("erphpdown")."payment/xhpay3.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a>  
					<?php }?>
					<?php if(get_option('erphpdown_paypy_key')){?> 
						<?php if(!get_option('erphpdown_paypy_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
						<?php if(!get_option('erphpdown_paypy_alipay')){?><a href="<?php echo constant("erphpdown")."payment/paypy.php?ice_type=".$user_type."&type=alipay"."&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
					<?php }?>
					<?php if(get_option('erphpdown_codepay_appid')){?> 
						<?php if(!get_option('erphpdown_codepay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_type=".$user_type."&type=1"."&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
						<?php if(!get_option('erphpdown_codepay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_type=".$user_type."&type=3"."&redirect_url=".urlencode(home_url());?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>
						<?php if(!get_option('erphpdown_codepay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/codepay.php?ice_type=".$user_type."&type=2"."&redirect_url=".urlencode(home_url());?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>    
					<?php }?>
					<?php if(get_option('erphpdown_epay_id')){?> 
						<?php if(!get_option('erphpdown_epay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_type=".$user_type."&type=wxpay&redirect_url=".urlencode(home_url());?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
						<?php if(!get_option('erphpdown_epay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_type=".$user_type."&type=alipay"."&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
						<?php if(!get_option('erphpdown_epay_qqpay')){?><a href="<?php echo constant("erphpdown")."payment/epay.php?ice_type=".$user_type."&type=qqpay"."&redirect_url=".urlencode(home_url());?>" class="pmt-qq erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-qqpay"></i> QQ钱包</a><?php }?>
					<?php }?>
					<?php if(get_option('erphpdown_vpay_key')){?> 
						<?php if(!get_option('erphpdown_vpay_wxpay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-wx erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-wxpay"></i> 微信支付</a><?php }?>  
						<?php if(!get_option('erphpdown_vpay_alipay')){?><a href="<?php echo constant("erphpdown")."payment/vpay.php?ice_type=".$user_type."&type=2"."&redirect_url=".urlencode(home_url());?>" class="pmt-ali erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-alipay"></i> 支付宝</a><?php }?>
					<?php }?>
					<?php if(get_option('erphpdown_stripe_pk') && plugin_check_stripe()){?> 
						<a href="<?php echo ERPHPDOWN_STRIPE_URL."/stripe.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-stripe erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-credit-card"></i> 信用卡</a>
					<?php }?>
					<?php if(get_option('ice_payapl_api_uid')){?> 
						<a href="<?php echo constant("erphpdown")."payment/paypal.php?ice_type=".$user_type."&redirect_url=".urlencode(home_url());?>" class="pmt-pp erphpdown-jump" target="_blank"><i class="erphp-iconfont erphp-icon-paypay"></i> Paypal</a>
					<?php }?>
				<?php echo '</div>';?>
				</div>
			</div>
		</div>
	<?php
			}else{
				echo "VIP价格错误";
			}
		}else{
			echo "VIP类型错误";
		}
	}else{
		echo "文章ID错误";
	}
	?>

	</div>
	<?php if($erphp_ajaxbuy){?>
	<script>
		$(".erphpdown-jump").click(function(){
			parent.layer.closeAll();
			window.parent.erphpdownOrderSuccess();
		});

		$(".erphpdown-jump2").click(function(){
			parent.layer.closeAll();
		});

		$(".do-erphpdown-pay").click(function(){
			var that = $(this);
			that.text("处理中...").attr("disabled","disabled");
			$.ajax({  
	            type: 'GET',  
	            url:  $(this).data("href"),  
	            dataType: 'json',
				data: {

				},
	            success: function(data){
	            	that.text("使用余额支付").removeAttr("disabled");  
	                if( data.error ){
	                    if( data.msg ){
	                        alert(data.msg);
	                    }
	                }else{
	                	if(data.jump == '2'){
	                		parent.location.reload();
	                	}else if(data.jump == '1'){
	                		parent.location.href=data.link;
	                	}else{
	                		parent.location.reload();
	                	}
	                }

	            }  

	        });
	        return false;
		});
	</script>
	<?php }?>
</body>
</html>
