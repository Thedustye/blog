<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------

if ( !defined('ABSPATH') ) {exit;}

$issearch = 0;
$ice_success = 'ice_success=1';
if(isset($_GET['type'])){
	if($_GET['type'] == 'all'){
		$ice_success = '1=1';
	}elseif($_GET['type'] == 'unpaid'){
		$ice_success = 'ice_success=0';
	}
}

if(isset($_GET['username']) && $_GET['username']){
	$user = get_user_by('login',$_GET['username']);
	if($user){
		$suid = $user->ID;
		$issearch = 1;
	}else{
		$suid = 0;
		echo '<div class="error settings-error"><p>用户不存在！</p></div>';
	}
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icemoney WHERE ice_user_id=".$suid." and ".$ice_success);
}elseif(isset($_GET['order']) && $_GET['order']){
	$issearch = 2;
	$ice_num = $_GET['order'];
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icemoney WHERE ice_num='".$ice_num."' and ".$ice_success);
}else{
	$total_trade   = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icemoney WHERE ".$ice_success);
}
$ice_perpage = 30;
$pages = ceil($total_trade / $ice_perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $ice_perpage*($page-1);

if($issearch == 1){
	$results=$wpdb->get_results("SELECT * FROM $wpdb->icemoney where ice_user_id=".$suid." and ".$ice_success." order by ice_time DESC limit $offset,$ice_perpage");
}elseif($issearch == 2){
	$results=$wpdb->get_results("SELECT * FROM $wpdb->icemoney where ice_num='".$ice_num."' and ".$ice_success." order by ice_time DESC limit $offset,$ice_perpage");
}else{
	$results=$wpdb->get_results("SELECT * FROM $wpdb->icemoney where ".$ice_success." order by ice_time DESC limit $offset,$ice_perpage");
}
?>

<div class="wrap">
	<h2>充值记录 <a href="admin.php?page=erphpdown%2Fadmin%2Ferphp-clear.php" style="font-size:14px;text-decoration: none;">清理数据表</a></h2>
	<ul class="subsubsub">
		<li class="all"><a href="admin.php?page=erphpdown/admin/erphp-chong-list.php&amp;type=all" class="<?php if(isset($_GET['type']) && $_GET['type'] == 'all') echo 'current';?>">全部</a> |</li>
		<li class="mine"><a href="admin.php?page=erphpdown/admin/erphp-chong-list.php" class="<?php if(!isset($_GET['type'])) echo 'current';?>">已支付</a> |</li>
		<li class="mine"><a href="admin.php?page=erphpdown/admin/erphp-chong-list.php&amp;type=unpaid" class="<?php if(isset($_GET['type']) && $_GET['type'] == 'unpaid') echo 'current';?>">未支付</a></li>
	</ul>
	<div class="tablenav top">
		<form method="get"><input type="hidden" name="page" value="erphpdown/admin/erphp-chong-list.php"><input type="text" name="username" placeholder="登录名，例如：admin" value="<?php if($issearch == 1) echo $_GET['username'];?>"><input type="text" name="order" placeholder="订单号" value="<?php if($issearch == 2) echo $_GET['order'];?>"><input type="submit" value="查询" class="button"></form>
	</div>
	<table class="widefat fixed striped posts">
		<thead>
			<tr>
				<th>用户ID</th>
				<th>订单号</th>
				<th><?php echo get_option('ice_name_alipay');?></th>
				<th>方式</th>
				<th>时间</th>
				<th>状态</th>
				<th>管理</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if($results) {
				foreach($results as $value)
				{
					echo "<tr>\n";
					if($value->ice_user_id){
						$cu = get_user_by('id',$value->ice_user_id);
						echo "<td>".$cu->user_login."<span style='font-size:12px;color:#999'>（昵称：".$cu->nickname."）</span></td>";
					}else{
						echo "<td>游客<span style='font-size:12px;color:#999'>（IP：".$value->ice_ip."）</span></td>";
					}
					echo "<td>$value->ice_num</td>\n";
					echo "<td>$value->ice_money</td>\n";
					if(intval($value->ice_note)==0)
					{
						if($value->ice_success){
							if($value->ice_alipay){
								echo "<td><font color=green>".$value->ice_alipay."</font></td>\n";
							}else{
								echo "<td><font color=green>在线充值</font></td>\n";
							}
						}else{
							if($value->ice_alipay){
								echo "<td>".$value->ice_alipay."</td>\n";
							}else{
								echo "<td>在线充值</td>\n";
							}
						}
					}elseif(intval($value->ice_note)==1)
					{
						echo "<td>后台充值</td>\n";
					}
					elseif(intval($value->ice_note)==4)
					{
						echo "<td><font color=orange>mycred兑换</font></td>\n";
					}
					elseif(intval($value->ice_note)==6)
					{
						echo "<td><font color=orange>充值卡</font></td>\n";
					}
					echo "<td>".$value->ice_time."</td>";
					echo "<td>".($value->ice_success?'<font color=green>已支付</font>':'未支付')."</td>";
					echo '<td><a href="javascript:;" class="delorder" data-id="'.$value->ice_id.'">删除</a></td>';
					echo "</tr>";
				}
			}
			else
			{
				echo '<tr><td colspan="6" align="center"><strong>没有记录</strong></td></tr>';
			}
			?>
		</tbody>
	</table>
	<?php echo erphp_admin_pagenavi($total_trade,$ice_perpage);?>
	　　		
</div>
<script>
	jQuery(".delorder").click(function(){
		if(confirm('确定删除？')){
			var that = jQuery(this);
			that.text("删除中...");
			jQuery.ajax({
				type: "post",
				url: "<?php echo constant("erphpdown");?>admin/action/order.php",
				data: "do=delchong&id=" + jQuery(this).data("id"),
				dataType: "html",
				success: function (data) {
					if(jQuery.trim(data) == '1'){
						that.parent().parent().remove();
					}
				},
				error: function (request) {
					that.text("删除");
					alert("删除失败");
				}
			});
		}
	});
</script>