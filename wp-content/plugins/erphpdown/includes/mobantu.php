<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------

if ( !defined('ABSPATH') ) {exit;}

function addDownLog($uid,$pid,$ip,$vip=1){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$sql="insert into $wpdb->down(ice_user_id,ice_post_id,ice_vip,ice_ip,ice_time)values('".$uid."','".$pid."','".$vip."','".$ip."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
}

function checkDownLogNoVip($uid,$pid,$times){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$result = $wpdb->get_var("select count(distinct ice_post_id) from $wpdb->down where ice_user_id=".$uid." and DATEDIFF(ice_time,NOW())=0");
	if($result > $times){
		return false;
	}elseif($result == $times){
		$exist = $wpdb->get_var("select ice_id from $wpdb->down where ice_user_id=".$uid." and DATEDIFF(ice_time,NOW())=0 and ice_post_id = $pid");
		if($exist) 
			return true;
		else 
			return false;
	}
	else{
		return true;
	}
}

function checkDownLog($uid,$pid,$times,$vip=1){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$result = $wpdb->get_var("select count(distinct ice_post_id) from $wpdb->down where ice_user_id=".$uid." and ice_vip=".$vip." and DATEDIFF(ice_time,NOW())=0");
	if($result > $times){
		return false;
	}elseif($result == $times){
		$exist = $wpdb->get_var("select ice_id from $wpdb->down where ice_user_id=".$uid." and ice_vip=".$vip." and DATEDIFF(ice_time,NOW())=0 and ice_post_id = $pid");
		if($exist) 
			return true;
		else 
			return false;
	}else{
		return true;
	}
}

function getSeeCountNoVip($uid){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$result = $wpdb->get_var("select count(distinct ice_post_id) from $wpdb->down where ice_user_id=".$uid." and DATEDIFF(ice_time,NOW())=0");
	return $result;
}

function getSeeCount($uid,$vip=1){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$result = $wpdb->get_var("select count(distinct ice_post_id) from $wpdb->down where ice_user_id=".$uid." and ice_vip=".$vip." and DATEDIFF(ice_time,NOW())=0");
	return $result;
}

function checkSeeLog($uid,$pid,$times,$ip,$vip=1){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$result = $wpdb->get_var("select count(distinct ice_post_id) from $wpdb->down where ice_user_id=".$uid." and ice_vip=".$vip." and DATEDIFF(ice_time,NOW())=0");
	if($result >= $times) 
		return false;
	else 
		return true;
}

function checkDownHas($uid,$pid,$vip=1){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$exist = $wpdb->get_var("select ice_id from $wpdb->down where ice_user_id=".$uid." and ice_vip=".$vip." and DATEDIFF(ice_time,NOW())=0 and ice_post_id = $pid");
	if($exist) 
		return true;
	else 
		return false;
}

function addVipLog($price,$userType){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$user_info = wp_get_current_user();
	$sql="insert into $wpdb->vip(ice_price,ice_user_id,ice_user_type,ice_time)values('".$price."','".$user_info->ID."','".$userType."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
}

function addVipLogByAdmin($price,$userType,$uid){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$sql="insert into $wpdb->vip(ice_price,ice_user_id,ice_user_type,ice_time)values('".$price."','".$uid."','".$userType."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
}

function addAffLog($price,$uid,$ip){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$sql="insert into $wpdb->aff(ice_price,ice_user_id,ice_ip,ice_time)values('".$price."','".$uid."','".$ip."','".date("Y-m-d H:i:s")."')";
	$wpdb->query($sql);
	addUserMoney($uid,$price,'????????????');
}

function checkAffLog($uid,$ip){
	global $wpdb;
	$result = $wpdb->get_var("select ice_id from $wpdb->aff where ice_user_id=".$uid." and ice_ip='".$ip."'");
	if($result) return false;
	else return true;
}


function getUsreMemberType(){
	if(is_user_logged_in()){
		date_default_timezone_set('Asia/Shanghai');
		$erphp_life_days    = get_option('erphp_life_days');
		global $wpdb;
		$user_info = wp_get_current_user();
		$userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);

		if($userTypeInfo){
			if($userTypeInfo->userType == '10' && !$erphp_life_days){

			}else{
				if(time() > strtotime($userTypeInfo->endTime) +24*3600)
				{
					$wpdb->query("update $wpdb->iceinfo set userType=0,endTime='1000-01-01' where ice_user_id=".$user_info->ID);
					return 0;
				}
			}
			return $userTypeInfo->userType;
		}
	}
	return false;
}

function getUsreMemberTypeById($uid){
	date_default_timezone_set('Asia/Shanghai');
	$erphp_life_days    = get_option('erphp_life_days');
	global $wpdb;
	$userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$uid);
	if($userTypeInfo)
	{
		if($userTypeInfo->userType == '10' && !$erphp_life_days){

		}else{
			if(time() > strtotime($userTypeInfo->endTime) +24*3600)
			{
				$wpdb->query("update $wpdb->iceinfo set userType=0,endTime='1000-01-01' where ice_user_id=".$uid);
				return 0;
			}
		}
		return $userTypeInfo->userType;
	}
	return false;
}

function getUsreMemberTypeEndTime(){
	global $wpdb;
	$erphp_life_days    = get_option('erphp_life_days');
	$user_info = wp_get_current_user();
	$userTypeInfo=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);
	if($userTypeInfo)
	{
		if($userTypeInfo->userType == '10' && !$erphp_life_days){
			return '??????';
		}else{
			return $userTypeInfo->endTime;
		}
	}
	return false;
}

function getUsreMemberTypeEndTimeById($uid){
	global $wpdb;
	$erphp_life_days    = get_option('erphp_life_days');
	$userTypeInfo=$wpdb->get_row("select * from  ".$wpdb->iceinfo." where ice_user_id=".$uid);
	if($userTypeInfo)
	{
		if($userTypeInfo->userType == '10' && !$erphp_life_days){
			return '??????';
		}else{
			return $userTypeInfo->endTime;
		}
	}
	return false;
}

function epd_vip_gift($userType, $uid){
	$erphp_life_gift    = get_option('erphp_life_gift');
	$erphp_year_gift    = get_option('erphp_year_gift');
	$erphp_quarter_gift = get_option('erphp_quarter_gift');
	$erphp_month_gift  = get_option('erphp_month_gift');
	$erphp_day_gift  = get_option('erphp_day_gift');
	if($userType == '6' && $erphp_day_gift){
		addUserMoney($uid, $erphp_day_gift, 'VIP??????');
	}elseif($userType == '7' && $erphp_month_gift){
		addUserMoney($uid, $erphp_month_gift, 'VIP??????');
	}elseif($userType == '8' && $erphp_quarter_gift){
		addUserMoney($uid, $erphp_quarter_gift, 'VIP??????');
	}elseif($userType == '9' && $erphp_year_gift){
		addUserMoney($uid, $erphp_year_gift, 'VIP??????');
	}elseif($userType == '10' && $erphp_life_gift){
		addUserMoney($uid, $erphp_life_gift, 'VIP??????');
	}
}


function userPayMemberSetData($userType){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$user_info = wp_get_current_user();
	$oldUserType = getUsreMemberType();
	$erphp_life_days    = get_option('erphp_life_days');
	$erphp_year_days    = get_option('erphp_year_days');
	$erphp_quarter_days = get_option('erphp_quarter_days');
	$erphp_month_days  = get_option('erphp_month_days');
	$erphp_day_days  = get_option('erphp_day_days');

	if($oldUserType){
		$vip_update_pay = get_option('vip_update_pay');
		$oldEndTime = getUsreMemberTypeEndTime();
		if($userType==6)
		{
			if($erphp_day_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_day_days." day",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+0 day",strtotime($oldEndTime)));
			}
		}
		elseif($userType==7)
		{
			if($vip_update_pay){
				if($oldUserType < 7){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$user_info->ID." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_month_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_month_days." day",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 month",strtotime($oldEndTime)));
			}
		}
		elseif ($userType==8)
		{
			if($vip_update_pay){
				if($oldUserType < 8){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$user_info->ID." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_quarter_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_quarter_days." month",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+3 month",strtotime($oldEndTime)));
			}
		}
		elseif ($userType==9)
		{
			if($vip_update_pay){
				if($oldUserType < 9){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$user_info->ID." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_year_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_year_days." month",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 year",strtotime($oldEndTime)));
			}
		}
		elseif ($userType==10)
		{
			if($vip_update_pay){
				if($oldUserType < 10){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$user_info->ID." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_life_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_life_days." year",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("2038-01-01"));
			}
		}
	}else{
		$endTime=date("Y-m-d");
		if($userType==6)
		{
			if($erphp_day_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_day_days." day"));
			}else{
				$endTime=date("Y-m-d",strtotime("+0 day"));
			}
		}
		elseif($userType==7)
		{
			if($erphp_month_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_month_days." day"));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 month"));
			}
		}
		elseif ($userType==8)
		{
			if($erphp_quarter_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_quarter_days." month"));
			}else{
				$endTime=date("Y-m-d",strtotime("+3 month"));
			}
		}
		elseif ($userType==9)
		{
			if($erphp_year_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_year_days." month"));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 year"));
			}
		}
		elseif ($userType==10)
		{
			if($erphp_life_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_life_days." year"));
			}else{
				$endTime=date("Y-m-d",strtotime("2038-01-01"));
			}
		}
	}

	epd_vip_gift($userType, $user_info->ID);

	if($oldUserType){
		if($oldUserType > $userType){
			$userType = $oldUserType;
		}
	}

	$sql="update ".$wpdb->iceinfo." set userType=".$userType.", endTime='".$endTime."' where ice_user_id=".$user_info->ID;
	$wpdb->query($sql);

	return true;

}

function userSetMemberSetData($userType,$uid)
{
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$oldUserType = getUsreMemberTypeById($uid);
	$erphp_life_days    = get_option('erphp_life_days');
	$erphp_year_days    = get_option('erphp_year_days');
	$erphp_quarter_days = get_option('erphp_quarter_days');
	$erphp_month_days  = get_option('erphp_month_days');
	$erphp_day_days  = get_option('erphp_day_days');

	if($oldUserType){
		$vip_update_pay = get_option('vip_update_pay');
		$oldEndTime = getUsreMemberTypeEndTimeById($uid);

		if($userType==6)
		{
			if($erphp_day_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_day_days." day",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+0 day",strtotime($oldEndTime)));
			}
		}
		elseif($userType==7)
		{
			if($vip_update_pay){
				if($oldUserType < 7){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$uid." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_month_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_month_days." day",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 month",strtotime($oldEndTime)));
			}
		}
		elseif ($userType==8)
		{
			if($vip_update_pay){
				if($oldUserType < 8){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$uid." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_quarter_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_quarter_days." month",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+3 month",strtotime($oldEndTime)));
			}
		}
		elseif ($userType==9)
		{
			if($vip_update_pay){
				if($oldUserType < 9){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$uid." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_year_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_year_days." month",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 year",strtotime($oldEndTime)));
			}
		}
		elseif ($userType==10)
		{
			if($vip_update_pay){
				if($oldUserType < 10){
					$oldEndTime = $wpdb->get_var("select ice_time from $wpdb->vip where ice_user_id=".$uid." and ice_user_type=".$oldUserType." order by ice_time DESC limit 1");
				}
			}

			if($erphp_life_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_life_days." year",strtotime($oldEndTime)));
			}else{
				$endTime=date("Y-m-d",strtotime("2038-01-01"));
			}
		}
	}else{
		$endTime=date("Y-m-d");
		if($userType==6)
		{
			if($erphp_day_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_day_days." day"));
			}else{
				$endTime=date("Y-m-d",strtotime("+0 day"));
			}
		}
		elseif($userType==7)
		{
			if($erphp_month_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_month_days." day"));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 month"));
			}
		}
		elseif ($userType==8)
		{
			if($erphp_quarter_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_quarter_days." month"));
			}else{
				$endTime=date("Y-m-d",strtotime("+3 month"));
			}
		}
		elseif ($userType==9)
		{
			if($erphp_year_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_year_days." month"));
			}else{
				$endTime=date("Y-m-d",strtotime("+1 year"));
			}
		}
		elseif ($userType==10)
		{
			if($erphp_life_days){
				$endTime=date("Y-m-d",strtotime("+".$erphp_life_days." year"));
			}else{
				$endTime=date("Y-m-d",strtotime("2038-01-01"));
			}
		}
	}

	epd_vip_gift($userType, $uid);

	if($oldUserType){
		if($oldUserType > $userType){
			$userType = $oldUserType;
		}
	}

	$sql="update ".$wpdb->iceinfo." set userType=".$userType.",endTime='".$endTime."' where ice_user_id=".$uid;
	$wpdb->query($sql);

	return true;
}


function epd_set_wppay_success($order_num,$total_fee,$pay_method=''){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;

	$order=$wpdb->get_row("select * from $wpdb->icemoney where ice_num='".$order_num."'");
	if($order){
		if(!$order->ice_success){
			$wpdb->query("UPDATE $wpdb->icemoney SET ice_success=1, ice_money = '".$total_fee*get_option('ice_proportion_alipay')."',ice_alipay = '".$pay_method."',ice_success_time = '".date("Y-m-d H:i:s")."' WHERE ice_num = '".$order_num."'");

			$ppost = get_post($order->ice_post_id);
			erphpAddDownloadByWppay($ppost->post_title,$order->ice_post_id,$order->ice_user_id,$order_num,$total_fee*get_option('ice_proportion_alipay'),1,'',$ppost->post_author,$order->ice_aff,$order->ice_ip);

			$erphp_down=get_post_meta($order->ice_post_id, 'erphp_down',TRUE);
    		if($erphp_down == 6){
    			$ice_data = explode('|', trim($order->ice_data));
    			$email = $ice_data[0];
    			$num = $ice_data[1];

    			if(function_exists('doErphpActKa')){
    				if($num > 1){
    					$activation_num = '';
    					for($i=0; $i<$num; $i++){
    						$activation_num .= doErphpActKa($order->ice_user_id, $order->ice_post_id, $order_num).'<br>';
    					}
    				}else{
						$activation_num = doErphpActKa($order->ice_user_id, $order->ice_post_id, $order_num);
					}

					$wpdb->query("update $wpdb->icealipay set ice_data = '".$activation_num."' where ice_num='".$order_num."'");
					if($email){
						$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
						wp_mail($email, '???'.$ppost->post_title.'?????????', '???????????????'.$ppost->post_title.'????????????<br>'.$activation_num, $headers);
					}
				}
    		}

			$EPD = new EPD();
        	$EPD->doAuthorAff($total_fee*get_option('ice_proportion_alipay'), $ppost->post_author);

        	if($order->ice_user_id){
        		$EPD->doAff($total_fee*get_option('ice_proportion_alipay'), $order->ice_user_id);
        	}else{
        		if($order->ice_aff){
        			$EPD->doAff2($total_fee*get_option('ice_proportion_alipay'), $order->ice_aff);
        		}
        	}

			if(get_option('erphp_remind')){
				$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
				wp_mail(get_option('admin_email'), '???'.get_bloginfo('name').'??????????????? - '.$ppost->post_title, '?????????????????????'.$total_fee.'????????????'.$ppost->post_title.get_permalink($post_id), $headers);
			}

		}
	}
}

function epd_set_order_success($order_num,$total_fee,$pay_method=''){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	$order=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$wpdb->escape($order_num)."'");
	if($order){
		if(!$order->ice_success){
			if($pay_method == 'paypy'){
				$total_fee = $order->ice_money;
			}

			$total_fee_old = $total_fee;

			if(!$order->ice_post_id && !$order->ice_user_type){
				$epd_game_price  = get_option('epd_game_price');
		        if($epd_game_price){
		          	$cnt = count($epd_game_price['buy']);
		          	for($i=0; $i<$cnt;$i++){
			            if($total_fee == $epd_game_price['buy'][$i]){
			              	$total_fee = $epd_game_price['get'][$i];
			              	break;
			            }
		          	}
		        }
		    }

			$updatOrder=$wpdb->query("update $wpdb->icemoney set ice_success=1, ice_money = '".$total_fee*get_option('ice_proportion_alipay')."', ice_alipay = '".$pay_method."', ice_success_time = '".date("Y-m-d H:i:s")."' where ice_num='".$wpdb->escape($order_num)."'");
			if($updatOrder){
				addUserMoney($order->ice_user_id,$total_fee*get_option('ice_proportion_alipay'),'????????????');
			}

			if($order->ice_post_id){
				$okMoney=erphpGetUserOkMoneyById($order->ice_user_id);
                $postid = $order->ice_post_id;
                $index = '';$index_name = '';
                $price = $total_fee*get_option('ice_proportion_alipay');
                if($okMoney >= $price){
                    if(erphpSetUserMoneyXiaoFeiByUid($price,$order->ice_user_id))
                    {
                    	addUserMoneyLog($order->ice_user_id, '-'.$price, '????????????');
                    	if($order->ice_post_index){
                    		$index = $order->ice_post_index;
                    		$urls = get_post_meta($postid, 'down_urls', true);
							if($urls){
								$cnt = count($urls['index']);
								if($cnt){
									for($i=0; $i<$cnt;$i++){
										if($urls['index'][$i] == $index){
					    					$index_name = $urls['name'][$i];
					    					break;
					    				}
									}
								}
							}
                    	}

                        $subject   = get_post($postid)->post_title;
                        if($index_name){
							$subject .= ' - '.$index_name;
						}
                        $postUserId=get_post($postid)->post_author;
                        
                        $result=erphpAddDownloadByUid($subject, $postid, $order->ice_user_id,$price,1, '', $postUserId, $index, $order->ice_ip);
                        if($result)
                        {

                        	$erphp_down=get_post_meta($order->ice_post_id, 'erphp_down',TRUE);
				    		if($erphp_down == 6){
				    			$ice_data = explode('|', trim($order->ice_data));
				    			$email = $ice_data[0];
				    			$num = $ice_data[1];

				    			if(function_exists('doErphpActKa')){
				    				if($num > 1){
				    					$activation_num = '';
				    					for($i=0; $i<$num; $i++){
				    						$activation_num .= doErphpActKa($order->ice_user_id, $order->ice_post_id, $order_num).'<br>';
				    					}
				    				}else{
										$activation_num = doErphpActKa($order->ice_user_id, $order->ice_post_id, $order_num);
									}

									$wpdb->query("update $wpdb->icealipay set ice_data = '".$activation_num."' where ice_num='".$order_num."'");
									if($email){
										$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
										wp_mail($email, '???'.$subject.'?????????', '???????????????'.$subject.'????????????<br>'.$activation_num, $headers);
									}
								}
				    		}else{
	                        	$down_activation = get_post_meta($postid, 'down_activation', true);
	                        	if($down_activation && function_exists('doErphpAct')){
									$activation_num = doErphpAct($order->ice_user_id,$postid);
									$wpdb->query("update $wpdb->icealipay set ice_data = '".$activation_num."' where ice_url='".$result."'");
									$cuser = get_user_by('id',$order->ice_user_id);
									if($cuser && $cuser->user_email){
										$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
										wp_mail($cuser->user_email, '???'.$subject.'????????????', '?????????????????????'.$subject.'???????????????'.$activation_num, $headers);
									}
								}
							}
                            
                            $EPD = new EPD();
                            $EPD->doAuthorAff($price, $postUserId);
							$EPD->doAff($price, $order->ice_user_id);

							if(get_option('erphp_remind')){
								$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
								wp_mail(get_option('admin_email'), '???'.get_bloginfo('name').'??????????????? - '.$subject, '??????'.get_user_by('id',$order->ice_user_id)->user_login.'??????'.$price.get_option('ice_name_alipay').'?????????'.$subject.get_permalink($postid), $headers);
							}

							do_action( 'erphpdown_post_checkout', $order->ice_user_id, $postid, $index, $price, $result );
                        } 
                    }
                }
			}elseif($order->ice_user_type){
				addUserMoney($order->ice_user_id, '-'.$total_fee*get_option('ice_proportion_alipay'), '??????VIP');
				userSetMemberSetData($order->ice_user_type,$order->ice_user_id);
				addVipLogByAdmin($total_fee*get_option('ice_proportion_alipay'), $order->ice_user_type, $order->ice_user_id);
				$EPD = new EPD();
				$EPD->doAff($total_fee*get_option('ice_proportion_alipay'), $order->ice_user_id);	

				if(get_option('erphp_remind')){
					$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
					$typeName = getVipTypeName($order->ice_user_type);
					wp_mail(get_option('admin_email'), '???'.get_bloginfo('name').'???VIP???????????? - '.$typeName, '??????'.get_user_by('id',$order->ice_user_id)->user_login.'??????'.$total_fee.'????????????'.$typeName, $headers);
				}
			}else{
				if(get_option('erphp_remind_recharge')){
					$headers = 'Content-Type: text/html; charset=' . get_option('blog_charset') . "\n";
					wp_mail(get_option('admin_email'), '???'.get_bloginfo('name').'??????????????? - '.$total_fee_old.'???', '??????'.get_user_by('id',$order->ice_user_id)->user_login.'???????????????'.$total_fee_old.'??????', $headers);
				}
			}
		}
	}
}

function erphpCheckAlipayReturnNum($orderNum,$money)
{
	global $wpdb;
	$row=$wpdb->get_row("select * from ".$wpdb->icemoney." where ice_num='".$orderNum."'");
	if($row)
	{
		if($row->ice_money == $money)
		{
			return true;
		}
	}
	return false;
}
function erphpAddDownloadByUid($subject,$postid,$userid,$price,$success,$data,$postUserId,$index='',$ip='')
{
	date_default_timezone_set('Asia/Shanghai');
	
	global $wpdb;
	$subject = str_replace("'","",$subject);
	$subject = str_replace("???","",$subject);
	$url       = md5(date("YmdHis").$postid.mt_rand(1000000, 9999999));
	$orderNum  = date("YmdHis").mt_rand(10000, 99999);
	$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,ice_index,
	ice_author,ice_ip)VALUES ('$orderNum','$subject','$postid','$price','$success','$url','".$userid."','".date("Y-m-d H:i:s")."','".$data."','".$index."','$postUserId','".$ip."')";
	if($wpdb->query($sql))
	{
		return $url;
	}
	
	return false;
}
function erphpAddDownloadByWppay($subject,$postid,$userid,$num,$price,$success,$data,$postUserId,$aff,$ip,$index='')
{
	date_default_timezone_set('Asia/Shanghai');
	
	global $wpdb;
	$subject = str_replace("'","",$subject);
	$subject = str_replace("???","",$subject);
	$url       = md5(date("YmdHis").$postid.mt_rand(1000000, 9999999));
	$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,ice_index,
	ice_author,ice_aff,ice_ip)VALUES ('$num','$subject','$postid','$price','$success','$url','".$userid."','".date("Y-m-d H:i:s")."','".$data."','".$index."','$postUserId','".$aff."','".$ip."')";
	if($wpdb->query($sql))
	{
		return $url;
	}
	
	return false;
}
function erphpAddDownloadBuyNum($subject,$postid,$num,$price,$success,$data,$postUserId,$index='')
{
	date_default_timezone_set('Asia/Shanghai');
	
	global $wpdb;
	$subject = str_replace("'","",$subject);
	$subject = str_replace("???","",$subject);
	$user_info = wp_get_current_user();
	$url       = md5(date("YmdHis").$postid.mt_rand(1000000, 9999999));
	$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,ice_index,
	ice_author)VALUES ('$num','$subject','$postid','$price','$success','$url','".$user_info->ID."','".date("Y-m-d H:i:s")."','".$data."','".$index."','$postUserId')";
	if($wpdb->query($sql))
	{
		return $url;
	}
	
	return false;
}
function erphpAddDownload($subject,$postid,$price,$success,$data,$postUserId,$index='',$ip='')
{
	date_default_timezone_set('Asia/Shanghai');
	
	global $wpdb;
	$subject = str_replace("'","",$subject);
	$subject = str_replace("???","",$subject);
	$user_info = wp_get_current_user();
	$url       = md5(date("YmdHis").$postid.mt_rand(1000000, 9999999));
	$orderNum  = date("YmdHis").mt_rand(10000, 99999);
	$sql       = "INSERT INTO $wpdb->icealipay (ice_num,ice_title,ice_post,ice_price,ice_success,ice_url,ice_user_id,ice_time,ice_data,ice_index,
	ice_author,ice_ip)VALUES ('$orderNum','$subject','$postid','$price','$success','$url','".$user_info->ID."','".date("Y-m-d H:i:s")."','".$data."','".$index."','$postUserId','".$ip."')";
	if($wpdb->query($sql))
	{
		return $url;
	}
	
	return false;
}
function erphpAddDownloadIndex($postid,$price,$index)
{
	date_default_timezone_set('Asia/Shanghai');
	
	global $wpdb;
	$user_info = wp_get_current_user();
	$url       = md5(date("YmdHis").$postid.mt_rand(1000000, 9999999));
	$orderNum  = date("YmdHis").mt_rand(10000, 99999);
	$sql       = "INSERT INTO $wpdb->iceindex (ice_num,ice_post,ice_price,ice_url,ice_user_id,ice_time,ice_index)VALUES ('$orderNum','$postid','$price','$url','".$user_info->ID."','".date("Y-m-d H:i:s")."','".$index."')";
	if($wpdb->query($sql))
	{
		return $url;
	}
	
	return false;
}
function erphpSetUserMoneyXiaoFei($num)
{
	if($num > 0){
		global $wpdb;
		$user_info=wp_get_current_user();
		return $wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money+".$num." where ice_user_id=".$user_info->ID);
	}else{
		return false;
	}
}
function erphpSetUserMoneyXiaoFeiByUid($num,$uid)
{
	if($num > 0){
		global $wpdb;
		return $wpdb->query("update $wpdb->iceinfo set ice_get_money=ice_get_money+".$num." where ice_user_id=".$uid);
	}else{
		return false;
	}
}
function erphpGetUserAllXiaofei($uid){
	global $wpdb;
	$money = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and ice_user_id=".$uid);
	$money2 = $wpdb->get_var("SELECT sum(ice_price) FROM $wpdb->vip where ice_user_id=".$uid);
	$money += $money2;
	return $money ? $money :'0';
}
function erphpGetUserOkAff()
{
	global $wpdb;
	$user_info=wp_get_current_user();
	if($user_info)
	{
		$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);
		return $userMoney ?sprintf("%.2f",($userMoney->ice_have_aff - $userMoney->ice_get_aff)) : 0;
	}
	return 0;
}
function erphpGetUserOkAffById($uid)
{
	global $wpdb;
	if($uid)
	{
		$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$uid);
		return $userMoney ?sprintf("%.2f",($userMoney->ice_have_aff - $userMoney->ice_get_aff)) : 0;
	}
	return 0;
}
function erphpGetUserOkMoney()
{
	global $wpdb;
	$user_info=wp_get_current_user();
	if($user_info)
	{
		$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$user_info->ID);
		return $userMoney ? sprintf("%.2f",($userMoney->ice_have_money - $userMoney->ice_get_money)) : 0;
	}
	return 0;
}
function erphpGetUserOkMoneyById($uid)
{
	global $wpdb;
	if($uid){
		$userMoney=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$uid);
		return $userMoney ? sprintf("%.2f",($userMoney->ice_have_money - $userMoney->ice_get_money)) : 0;
	}
	return 0;
}
function getProductSales($pid){
	global $wpdb;
	$total_trade  = $wpdb->get_var("SELECT COUNT(ice_id) FROM $wpdb->icealipay WHERE ice_success>0 and ice_post=".$pid);
	return $total_trade;
}

function getProductMember($pid){
	$type = get_post_meta($pid,"member_down",true);
	if($type == "1"){
		return "???";
	}elseif($type == "2"){
		return "VIP 5???";
	}elseif($type == "3"){
		return "VIP??????";
	}elseif($type == "4"){
		return "VIP??????";
	}elseif($type == "5"){
		return "VIP 8???";
	}elseif($type == "6"){
		return "??????VIP??????";
	}elseif($type == "7"){
		return "??????VIP??????";
	}elseif($type == "8"){
		return "??????VIP??????";
	}elseif($type == "9"){
		return "??????VIP??????";
	}elseif($type == "10"){
		return "VIP????????????";
	}elseif($type == "11"){
		return "VIP????????????|??????5???";
	}elseif($type == "12"){
		return "VIP????????????|??????8???";
	}elseif($type == "13"){
		return "VIP 5???|????????????";
	}elseif($type == "14"){
		return "VIP 8???|????????????";
	}elseif($type == "15"){
		return "??????VIP??????";
	}elseif($type == "16"){
		return "??????VIP??????";
	}else{
		return "??????";
	}
}

function getProductDownType($pid){
	$start_down = get_post_meta($pid,"start_down",true);
	$start_down2 = get_post_meta($pid,"start_down2",true);
	$start_see = get_post_meta($pid,"start_see",true);
	$start_see2 = get_post_meta($pid,"start_see2",true);
	$erphp_down = get_post_meta($pid,"erphp_down",true);
	if($start_down == "yes"){
		return "??????";
	}elseif($start_down2 == "yes"){
		return "?????????";
	}elseif($start_see == "yes"){
		return "??????";
	}elseif($start_see2 == "yes"){
		return "????????????";
	}elseif($erphp_down == "6"){
		return "??????";
	}else{
		return "???";
	}
}

function getVipTypeName($userTypeId){
	$erphp_life_name    = get_option('erphp_life_name')?get_option('erphp_life_name'):'??????VIP';
	$erphp_year_name    = get_option('erphp_year_name')?get_option('erphp_year_name'):'??????VIP';
	$erphp_quarter_name = get_option('erphp_quarter_name')?get_option('erphp_quarter_name'):'??????VIP';
	$erphp_month_name  = get_option('erphp_month_name')?get_option('erphp_month_name'):'??????VIP';
	$erphp_day_name  = get_option('erphp_day_name')?get_option('erphp_day_name'):'??????VIP';
	$erphp_vip_name  = get_option('erphp_vip_name')?get_option('erphp_vip_name'):'VIP';

    if($userTypeId==6){
        return $erphp_day_name;
    }elseif($userTypeId==7){
        return $erphp_month_name;
    }elseif ($userTypeId==8){
        return $erphp_quarter_name;
    }elseif ($userTypeId==9){
        return $erphp_year_name;
    }elseif ($userTypeId==10){
        return $erphp_life_name;
    }else {
        return '????????????';
    }
}

add_action('wp_dashboard_setup', 'erphp_modify_dashboard_widgets' );
function erphp_modify_dashboard_widgets() {
	if(current_user_can('administrator')){
		add_meta_box( 'erphpdown_total_widget', 'Erphpdown', 'erphpdown_dashboard_widget_function','dashboard', 'normal', 'core' );
	}
}
function erphpdown_dashboard_widget_function() {
	global $wpdb, $wppay_table_name;
	if(current_user_can('administrator')){
		$ice_proportion_alipay = get_option('ice_proportion_alipay');
		$ice_proportion_alipay = $ice_proportion_alipay?$ice_proportion_alipay:1;

		$today_chong_money = $wpdb->get_var("SELECT SUM(ice_money) FROM $wpdb->icemoney WHERE ice_success>0 and TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");
		$today_order_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and TO_DAYS(NOW())- TO_DAYS(ice_time) = 0");

		$yestoday_chong_money = $wpdb->get_var("SELECT SUM(ice_money) FROM $wpdb->icemoney WHERE ice_success>0 and TO_DAYS(NOW())- TO_DAYS(ice_time) = 1");
		$yestoday_order_money   = $wpdb->get_var("SELECT SUM(ice_price) FROM $wpdb->icealipay WHERE ice_success>0 and TO_DAYS(NOW())- TO_DAYS(ice_time) = 1");

		echo '<div class="activity-block"><ul style="margin-top:0;overflow:hidden">';
		echo '<li style="margin-bottom:10px;float:left;width:50%"><span>????????????</span>???<a href="'.admin_url('admin.php?page=erphpdown/admin/erphp-chong-list.php').'">'.($today_chong_money?$today_chong_money:'0').' '.get_option('ice_name_alipay').'</a></li>';
		echo '<li style="margin-bottom:10px;float:left;width:50%"><span>????????????</span>???<a href="'.admin_url('admin.php?page=erphpdown/admin/erphp-chong-list.php').'">'.($yestoday_chong_money?$yestoday_chong_money:'0').' '.get_option('ice_name_alipay').'</a></li>';
		echo '<li style="margin-bottom:10px;float:left;width:50%"><span>????????????</span>???<a href="'.admin_url('admin.php?page=erphpdown/admin/erphp-orders-list.php').'">'.($today_order_money?$today_order_money:'0').' '.get_option('ice_name_alipay').'</a></li>';
		echo '<li style="margin-bottom:10px;float:left;width:50%"><span>????????????</span>???<a href="'.admin_url('admin.php?page=erphpdown/admin/erphp-orders-list.php').'">'.($yestoday_order_money?$yestoday_order_money:'0').' '.get_option('ice_name_alipay').'</a></li>';
		echo '</ul></div>';

		$month_total = $wpdb->get_row("select
		    sum(case month(ice_time) when '1'  then ice_money else 0 end) as Jan,
		    sum(case month(ice_time) when '2'  then ice_money else 0 end) as Feb,
		    sum(case month(ice_time) when '3'  then ice_money else 0 end) as Mar,
		    sum(case month(ice_time) when '4'  then ice_money else 0 end) as Apr,
		    sum(case month(ice_time) when '5'  then ice_money else 0 end) as May,
		    sum(case month(ice_time) when '6'  then ice_money else 0 end) as June,
		    sum(case month(ice_time) when '7'  then ice_money else 0 end) as July,
		    sum(case month(ice_time) when '8'  then ice_money else 0 end) as Aug,
		    sum(case month(ice_time) when '9'  then ice_money else 0 end) as Sept,
		    sum(case month(ice_time) when '10' then ice_money else 0 end) as Oct,
		    sum(case month(ice_time) when '11' then ice_money else 0 end) as Nov,
		    sum(case month(ice_time) when '12' then ice_money else 0 end) as Dece
		from $wpdb->icemoney where year(ice_time)='".date("Y")."' and ice_success>0;");

		$month_total2 = $wpdb->get_row("select
		    sum(case month(order_time) when '1'  then post_price else 0 end) as Jan,
		    sum(case month(order_time) when '2'  then post_price else 0 end) as Feb,
		    sum(case month(order_time) when '3'  then post_price else 0 end) as Mar,
		    sum(case month(order_time) when '4'  then post_price else 0 end) as Apr,
		    sum(case month(order_time) when '5'  then post_price else 0 end) as May,
		    sum(case month(order_time) when '6'  then post_price else 0 end) as June,
		    sum(case month(order_time) when '7'  then post_price else 0 end) as July,
		    sum(case month(order_time) when '8'  then post_price else 0 end) as Aug,
		    sum(case month(order_time) when '9'  then post_price else 0 end) as Sept,
		    sum(case month(order_time) when '10' then post_price else 0 end) as Oct,
		    sum(case month(order_time) when '11' then post_price else 0 end) as Nov,
		    sum(case month(order_time) when '12' then post_price else 0 end) as Dece
		from $wppay_table_name where year(order_time)='".date("Y")."' and order_status=1;");

		$day_total = $wpdb->get_row("select
		    sum(case day(ice_time) when '1'  then ice_money else 0 end) as one,
		    sum(case day(ice_time) when '2'  then ice_money else 0 end) as two,
		    sum(case day(ice_time) when '3'  then ice_money else 0 end) as three,
		    sum(case day(ice_time) when '4'  then ice_money else 0 end) as four,
		    sum(case day(ice_time) when '5'  then ice_money else 0 end) as five,
		    sum(case day(ice_time) when '6'  then ice_money else 0 end) as six,
		    sum(case day(ice_time) when '7'  then ice_money else 0 end) as seven,
		    sum(case day(ice_time) when '8'  then ice_money else 0 end) as eight,
		    sum(case day(ice_time) when '9'  then ice_money else 0 end) as nine,
		    sum(case day(ice_time) when '10' then ice_money else 0 end) as ten,
		    sum(case day(ice_time) when '11' then ice_money else 0 end) as eleven,
		    sum(case day(ice_time) when '12' then ice_money else 0 end) as twelve,
		    sum(case day(ice_time) when '13' then ice_money else 0 end) as thirteen,
		    sum(case day(ice_time) when '14' then ice_money else 0 end) as fourteen,
		    sum(case day(ice_time) when '15' then ice_money else 0 end) as fifteen,
		    sum(case day(ice_time) when '16' then ice_money else 0 end) as sixteen,
		    sum(case day(ice_time) when '17' then ice_money else 0 end) as seventeen,
		    sum(case day(ice_time) when '18' then ice_money else 0 end) as eighteen,
		    sum(case day(ice_time) when '19' then ice_money else 0 end) as nineteen,
		    sum(case day(ice_time) when '20' then ice_money else 0 end) as twenty,
		    sum(case day(ice_time) when '21' then ice_money else 0 end) as twentyone,
		    sum(case day(ice_time) when '22' then ice_money else 0 end) as twentytwo,
		    sum(case day(ice_time) when '23' then ice_money else 0 end) as twentythree,
		    sum(case day(ice_time) when '24' then ice_money else 0 end) as twentyfour,
		    sum(case day(ice_time) when '25' then ice_money else 0 end) as twentyfive,
		    sum(case day(ice_time) when '26' then ice_money else 0 end) as twentysix,
		    sum(case day(ice_time) when '27' then ice_money else 0 end) as twentyseven,
		    sum(case day(ice_time) when '28' then ice_money else 0 end) as twentyeight,
		    sum(case day(ice_time) when '29' then ice_money else 0 end) as twentynine,
		    sum(case day(ice_time) when '30' then ice_money else 0 end) as thirty,
		    sum(case day(ice_time) when '31' then ice_money else 0 end) as thirtyone
		from $wpdb->icemoney where year(ice_time)='".date("Y")."' and month(ice_time)='".ltrim(date("m"),'0')."' and ice_success>0;");

		$day_total2 = $wpdb->get_row("select
		    sum(case day(order_time) when '1'  then post_price else 0 end) as one,
		    sum(case day(order_time) when '2'  then post_price else 0 end) as two,
		    sum(case day(order_time) when '3'  then post_price else 0 end) as three,
		    sum(case day(order_time) when '4'  then post_price else 0 end) as four,
		    sum(case day(order_time) when '5'  then post_price else 0 end) as five,
		    sum(case day(order_time) when '6'  then post_price else 0 end) as six,
		    sum(case day(order_time) when '7'  then post_price else 0 end) as seven,
		    sum(case day(order_time) when '8'  then post_price else 0 end) as eight,
		    sum(case day(order_time) when '9'  then post_price else 0 end) as nine,
		    sum(case day(order_time) when '10' then post_price else 0 end) as ten,
		    sum(case day(order_time) when '11' then post_price else 0 end) as eleven,
		    sum(case day(order_time) when '12' then post_price else 0 end) as twelve,
		    sum(case day(order_time) when '13' then post_price else 0 end) as thirteen,
		    sum(case day(order_time) when '14' then post_price else 0 end) as fourteen,
		    sum(case day(order_time) when '15' then post_price else 0 end) as fifteen,
		    sum(case day(order_time) when '16' then post_price else 0 end) as sixteen,
		    sum(case day(order_time) when '17' then post_price else 0 end) as seventeen,
		    sum(case day(order_time) when '18' then post_price else 0 end) as eighteen,
		    sum(case day(order_time) when '19' then post_price else 0 end) as nineteen,
		    sum(case day(order_time) when '20' then post_price else 0 end) as twenty,
		    sum(case day(order_time) when '21' then post_price else 0 end) as twentyone,
		    sum(case day(order_time) when '22' then post_price else 0 end) as twentytwo,
		    sum(case day(order_time) when '23' then post_price else 0 end) as twentythree,
		    sum(case day(order_time) when '24' then post_price else 0 end) as twentyfour,
		    sum(case day(order_time) when '25' then post_price else 0 end) as twentyfive,
		    sum(case day(order_time) when '26' then post_price else 0 end) as twentysix,
		    sum(case day(order_time) when '27' then post_price else 0 end) as twentyseven,
		    sum(case day(order_time) when '28' then post_price else 0 end) as twentyeight,
		    sum(case day(order_time) when '29' then post_price else 0 end) as twentynine,
		    sum(case day(order_time) when '30' then post_price else 0 end) as thirty,
		    sum(case day(order_time) when '31' then post_price else 0 end) as thirtyone
		from $wppay_table_name where year(order_time)='".date("Y")."' and month(order_time)='".ltrim(date("m"),'0')."' and order_status=1;");
?>
		<script src="<?php echo ERPHPDOWN_URL;?>/static/chart.js"></script>
		<canvas id="erphpdown_month_total" class="activity-block"></canvas>
		<canvas id="erphpdown_year_total" class="activity-block"></canvas>
		<script>
			var config = {
				type: 'bar',
				data: {
					labels: ['1???', '2???', '3???', '4???', '5???', '6???', '7???', '8???', '9???', '10???', '11???', '12???'],
					datasets: [{
						label: '??????',
						backgroundColor: window.chartColors.red,
						borderColor: window.chartColors.red,
						data: [
							<?php echo $month_total->Jan / $ice_proportion_alipay;?>,
							<?php echo $month_total->Feb / $ice_proportion_alipay;?>,
							<?php echo $month_total->Mar / $ice_proportion_alipay;?>,
							<?php echo $month_total->Apr / $ice_proportion_alipay;?>,
							<?php echo $month_total->May / $ice_proportion_alipay;?>,
							<?php echo $month_total->June / $ice_proportion_alipay;?>,
							<?php echo $month_total->July / $ice_proportion_alipay;?>,
							<?php echo $month_total->Aug / $ice_proportion_alipay;?>,
							<?php echo $month_total->Sept / $ice_proportion_alipay;?>,
							<?php echo $month_total->Oct / $ice_proportion_alipay;?>,
							<?php echo $month_total->Nov / $ice_proportion_alipay;?>,
							<?php echo $month_total->Dece / $ice_proportion_alipay;?>
						],
						fill: false,
					}, {
						label: '???????????????????????????????????????',
						fill: false,
						backgroundColor: window.chartColors.blue,
						borderColor: window.chartColors.blue,
						data: [
							<?php echo $month_total2->Jan?$month_total2->Jan:0;?>,
							<?php echo $month_total2->Feb?$month_total2->Feb:0;?>,
							<?php echo $month_total2->Mar?$month_total2->Mar:0;?>,
							<?php echo $month_total2->Apr?$month_total2->Apr:0;?>,
							<?php echo $month_total2->May?$month_total2->May:0;?>,
							<?php echo $month_total2->June?$month_total2->June:0;?>,
							<?php echo $month_total2->July?$month_total2->July:0;?>,
							<?php echo $month_total2->Aug?$month_total2->Aug:0;?>,
							<?php echo $month_total2->Sept?$month_total2->Sept:0;?>,
							<?php echo $month_total2->Oct?$month_total2->Oct:0;?>,
							<?php echo $month_total2->Nov?$month_total2->Nov:0;?>,
							<?php echo $month_total2->Dece?$month_total2->Dece:0;?>
						],
					}]
				},
				options: {
					responsive: true,
					title: {
						display: true,
						text: '???????????????????????????'
					},
					tooltips: {
						mode: 'index',
						intersect: false,
					},
					hover: {
						mode: 'nearest',
						intersect: true
					}
				}
			};

			var config2 = {
				type: 'bar',
				data: {
					labels: ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31'],
					datasets: [{
						label: '??????',
						backgroundColor: window.chartColors.red,
						borderColor: window.chartColors.red,
						data: [
							<?php echo $day_total->one / $ice_proportion_alipay;?>,
							<?php echo $day_total->two / $ice_proportion_alipay;?>,
							<?php echo $day_total->three / $ice_proportion_alipay;?>,
							<?php echo $day_total->four / $ice_proportion_alipay;?>,
							<?php echo $day_total->five / $ice_proportion_alipay;?>,
							<?php echo $day_total->six / $ice_proportion_alipay;?>,
							<?php echo $day_total->seven / $ice_proportion_alipay;?>,
							<?php echo $day_total->eight / $ice_proportion_alipay;?>,
							<?php echo $day_total->nine / $ice_proportion_alipay;?>,
							<?php echo $day_total->ten / $ice_proportion_alipay;?>,
							<?php echo $day_total->eleven / $ice_proportion_alipay;?>,
							<?php echo $day_total->twelve / $ice_proportion_alipay;?>,
							<?php echo $day_total->thirteen / $ice_proportion_alipay;?>,
							<?php echo $day_total->fourteen / $ice_proportion_alipay;?>,
							<?php echo $day_total->fifteen / $ice_proportion_alipay;?>,
							<?php echo $day_total->sixteen / $ice_proportion_alipay;?>,
							<?php echo $day_total->seventeen / $ice_proportion_alipay;?>,
							<?php echo $day_total->eighteen / $ice_proportion_alipay;?>,
							<?php echo $day_total->nineteen / $ice_proportion_alipay;?>,
							<?php echo $day_total->twenty / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentyone / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentytwo / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentythree / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentyfour / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentyfive / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentysix / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentyseven / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentyeight / $ice_proportion_alipay;?>,
							<?php echo $day_total->twentynine / $ice_proportion_alipay;?>,
							<?php echo $day_total->thirty / $ice_proportion_alipay;?>,
							<?php echo $day_total->thirtyone / $ice_proportion_alipay;?>
						],
						fill: false,
					}, {
						label: '???????????????????????????????????????',
						fill: false,
						backgroundColor: window.chartColors.blue,
						borderColor: window.chartColors.blue,
						data: [
							<?php echo $day_total2->one?$day_total2->one:0;?>,
							<?php echo $day_total2->two?$day_total2->two:0;?>,
							<?php echo $day_total2->three?$day_total2->three:0;?>,
							<?php echo $day_total2->four?$day_total2->four:0;?>,
							<?php echo $day_total2->five?$day_total2->five:0;?>,
							<?php echo $day_total2->six?$day_total2->six:0;?>,
							<?php echo $day_total2->seven?$day_total2->seven:0;?>,
							<?php echo $day_total2->eight?$day_total2->eight:0;?>,
							<?php echo $day_total2->nine?$day_total2->nine:0;?>,
							<?php echo $day_total2->ten?$day_total2->ten:0;?>,
							<?php echo $day_total2->eleven?$day_total2->eleven:0;?>,
							<?php echo $day_total2->twelve?$day_total2->twelve:0;?>,
							<?php echo $day_total2->thirteen?$day_total2->thirteen:0;?>,
							<?php echo $day_total2->fourteen?$day_total2->fourteen:0;?>,
							<?php echo $day_total2->fifteen?$day_total2->fifteen:0;?>,
							<?php echo $day_total2->sixteen?$day_total2->sixteen:0;?>,
							<?php echo $day_total2->seventeen?$day_total2->seventeen:0;?>,
							<?php echo $day_total2->eighteen?$day_total2->eighteen:0;?>,
							<?php echo $day_total2->nineteen?$day_total2->nineteen:0;?>,
							<?php echo $day_total2->twenty?$day_total2->twenty:0;?>,
							<?php echo $day_total2->twentyone?$day_total2->twentyone:0;?>,
							<?php echo $day_total2->twentytwo?$day_total2->twentytwo:0;?>,
							<?php echo $day_total2->twentythree?$day_total2->twentythree:0;?>,
							<?php echo $day_total2->twentyfour?$day_total2->twentyfour:0;?>,
							<?php echo $day_total2->twentyfive?$day_total2->twentyfive:0;?>,
							<?php echo $day_total2->twentysix?$day_total2->twentysix:0;?>,
							<?php echo $day_total2->twentyseven?$day_total2->twentyseven:0;?>,
							<?php echo $day_total2->twentyeight?$day_total2->twentyeight:0;?>,
							<?php echo $day_total2->twentynine?$day_total2->twentynine:0;?>,
							<?php echo $day_total2->thirty?$day_total2->thirty:0;?>,
							<?php echo $day_total2->thirtyone?$day_total2->thirtyone:0;?>
						],
					}]
				},
				options: {
					responsive: true,
					title: {
						display: true,
						text: '???????????????????????????'
					},
					tooltips: {
						mode: 'index',
						intersect: false,
					},
					hover: {
						mode: 'nearest',
						intersect: true
					}
				}
			};

			window.onload = function() {
				var ctx = document.getElementById('erphpdown_year_total').getContext('2d');
				window.myLine = new Chart(ctx, config);

				var ctx2 = document.getElementById('erphpdown_month_total').getContext('2d');
				window.myLine2 = new Chart(ctx2, config2);
			};
		</script>

<?php
		echo '<div style="padding: 8px 12px 0;margin:0 -12px"><i class="dashicons dashicons-external"></i> ??????????????????????????????<a href="https://www.mobantu.com/7191.html" target="_blank">Modown</a>???</div>';
	}
}

function addUserAff($userId,$money){
	global $wpdb;
	$myinfo=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$userId);
	if(!$myinfo){
		return $wpdb->query("insert into $wpdb->iceinfo(ice_have_money,ice_user_id,ice_get_money,ice_have_aff)values(0,'$userId',0,'$money')");
	}else{
		return $wpdb->query("update $wpdb->iceinfo set ice_have_aff=ice_have_aff+".$money." where ice_user_id=".$userId);
	}
}

function addUserAffXiaoFei($userId,$money){
	global $wpdb;
	return $wpdb->query("update $wpdb->iceinfo set ice_get_aff=ice_get_aff+".$money." where ice_user_id=".$userId);	
}

function addUserMoney($userId,$money,$why=''){
	global $wpdb;
	if($userId){
		if($why){
			addUserMoneyLog($userId,$money,$why);
		}
		$myinfo=$wpdb->get_row("select * from ".$wpdb->iceinfo." where ice_user_id=".$userId);
		if(!$myinfo){
			return $wpdb->query("insert into $wpdb->iceinfo(ice_have_money,ice_user_id,ice_get_money)values('$money','$userId',0)");
		}else{
			return $wpdb->query("update $wpdb->iceinfo set ice_have_money=ice_have_money+".$money." where ice_user_id=".$userId);
		}
	}else{
		return false;
	}
}

function addUserMoneyLog($userId,$money,$why=''){
	date_default_timezone_set('Asia/Shanghai');
	global $wpdb;
	if($userId){
		if($wpdb->get_var("show tables like '".$wpdb->icelog."'")){
			$wpdb->query("insert into $wpdb->icelog(user_id,ice_money,ice_note,ice_time)values('$userId','$money','$why','".date("Y-m-d H:i:s")."')");
		}
	}
}

function erphpdown_check_xiaofei($uid){
	global $wpdb;
	$down_info=$wpdb->get_row("select * from ".$wpdb->icealipay." where ice_success=1 and ice_user_id=".$uid);
	if($down_info)
		return true;
	return false;
}

function erphpdown_check_checkin($uid){
	date_default_timezone_set('Asia/Shanghai');
    global $wpdb;
    $result = $wpdb->get_var("select count(ID) from $wpdb->checkin where TO_DAYS(create_time) = TO_DAYS(NOW()) and user_id=".$uid);
    if($result){
        return 1;
    }
    return 0;
}

function erphpdown_download_file($file_dir){
	//$file_dir = iconv('UTF-8', 'GBK//TRANSLIT', $file_dir);
	if(substr($file_dir,0,7) == 'http://' || substr($file_dir,0,8) == 'https://' || substr($file_dir,0,10) == 'thunder://' || substr($file_dir,0,7) == 'magnet:' || substr($file_dir,0,5) == 'ed2k:' || substr($file_dir,0,4) == 'ftp:'){
		$file_path = chop($file_dir);
		$allow_type = get_option('erphpdown_direct_type');
		if($allow_type){
			$allow = explode(",",$allow_type); 
			if (erphpdown_file_suffix($file_path,$allow)){
	            ob_clean();
	            ob_start();
	            if(strpos(strtolower($file_path),'.pdf')){
	            	header('Content-type: application/pdf');
	            }
	            header('Content-Disposition: attachment; filename="'.basename($file_path).'"');
				readfile($file_path);
			}else{
				echo "<script type='text/javascript'>window.location='$file_path';</script>";
			}
		}else{
			echo "<script type='text/javascript'>window.location='$file_path';</script>";
		}

		exit;
	}

	$file_dir=chop($file_dir);
	if(!file_exists($file_dir)){
		return false;
	}
	
	$temp=explode("/",$file_dir);

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".end($temp)."\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($file_dir));
	ob_end_flush();
	@readfile($file_dir);
}

function erphpdown_file_suffix($file_name, $allow_type = array()){
  $fnarray=explode('.', $file_name);
    $file_suffix = strtolower(array_pop($fnarray));
  if (empty($allow_type))
  {
    return $file_suffix;
  }
  else
  {
    if (in_array($file_suffix, $allow_type))
    {
      return true;
    }
    else
    {
      return false;
    }
  }
}


function erphpdown_modify_user_table( $column ) {
    $column['vip'] = 'VIP';
    $column['money'] = '????????????';
    $column['money2'] = '????????????';
    $column['cart'] = '????????????';
    $column['reg'] = '????????????';
    return $column;
}
add_filter( 'manage_users_columns', 'erphpdown_modify_user_table' , 20, 1);

function erphpdown_modify_user_table_row( $val, $column_name, $user_id ) {
    switch ($column_name) {
        case 'vip' :
        	$userType = getUsreMemberTypeById($user_id);
            if($userType == 6){
            	return '??????';
            }elseif($userType == 7){
            	return '??????';
            }elseif($userType == 8){
            	return '??????';
            }elseif($userType == 9){
            	return '??????';
            }elseif($userType == 10){
            	return '??????';
            }else{
            	return '???';
            }
            break;
        case 'money':
            return erphpGetUserOkMoneyById($user_id);
        	break;
        case 'money2':
            return erphpGetUserOkAffById($user_id);
        	break;
        default:
        case 'cart':
            return erphpdown_check_xiaofei($user_id)?'???':'???';
        	break;
        case 'reg':
        	$user = get_user_by("ID",$user_id);
            return get_date_from_gmt($user->user_registered);
        	break;
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'erphpdown_modify_user_table_row', 10, 3 );

add_filter( 'manage_users_sortable_columns', 'erphpdown_modify_user_table_row_sortable' );
function erphpdown_modify_user_table_row_sortable( $columns ) {
	return wp_parse_args( array( 'reg' => 'registered' ), $columns );
}

add_action( 'pre_user_query', 'erphpdown_users_search_order' ); 
function erphpdown_users_search_order($obj){ 
	if(!isset($_REQUEST['orderby']) || $_REQUEST['orderby']=='reg' ){ 
	    $order = 'desc';
		if( isset($_REQUEST['order']) && !in_array($_REQUEST['order'],array('asc','desc')) ){ 
			$order = 'desc'; 
		} 
		$obj->query_orderby = "ORDER BY user_registered ".$order.""; 
	} 
} 

function erphpdown_column_width() {
    echo '<style type="text/css">';
    echo '.column-vip, .column-money, .column-money2 , .column-cart{ text-align: center !important; width:74px;}.column-reg{ text-align: center !important; width:90px;}';
    echo '</style>';
}
add_action('admin_head', 'erphpdown_column_width');

function erphpdown_download_ad_callback() {
    $erphp_ad_download = str_replace('\"', '"', get_option("erphp_ad_download"));
    if($erphp_ad_download){
    	echo '<div class="erphpdown-download-da">'.$erphp_ad_download.'</div>';
    }
}
add_action( 'erphpdown_download_ad', 'erphpdown_download_ad_callback', 10, 0 );