<?php
session_start();
require( dirname(__FILE__) . '/../../../../../wp-load.php' );
$erphpdown_token = $_SESSION['erphpdown_token'];
if(is_user_logged_in()){
	global $wpdb, $wppay_table_name;
	if($_POST['do']=='checkOrder' && $_POST['token'] == $erphpdown_token && $erphpdown_token){
		global $current_user;
		$id = $wpdb->escape($_POST['order']);
		$result = $wpdb->get_var("select ice_success from $wpdb->icemoney where ice_user_id = '".$current_user->ID."' and ice_id='".$id."'");
		if($result){
			echo '1';
		}else{
			echo '0';
		}
	}elseif($_POST['do'] == 'delorder'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->icealipay where ice_id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'delviporder'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->vip where ice_id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'delpost'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->icealipay where ice_post=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'delwppay'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wppay_table_name where id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'delchong'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->icemoney where ice_id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'delindex'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->iceindex where ice_id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'deltuan'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from ".$wpdb->prefix."ice_tuan_order where ice_id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'delfreedown'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->down where ice_id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}elseif($_POST['do'] == 'dellog'){
		if(current_user_can('administrator')){
			$result = $wpdb->query("delete from $wpdb->icelog where id=".$wpdb->escape($_POST['id']));
			if($result){
				echo '1';
			}else{
				echo '0';
			}
		}
	}
}else{
	global $wpdb, $wppay_table_name;
	if($_POST['do']=='checkOrder' && $_POST['token'] == $erphpdown_token && $erphpdown_token){
		$id = $wpdb->escape($_POST['order']);
		$result = $wpdb->get_var("select ice_success from $wpdb->icemoney where ice_user_id = 0 and ice_id='".$id."'");
		if($result){
			echo '1';
		}else{
			echo '0';
		}
	}
}
exit;