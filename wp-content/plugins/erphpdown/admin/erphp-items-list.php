<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------
if ( !defined('ABSPATH') ) {exit;}

$total_trade   = $wpdb->get_var("select count(DISTINCT ice_post) as aa from $wpdb->icealipay where ice_success>0");
$ice_perpage = 20;
$pages = ceil($total_trade / $ice_perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $ice_perpage*($page-1);
$list = $wpdb->get_results("select ice_post,ice_title,count(ice_id) as ice_total,sum(ice_price) as ice_money from $wpdb->icealipay where ice_success>0 group by ice_post order by ice_total DESC limit $offset,$ice_perpage");

?>
<div class="wrap">
	<h2>销售排行</h2>
	<p><?php printf(('共<strong>%s</strong>个资源'), $total_trade); ?></p>
	<table class="widefat fixed striped posts">
		<thead>
			<tr>
				<th>资源名称</th>
				<th>销量</th>
				<th>销售额(<?php echo get_option('ice_name_alipay');?>)</th>
				<th>管理</th>		
			</tr>
		</thead>
		<tbody>
			<?php
			if($list) {
				foreach($list as $value){
					echo "<tr>\n";
					echo "<td><a target=_blank href='".get_permalink($value->ice_post)."'>".get_post($value->ice_post)->post_title."</a></td>\n";
					echo "<td>".getProductSales($value->ice_post)."</td>";
					echo "<td>".intval($value->ice_money)."</td>";
					echo "<td><a target=_blank href='".get_bloginfo('wpurl')."/wp-admin/post.php?post=".$value->ice_post."&action=edit'>编辑</a>&nbsp;&nbsp;&nbsp;&nbsp;<a href='javascript:;' class='delorder' data-id='".$value->ice_post."'>删除</a></td>\n";
					echo "</tr>";  
				}
			}else{
				echo '<tr><td colspan="4" align="center"><strong>没有销售记录</strong></td></tr>';
			}
			?>
		</tbody>
	</table>
	<?php echo erphp_admin_pagenavi($total_trade,$ice_perpage);?>
</div>
<script>
	jQuery(".delorder").click(function(){
		if(confirm('确定删除此资源的所有购买记录？')){
			var that = jQuery(this);
			that.text("删除中...");
			jQuery.ajax({
				type: "post",
				url: "<?php echo constant("erphpdown");?>admin/action/order.php",
				data: "do=delpost&id=" + jQuery(this).data("id"),
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
