<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------
if ( !defined('ABSPATH') ) {exit;}

function erphpdown_term_post_count( $taxonomy = 'category', $term = '', $args = [] ){
    if ( !$term )
        return false;

    if ( $term !== 'all' ) {
        if ( !is_array( $term ) ) {
            $term = filter_var(       $term, FILTER_VALIDATE_INT );
        } else {
            $term = filter_var_array( $term, FILTER_VALIDATE_INT );
        }
    }

    if ( $taxonomy !== 'category' ) {
        $taxonomy = filter_var( $taxonomy, FILTER_SANITIZE_STRING );
        if ( !taxonomy_exists( $taxonomy ) )
            return false;
    }

    if ( $args ) {
        if ( !is_array ) 
            return false;
    }

    $defaults = [
        'posts_per_page' => 1,
        'fields'         => 'ids'
    ];

    if ( $term !== 'all' ) {
        $defaults['tax_query'] = [
            [
                'taxonomy' => $taxonomy,
                'terms'    => $term
            ]
        ];
    }
    $combined_args = wp_parse_args( $args, $defaults );
    $q = new WP_Query( $combined_args );

    return $q->found_posts;
}

$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;

if(isset($_GET['cat']) && $_GET['cat']){
	$total_trade = erphpdown_term_post_count("category",$_GET['cat']);
}else{
	$total_trade = $wpdb->get_var("SELECT COUNT(ID) FROM $wpdb->posts WHERE post_status='publish' and post_type='post' ");
}

$ice_perpage = isset($_GET['perpage']) ?intval($_GET['perpage']) :50;
$pages = ceil($total_trade / $ice_perpage);
$page=isset($_GET['paged']) ?intval($_GET['paged']) :1;
$offset = $ice_perpage*($page-1);

if(isset($_GET['cat']) && $_GET['cat']){
	$args = array(
        'post_type' => 'post',
        'cat' => $_GET['cat'],
	    'ignore_sticky_posts' => 1,
	    'offset' => $offset,
    	'posts_per_page' => $ice_perpage
	);
	if(isset($_GET['s']) && $_GET['s']){
		$args['s'] = $_GET['s'];
	}
	query_posts($args);
}elseif(isset($_GET['s']) && $_GET['s']){
	$args = array(
        'post_type' => 'post',
	    'ignore_sticky_posts' => 1,
	    'offset' => $offset,
	    's'  => $_GET['s'],
    	'posts_per_page' => $ice_perpage
	);
	query_posts($args);
}else{
	$args = array(
        'post_type' => 'post',
	    'ignore_sticky_posts' => 1,
	    'offset' => $offset,
    	'posts_per_page' => $ice_perpage
	);
	query_posts($args);
}

?>

<div id="screen-meta" class="metabox-prefs" style="display: none;">
		<div id="screen-options-wrap" class="hidden" tabindex="-1" aria-label="???????????????????????????" style="display: none;">
<form id="adv-settings" method="get" action="">
		<input type="hidden" name="page" value="<?php echo plugin_basename(__FILE__);?>">
				<fieldset class="screen-options">
		<legend>??????</legend>
							<label for="edit_post_per_page">?????????????????????</label>
				<input type="number" step="1" min="1" max="999" class="screen-per-page" name="perpage" maxlength="3" value="<?php echo $ice_perpage;?>">
		</fieldset>
				
		<p class="submit"><input type="submit" class="button button-primary" value="??????"></p>
		
</form>
</div>		</div>
<div id="screen-meta-links">
					<div id="screen-options-link-wrap" class="hide-if-no-js screen-meta-toggle">
			<button type="button" id="show-settings-link" class="button show-settings" aria-controls="screen-options-wrap" aria-expanded="false">????????????</button>
			</div>
				</div>

<div class="wrap">
	<h2>????????????</h2>
	<form method="post" style="margin:0 0 10px">
		<p>??????????????????
		<input type="radio" name="start_down" value="0" checked />????????? &nbsp;
		<input type="radio" name="start_down" value="4" />????????? &nbsp;
		<input type="radio" name="start_down" value="1" />?????? &nbsp;
		<input type="radio" name="start_down" value="5" />?????????&nbsp;
		<input type="radio" name="start_down" value="2" />??????&nbsp;
		<input type="radio" name="start_down" value="3" />????????????&nbsp;
		<input type="radio" name="start_down" value="6" />??????</p>
		<p>???VIP ??? ??????
		<input type="radio" name="viptype" value="0" checked/>?????????&nbsp;
		<input type="radio" name="viptype" value="1"/>???&nbsp;
		<input type="radio" name="viptype" value="4" />VIP??????&nbsp;
		<input type="radio" name="viptype" value="3" />VIP??????&nbsp;
		<input type="radio" name="viptype" value="2" />VIP5???&nbsp;
		<input type="radio" name="viptype" value="5" />VIP8???&nbsp;
		<input type="radio" name="viptype" value="16" />??????VIP??????&nbsp;
		<input type="radio" name="viptype" value="6" />??????VIP??????&nbsp;
		<input type="radio" name="viptype" value="7" />??????VIP??????&nbsp;
		<input type="radio" name="viptype" value="15" />??????VIP??????&nbsp;
		<input type="radio" name="viptype" value="8" />??????VIP??????&nbsp;
		<input type="radio" name="viptype" value="9" />??????VIP??????&nbsp;
		<input type="radio" name="viptype" value="10" />VIP????????????&nbsp;
		<input type="radio" name="viptype" value="11" />VIP????????????|??????5???&nbsp;
		<input type="radio" name="viptype" value="12" />VIP????????????|??????8???&nbsp;
		<input type="radio" name="viptype" value="13" />VIP5???|????????????&nbsp;
		<input type="radio" name="viptype" value="14" />VIP8???|????????????&nbsp;</p>
		<p>??????????????????
		<input type="number" id="price" step="0.01" /> ?????????????????????????????????????????????</p>
		<p>??????????????????
		<input type="number" id="days" step="1" /> ??????????????????????????????0???????????????</p>
		<p>??????????????????
		<input type="radio" name="repeat" value="0" checked/>?????????&nbsp;
		<input type="radio" name="repeat" value="1"/>???&nbsp;
		<input type="radio" name="repeat" value="2" />???&nbsp;</p>
		<input type="button" value="??????????????????" class="button-primary viptypedo"> ????????????????????????
	</form>
	<form method="get">
		<div class="tablenav top">
			<div class="alignleft actions">
				<input type="hidden" name="page" value="erphpdown/admin/erphp-shop-list.php">
				<?php wp_dropdown_categories('show_option_all=????????????&orderby=name&hierarchical=1&depth=0&hide_empty=1&selected='.((isset($_GET['s']) && $_GET['s'])?$_GET['s']:'-1'));?>
				<input type="text" name="s" placeholder="????????????/??????" value="<?php if(isset($_GET['s']) && $_GET['s']) echo $_GET['s'];?>" />
				<input type="submit" class="button" value="??????">
			</div>
		</div>
	</form>
	<?php if(isset($_GET['cat']) && $_GET['cat']){?>
	<script>
		jQuery("#cat").find("option[value='<?php echo $_GET['cat'];?>']").attr("selected",true);
	</script>
	<?php }?>
	<table class="widefat fixed striped posts">
		<thead>
			<tr>
				<th width="3%"><input type="checkbox" id="checkbox" onclick="selectAll()" style='margin-left:0'></th>
				<th>??????</th>
				<th>??????</th>
				<th>VIP??????</th>
				<th>????????????</th>
				<th>????????????</th>
				<th>????????????</th>
				<th>????????????</th>
				<th>??????</th>		
			</tr>
		</thead>
		<tbody>
			<?php
				while ( have_posts() ) : the_post(); 
					$ice_price = get_post_meta(get_the_ID(),"down_price",true);
					$ice_price = $ice_price?$ice_price:'';

					$down_days = get_post_meta(get_the_ID(),"down_days",true);
					$down_repeat = get_post_meta(get_the_ID(),"down_repeat",true);

					echo "<tr>\n";
					echo "<td><input type='checkbox' class='checkbox' value='".get_the_ID()."'></td>";
					echo "<td><a target=_blank href='".get_permalink(get_the_ID())."'>".get_the_title()."</a></td>\n";
					echo '<td><input type="number" min="0" step="0.01" name="p_price_'.get_the_ID().'" id="p_price_'.get_the_ID().'" value="'.$ice_price.'" style="width:60px;" /><a href="javascript:;" id="editpricebtn_'.get_the_ID().'" onclick="javascript:editPrice('.get_the_ID().')" >??????</a></td>';
					echo "<td>".getProductMember(get_the_ID())."</td>";
					echo "<td>".getProductDownType(get_the_ID())."</td>";
					echo "<td>".($down_days?$down_days:'')."</td>\n";
					echo "<td>".($down_repeat?'???':'')."</td>\n";
					echo "<td>".get_the_date('Y-m-d H:i:s')."</td>\n";
					echo "<td><a target=_blank href='".get_bloginfo('wpurl')."/wp-admin/post.php?post=".get_the_ID()."&action=edit'>??????</a></td>\n";
					echo "</tr>";  
				endwhile;wp_reset_query(); 
			?>
		</tbody>
	</table>
	<?php echo erphp_admin_pagenavi($total_trade,$ice_perpage);?>
</div>
<script type="text/javascript">

	jQuery(".viptypedo").click(function(){
		var that = jQuery(this);
		var ids = '';
		jQuery(".checkbox").each(function() {
			if (jQuery(this).is(':checked')) {
		      ids += ',' + jQuery(this).val();
		  }
		});
		ids = ids.substring(1);
		if (ids.length == 0) {
			alert('????????????????????????');
		} else {
			if (confirm("???????????????")) {
				that.attr("disabled","disabled").val("?????????...");
				jQuery.ajax({
					type: "post",
					url: "<?php echo constant("erphpdown");?>admin/action/vip.php",
					data: "do=type&ids=" + ids+"&price="+jQuery("#price").val() + "&type=" + jQuery("input[name='viptype']:checked").val()+ "&down=" + jQuery("input[name='start_down']:checked").val()+ "&repeat=" + jQuery("input[name='repeat']:checked").val()+"&days="+jQuery("#days").val(),
					date:"",
					dataType: "html",
					success: function (data) {
						if(data == 'success'){
							alert("????????????");
							location.reload();
						}
					},
					error: function (request) {
						that.attr("disabled","").val("??????");
						alert("?????????????????????????????????");
					}
				});
			}
		}
		return false;
	});

	function editPrice(id){	
		jQuery("#editpricebtn_"+id).text("?????????..");
		jQuery.ajax({
			type: "post",
			url: "<?php echo constant("erphpdown");?>admin/action/price.php",
			data: "do=editprice&postid=" + id + "&new_price=" + jQuery("#p_price_"+id).val(),
			date:"",
			dataType: "html",
			success: function (data) {
				if(data == 'success'){
					jQuery("#editpricebtn_"+id).text("????????????");
					setTimeout("editsuccess("+id+")",3000)
				}
			},
			error: function (request) {
				jQuery("#editpricebtn_"+id).text("??????");
				alert("????????????");
			}
		});
	}

	function editsuccess(id){
		jQuery("#editpricebtn_"+id).text("??????");
	}

	function selectAll(){
		if (jQuery('#checkbox').is(':checked')) {
			jQuery(".checkbox").attr("checked", true);
		} else {
			jQuery(".checkbox").attr("checked", false);
		}

	}
</script>
