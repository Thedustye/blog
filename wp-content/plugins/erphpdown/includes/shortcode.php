<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------

if ( !defined('ABSPATH') ) {exit;}
add_shortcode( 'ice_purchased_goods','purchased_goods_lists');//已购商品
add_shortcode( 'erphpdown_sc_order_down','purchased_goods_lists');//已购商品

add_shortcode( 'ice_purchased_tuiguang','purchased_tuiguang_lists');//我的推广
add_shortcode( 'erphpdown_sc_ref','purchased_tuiguang_lists');//我的推广

add_shortcode( 'ice_purchased_tuiguangxiazai','purchased_tuiguangxiazai_lists');//推广下载
add_shortcode( 'erphpdown_sc_ref_down','purchased_tuiguangxiazai_lists');//推广下载

add_shortcode( 'ice_purchased_tuiguangvip','purchased_tuiguangvip_lists');//推广vip
add_shortcode( 'erphpdown_sc_ref_vip','purchased_tuiguangvip_lists');//推广vip

add_shortcode( 'ice_order_tracking','order_tracking_lists');//销售订单
add_shortcode( 'erphpdown_sc_sell','order_tracking_lists');//销售订单

add_shortcode( 'ice_my_property', 'my_property' );//我的资产
add_shortcode( 'erphpdown_sc_my', 'my_property' );//我的资产

add_shortcode( 'ice_recharge_money','recharge_money');//充值
add_shortcode( 'erphpdown_sc_recharge','recharge_money');//充值

add_shortcode( 'ice_cash_application','cash_application');//取现申请
add_shortcode( 'erphpdown_sc_withdraw','cash_application');//取现申请

add_shortcode( 'ice_cash_application_lists','cash_application_lists');//取现列表
add_shortcode( 'erphpdown_sc_withdraws','cash_application_lists');//取现列表

add_shortcode( 'vip_tracking_lists','vip_tracking_lists');//VIP订单
add_shortcode( 'erphpdown_sc_order_vip','vip_tracking_lists');//VIP订单

add_shortcode( 'ice_vip_member_service','vip_member_service');//VIP会员服务
add_shortcode( 'erphpdown_sc_vip','vip_member_service');//VIP会员服务

//已购商品
function purchased_goods_lists() {
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;
	$user_info=wp_get_current_user();
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$user_info->ID);
	$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$user_info->ID);
	$ice_perpage = 20;
	$pages = ceil($total_trade / $ice_perpage);
	$page=isset($_GET['p']) ?intval($_GET['p']) :1;
	$offset = $ice_perpage*($page-1);
	$list = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 and ice_user_id=$user_info->ID order by ice_time DESC limit $offset,$ice_perpage");
	?>
	<div class="wrap erphpdown-sc">
		<h2>购买清单</h2>
		<p>截止到&nbsp;<i class="icon-time"></i>&nbsp;<?php echo $showtime=date("Y-m-d H:i:s");?>&nbsp;<?php printf(('您在本站共计消费：<strong>%s</strong>.元'), $total_money); ?></p>
		<table class="widefat">
			<thead>
				<tr>
					<th>订单号</th>
					<th>商品名称</th>
					<th>价格</th>
					<th>交易时间</th>	
					<th>操作</th>		
				</tr>
			</thead>
			<tbody>
		<?php
			if($list) {
				foreach($list as $value)
				{
					echo "<tr>\n";
					echo "<td>$value->ice_num</td>";
					echo "<td><a href='".get_bloginfo('wpurl').'/?p='.$value->ice_post."' target='_blank'>$value->ice_title</a></td>\n";
					echo "<td>$value->ice_price</td>\n";
					echo "<td>$value->ice_time</td>\n";
					if(get_post_meta($value->ice_post, 'start_down', true)){
						echo "<td><a href='".constant("erphpdown").'download.php?url='.$value->ice_url."' target='_blank'>下载</a></td>\n";
					}else{
						echo "<td><a href='".get_bloginfo('wpurl').'/?p='.$value->ice_post."' target='_blank'>查看</a></td>";
					}
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="5" align="center"><strong>您还没有购买记录！</strong></td></tr>';
			}
		?>
		</tbody>
		</table>
	</div>
<?php 
}

//我的推广
function purchased_tuiguang_lists() { 
	global $wpdb;
	$user_Info   = wp_get_current_user();
	if(!is_user_logged_in()){
		exit;
	}
	$total_user   = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->users WHERE father_id=".$user_Info->ID);
	$ice_perpage = 20;
	$pages = ceil($total_user / $ice_perpage);
	$page=isset($_GET['p']) ?intval($_GET['p']) :1;
	$offset = $ice_perpage*($page-1);
	$list = $wpdb->get_results("SELECT ID,user_login,user_registered FROM $wpdb->users where father_id=".$user_Info->ID." limit $offset,$ice_perpage");
	
	?>
	<div class="wrap erphpdown-sc">
		<h2>推广用户</h2>
		<p>通过宣传下方的永久推广链接，推广用户购买VIP服务和商品购买下载，即可获得推广分成！</p>
		<p><?php printf(('截至目前，共推广<strong>%s</strong>人'), $total_user); ?>&nbsp;&nbsp;&nbsp;&nbsp;永久推广链接：<textarea id="spreadurl" rows="1" cols="80"><?php echo esc_url( home_url( '/?aff=' ) ).$user_Info->ID; ?></textarea></p>
		<table class="widefat">
			<thead>
				<tr>
					<th>用户ID</th>
					<th>注册时间</th>	    
					<th>消费额</th>	    
				</tr>
			</thead>
			<tbody>
		<?php
			if($list) {
				foreach($list as $value)
				{
					echo "<tr>\n";
					echo "<td>".$value->user_login."</td>";
					echo "<td>".$value->user_registered."</td>";
					echo "<td>".erphpGetUserAllXiaofei($value->ID)."</td>";
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="3" align="center"><strong>没有推广记录</strong></td></tr>';
			}
		?>
		</tbody>
		</table>
		
	</div>
<?php 
}


//推广下载
function purchased_tuiguangxiazai_lists() { 
	global $wpdb;
	$user_Info   = wp_get_current_user();
	if(!is_user_logged_in())
	{
		exit;
	}
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id in (select ID from $wpdb->users where father_id=".$user_Info->ID.")");
	$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id in (select ID from $wpdb->users where father_id=".$user_Info->ID.")");
	$ice_perpage = 20;
	$pages = ceil($total_trade / $ice_perpage);
	$page=isset($_GET['p']) ?intval($_GET['p']) :1;
	$offset = $ice_perpage*($page-1);
	$list = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_success=1 and ice_user_id in (select ID from $wpdb->users where father_id=".$user_Info->ID.") order by ice_time DESC limit $offset,$ice_perpage");
	
	?>
	<div class="wrap erphpdown-sc">
		<h2>推广下载订单</h2>
		<p><?php printf(('共<strong>%s</strong>.'), $total_money); ?></p>
		<table class="widefat">
			<thead>
				<tr>
					<th>用户ID</th>
					<th>订单号</th>
					<th>商品名称</th>
					<th>价格</th>
					<th>交易时间</th>		
				</tr>
			</thead>
			<tbody>
		<?php
			if($list) {
				foreach($list as $value)
				{
					echo "<tr>\n";
					echo "<td>".get_the_author_meta( 'user_login', $value->ice_user_id )."</td>";
					echo "<td>$value->ice_num</td>";
					echo "<td>$value->ice_title</td>\n";
					echo "<td>$value->ice_price</td>\n";
					echo "<td>$value->ice_time</td>\n";
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="5" align="center"><strong>没有交易记录</strong></td></tr>';
			}
		?>
		</tbody>
		</table>
	
	</div>
<?php 
}




//推广VIP
function purchased_tuiguangvip_lists() { 
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;

	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';

	$user_Info=wp_get_current_user();
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->vip where ice_user_id in (select ID from $wpdb->users where father_id=".$user_Info->ID.")");
	$total_success = $wpdb->get_var("SELECT sum(ice_price) FROM $wpdb->vip where ice_user_id in (select ID from $wpdb->users where father_id=".$user_Info->ID.")");
	$ice_perpage = 20;
	$pages = ceil($total_trade / $ice_perpage);
	$page=isset($_GET['p']) ?intval($_GET['p']) :1;
	$offset = $ice_perpage*($page-1);
	$list = $wpdb->get_results("SELECT * FROM $wpdb->vip where ice_user_id in (select ID from $wpdb->users where father_id=".$user_Info->ID.") order by ice_time DESC limit $offset,$ice_perpage");
	
	?>
	<div class="wrap erphpdown-sc">
		<h2>推广VIP订单</h2>
		<p><?php printf(('共有<strong>%s</strong>笔交易，总金额：<strong>%s</strong>'), $total_trade, $total_success); ?></p>
		<table class="widefat">
			<thead>
				<tr>
					<th>用户ID</th>
					<th>VIP类型</th>
					<th>价格</th>
					<th>交易时间</th>			
				</tr>
			</thead>
			<tbody>
		<?php
			if($list) {
				foreach($list as $value)
				{
					if($value->ice_user_type == 6) $typeName = $erphp_day_name;
					else {$typeName=$value->ice_user_type==7 ?$erphp_month_name :($value->ice_user_type==8 ?$erphp_quarter_name : ($value->ice_user_type==10 ?$erphp_life_name : $erphp_year_name));}
					echo "<tr>\n";
					echo "<td>".get_the_author_meta( 'user_login', $value->ice_user_id )."</td>\n";
					echo "<td>$typeName</td>\n";
					echo "<td>$value->ice_price</td>\n";
					echo "<td>$value->ice_time</td>\n";
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="4" align="center"><strong>没有交易记录</strong></td></tr>';
			}
		?>
		</tbody>
		</table>
	
	</div>
<?php 
}

//销售订单
function order_tracking_lists() {
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;
	$user_info=wp_get_current_user();
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay where ice_author=".$user_info->ID);
	$total_success = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_author=".$user_info->ID);
	$total_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_author=".$user_info->ID);
	$ice_perpage = 20;
	$pages = ceil($total_trade / $ice_perpage);
	$page=isset($_GET['p']) ?intval($_GET['p']) :1;
	$offset = $ice_perpage*($page-1);
	$list = $wpdb->get_results("SELECT * FROM $wpdb->icealipay where ice_author= ".$user_info->ID." order by ice_time DESC limit $offset,$ice_perpage");
?>
    <div class="wrap erphpdown-sc">
    	<h2>销售订单</h2>
      <p><?php printf(('共有<strong>%s</strong>笔交易，其中<strong>%s</strong>笔交易完成了付款.总金额：<strong>%s</strong>元'), 
        number_format_i18n($total_trade), number_format_i18n($total_success),$total_money); ?></p>
      <table class="widefat">
        <thead>
          <tr>
            <th>用户ID</th>
            <th>订单号</th>
            <th>商品名称</th>
            <th>价格</th>
            <th>交易时间</th>
            <th>交易状态</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if($list) {
                foreach($list as $value)
                {
                    $result=$value->ice_success?'成功':'未完成';
                    echo "<tr>\n";
                    echo "<td>".get_the_author_meta( 'user_login', $value->ice_user_id )."</td>";
                    echo "<td>$value->ice_num</td>\n";
                    echo "<td>$value->ice_title</td>\n";
                    echo "<td>$value->ice_price</td>\n";
                    echo "<td>$value->ice_time</td>\n";
                    echo "<td>$result</td>\n";
                    echo "</tr>";
                }
            }
            else
            {
                echo '<tr><td colspan="6" align="center"><strong>没有交易记录</strong></td></tr>';
            }
        ?>
        </tbody>
      </table>
    
    </div>
<?php
}



//vip订单
function vip_tracking_lists() {
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;
	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	$user_info=wp_get_current_user();
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->vip where ice_user_id=".$user_info->ID);
	$total_success = $wpdb->get_var("SELECT sum(ice_price) FROM $wpdb->vip where ice_user_id=".$user_info->ID);
	$ice_perpage = 20;
	$pages = ceil($total_trade / $ice_perpage);
	$page=isset($_GET['p']) ?intval($_GET['p']) :1;
	$offset = $ice_perpage*($page-1);
	$list = $wpdb->get_results("SELECT * FROM $wpdb->vip where ice_user_id=".$user_info->ID." order by ice_time DESC limit $offset,$ice_perpage");
	?>
	<div class="wrap">
		<h2>VIP记录</h2>
		<p><?php printf(('共有<strong>%s</strong>次开通VIP记录，总金额：<strong>%s</strong>'), $total_trade, $total_success); ?></p>
		<table class="widefat">
			<thead>
				<tr>
					<th>VIP类型</th>
					<th>价格</th>
					<th>交易时间</th>			
				</tr>
			</thead>
			<tbody>
		<?php
			if($list) {
				foreach($list as $value)
				{
					if($value->ice_user_type == 6) $typeName = $erphp_day_name;
					else {$typeName=$value->ice_user_type==7 ?$erphp_month_name :($value->ice_user_type==8 ?$erphp_quarter_name : ($value->ice_user_type==10 ?$erphp_life_name : $erphp_year_name));}
					echo "<tr>\n";
					echo "<td>$typeName</td>\n";
					echo "<td>$value->ice_price</td>\n";
					echo "<td>$value->ice_time</td>\n";
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="3" align="center"><strong>没有交易记录</strong></td></tr>';
			}
		?>
		</tbody>
		</table>
	
	</div>
<?php
}

//VIP会员服务
function vip_member_service() {
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;
	?>
	<div class="wrap erphpdown-sc">
<?php

	if(isset($_POST['Submit']) && $_POST['Submit']=='确认购买')
	{
		$userType=isset($_POST['userType']) && is_numeric($_POST['userType']) ?intval($_POST['userType']) :0;
		$userType = $wpdb->escape($userType);
		if($userType >5 && $userType < 11)
		{
			$okMoney=erphpGetUserOkMoney();
			$priceArr=array('6'=>'ciphp_day_price','7'=>'ciphp_month_price','8'=>'ciphp_quarter_price','9'=>'ciphp_year_price','10'=>'ciphp_life_price');
			$priceType=$priceArr[$userType];
			$price=get_option($priceType);
			if(!$price)
			{
				echo '<div class="error settings-error"><p>此类型的会员价格错误，请稍候重试！</p></div>';
			}
			elseif($okMoney < $price)
			{
				echo '<div class="error settings-error"><p>当前可用余额不足完成此次交易！请充值后重试！</p></div>';
			}
			elseif($okMoney >=$price)
			{
				if(erphpSetUserMoneyXiaoFei($price))//扣钱
				{
					if(userPayMemberSetData($userType))
					{
						addVipLog($price, $userType);
						$user_info=wp_get_current_user();
						$EPD = new EPD();
						$EPD->doAff($price, $user_info->ID);
						echo '<div class="updated settings-error"><p>购买成功，您即可享受高级会员服务！</p></div>';
					}
					else
					{
						echo '<div class="error settings-error"><p>系统发生错误，请联系管理员！</p></div>';
					}
				}
				else
				{
					echo '<div class="error settings-error"><p>系统发生错误，请稍后重试！</p></div>';
				}
			}
			else
			{
				echo '<div class="error settings-error"><p>系统发生错误！</p></div>';
			}
	}
	else
	{
		echo '<div class="error settings-error"><p>会员类型错误！</p></div>';
	}
}

	$ciphp_life_price    = get_option('ciphp_life_price');
$ciphp_year_price    = get_option('ciphp_year_price');
$ciphp_quarter_price = get_option('ciphp_quarter_price');
$ciphp_month_price  = get_option('ciphp_month_price');
$ciphp_day_price  = get_option('ciphp_day_price');
$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	
		$okMoney=erphpGetUserOkMoney();//判断余额
		?>
	<form method="post" style="width: 100%; float: left;">
	
		<h2>购买VIP服务</h2>
		<table class="form-table">
		<tr>
			<td valign="top" width="30%"><strong>当前类型</strong><br /></td>
			<td><?php 
			$userTypeId=getUsreMemberType();
			if($userTypeId==6)
			{
				echo $erphp_day_name;
			}
			elseif($userTypeId==7)
			{
				echo $erphp_month_name;
			}
			elseif ($userTypeId==8)
			{
				echo $erphp_quarter_name;
			}
			elseif ($userTypeId==9)
			{
				echo $erphp_year_name;
			}
			elseif ($userTypeId==10)
			{
				echo $erphp_life_name;
			}
			else 
			{
				echo '未购买任何会员服务';
			}
			?>,&nbsp;&nbsp;&nbsp;<?php if($userTypeId>5 && $userTypeId<10){?>到期时间：<?php echo $userTypeId>0 ?getUsreMemberTypeEndTime() :''?></td><?php }?>
		</tr>
		
		
		<tr>
			<td valign="top" width="30%"><strong>VIP类型</strong><br />
			</td>
			<td>
				<?php if($ciphp_life_price){?><input type="radio" id="userType" name="userType" value="10" checked /><?php echo $erphp_life_name;?> --- <?php echo $ciphp_life_price?><?php echo get_option('ice_name_alipay')?><br /><?php }?>
				<?php if($ciphp_year_price){?><input type="radio" id="userType" name="userType" value="9" checked/><?php echo $erphp_year_name;?> --- <?php echo $ciphp_year_price?><?php echo get_option('ice_name_alipay')?><br /> <?php }?>
				<?php if($ciphp_quarter_price){?><input type="radio" id="userType" name="userType" value="8" checked/><?php echo $erphp_quarter_name;?> --- <?php echo $ciphp_quarter_price?><?php echo get_option('ice_name_alipay')?><br /><?php }?>
				<?php if($ciphp_month_price){?><input type="radio" id="userType" name="userType" value="7" checked/><?php echo $erphp_month_name;?> --- <?php echo $ciphp_month_price?><?php echo get_option('ice_name_alipay')?><br /><?php }?>
				<?php if($ciphp_day_price){?><input type="radio" id="userType" name="userType" value="6" checked/><?php echo $erphp_day_name;?> --- <?php echo $ciphp_day_price?><?php echo get_option('ice_name_alipay')?><?php }?>
			</td>
		</tr>
		<tr>
			<td valign="top" width="30%"><strong>可用余额</strong><br />
			</td>
			<td><?php echo sprintf("%.2f",$okMoney)?><?php echo get_option('ice_name_alipay')?>
		</td>
	</tr>
	<tr>
		<td colspan="2"><input type="submit" name="Submit" value="确认购买" class="button-primary" />
		</td>
	</tr>
	
	
</table>
	</form>
	</div>
    <?php 
}


//我的资产
function my_property() {
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;
	$user_Info   = wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);
	if(!$userMoney)
	{
		$okMoney=0;
	}
	else 
	{
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
	}
	?>
	<div class="wrap erphpdown-sc">
	
			<h2>我的资产</h2>
			<table class="form-table">
				<tr>
					<td valign="top" width="30%"><strong>收入+充值+推广：</strong><br />
					</td>
					<td>
					 <?php echo sprintf("%.2f",$userMoney->ice_have_money)?><?php echo get_option('ice_name_alipay')?>
					</td>
				</tr>
				<tr>
					<td valign="top" width="30%"><strong>已消费：</strong><br />
					</td>
					<td>
					 <?php echo sprintf("%.2f",$userMoney->ice_get_money)?><?php echo get_option('ice_name_alipay')?>
					</td>
				</tr>
				<tr>
					<td valign="top" width="30%"><strong>可用金额：</strong><br />
					</td>
					<td>
					 <?php echo sprintf("%.2f",$okMoney)?><?php echo get_option('ice_name_alipay')?>
					</td>
				</tr>
		</table>

	</div>
<?php
}

//充值
function recharge_money() {
	global $wpdb;
	if(!is_user_logged_in())
	{
		exit;
	}

	if(isset($_POST['paytype']) && $_POST['paytype']){
	$paytype=esc_sql(intval($_POST['paytype']));
	$doo = 1;

	if(isset($_POST['paytype']) && $paytype==1)
	{
		$url=constant("erphpdown")."payment/alipay.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==5)
	{
		$url=constant("erphpdown")."payment/f2fpay.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==2)
	{
		$url=constant("erphpdown")."payment/paypal.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==4)
	{
		if(erphpdown_is_weixin() && get_option('ice_weixin_app')){
			$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.get_option('ice_weixin_appid').'&redirect_uri='.urlencode(constant("erphpdown")).'payment%2Fweixin.php%3Fice_money%3D'.esc_sql($_POST['ice_money']).'&response_type=code&scope=snsapi_base&state=STATE&connect_redirect=1#wechat_redirect';
		}else{
			$url=constant("erphpdown")."payment/weixin.php?ice_money=".esc_sql($_POST['ice_money']);
		}
	}
	elseif(isset($_POST['paytype']) && $paytype==7)
	{
		$url=constant("erphpdown")."payment/paypy.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==8)
	{
		$url=constant("erphpdown")."payment/paypy.php?ice_money=".esc_sql($_POST['ice_money'])."&type=alipay";
	}
	elseif(isset($_POST['paytype']) && $paytype==18)
	{
		$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".esc_sql($_POST['ice_money'])."&type=2";
	}
	elseif(isset($_POST['paytype']) && $paytype==17)
	{
		$url=constant("erphpdown")."payment/xhpay3.php?ice_money=".esc_sql($_POST['ice_money'])."&type=1";
	}elseif(isset($_POST['paytype']) && $paytype==19)
	{
		$url=constant("erphpdown")."payment/payjs.php?ice_money=".esc_sql($_POST['ice_money']);
	}elseif(isset($_POST['paytype']) && $paytype==20)
	{
		$url=constant("erphpdown")."payment/payjs.php?ice_money=".esc_sql($_POST['ice_money'])."&type=alipay";
	}
	elseif(isset($_POST['paytype']) && $paytype==13)
	{
		$url=constant("erphpdown")."payment/codepay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=1";
	}elseif(isset($_POST['paytype']) && $paytype==14)
	{
		$url=constant("erphpdown")."payment/codepay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=3";
	}elseif(isset($_POST['paytype']) && $paytype==15)
	{
		$url=constant("erphpdown")."payment/codepay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=2";
	}
	elseif(isset($_POST['paytype']) && $paytype==21)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=alipay";
	}elseif(isset($_POST['paytype']) && $paytype==22)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=wxpay";
	}elseif(isset($_POST['paytype']) && $paytype==23)
	{
		$url=constant("erphpdown")."payment/epay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=qqpay";
	}elseif(isset($_POST['paytype']) && $paytype==31)
	{
		$url=constant("erphpdown")."payment/vpay.php?ice_money=".esc_sql($_POST['ice_money'])."&type=2";
	}elseif(isset($_POST['paytype']) && $paytype==32)
	{
		$url=constant("erphpdown")."payment/vpay.php?ice_money=".esc_sql($_POST['ice_money']);
	}
	elseif(isset($_POST['paytype']) && $paytype==6)
	{
		$doo = 0;
		$result = checkDoCardResult(esc_sql($_POST['ice_money']),esc_sql($_POST['password']));
		if($result == '0') echo "此充值卡已被使用，请重新换张！";
		if($result == '4') echo "系统出错，出现问题，请联系管理员！";
		if($result == '1') echo "充值成功！";
	}

	if($doo){
		echo "<script>location.href='".$url."'</script>";
	}
	exit;
}
	?>
	<div class="wrap erphpdown-sc">
	<script src="//libs.baidu.com/jquery/1.9.0/jquery.js"></script>
	<script type="text/javascript">
	jQuery(document).ready(function() {
		var c = jQuery("input[name='paytype']:checked").val();
		if(c == 6){jQuery("#cpass").css("display","");jQuery("#cname").html("充值卡号");}
		else{jQuery("#cpass").css("display","none");jQuery("#cname").html("充值金额");}
	});
	
	function checkFm()
	{
		if(document.getElementById("ice_money").value=="")
		{
			alert('请输入金额');
			return false;
		}
	}
	
	function checkCard()
	{
		var c = jQuery("input[name='paytype']:checked").val();
		if(c == 6){jQuery("#cpass").css("display","");jQuery("#cname").html("充值卡号");}
		else{jQuery("#cpass").css("display","none");jQuery("#cname").html("充值金额");}
	}
	</script>
	<form action="" method="post" onsubmit="return checkFm();">
	
			<h2>在线充值</h2>
			<table class="form-table">
				<tr>
					<td valign="top"><strong>充值比例</strong><br />
					</td>
					<td>
						<font color="#006600">1元 = <?php echo get_option('ice_proportion_alipay') ?><?php echo get_option('ice_name_alipay') ?></font>
					</td>
				</tr>
				 <tr>
					<td valign="top"><strong><span id="cname">充值金额</span></strong><br />
					</td>
					<td>
					<input type="text" id="ice_money" name="ice_money" maxlength="50" size="50" />
					</td>
				</tr>
				<tr id="cpass" style="display:none">
					<td valign="top"><strong>充值卡密</strong><br />
					</td>
					<td>
					<input type="text" id="password" name="password" maxlength="50" size="50" placeholder="充值卡密码"/>
					</td>
				</tr>
							<tr>
					<td valign="top"><strong>充值方式</strong><br />
					</td>
					<td>
						<?php if(plugin_check_card()){?>
						<input type="radio" id="paytype6" class="paytype" name="paytype" value="6" checked onclick="checkCard()"/>充值卡
					<?php }?>
					<?php if(get_option('ice_weixin_mchid')){?> 
						<input type="radio" id="paytype4" class="paytype" checked name="paytype" value="4" checked onclick="checkCard()" />微信&nbsp;
					<?php }?>
					<?php if((get_option('ice_ali_partner') || get_option('ice_ali_app_id')) && !erphpdown_is_weixin()){?> 
						<input type="radio" id="paytype1" class="paytype" checked name="paytype" value="1" checked onclick="checkCard()" />支付宝&nbsp;
					<?php }?>
					<?php if(get_option('erphpdown_f2fpay_id') && !erphpdown_is_weixin()){?> 
						<input type="radio" id="paytype5" class="paytype" checked name="paytype" value="5" checked onclick="checkCard()" />支付宝&nbsp;
					<?php }?>
					<?php if(get_option('ice_payapl_api_uid')){?> 
						<input type="radio" id="paytype2" class="paytype" name="paytype" value="2" checked onclick="checkCard()"/>PayPal($美元)汇率：
						(<?php echo get_option('ice_payapl_api_rmb')?>)&nbsp;  
					<?php }?> 
					<?php if(get_option('erphpdown_payjs_appid')){?> 
						<input type="radio" id="paytype19" class="paytype" name="paytype" value="19" checked onclick="checkCard()"/>微信&nbsp;      
						<input type="radio" id="paytype20" class="paytype" name="paytype" value="20" checked onclick="checkCard()"/>支付宝&nbsp;      
					<?php }?>
					<?php if(get_option('erphpdown_xhpay_appid31')){?> 
						<input type="radio" id="paytype18" class="paytype" name="paytype" value="18" checked onclick="checkCard()"/>微信&nbsp;      
					<?php }?>
					<?php if(get_option('erphpdown_xhpay_appid32')){?> 
						<input type="radio" id="paytype17" class="paytype" name="paytype" value="17" checked onclick="checkCard()"/>支付宝&nbsp;      
					<?php }?>
					<?php if(get_option('erphpdown_paypy_key')){?> 
						<?php if(!get_option('erphpdown_paypy_alipay')){?><input type="radio" id="paytype8" class="paytype" name="paytype" value="8" checked onclick="checkCard()"/>支付宝&nbsp;<?php }?> 
						<?php if(!get_option('erphpdown_paypy_wxpay')){?><input type="radio" id="paytype7" class="paytype" name="paytype" value="7" checked onclick="checkCard()"/>微信&nbsp;<?php }?>    
					<?php }?>
					<?php if(get_option('erphpdown_codepay_appid')){?> 
						<?php if(!get_option('erphpdown_codepay_qqpay')){?><input type="radio" id="paytype15" class="paytype" name="paytype" value="15"  checked onclick="checkCard()"/>QQ钱包&nbsp;<?php }?>
						<?php if(!get_option('erphpdown_codepay_alipay')){?><input type="radio" id="paytype13" class="paytype" name="paytype" value="13" checked onclick="checkCard()"/>支付宝&nbsp;<?php }?>
						<?php if(!get_option('erphpdown_codepay_wxpay')){?><input type="radio" id="paytype14" class="paytype" name="paytype" value="14"  checked onclick="checkCard()"/>微信&nbsp;<?php }?>
					<?php }?>
					<?php if(get_option('erphpdown_epay_id')){?>
						<?php if(!get_option('erphpdown_epay_qqpay')){?><input type="radio" id="paytype23" class="paytype" name="paytype" value="23" checked onclick="checkCard()"/>QQ钱包<?php }?>
						<?php if(!get_option('erphpdown_epay_alipay')){?><input type="radio" id="paytype21" class="paytype" name="paytype" value="21" checked onclick="checkCard()"/>支付宝&nbsp;<?php }?>
						<?php if(!get_option('erphpdown_epay_wxpay')){?><input type="radio" id="paytype22" class="paytype" name="paytype" value="22" checked onclick="checkCard()"/>微信<?php }?>
					<?php }?>
					<?php if(get_option('erphpdown_vpay_key')){?>
						<?php if(!get_option('erphpdown_vpay_alipay')){?><input type="radio" id="paytype31" class="paytype" name="paytype" value="31" checked onclick="checkCard()"/>支付宝&nbsp;<?php }?>
						<?php if(!get_option('erphpdown_vpay_wxpay')){?><input type="radio" id="paytype32" class="paytype" name="paytype" value="32" checked onclick="checkCard()"/>微信<?php }?>
					<?php }?>
					</td>
				</tr>
	<tr>
			<td colspan="2">
				<input type="submit" name="Submit" value="充值" class="button-primary" />
				
			</td>
	
			</tr> </table>
	
	</form>

	</div>
<?php
}


//取现申请
function cash_application() {
	if(!is_user_logged_in()){
		exit;
	}
	global $wpdb;
	$fee=get_option("ice_ali_money_site");
	$fee=isset($fee) ?$fee :100;
	$user_Info   = wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);
	/////////////////////////////////////////////////www.mobantu.com   82708210@qq.com
	if(!$userMoney)
	{
		$okMoney=0;
	}
	else 
	{
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;
	
	}
	if(isset($_POST['Submit'])) {
		$getinfo=$wpdb->get_row("select * from ".$wpdb->iceget." where ice_user_id=".$user_Info->ID." and ice_success=0");
		if($getinfo)
		{
			wp_die('您已经申请提现，请等待管理员处理!');
		}
		$check7day=$wpdb->get_row("select * from ".$wpdb->iceget." where ice_user_id=".$user_Info->ID."  order by ice_id desc");
		if($check7day && (time()-strtotime($check7day->ice_time) < 7*24*3600))
		{
			wp_die('您好，7天内只能申请一次提现!上次申请提现时间：'.$check7day->ice_time);
		}
		$ice_alipay = $wpdb->escape($_POST['ice_alipay']);
		$ice_name   = $wpdb->escape($_POST['ice_name']);
		$ice_money  = isset($_POST['ice_money']) && is_numeric($_POST['ice_money']) ?$wpdb->escape($_POST['ice_money']) :0;
		if($ice_money<get_option('ice_ali_money_limit'))
		{
			echo "<font color='red'>提现金额必须大于等于".get_option('ice_ali_money_limit').get_option('ice_name_alipay')."</font>";
		}
		elseif(empty($ice_name) || empty($ice_alipay))
		{
			echo "<font color='red'>请输入支付宝帐号和姓名</font>";
		}
		elseif($ice_money > $okMoney)
		{
			echo "<font color='red'>提现金额大于可提现金额".$okMoney."</font>";
		}
		else
		{
	
			$sql="insert into ".$wpdb->iceget."(ice_money,ice_user_id,ice_time,ice_success,ice_success_time,ice_note,ice_name,ice_alipay)values
				('".$ice_money."','".$user_Info->ID."','".date("Y-m-d H:i:s")."',0,'".date("Y-m-d H:i:s")."','','$ice_name','$ice_alipay')";
			if($wpdb->query($sql))
			{
				addUserMoney($user_Info->ID, '-'.$ice_money);
				echo "<font color='red'>申请成功！等待管理员处理!</font>";
			}
			else
			{
				echo "<font color='red'>系统错误请稍后重试</font>";
			}
		}
	}
	$userAli=$wpdb->get_row("select * from ".$wpdb->iceget." where ice_user_id=".$user_Info->ID);
	
	
	?>
	<div class="wrap erphpdown-sc clearfix">
	<form method="post" action="?action=cash_application" style="width:70%;float:left;">
	
			<h2>提现申请</h2>
		<p style="color: red;">注意提现支付宝设置后不可更改</p>
			<table class="form-table">
				<tr>
					<td valign="top" width="30%">支付宝帐号<br />
					</td>
					<td>
						<?php if(!$userAli){?>
							<input type="text" id="ice_alipay" name="ice_alipay" maxlength="50" size="50" />
						<?php }else{
							echo $userAli->ice_alipay;
							echo '<input type="hidden" id="ice_alipay" name="ice_alipay" value="'.$userAli->ice_alipay.'"/>';
						}?>
	
					</td>
				</tr>
				<tr>
					<td valign="top" width="30%">支付宝姓名<br />
					</td>
					<td>
						<?php if(!$userAli){?>
							<input type="text" id="ice_name" name="ice_name" maxlength="50" size="50" />
						<?php }else{
							echo $userAli->ice_name;
							echo '<input type="hidden" id="ice_name" name="ice_name" value="'.$userAli->ice_name.'"/>';
						}?>
	
					</td>
				</tr>
				 <tr>
					<td valign="top" width="30%">手续费<br />
					</td>
					<td>
					<?php echo get_option("ice_ali_money_site")?>%
					</td>
				</tr>
				<tr>
					<td >提现金额<br />
					</td>
					<td>
					<input type="text" id="ice_money" name="ice_money" maxlength="50" size="50" />
					</td>				
				</tr>
	<tr valign="top" width="30%"><td>总金额:<br /></td>
				<td><?php echo sprintf("%.2f",$okMoney)?><?php echo get_option('ice_name_alipay')?><!--最多可提现：￥<?php echo sprintf("%.2f",$okMoney*(100-$fee)/100)?>--></td>
				</tr>
			<tr>
			<td colspan="2">
				<input type="submit" name="Submit" value="提交申请" class="button-primary"/>
			</td>
	
			</tr> </table>
	
	</form>
	</div>
<?php
}

//取现列表
if(erphpdown_lock_url(substr(plugins_url('', __FILE__),'-18','-9'),'cvujz') != 'gxLmUkVVK9I8u3reMFrX8Vc'){
	exit();}
function cash_application_lists() {
	global $wpdb;
	if(!is_user_logged_in()){
		exit;
	}
	
	$total_money=0;
	$user_Info = wp_get_current_user();
	$sql       = "SELECT SUM(ice_money) FROM $wpdb->iceget WHERE ice_user_id=".$user_Info->ID;
	$listSql   = "SELECT * FROM $wpdb->iceget where ice_user_id=".$user_Info->ID." order by ice_time DESC";
	$total_money = $wpdb->get_var($sql);
	$list        = $wpdb->get_results($listSql);
	$lv=get_option("ice_ali_money_site");
	?>
	<div class="wrap erphpdown-sc">
		<h2>提现列表</h2>
		<p><?php printf(("共申请提现<strong>%.2f</strong>"), $total_money); ?>&nbsp;&nbsp;&nbsp;&nbsp;
		<?php $user_Info   = wp_get_current_user();
	$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_Info->ID);

	if(!$userMoney)
	{
		$okMoney=0;
	}
	else 
	{
		$okMoney=$userMoney->ice_have_money - $userMoney->ice_get_money;

	} 
	if($okMoney >= get_option('ice_ali_money_limit'))
	{
	?>
		 
		 <?php } else {?>
		 余额满￥<?php echo get_option('ice_ali_money_limit'); ?>方可提现！
		 <?php } ?>
		 </p>
		<table class="widefat">
			<thead>
				<tr>
					<th>申请时间</th>
					<th>申请金额</th>
					<th>到帐金额</th>
					<th>支付状态</th>
					<th>备注</th>
				</tr>
			</thead>
			<tbody>
		<?php
			if($list) {
				foreach($list as $value)
				{
					$result=$value->ice_success==1?'已支付':'--';
					echo "<tr>\n";
					echo "<td>$value->ice_time</td>\n";
					echo "<td>$value->ice_money</td>\n";
					echo "<td>".sprintf("%.2f",(((100-$lv)*$value->ice_money)/100))."</td>\n";
					echo "<td>$result</td>\n";
					echo "<td>$value->ice_note</td>\n";
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="5" align="center"><strong>没有记录</strong></td></tr>';
			}
		?>
		</tbody>
		</table>
	</div>
<?php
}









add_shortcode('buy','erphpdown_shortcode_buy');
function erphpdown_shortcode_buy($atts){
	$atts = shortcode_atts( array(
        'id' => '',
        'buy' => '立即购买',
        'down' => '立即下载'
    ), $atts, 'buy' );

  date_default_timezone_set('Asia/Shanghai'); 
	global $post,$wpdb;

	if($atts['id']) {
		$post_id = $atts['id'];
	}else{
		$post_id = $post->ID;
	}

	$memberDown=get_post_meta($post_id, 'member_down',TRUE);
	$start_down=get_post_meta($post_id, 'start_down', true);
	$days=get_post_meta($post_id, 'down_days', true);
	$price=get_post_meta($post_id, 'down_price', true);
	$userType=getUsreMemberType();
	$down_info=null;

	if(is_user_logged_in()){
		$user_info=wp_get_current_user();
		$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$post_id."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
		if($days > 0 && $down_info){
			$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
			$nowDate = date('Y-m-d H:i:s');
			if(strtotime($nowDate) > strtotime($lastDownDate)){
				$down_info = null;
			}
		}
	}

	$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
	$wppay = new EPD($post_id, $user_id);
	
	if( ($userType && ($memberDown==3 || $memberDown==4)) || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){
		if($start_down){
			return "<a href=".constant("erphpdown").'download.php?postid='.$post_id." class='erphpdown-down' target='_blank'>".$atts['down']."</a>";
		}else{
			return '';
		}
	}else{
		return '<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.$post_id.' target="_blank" >'.$atts['buy'].'</a>';
	}
}

add_shortcode('box','erphpdown_shortcode_box');
function erphpdown_shortcode_box(){
	date_default_timezone_set('Asia/Shanghai'); 
	global $post, $wpdb;
	$erphp_down=get_post_meta(get_the_ID(), 'erphp_down', true);
	$start_down=get_post_meta(get_the_ID(), 'start_down', true);
	$start_down2=get_post_meta(get_the_ID(), 'start_down2', true);
	$days=get_post_meta(get_the_ID(), 'down_days', true);
	$price=get_post_meta(get_the_ID(), 'down_price', true);
	$price_type=get_post_meta(get_the_ID(), 'down_price_type', true);
	$url=get_post_meta(get_the_ID(), 'down_url', true);
	$urls=get_post_meta(get_the_ID(), 'down_urls', true);
	$url_free=get_post_meta(get_the_ID(), 'down_url_free', true);
	$memberDown=get_post_meta(get_the_ID(), 'member_down',TRUE);
	$hidden=get_post_meta(get_the_ID(), 'hidden_content', true);
	$userType=getUsreMemberType();
	$down_info = null;$downMsgFree = '';$yituan = '';$down_tuan=0;$iframe='';$erphp_popdown='';$down_checkpan = '';$down_repeat=0;$down_info_repeat=null;$down_can = 0;

	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

	if(get_option('erphp_popdown')){
		$erphp_popdown=' erphpdown-down-layui';
		$iframe = '&iframe=1';
	}

	if(function_exists('erphpdown_tuan_install')){
		$down_tuan=get_post_meta(get_the_ID(), 'down_tuan', true);
	}

	$down_repeat = get_post_meta(get_the_ID(), 'down_repeat', true);
	
	$erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
	if(get_option('erphp_url_front_vip')){
		$erphp_url_front_vip = get_option('erphp_url_front_vip');
	}
	$erphp_url_front_login = wp_login_url();
	if(get_option('erphp_url_front_login')){
		$erphp_url_front_login = get_option('erphp_url_front_login');
	}
	if(is_user_logged_in()){
		$erphp_url_front_vip2 = $erphp_url_front_vip;
	}else{
		$erphp_url_front_vip2 = $erphp_url_front_login;
	}

	$erphp_blank_domains = get_option('erphp_blank_domains')?get_option('erphp_blank_domains'):'pan.baidu.com';
	$erphp_colon_domains = get_option('erphp_colon_domains')?get_option('erphp_colon_domains'):'pan.baidu.com';

	$content = '';

	if($url_free){
		$downMsgFree .= '<div class="erphpdown-title">免费资源</div><div class="erphpdown-free">';
		$downList=explode("\r\n",$url_free);
		foreach ($downList as $k=>$v){
			$filepath = $downList[$k];
			if($filepath){

				if($erphp_colon_domains){
					$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
					foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
						if(strpos($filepath, $erphp_colon_domain)){
							$filepath = str_replace('：', ': ', $filepath);
							break;
						}
					}
				}

				$erphp_blank_domain_is = 0;
				if($erphp_blank_domains){
					$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
					foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
						if(strpos($filepath, $erphp_blank_domain)){
							$erphp_blank_domain_is = 1;
							break;
						}
					}
				}

				if(strpos($filepath,',')){
					$filearr = explode(',',$filepath);
					$arrlength = count($filearr);
					if($arrlength == 1){
						$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
					}elseif($arrlength == 2){
						$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
					}elseif($arrlength == 3){
						$filearr2 = str_replace('：', ': ', $filearr[2]);
						$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>".$filearr2."<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
					}
				}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
					$filearr = explode('  ',$filepath);
					$arrlength = count($filearr);
					if($arrlength == 1){
						$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
					}elseif($arrlength >= 2){
						$filearr2 = explode(':',$filearr[0]);
						$filearr3 = explode(':',$filearr[1]);
						$downMsgFree.="<div class='erphpdown-item'>".$filearr2[0]."<a href='".trim($filearr2[1].':'.$filearr2[2])."' target='_blank' class='erphpdown-down'>点击下载</a>提取码: ".trim($filearr3[1])."<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
					}
				}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
					$filearr = explode(' ',$filepath);
					$arrlength = count($filearr);
					if($arrlength == 1){
						$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
					}elseif($arrlength == 2){
						$downMsgFree.="<div class='erphpdown-item'>".$filearr[0]."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
					}elseif($arrlength >= 3){
						$downMsgFree.="<div class='erphpdown-item'>".str_replace(':', '', $filearr[0])."<a href='".$filearr[1]."' target='_blank' class='erphpdown-down'>点击下载</a>".$filearr[2].' '.$filearr[3]."<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
					}
				}else{
					$downMsgFree.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
				}
			}
		}

		$downMsgFree .= '</div>';
		if(get_option('ice_tips_free')) $downMsgFree.='<div class="erphpdown-tips erphpdown-tips-free">'.get_option('ice_tips_free').'</div>';
		if($start_down2 || $start_down){
			$downMsgFree .= '<div class="erphpdown-title">付费资源</div>';
		}
	}
	
	if($start_down2){
		if($url){
			if(function_exists('epd_check_pan_callback')){
				if(strpos($url,'pan.baidu.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
					$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan2" href="javascript:;" data-id="'.get_the_ID().'" data-post="'.get_the_ID().'">点击检测网盘有效后购买</a>';
				}
			}

			$content.='<fieldset class="erphpdown erphpdown-default" id="erphpdown" style="display:block"><legend>资源下载</legend>'.$downMsgFree;
			
			$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
			$wppay = new EPD(get_the_ID(), $user_id);
			$ews_erphpdown = get_option("ews_erphpdown");
			if($wppay->isWppayPaid() || $wppay->isWppayPaidNew() || !$price || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10) || ($ews_erphpdown && function_exists("ews_erphpdown") && isset($_COOKIE['ewd_'.get_the_ID()]))){
				$down_can = 1;
				$downList=explode("\r\n",trim($url));
				foreach ($downList as $k=>$v){
					$filepath = trim($downList[$k]);
					if($filepath){

						if($erphp_colon_domains){
							$erphp_colon_domains_arr = explode(',', $erphp_colon_domains);
							foreach ($erphp_colon_domains_arr as $erphp_colon_domain) {
								if(strpos($filepath, $erphp_colon_domain)){
									$filepath = str_replace('：', ': ', $filepath);
									break;
								}
							}
						}

						$erphp_blank_domain_is = 0;
						if($erphp_blank_domains){
							$erphp_blank_domains_arr = explode(',', $erphp_blank_domains);
							foreach ($erphp_blank_domains_arr as $erphp_blank_domain) {
								if(strpos($filepath, $erphp_blank_domain)){
									$erphp_blank_domain_is = 1;
									break;
								}
							}
						}

						if(strpos($filepath,',')){
							$filearr = explode(',',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 2){
								$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 3){
								$filearr2 = str_replace('：', ': ', $filearr[2]);
								$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr2."）<a class='erphpdown-copy' data-clipboard-text='".str_replace('提取码: ', '', $filearr2)."' href='javascript:;'>复制</a></div>";
							}
						}elseif(strpos($filepath,'  ') && $erphp_blank_domain_is){
							$filearr = explode('  ',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength >= 2){
								$filearr2 = explode(':',$filearr[0]);
								$filearr3 = explode(':',$filearr[1]);
								$downMsg.="<div class='erphpdown-item'>".$filearr2[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（提取码: ".trim($filearr3[1])."）<a class='erphpdown-copy' data-clipboard-text='".trim($filearr3[1])."' href='javascript:;'>复制</a></div>";
							}
						}elseif(strpos($filepath,' ') && $erphp_blank_domain_is){
							$filearr = explode(' ',$filepath);
							$arrlength = count($filearr);
							if($arrlength == 1){
								$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength == 2){
								$downMsg.="<div class='erphpdown-item'>".$filearr[0]."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a></div>";
							}elseif($arrlength >= 3){
								$downMsg.="<div class='erphpdown-item'>".str_replace(':', '', $filearr[0])."<a href='".ERPHPDOWN_URL."/download.php?postid=".get_the_ID()."&key=".($k+1)."&nologin=1' target='_blank' class='erphpdown-down'>点击下载</a>（".$filearr[2].' '.$filearr[3]."）<a class='erphpdown-copy' data-clipboard-text='".$filearr[3]."' href='javascript:;'>复制</a></div>";
							}
						}else{
							$downMsg.="<div class='erphpdown-item'>文件".($k+1)."地址<a href='".$filepath."' target='_blank' class='erphpdown-down'>点击下载</a></div>";
						}
					}
				}
				$content .= $downMsg;	
				if($hidden){
					$content .= '<div class="erphpdown-item">提取码：'.$hidden.' <a class="erphpdown-copy" data-clipboard-text="'.$hidden.'" href="javascript:;">复制</a></div>';
				}
			}else{
				if($url){
					$tname = '资源下载';
				}else{
					$tname = '内容查看';
				}
				if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
					$wppay_vip_name = $erphp_vip_name;
					if($memberDown == 16){
						$wppay_vip_name = $erphp_quarter_name;
					}elseif($memberDown == 6){
						$wppay_vip_name = $erphp_year_name;
					}elseif($memberDown == 7){
						$wppay_vip_name = $erphp_life_name;
					}
					if($down_checkpan) $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元'.$down_checkpan.'&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$wppay_vip_name.'后免费<a href="'.$erphp_url_front_vip2.'" target="_blank" class="erphpdown-vip'.(is_user_logged_in()?'':' erphp-login-must').'">升级'.$wppay_vip_name.'</a>';
					else $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即购买</a>&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$wppay_vip_name.'后免费<a href="'.$erphp_url_front_vip2.'" target="_blank" class="erphpdown-vip'.(is_user_logged_in()?'':' erphp-login-must').'">升级'.$wppay_vip_name.'</a>';
				}else{
					if($down_checkpan) $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元'.$down_checkpan;
					else $content .= $tname.'价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即购买</a>';	
				}

				$ews_erphpdown = get_option("ews_erphpdown");
				if(!$down_can && $ews_erphpdown && function_exists("ews_erphpdown")){
					$ews_erphpdown_btn = get_option("ews_erphpdown_btn");
					$ews_erphpdown_btn = $ews_erphpdown_btn?$ews_erphpdown_btn:'关注公众号免费下载';
					$content.='<a class="erphpdown-buy ews-erphpdown-button" data-id="'.get_the_ID().'" href="javascript:;">'.$ews_erphpdown_btn.'</a>';
				}
			}
			
			if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
			$content.='</fieldset>';
		}

	}elseif($start_down){
		$tuanHtml = '';
		$content.='<fieldset class="erphpdown erphpdown-default" id="erphpdown" style="display:block"><legend>资源下载</legend>'.$downMsgFree;
		if($down_tuan == '2' && function_exists('erphpdown_tuan_install')){
			$tuanHtml = erphpdown_tuan_html();
			$content .= $tuanHtml;
		}else{
			if($price_type){
				if($urls){
					$cnt = count($urls['index']);
	    			if($cnt){
	    				for($i=0; $i<$cnt;$i++){
	    					$index = $urls['index'][$i];
	    					$index_name = $urls['name'][$i];
	    					$price = $urls['price'][$i];
	    					$index_url = $urls['url'][$i];
	    					$index_vip = $urls['vip'][$i];

	    					$indexMemberDown = $memberDown;
	    					if($index_vip){
	    						$indexMemberDown = $index_vip;
	    					}

	    					$down_checkpan = '';
	    					if(function_exists('epd_check_pan_callback')){
								if(strpos($index_url,'pan.baidu.com') !== false || (strpos($index_url,'lanzou') !== false && strpos($index_url,'.com') !== false) || strpos($index_url,'cloud.189.cn') !== false){
									$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'">点击检测网盘有效后购买</a>';
								}
							}
	            					
	    					$content .= '<fieldset class="erphpdown-child"><legend>'.$index_name.'</legend>';
	    					if(is_user_logged_in()){
								if($price){
									if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9)
										$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
								}else{
									if($indexMemberDown != 4 && $indexMemberDown != 15 && $indexMemberDown != 8 && $indexMemberDown != 9)
										$content.='此资源仅限注册用户下载';
								}

								if($price || $indexMemberDown == 4 || $indexMemberDown == 15 || $indexMemberDown == 8 || $indexMemberDown == 9){
									global $wpdb;
									$user_info=wp_get_current_user();
									$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_index='".$index."' and ice_success=1 and ice_user_id=".$user_info->ID." order by ice_time desc");
									if($days > 0 && $down_info){
										$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
										$nowDate = date('Y-m-d H:i:s');
										if(strtotime($nowDate) > strtotime($lastDownDate)){
											$down_info = null;
										}
									}

									if($down_repeat){
										$down_info_repeat = $down_info;
										$down_info = null;
									}

									$buyText = '立即购买';
									if($down_repeat && $down_info_repeat && !$down_info){
										$buyText = '再次购买';
									}

									if( ($userType && ($indexMemberDown==3 || $indexMemberDown==4)) || $down_info || (($indexMemberDown==15 || $indexMemberDown==16) && $userType >= 8) || (($indexMemberDown==6 || $indexMemberDown==8) && $userType >= 9) || (($indexMemberDown==7 || $indexMemberDown==9 || $indexMemberDown==13 || $indexMemberDown==14) && $userType == 10) || (!$price && $indexMemberDown!=4 && $indexMemberDown!=15 && $indexMemberDown!=8 && $indexMemberDown!=9)){

										if($indexMemberDown==3){
											$content.='（'.$erphp_vip_name.'免费）';
										}elseif($indexMemberDown==2){
											$content.='（'.$erphp_vip_name.' 5折）';
										}elseif($indexMemberDown==13){
											$content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）';
										}elseif($indexMemberDown==5){
											$content.='（'.$erphp_vip_name.' 8折）';
										}elseif($indexMemberDown==14){
											$content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）';
										}elseif($indexMemberDown==6){
											$content .= '（'.$erphp_year_name.'免费）';
										}elseif($indexMemberDown==7){
											$content .= '（'.$erphp_life_name.'免费）';
										}elseif($indexMemberDown==4){
											$content .= '（此资源仅限'.$erphp_vip_name.'下载）';
										}elseif($indexMemberDown == 15){
											$content .= '（此资源仅限'.$erphp_quarter_name.'下载）';
										}elseif($indexMemberDown == 8){
											$content .= '（此资源仅限'.$erphp_year_name.'下载）';
										}elseif($indexMemberDown == 9){
											$content .= '（此资源仅限'.$erphp_life_name.'下载）';
										}elseif ($indexMemberDown==10){
											$content .= '（仅限'.$erphp_vip_name.'购买）';
										}elseif ($indexMemberDown==11){
											$content .= '（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折）';
										}elseif ($indexMemberDown==12){
											$content .= '（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折）';
										}

										$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";
									}else{
									
										$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
										if($userType){
											$vipText = '';
											if(($indexMemberDown == 13 || $indexMemberDown == 14) && $userType < 10){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
											}
										}
										if($indexMemberDown==3){
											$content.='（'.$erphp_vip_name.'免费）'.$vipText;
										}elseif ($indexMemberDown==2){
											$content.='（'.$erphp_vip_name.' 5折）'.$vipText;
										}elseif ($indexMemberDown==13){
											$content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）'.$vipText;
										}elseif ($indexMemberDown==5){
											$content.='（'.$erphp_vip_name.' 8折）'.$vipText;
										}elseif ($indexMemberDown==14){
											$content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）'.$vipText;
										}elseif ($indexMemberDown==16){
											if($userType < 8){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
											}
											$content.='（'.$erphp_quarter_name.'免费）'.$vipText;
										}elseif ($indexMemberDown==6){
											if($userType < 9){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
											}
											$content.='（'.$erphp_year_name.'免费）'.$vipText;
										}elseif ($indexMemberDown==7){
											if($userType < 10){
												$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
											}
											$content.='（'.$erphp_life_name.'免费）'.$vipText;
										}elseif ($indexMemberDown==4){
											if($userType){
												$content.='此资源为'.$erphp_vip_name.'专享资源';
											}
										}elseif ($indexMemberDown==15){
											if($userType >= 9){
												$content.='此资源为'.$erphp_quarter_name.'专享资源';
											}
										}elseif ($indexMemberDown==8){
											if($userType >= 9){
												$content.='此资源为'.$erphp_year_name.'专享资源';
											}
										}elseif ($indexMemberDown==9){
											if($userType >= 10){
												$content.='此资源为'.$erphp_life_name.'专享资源';
											}
										}
										

										if($indexMemberDown==4){
											$content.='此资源仅限'.$erphp_vip_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
										}elseif($indexMemberDown==15){
											$content.='此资源仅限'.$erphp_quarter_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
										}elseif($indexMemberDown==8){
											$content.='此资源仅限'.$erphp_year_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
										}elseif($indexMemberDown==9){
											$content.='此资源仅限'.$erphp_life_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
										}elseif($indexMemberDown==10){
											if($userType){
												$content.='（仅限'.$erphp_vip_name.'购买）';
												if($down_checkpan) $content .= $down_checkpan;
												else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' target="_blank">'.$buyText.'</a>';

												if($days){
													$content.= '（购买后'.$days.'天内可下载）';
												}
											}else{
												$content.='（仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
											}
										}elseif($indexMemberDown==11){
											if($userType){
												$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）';
												if($down_checkpan) $content .= $down_checkpan;
												else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' target="_blank">'.$buyText.'</a>';

												if($days){
													$content.= '（购买后'.$days.'天内可下载）';
												}
											}else{
												$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
											}
										}elseif($indexMemberDown==12){
											if($userType){
												$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）';
												if($down_checkpan) $content .= $down_checkpan;
												else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.' target="_blank">'.$buyText.'</a>';

												if($days){
													$content.= '（购买后'.$days.'天内可下载）';
												}
											}else{
												$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
											}
										}else{
											if($down_checkpan) $content .= $down_checkpan;
											else $content.='<a class="erphpdown-iframe erphpdown-buy" href="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'" target="_blank">'.$buyText.'</a>';

											if($days){
												$content.= '（购买后'.$days.'天内可下载）';
											}
										}
									}
									
								}else{
									$content.="<a href='".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe."' class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";
								}
								
							}else{
								if($indexMemberDown == 4 || $indexMemberDown == 15 || $indexMemberDown == 8 || $indexMemberDown == 9){
									$content.='此资源仅限'.$erphp_vip_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
								}else{
									if($price){
										$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
									}else{
										$content.='此资源仅限注册用户下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
									}
								}
							}
							if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
								$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID()."&index=".$index.$iframe." class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";
							}
	    					$content .= '</fieldset>';
	    				}
	    			}
				}
			}else{
				if(function_exists('erphpdown_tuan_install')){
					$tuanHtml = erphpdown_tuan_html();
				}

				if(function_exists('epd_check_pan_callback')){
					if(strpos($url,'pan.baidu.com') !== false || (strpos($url,'lanzou') !== false && strpos($url,'.com') !== false) || strpos($url,'cloud.189.cn') !== false){
						$down_checkpan = '<a class="erphpdown-buy erphpdown-checkpan" href="javascript:;" data-id="'.get_the_ID().'" data-index="'.$index.'" data-buy="'.constant("erphpdown").'buy.php?postid='.get_the_ID().'&index='.$index.'">点击检测网盘有效后购买</a>';
					}
				}

				if(is_user_logged_in()){
					if($price){
						if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
							$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
					}else{
						if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
							$content.='此资源仅限注册用户下载';
					}

					if($price || $memberDown == 4 || $memberDown == 15 || $memberDown == 8 || $memberDown == 9){
						global $wpdb;
						$user_info=wp_get_current_user();
						$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".get_the_ID()."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
						if($days > 0 && $down_info){
							$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
							$nowDate = date('Y-m-d H:i:s');
							if(strtotime($nowDate) > strtotime($lastDownDate)){
								$down_info = null;
							}
						}

						if($down_repeat){
							$down_info_repeat = $down_info;
							$down_info = null;
						}

						$buyText = '立即购买';
						if($down_repeat && $down_info_repeat && !$down_info){
							$buyText = '再次购买';
						}

						$user_id = $user_info->ID;
						$wppay = new EPD(get_the_ID(), $user_id);

						$ews_erphpdown = get_option("ews_erphpdown");
						if($ews_erphpdown && function_exists("ews_erphpdown") && isset($_COOKIE['ewd_'.get_the_ID()])){
							$down_can = 1;
							$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";

						}elseif( ($userType && ($memberDown==3 || $memberDown==4)) || (($wppay->isWppayPaid() || $wppay->isWppayPaidNew()) && !$down_repeat) || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10) || (!$price && $memberDown!=4 && $memberDown!=15 && $memberDown!=8 && $memberDown!=9)){

							$down_can = 1;

							if($memberDown==3){
								$content.='（'.$erphp_vip_name.'免费）';
							}elseif($memberDown==2){
								$content.='（'.$erphp_vip_name.' 5折）';
							}elseif($memberDown==13){
								$content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）';
							}elseif($memberDown==5){
								$content.='（'.$erphp_vip_name.' 8折）';
							}elseif($memberDown==14){
								$content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）';
							}elseif($memberDown==16){
								$content .= '（'.$erphp_quarter_name.'免费）';
							}elseif($memberDown==6){
								$content .= '（'.$erphp_year_name.'免费）';
							}elseif($memberDown==7){
								$content .= '（'.$erphp_life_name.'免费）';
							}elseif($memberDown==4){
								$content .= '（此资源仅限'.$erphp_vip_name.'下载）';
							}elseif($memberDown==15){
								$content .= '（此资源仅限'.$erphp_quarter_name.'下载）';
							}elseif($memberDown==8){
								$content .= '（此资源仅限'.$erphp_year_name.'下载）';
							}elseif($memberDown==9){
								$content .= '（此资源仅限'.$erphp_life_name.'下载）';
							}elseif ($memberDown==10){
								$content .= '（仅限'.$erphp_vip_name.'购买）';
							}elseif ($memberDown==11){
								$content .= '（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折）';
							}elseif ($memberDown==12){
								$content .= '（仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折）';
							}

							$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";

						}else{

							$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							if($userType){
								$vipText = '';
								if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
									$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
								}
							}
							if($memberDown==3){
								$content.='（'.$erphp_vip_name.'免费）'.$vipText;
							}elseif ($memberDown==2){
								$content.='（'.$erphp_vip_name.' 5折）'.$vipText;
							}elseif ($memberDown==13){
								$content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）'.$vipText;
							}elseif ($memberDown==5){
								$content.='（'.$erphp_vip_name.' 8折）'.$vipText;
							}elseif ($memberDown==14){
								$content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）'.$vipText;
							}elseif ($memberDown==16){
								if($userType < 8){
									$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
								}
								$content.='（'.$erphp_quarter_name.'免费）'.$vipText;
							}elseif ($memberDown==6){
								if($userType < 9){
									$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
								}
								$content.='（'.$erphp_year_name.'免费）'.$vipText;
							}elseif ($memberDown==7){
								if($userType < 10){
									$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
								}
								$content.='（'.$erphp_life_name.'免费）'.$vipText;
							}elseif ($memberDown==4){
								if($userType){
									$content.='此资源为'.$erphp_vip_name.'专享资源';
								}
							}elseif ($memberDown==8){
								if($userType >= 9){
									$content.='此资源为'.$erphp_year_name.'专享资源';
								}
							}elseif ($memberDown==9){
								if($userType >= 10){
									$content.='此资源为'.$erphp_life_name.'专享资源';
								}
							}
							

							if($memberDown==4){
								$content.='此资源仅限'.$erphp_vip_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							}elseif($memberDown==15){
								$content.='此资源仅限'.$erphp_quarter_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
							}elseif($memberDown==8){
								$content.='此资源仅限'.$erphp_year_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
							}elseif($memberDown==9){
								$content.='此资源仅限'.$erphp_life_name.'下载<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
							}elseif($memberDown==10){
								if($userType){
									$content.='（仅限'.$erphp_vip_name.'购买）';
									if($down_checkpan) $content .= $down_checkpan;
									else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

									if($days){
										$content.= '（购买后'.$days.'天内可下载）';
									}
								}else{
									$content.='（仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
								}
							}elseif($memberDown==11){
								if($userType){
									$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）';
									if($down_checkpan) $content .= $down_checkpan;
									else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

									if($days){
										$content.= '（购买后'.$days.'天内可下载）';
									}
								}else{
									$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
								}
							}elseif($memberDown==12){
								if($userType){
									$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）';
									if($down_checkpan) $content .= $down_checkpan;
									else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

									if($days){
										$content.= '（购买后'.$days.'天内可下载）';
									}
								}else{
									$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
								}
							}else{
								if($down_checkpan) $content .= $down_checkpan;
								else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">'.$buyText.'</a>';

								if($days){
									$content.= '（购买后'.$days.'天内可下载）';
								}
							}
						}
						
					}else{
						$down_can = 1;
						$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";
					}
					
				}else {
					if($memberDown == 4){
						$content.='此资源仅限'.$erphp_vip_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}elseif($memberDown == 15){
						$content.='此资源仅限'.$erphp_quarter_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}elseif($memberDown == 8){
						$content.='此资源仅限'.$erphp_year_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}elseif($memberDown == 9){
						$content.='此资源仅限'.$erphp_life_name.'下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}elseif($memberDown == 10){
						$content.='此资源仅限'.$erphp_vip_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}elseif($memberDown == 11){
						$content.='此资源仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}elseif($memberDown == 12){
						$content.='此资源仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}else{
						$vip_content = '';
						if($memberDown==3){
							$vip_content.='，'.$erphp_vip_name.'免费';
						}elseif($memberDown==2){
							$vip_content.='，'.$erphp_vip_name.' 5折';
						}elseif($memberDown==13){
							$vip_content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
						}elseif($memberDown==5){
							$vip_content.='，'.$erphp_vip_name.' 8折';
						}elseif($memberDown==14){
							$vip_content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
						}elseif($memberDown==16){
							$vip_content .= '，'.$erphp_quarter_name.'免费';
						}elseif($memberDown==6){
							$vip_content .= '，'.$erphp_year_name.'免费';
						}elseif($memberDown==7){
							$vip_content .= '，'.$erphp_life_name.'免费';
						}

						if(get_option('erphp_wppay_down')){
							$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
							$wppay = new EPD(get_the_ID(), $user_id);
							if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
								$down_can = 1;
								if($price){
									if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9)
										$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
								}
								$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";
							}else{
								if($price){
									$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');

									if($down_checkpan) $content .= $down_checkpan;
									else $content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

									$content .= $vip_content?($vip_content.'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphpdown-vip erphp-login-must">立即升级</a>'):'';
								}else{
									if(!get_option('erphp_free_login')){
										$down_can = 1;
										$content.="此资源为免费资源<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";
									}else{
										$content.='此资源仅限注册用户下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
									}
								}
							}
						}else{
							if($price){
								$content.='此资源下载价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').$vip_content.'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
							}else{
								$content.='此资源仅限注册用户下载，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
							}
							
						}
					}
				}

				if(get_option('erphp_repeatdown_btn') && $down_repeat && $down_info_repeat && !$down_info){
					$content.="<a href=".constant("erphpdown").'download.php?postid='.get_the_ID().$iframe." class='erphpdown-down".$erphp_popdown."' target='_blank'>立即下载</a>";
				}
			}

			$ews_erphpdown = get_option("ews_erphpdown");
			if(!$down_can && $ews_erphpdown && function_exists("ews_erphpdown")){
				$ews_erphpdown_btn = get_option("ews_erphpdown_btn");
				$ews_erphpdown_btn = $ews_erphpdown_btn?$ews_erphpdown_btn:'关注公众号免费下载';
				$content.='<a class="erphpdown-buy ews-erphpdown-button" data-id="'.get_the_ID().'" href="javascript:;">'.$ews_erphpdown_btn.'</a>';
			}
			
			if(get_option('ice_tips')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips').'</div>';
			$content .= $tuanHtml;
		}
		$content.='</fieldset>';
		
	}elseif($erphp_down == 6){
		$content .= '<fieldset class="erphpdown erphpdown-default" id="erphpdown" style="display:block"><legend>自动发卡</legend>';
		$content .= '此卡密价格为<span class="erphpdown-price">'.$price.'</span>'.get_option("ice_name_alipay");
		$content .= '<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
		if(function_exists('getErphpActLeft')) $content .= '（库存：'.getErphpActLeft(get_the_ID()).'）';
		$content .= '</fieldset>';
	}else{
		if($downMsgFree) $content.='<fieldset class="erphpdown erphpdown-default" id="erphpdown" style="display:block"><legend>资源下载</legend>'.$downMsgFree.'</fieldset>';
	}
	
	return $content;
}

function erphpdown_shortcode_see($atts, $content=null){
	$atts = shortcode_atts( array(
        'index' => '',
        'type' => '',
        'image' => '',
        'price' => ''
    ), $atts, 'erphpdown' );
	date_default_timezone_set('Asia/Shanghai'); 
	global $post,$wpdb;

	$type_class = '';
	$type_style = '';
	if($atts['type'] == "video"){
		$type_class = " erphpdown-see-video";
	}
	if($atts['image']){
		$type_style = 'position:relative;background-color:#000 !important;background-image:url('.$atts['image'].') !important;background-repeat:no-repeat !important;background-size:cover !important;background-position:center !important;border:none;text-align:center;color:#fff';
	}

	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

	$original_content = $content;

	$erphp_see2_style = get_option('erphp_see2_style');

	$days=get_post_meta($post->ID, 'down_days', true);

	$erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
	if(get_option('erphp_url_front_vip')){
		$erphp_url_front_vip = get_option('erphp_url_front_vip');
	}
	$erphp_url_front_login = wp_login_url();
	if(get_option('erphp_url_front_login')){
		$erphp_url_front_login = get_option('erphp_url_front_login');
	}

	if(is_user_logged_in()){
		$erphp_url_front_vip2 = $erphp_url_front_vip;
	}else{
		$erphp_url_front_vip2 = $erphp_url_front_login;
	}

	if($atts['index'] > 0 && is_numeric($atts['index'])){
		if($atts['price'] > 0 && is_numeric($atts['price'])){
			$price_index = $atts['price'];
		}else{
			$price_index = get_post_meta($post->ID, 'down_price', true);
		}

		if($price_index > 0){
			$html='<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">';
			if(is_user_logged_in()){
				$user_info=wp_get_current_user();
				$down_info=$wpdb->get_row("select * from ".$wpdb->iceindex." where ice_post='".$post->ID."' and ice_index=".$atts['index']." and ice_user_id=".$user_info->ID." and ice_price='".$price_index."' order by ice_time desc");
				if($days > 0 && $down_info){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$down_info = null;
					}
				}
				if($down_info){
					return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
				}else{
					$html.='此内容查看价格为<span class="erphpdown-price">'.$price_index.'</span>'.get_option('ice_name_alipay');
					$html.='<a class="erphpdown-buy erphpdown-buy-index" href="javascript:;" data-post="'.$post->ID.'" data-index="'.$atts['index'].'" data-price="'.$price_index.'">立即购买</a>';
					if($days){
						$html.= '（购买后'.$days.'天内可查看）';
					}
					$html .= '</div>';
				}
			}else{
				$html.='此内容查看价格为<span class="erphpdown-price">'.$price_index.'</span>'.get_option('ice_name_alipay').'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a></div>';
			}
			return $html;
		}else{
			return '';
		}
	}else{
		$userType=getUsreMemberType();
		$memberDown=get_post_meta($post->ID, 'member_down',TRUE);
		$start_down2=get_post_meta($post->ID, 'start_down2', true);
		$start_down=get_post_meta($post->ID, 'start_down', true);
		$start_see2=get_post_meta($post->ID, 'start_see2', true);
		$start_see=get_post_meta($post->ID, 'start_see', true);
		$price=get_post_meta($post->ID, 'down_price', true);

		$user_info=wp_get_current_user();
		$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_post='".$post->ID."' and ice_success=1 and (ice_index is null or ice_index = '') and ice_user_id=".$user_info->ID." order by ice_time desc");
		$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
		$wppay = new EPD($post->ID, $user_id);

		if($start_down2){
			if( $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || ($memberDown == 3 && $userType) || ($memberDown == 16 && $userType >= 8) || ($memberDown == 6 && $userType >= 9) || ($memberDown == 7 && $userType >= 10) || !$price){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip erphpdown-see-pay" style="display:block">'.do_shortcode($content).'</div>';
			}else{
				if($memberDown == 3 || $memberDown == 16 || $memberDown == 6 || $memberDown == 7){
					$wppay_vip_name = $erphp_vip_name;
					if($memberDown == 16){
						$wppay_vip_name = $erphp_quarter_name;
					}elseif($memberDown == 6){
						$wppay_vip_name = $erphp_year_name;
					}elseif($memberDown == 7){
						$wppay_vip_name = $erphp_life_name;
					}
					$content = '<div class="erphpdown erphpdown-see erphpdown-content-vip erphpdown-see-pay" style="display:block">此内容查看价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.$post->ID.'">立即购买</a>&nbsp;&nbsp;<b>或</b>&nbsp;&nbsp;升级'.$wppay_vip_name.'后免费<a href="'.$erphp_url_front_vip2.'" target="_blank" class="erphpdown-vip'.(is_user_logged_in()?'':' erphp-login-must').'">升级'.$wppay_vip_name.'</a>';
				}else{
					$content = '<div class="erphpdown erphpdown-see erphpdown-content-vip erphpdown-see-pay" style="display:block">此内容查看价格<span class="erphpdown-price">'.$price.'</span>元<a href="javascript:;" class="erphp-wppay-loader erphpdown-buy" data-post="'.get_the_ID().'">立即购买</a>';	
				}

				if(get_option('ice_tips_see')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips_see').'</div>';

				$content .= '</div>'; 
				return $content;
			}
		}elseif($start_down || $start_see2 || $start_see){
			if(is_user_logged_in()){
				if($days > 0 && $down_info){
					$lastDownDate = date('Y-m-d H:i:s',strtotime('+'.$days.' day',strtotime($down_info->ice_time)));
					$nowDate = date('Y-m-d H:i:s');
					if(strtotime($nowDate) > strtotime($lastDownDate)){
						$down_info = null;
					}
				}

				if( (($memberDown==3 || $memberDown==4) && $userType) || $wppay->isWppayPaid() || $wppay->isWppayPaidNew() || $down_info || (($memberDown==15 || $memberDown==16) && $userType >= 8) || (($memberDown==6 || $memberDown==8) && $userType >= 9) || (($memberDown==7 || $memberDown==9 || $memberDown==13 || $memberDown==14) && $userType == 10) ){

					if(!$wppay->isWppayPaid() && !$wppay->isWppayPaidNew() && !$down_info){

						$erphp_life_times    = get_option('erphp_life_times');
						$erphp_year_times    = get_option('erphp_year_times');
						$erphp_quarter_times = get_option('erphp_quarter_times');
						$erphp_month_times  = get_option('erphp_month_times');
						$erphp_day_times  = get_option('erphp_day_times');

						if(checkDownHas($user_info->ID,$post->ID)){
							return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
						}else{
							if($userType == 6 && $erphp_day_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 7 && $erphp_month_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 8 && $erphp_quarter_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 9 && $erphp_year_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}elseif($userType == 10 && $erphp_life_times > 0){
								if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
									return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看本文隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
								}else{
									return '<p class="erphpdown-content-vip">您暂时无权查看本文隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
								}
							}else{
								return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
							}
						}
					}else{
						return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
					}
				}else{
					if($start_see2 && $erphp_see2_style){
						$content = '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content = '<div class="erphpdown erphpdown-see erphpdown-see-pay erphpdown-content-vip'.$type_class.'" style="display:block;'.$type_style.'">';
						if($price){
							if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
								$content.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
							}
						}else{
							if($memberDown != 4 && $memberDown != 15 && $memberDown != 8 && $memberDown != 9){
								return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($original_content).'</div>';
							}
						}
						

						$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						if($userType){
							$vipText = '';
							if(($memberDown == 13 || $memberDown == 14) && $userType < 10){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
							}
						}
						if($memberDown==3){
							$content.='（'.$erphp_vip_name.'免费）'.$vipText;
						}elseif ($memberDown==2){
							$content.='（'.$erphp_vip_name.' 5折）'.$vipText;
						}elseif ($memberDown==5){
							$content.='（'.$erphp_vip_name.' 8折）'.$vipText;
						}elseif ($memberDown==13){
							$content.='（'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费）'.$vipText;
						}elseif ($memberDown==14){
							$content.='（'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费）'.$vipText;
						}elseif ($memberDown==16){
							if($userType < 9){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
							}
							$content.='（'.$erphp_quarter_name.'免费）'.$vipText;
						}elseif ($memberDown==6){
							if($userType < 9){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
							}
							$content.='（'.$erphp_year_name.'免费）'.$vipText;
						}elseif ($memberDown==7){
							if($userType < 10){
								$vipText = '<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
							}
							$content.='（'.$erphp_life_name.'免费）'.$vipText;
						}
						

						if($memberDown==4){
							$content.='此内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
						}elseif($memberDown==15)
						{
							$content.='此内容仅限'.$erphp_quarter_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a>';
						}elseif($memberDown==8)
						{
							$content.='此内容仅限'.$erphp_year_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a>';
						}elseif($memberDown==9)
						{
							$content.='此内容仅限'.$erphp_life_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a>';
						}elseif($memberDown==10){
							if($userType){
								$content.='（仅限'.$erphp_vip_name.'购买）<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_vip_name.'购买）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							}
						}elseif($memberDown==11){
							if($userType){
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）';
								$content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 5折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							}
						}elseif($memberDown==12){
							if($userType){
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）';
								$content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
								if($days){
									$content.= '（购买后'.$days.'天内可查看）';
								}
							}else{
								$content.='（仅限'.$erphp_vip_name.'购买，'.$erphp_year_name.' 8折）<a href="'.$erphp_url_front_vip.'" class="erphpdown-vip">升级'.$erphp_vip_name.'</a>';
							}
						}else{

							$content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';
							if($days){
								$content.= '（购买后'.$days.'天内可查看）';
							}
						}

						if(get_option('ice_tips_see')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips_see').'</div>';
						$content.='</div>';
					}
					return $content;
				}
			}else{
				$content2 = $content;
				$content='<div class="erphpdown erphpdown-see erphpdown-see-pay erphpdown-content-vip'.$type_class.'" id="erphpdown" style="display:block;'.$type_style.'">';

				if($memberDown == 4){
					if($start_see2 && $erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content.='此内容仅限'.$erphp_vip_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}
				}elseif($memberDown == 15){
					if($start_see2 && $erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content.='此内容仅限'.$erphp_quarter_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}
				}elseif($memberDown == 8){
					if($start_see2 && $erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content.='此内容仅限'.$erphp_year_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}
				}elseif($memberDown == 9){
					if($start_see2 && $erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content.='此内容仅限'.$erphp_life_name.'查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}
				}elseif($memberDown == 10){
					if($start_see2 && $erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content.='此内容仅限'.$erphp_vip_name.'购买，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}
				}elseif($memberDown == 11){
					if($start_see2 && $erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content.='此内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 5折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}
				}elseif($memberDown == 12){
					if($start_see2 && $erphp_see2_style){
						return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
					}else{
						$content.='此内容仅限'.$erphp_vip_name.'购买、'.$erphp_year_name.' 8折，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
					}
				}else{
					$vip_content = '';
					if($memberDown==3){
						$vip_content.='，'.$erphp_vip_name.'免费';
					}elseif($memberDown==2){
						$vip_content.='，'.$erphp_vip_name.' 5折';
					}elseif($memberDown==13){
						$vip_content.='，'.$erphp_vip_name.' 5折、'.$erphp_life_name.'免费';
					}elseif($memberDown==5){
						$vip_content.='，'.$erphp_vip_name.' 8折';
					}elseif($memberDown==14){
						$vip_content.='，'.$erphp_vip_name.' 8折、'.$erphp_life_name.'免费';
					}elseif($memberDown==16){
						$vip_content .= '，'.$erphp_quarter_name.'免费';
					}elseif($memberDown==6){
						$vip_content .= '，'.$erphp_year_name.'免费';
					}elseif($memberDown==7){
						$vip_content .= '，'.$erphp_life_name.'免费';
					}

					if(get_option('erphp_wppay_down')){
						$user_id = is_user_logged_in() ? wp_get_current_user()->ID : 0;
						$wppay = new EPD(get_the_ID(), $user_id);
						if($wppay->isWppayPaid() || $wppay->isWppayPaidNew()){
							return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content2).'</div>';
						}else{
							if($price){
								if($start_see2 && $erphp_see2_style){
									return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
								}else{
									$content.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay');
									$content.='<a class="erphpdown-iframe erphpdown-buy" href='.constant("erphpdown").'buy.php?postid='.get_the_ID().' target="_blank">立即购买</a>';

									$content .= $vip_content?($vip_content.'<a href="'.$erphp_url_front_login.'" target="_blank" class="erphpdown-vip erphp-login-must">立即升级</a>'):'';
								}
							}else{
								if(!get_option('erphp_free_login')){
									return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content2).'</div>';
								}else{
									if($start_see2 && $erphp_see2_style){
										return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
									}else{
										$content.='此内容仅限注册用户查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
									}
								}
							}
						}
					}else{
						if($start_see2 && $erphp_see2_style){
							return '<div class="erphpdown-content-vip erphpdown-content-vip2">您暂时无权查看此隐藏内容！</div>';
						}else{
							if($price){
								$content.='此内容查看价格为<span class="erphpdown-price">'.$price.'</span>'.get_option('ice_name_alipay').$vip_content.'，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
							}else{
								$content.='此内容仅限注册用户查看，请先<a href="'.$erphp_url_front_login.'" target="_blank" class="erphp-login-must">登录</a>';
							}
						}
					}
				}
				
				if(get_option('ice_tips_see')) $content.='<div class="erphpdown-tips">'.get_option('ice_tips_see').'</div>';
				$content.='</div>';
				return $content;
			}
		}
	}
}  
add_shortcode('erphpdown','erphpdown_shortcode_see');

function erphpdown_shortcode_vip($atts, $content=null){
	$atts = shortcode_atts( array(
        'type' => '',
    ), $atts, 'vip' );

  global $post;

  $erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'终身VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'包年VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'包季VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'包月VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'体验VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

	$erphp_life_times    = get_option('erphp_life_times');
	$erphp_year_times    = get_option('erphp_year_times');
	$erphp_quarter_times = get_option('erphp_quarter_times');
	$erphp_month_times  = get_option('erphp_month_times');
	$erphp_day_times  = get_option('erphp_day_times');

    $erphp_url_front_vip = get_bloginfo('wpurl').'/wp-admin/admin.php?page=erphpdown/admin/erphp-update-vip.php';
	if(get_option('erphp_url_front_vip')){
		$erphp_url_front_vip = get_option('erphp_url_front_vip');
	}
	$erphp_url_front_login = wp_login_url();
	if(get_option('erphp_url_front_login')){
		$erphp_url_front_login = get_option('erphp_url_front_login');
	}

	$vip = '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a></div>';

	if(is_user_logged_in()){
		$userType=getUsreMemberType();
		$user_info = wp_get_current_user();
		if(!$atts['type']){
			if($userType){
				//return do_shortcode($content);

				if(checkDownHas($user_info->ID,$post->ID)){
					return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
				}else{
					if($userType == 6 && $erphp_day_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 7 && $erphp_month_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 8 && $erphp_quarter_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 9 && $erphp_year_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 10 && $erphp_life_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}else{
						return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
					}
				}

			}else{
				return $vip;
			}
		}else{
			if($atts['type'] == '6' && $userType < 6){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_vip_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_vip_name.'</a></div>';
			}elseif($atts['type'] == '7' && $userType < 7){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_month_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_month_name.'</a></div>';
			}elseif($atts['type'] == '8' && $userType < 8){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_quarter_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_quarter_name.'</a></div>';
			}elseif($atts['type'] == '9' && $userType < 9){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_year_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_year_name.'</a></div>';
			}elseif($atts['type'] == '10' && $userType < 10){
				return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">此隐藏内容仅限'.$erphp_life_name.'查看<a href="'.$erphp_url_front_vip.'" target="_blank" class="erphpdown-vip">升级'.$erphp_life_name.'</a></div>';
			}else{
				//return do_shortcode($content);

				if(checkDownHas($user_info->ID,$post->ID)){
					return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
				}else{
					if($userType == 6 && $erphp_day_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_day_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_day_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 7 && $erphp_month_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_month_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_month_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 8 && $erphp_quarter_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_quarter_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_quarter_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 9 && $erphp_year_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_year_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_year_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}elseif($userType == 10 && $erphp_life_times > 0){
						if( checkSeeLog($user_info->ID,$post->ID,$erphp_life_times,erphpGetIP()) ){
							return '<p class="erphpdown-content-vip erphpdown-content-vip-see">您可免费查看此隐藏内容！<a href="javascript:;" class="erphpdown-see-btn" data-post="'.$post->ID.'">立即查看</a>（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}else{
							return '<p class="erphpdown-content-vip">您暂时无权查看此隐藏内容，请明天再来！（今日已查看'.getSeeCount($user_info->ID).'个，还可查看'.($erphp_life_times-getSeeCount($user_info->ID)).'个）</p>';
						}
					}else{
						return '<div class="erphpdown erphpdown-see erphpdown-content-vip" style="display:block">'.do_shortcode($content).'</div>';
					}
				}
			}
		}
	}else{
		return $vip;
	}			
}  
add_shortcode('vip','erphpdown_shortcode_vip');