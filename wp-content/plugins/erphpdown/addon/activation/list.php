<?php 
if ( !defined('ABSPATH') ) {exit;}
global $wpdb;

if(isset($_POST['delid'])){
	$sql = "delete from $wpdb->erphpact where id=".$_POST['delid'];
	$result=$wpdb->query($sql);
	if(!$result)
	{
		echo "<div id=message><div class='error settings-error'><p>删除失败！</p></div></div>";
	}else{echo "<div id=message><div class='updated settings-error'><p>删除成功！</p></div></div>";}
}
$suid = 0;
if(isset($_POST['username'])){
	$user = get_user_by('login',$_POST['username']);
	if($user){
		$suid = $user->ID;
		$totals = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->erphpact where uid = '".trim($suid)."'");
	}else{
		$suid = 0;
		echo '<div class="error settings-error"><p>用户不存在！</p></div>';
	}
}elseif(isset($_POST['actcode'])){
	$totals = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->erphpact where num like '%".trim($_POST['actcode'])."%'");
}else{
	$totals = $wpdb->get_var("SELECT COUNT(id) FROM $wpdb->erphpact");
}
$perpage = 50;
$pages = ceil($totals / $perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $perpage*($page-1);
if($suid){
	$results = $wpdb->get_results("select * from $wpdb->erphpact where uid = '".trim($suid)."' order by id desc limit $offset,$perpage");
}elseif(isset($_POST['actcode'])){
	$results = $wpdb->get_results("select * from $wpdb->erphpact where num like '%".trim($_POST['actcode'])."%' order by id desc limit $offset,$perpage");
}else{
	$results = $wpdb->get_results("select * from $wpdb->erphpact order by id desc limit $offset,$perpage");
}

?>
<div class="wrap">
	<h2>激活码列表</h2>
	<form method="post">搜索激活码：<input type="text" name="actcode" placeholder="激活码" value="<?php if(isset($_POST['actcode'])) echo trim($_POST['actcode']);?>"><input type="text" name="username" placeholder="用户名" value="<?php if(isset($_POST['username'])) echo trim($_POST['username']);?>"><input type="submit" value="查询" class="button"></form><br>
	<table class="wp-list-table widefat fixed posts">
            <thead>
            <tr>
            	<th width="3%"><input type="checkbox" id="checkbox" onclick="selectAll()" style='margin-left:0'></th>
				<th width="7%">序号</th>
				<th width="16%">激活码</th>
                <th width="40%">文章ID</th>
				<th width="30%">发放</th>
				<th width="7%">删除</th>
            </tr>
            </thead>
            <tbody>
			<?php 
				if($results){
					foreach($results as $result){
						echo '<tr>';
						echo "<td><input type='checkbox' class='checkbox' value='".$result->id."'></td>";
						echo '<td>'.$result->id.'</td>';
						echo '<td>'.$result->num.'</td>';
						echo '<td><a href="'.get_permalink($result->pid).'" target="_blank">'.get_the_title($result->pid).'</a></td>';
						echo '<td>'.getErphpActStatus($result->id).'</td>';
						echo "<td>";
						echo "<form method=post ><input type=hidden id=delid name=delid value=".$result->id."><input type=submit value=删除 class=button onclick=\"return confirm('确认删除?');\"></form>";
						echo "</td>";
						echo '</tr>';
					}
				}
			?>
			</tbody>
	</table>
<?php erphp_admin_pagenavi($totals,$perpage);?>
<input type="button" value="批量删除所选" class="button-primary delete-more">
</div>
<script type="text/javascript">
	jQuery(".delete-more").click(function(){
		var that = jQuery(this);
		var ids = '';
		jQuery(".checkbox").each(function() {
			if (jQuery(this).is(':checked')) {
		      	ids += ',' + jQuery(this).val();
		  	}
		});
		ids = ids.substring(1);
		if (ids.length == 0) {
			alert('请至少选择一项！');
		} else {
			if (confirm("确定操作？")) {
				that.attr("disabled","disabled").val("处理中...");
				jQuery.ajax({
					type: "post",
					url: "<?php echo ERPHPDOWN_URL;?>/addon/activation/do.php",
					data: "do=delcard&ids=" + ids,
					dataType: "html",
					success: function (data) {
						if(data == 'success'){
							alert("操作成功");
							location.reload();
						}
					},
					error: function (request) {
						that.attr("disabled","").val("确认");
						alert("操作失败，请稍后重试！");
					}
				});
			}
		}
		return false;
	});

	function selectAll(){
		if (jQuery('#checkbox').is(':checked')) {
			jQuery(".checkbox").attr("checked", true);
		} else {
			jQuery(".checkbox").attr("checked", false);
		}
	}
</script>