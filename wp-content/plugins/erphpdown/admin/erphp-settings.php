<?php
// +----------------------------------------------------------------------
// | ERPHP [ PHP DEVELOP ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.mobantu.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: mobantu <82708210@qq.com>
// +----------------------------------------------------------------------

if ( !defined('ABSPATH') ) {exit;}

if(isset($_POST['Submit'])) {
	if(isset($_POST['ice_ali_money_limit'])) update_option('ice_ali_money_limit', trim($_POST['ice_ali_money_limit']));
	if(isset($_POST['ice_ali_money_site'])) update_option('ice_ali_money_site', trim($_POST['ice_ali_money_site']));
	if(isset($_POST['ice_ali_money_author'])) update_option('ice_ali_money_author', trim($_POST['ice_ali_money_author']));
	if(isset($_POST['ice_ali_money_ref'])) update_option('ice_ali_money_ref', trim($_POST['ice_ali_money_ref']));
	if(isset($_POST['ice_ali_money_ref2'])) update_option('ice_ali_money_ref2', trim($_POST['ice_ali_money_ref2']));
	if(isset($_POST['ice_ali_money_checkin'])) update_option('ice_ali_money_checkin', trim($_POST['ice_ali_money_checkin']));
	if(isset($_POST['ice_ali_money_new'])) update_option('ice_ali_money_new', trim($_POST['ice_ali_money_new']));
	if(isset($_POST['ice_ali_money_reg'])) update_option('ice_ali_money_reg', trim($_POST['ice_ali_money_reg']));
	if(isset($_POST['erphp_mycred'])){
		update_option('erphp_mycred', trim($_POST['erphp_mycred']));
	}else{
		delete_option('erphp_mycred');
	}
	if(isset($_POST['erphp_to_mycred'])) update_option('erphp_to_mycred', trim($_POST['erphp_to_mycred']));
	if(isset($_POST['ice_tips'])) update_option('ice_tips', str_replace('\"', '"', trim($_POST['ice_tips'])));
	if(isset($_POST['ice_tips_see'])) update_option('ice_tips_see', str_replace('\"', '"', trim($_POST['ice_tips_see'])));
	if(isset($_POST['ice_tips_faka'])) update_option('ice_tips_faka', str_replace('\"', '"', trim($_POST['ice_tips_faka'])));
	if(isset($_POST['ice_tips_free'])) update_option('ice_tips_free', str_replace('\"', '"', trim($_POST['ice_tips_free'])));
	if(isset($_POST['ice_tips_card'])) update_option('ice_tips_card', str_replace('\"', '"', trim($_POST['ice_tips_card'])));
	if(isset($_POST['erphpdown_downkey'])) update_option('erphpdown_downkey', trim($_POST['erphpdown_downkey']));
	if(isset($_POST['erphp_ajaxbuy'])){
		update_option('erphp_ajaxbuy', trim($_POST['erphp_ajaxbuy']));
	}else{
		delete_option('erphp_ajaxbuy');
	}
	if(isset($_POST['erphp_popdown'])){
		update_option('erphp_popdown', trim($_POST['erphp_popdown']));
	}else{
		delete_option('erphp_popdown');
	}
	if(isset($_POST['erphp_repeatdown_btn'])){
		update_option('erphp_repeatdown_btn', trim($_POST['erphp_repeatdown_btn']));
	}else{
		delete_option('erphp_repeatdown_btn');
	}
	if(isset($_POST['erphp_justbuy'])){
		update_option('erphp_justbuy', trim($_POST['erphp_justbuy']));
	}else{
		delete_option('erphp_justbuy');
	}
	if(isset($_POST['erphp_free_wait'])) update_option('erphp_free_wait', trim($_POST['erphp_free_wait']));
	if(isset($_POST['erphp_remind'])){
		update_option('erphp_remind', trim($_POST['erphp_remind']));
	}else{
		delete_option('erphp_remind');
	}
	if(isset($_POST['erphp_aff_money'])){
		update_option('erphp_aff_money', trim($_POST['erphp_aff_money']));
	}else{
		delete_option('erphp_aff_money');
	}
	if(isset($_POST['erphp_remind_recharge'])){
		update_option('erphp_remind_recharge', trim($_POST['erphp_remind_recharge']));
	}else{
		delete_option('erphp_remind_recharge');
	}
	if(isset($_POST['ice_name_alipay'])) update_option('ice_name_alipay', trim($_POST['ice_name_alipay']));
	if(isset($_POST['ice_proportion_alipay'])) update_option('ice_proportion_alipay', trim($_POST['ice_proportion_alipay']));
	if(isset($_POST['erphpdown_min_price'])) update_option('erphpdown_min_price', trim($_POST['erphpdown_min_price']));
	if(isset($_POST['epd_game_price'])){
		update_option('epd_game_price', $_POST['epd_game_price']);
	}else{
		delete_option('epd_game_price');
	}
	if(isset($_POST['erphp_wppay_cookie'])) update_option('erphp_wppay_cookie', trim($_POST['erphp_wppay_cookie']));
	if(isset($_POST['erphp_wppay_down'])){
		update_option('erphp_wppay_down', trim($_POST['erphp_wppay_down']));
	}else{
		delete_option('erphp_wppay_down');
	}
	if(isset($_POST['erphp_free_login'])){
		update_option('erphp_free_login', trim($_POST['erphp_free_login']));
	}else{
		delete_option('erphp_free_login');
	}
	if(isset($_POST['erphp_wppay_ip'])){
		update_option('erphp_wppay_ip', trim($_POST['erphp_wppay_ip']));
	}else{
		delete_option('erphp_wppay_ip');
	}
	if(isset($_POST['erphp_wppay_type'])) update_option('erphp_wppay_type', trim($_POST['erphp_wppay_type']));
	if(isset($_POST['erphp_wppay_payment'])) update_option('erphp_wppay_payment', trim($_POST['erphp_wppay_payment']));
	if(isset($_POST['erphp_addon_card'])){
		update_option('erphp_addon_card', trim($_POST['erphp_addon_card']));
	}else{
		delete_option('erphp_addon_card');
	}
	if(isset($_POST['erphp_addon_vipcard'])){
		update_option('erphp_addon_vipcard', trim($_POST['erphp_addon_vipcard']));
	}else{
		delete_option('erphp_addon_vipcard');
	}
	if(isset($_POST['erphp_addon_activation'])){
		update_option('erphp_addon_activation', trim($_POST['erphp_addon_activation']));
	}else{
		delete_option('erphp_addon_activation');
	}
	if(isset($_POST['erphp_addon_pancheck'])){
		update_option('erphp_addon_pancheck', trim($_POST['erphp_addon_pancheck']));
	}else{
		delete_option('erphp_addon_pancheck');
	}
	if(isset($_POST['erphpdown_direct_type'])) update_option('erphpdown_direct_type', trim($_POST['erphpdown_direct_type']));

	echo'<div class="updated settings-error"><p>???????????????</p></div>';
}

$ice_ali_money_limit    = get_option('ice_ali_money_limit');
$ice_ali_money_site    = get_option('ice_ali_money_site');
$ice_ali_money_author   = get_option('ice_ali_money_author');
$ice_ali_money_ref    = get_option('ice_ali_money_ref');
$ice_ali_money_ref2    = get_option('ice_ali_money_ref2');
$ice_ali_money_checkin = get_option('ice_ali_money_checkin');
$ice_ali_money_new    = get_option('ice_ali_money_new');
$ice_ali_money_reg    = get_option('ice_ali_money_reg');
$erphp_mycred    = get_option('erphp_mycred');
$erphp_to_mycred    = get_option('erphp_to_mycred');
$ice_tips    = get_option('ice_tips');
$ice_tips_see    = get_option('ice_tips_see');
$ice_tips_faka    = get_option('ice_tips_faka');
$ice_tips_free    = get_option('ice_tips_free');
$ice_tips_card    = get_option('ice_tips_card');
$erphpdown_downkey    = get_option('erphpdown_downkey')?get_option('erphpdown_downkey'):wp_generate_password(7, false);
$erphp_ajaxbuy    = get_option('erphp_ajaxbuy');
$erphp_popdown    = get_option('erphp_popdown');
$erphp_repeatdown_btn = get_option('erphp_repeatdown_btn');
$erphp_justbuy = get_option('erphp_justbuy');
$erphp_free_wait = get_option('erphp_free_wait');
$erphp_remind = get_option('erphp_remind');
$erphp_aff_money = get_option('erphp_aff_money');
$erphp_remind_recharge = get_option('erphp_remind_recharge');
$ice_name_alipay    = get_option('ice_name_alipay');
$ice_proportion_alipay    = get_option('ice_proportion_alipay');
$erphpdown_min_price    = get_option('erphpdown_min_price');
$epd_game_price  = get_option('epd_game_price');
$erphp_wppay_cookie    = get_option('erphp_wppay_cookie');
$erphp_wppay_down    = get_option('erphp_wppay_down');
$erphp_free_login    = get_option('erphp_free_login');
$erphp_wppay_ip    = get_option('erphp_wppay_ip');
$erphp_wppay_type    = get_option('erphp_wppay_type');
$erphp_wppay_payment    = get_option('erphp_wppay_payment');
$erphp_addon_card    = get_option('erphp_addon_card');
$erphp_addon_vipcard    = get_option('erphp_addon_vipcard');
$erphp_addon_activation = get_option('erphp_addon_activation');
$erphp_addon_pancheck = get_option('erphp_addon_pancheck');
$erphpdown_direct_type = get_option('erphpdown_direct_type');

$erphpdown_payname = get_option('ice_name_alipay')?get_option('ice_name_alipay'):'?????????';
?>
 <style>.form-table th{font-weight: 400}</style>
 <div class="wrap">
 	<h1>????????????</h1>
 	<form method="post" action="<?php echo admin_url('admin.php?page='.plugin_basename(__FILE__)); ?>">
 		<h3>????????????</h3>
 		<table class="form-table">
 			<tr>
 				<th valign="top">???????????? *</th>
 				<td>
 					<input type="text" id="ice_name_alipay" name="ice_name_alipay" value="<?php echo $ice_name_alipay;?>" class="regular-text"/> ????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">?????????????????????????????????*</th>
 				<td>
 					<input type="number" step="0.01" id="ice_ali_money_ref" name="ice_ali_money_ref" value="<?php echo $ice_ali_money_ref; ?>" required="required" class="regular-text"/>% 
                    <p>A??????B???B?????????A?????????</p>
 				</td>
 			</tr>
            <tr>
                <th valign="top">???????????????????????????????????????</th>
                <td>
                    <input type="number" step="0.01" id="ice_ali_money_ref2" name="ice_ali_money_ref2" value="<?php echo $ice_ali_money_ref2; ?>" class="regular-text"/>% 
                    <p>A??????B???B??????C???C?????????A?????????</p>
                </td>
            </tr>
 			<tr>
 				<th valign="top">???????????????????????????</th>
 				<td>
 					<input type="number" step="0.01" id="ice_ali_money_author" name="ice_ali_money_author" value="<?php echo $ice_ali_money_author; ?>"  class="regular-text"/>%
                    <p>????????????80???????????????A???????????????????????????B????????????A???????????????????????????80%??????????????????100%</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<input type="number" step="0.01" id="ice_ali_money_checkin" name="ice_ali_money_checkin" value="<?php echo $ice_ali_money_checkin; ?>"  class="regular-text"/> <?php echo $erphpdown_payname;?> ???????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">???????????????</th>
 				<td>
 					<input type="number" step="0.01" id="ice_ali_money_new" name="ice_ali_money_new" value="<?php echo $ice_ali_money_new; ?>" class="regular-text"/> <?php echo $erphpdown_payname;?> ?????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<input type="number" step="0.01" id="ice_ali_money_reg" name="ice_ali_money_reg" value="<?php echo $ice_ali_money_reg; ?>" class="regular-text"/> <?php echo $erphpdown_payname;?> ?????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">???????????? *</th>
 				<td>
 					<input type="number" step="0.01" id="ice_ali_money_limit" name="ice_ali_money_limit" value="<?php echo $ice_ali_money_limit; ?>" required="required" class="regular-text"/> <?php echo $erphpdown_payname;?>?????????????????? ???????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????????????????*</th>
 				<td>
 					<input type="number" step="0.01" id="ice_ali_money_site" name="ice_ali_money_site" value="<?php echo $ice_ali_money_site; ?>" required="required" class="regular-text"/>% ???????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<textarea id="ice_tips" name="ice_tips" placeholder="??????QQ???82708210" rows="5" cols="70"><?php echo $ice_tips; ?></textarea>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<textarea id="ice_tips_see" name="ice_tips_see" placeholder="??????QQ???82708210" rows="5" cols="70"><?php echo $ice_tips_see; ?></textarea>
 					<p>????????????????????????[erphpdown]???</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<textarea id="ice_tips_see" name="ice_tips_faka" placeholder="??????QQ???82708210" rows="5" cols="70"><?php echo $ice_tips_faka; ?></textarea>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<textarea id="ice_tips_free" name="ice_tips_free" placeholder="??????QQ???82708210" rows="5" cols="70"><?php echo $ice_tips_free; ?></textarea>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">?????????????????????</th>
 				<td>
 					<textarea id="ice_tips_card" name="ice_tips_card" placeholder="??????QQ???82708210??????????????????http://erphpdown.com/card" rows="5" cols="70"><?php echo $ice_tips_card; ?></textarea>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">Ajax???????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_ajaxbuy" name="erphp_ajaxbuy" value="yes" <?php if($erphp_ajaxbuy == 'yes') echo 'checked'; ?> /> 
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_justbuy" name="erphp_justbuy" value="yes" <?php if($erphp_justbuy == 'yes') echo 'checked'; ?> /> ???????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_remind_recharge" name="erphp_remind_recharge" value="yes" <?php if($erphp_remind_recharge == 'yes') echo 'checked'; ?> /> ??????????????????????????????????????????????????????????????????-????????????????????????????????????????????????????????????SMTP????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_remind" name="erphp_remind" value="yes" <?php if($erphp_remind == 'yes') echo 'checked'; ?> /> ??????????????????????????????????????????????????????????????????-????????????????????????????????????????????????????????????SMTP????????????????????????????????????????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_aff_money" name="erphp_aff_money" value="yes" <?php if($erphp_aff_money == 'yes') echo 'checked'; ?> /> ?????????+??????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????v13.2????????????????????????????????????
 				</td>
 			</tr>
 		</table>
 		<h3>????????????</h3>
 		<table class="form-table">
 			<tr>
 				<th valign="top">???????????? *</th>
 				<td>
 					<input type="number" id="ice_proportion_alipay" name="ice_proportion_alipay" value="<?php echo $ice_proportion_alipay;?>" required="required" class="regular-text"/> ????????????????????????????????????10?????????1???=10 <?php echo $erphpdown_payname;?>???
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????</th>
 				<td>
 					<input type="text" id="erphpdown_min_price" name="erphpdown_min_price" value="<?php echo $erphpdown_min_price;?>" class="regular-text"/> ???????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
				<th valign="top">????????????</th>
				<td>
					<?php if($epd_game_price){ $cnt = count($epd_game_price['buy']); if($cnt){?>
					<div class="prices">
						<?php for($i=0; $i<$cnt;$i++){?>
						<p>?????? <input type="number" name="epd_game_price[buy][]" value="<?php echo $epd_game_price['buy'][$i]?>" class="regular-text" style="width:150px;" step="0.01"/> ??? ???????????? <input type="number" name="epd_game_price[get][]" value="<?php echo $epd_game_price['get'][$i]?>" class="regular-text" style="width:150px;" step="0.01"/> ??? <a href="javascript:;" class="del-price">??????</a></p>
						<?php }?>
					</div>
					<?php }}else{?>
					<div class="prices"></div>
					<?php }?>
					<button class="button add-more-price" type="button">+????????????</button>
					<p>???????????????????????????1:10???????????????????????????1??????10<?php echo get_option('ice_name_alipay');?>??????????????????1.2??????12<?php echo get_option('ice_name_alipay');?>????????????????????????2<?php echo get_option('ice_name_alipay');?>???</p>
				</td>
			</tr>
 		</table>
 		<h3>????????????</h3>
 		<table class="form-table">
 			<tr>
 				<th valign="top">????????????????????????</th>
 				<td>
 					<input type="number" step="1" id="erphp_free_wait" name="erphp_free_wait" value="<?php echo $erphp_free_wait; ?>"  class="regular-text"/>???
 					<p>???????????????????????????????????????????????????VIP????????????????????????????????????</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????? *</th>
 				<td>
 					<input type="text" id="erphpdown_downkey" name="erphpdown_downkey" value="<?php echo $erphpdown_downkey;?>" class="regular-text" required="required"/> 
          <p>?????????????????????????????????????????????8????????????????????????????????????</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">????????????????????????</th>
 				<td>
 					<input type="text" id="erphpdown_direct_type" name="erphpdown_direct_type" value="<?php echo $erphpdown_direct_type;?>" class="regular-text"/> 
          <p>???????????????????????????????????????????????????????????????????????????pdf,doc,txt,jpg???????????????????????????????????????????????????????????????????????????<code>,</code>??????<br>????????????????????????????????????????????????????????????????????????????????????????????????<br>??????????????????????????????????????????????????????????????????????????????????????????</p>
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_popdown" name="erphp_popdown" value="yes" <?php if($erphp_popdown == 'yes') echo 'checked'; ?> /> 
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_repeatdown_btn" name="erphp_repeatdown_btn" value="yes" <?php if($erphp_repeatdown_btn == 'yes') echo 'checked'; ?> /> 
 					<p>??????????????????????????????????????????????????????????????????????????????????????????????????????????????????</p>
 				</td>
 			</tr>
 		</table>
 		<h3>?????????????????????</h3>
 		<p class="description">??????????????????????????????????????????????????????????????????????????????????????????????????????13.0????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????</p>
 		<table class="form-table">
 			<tr>
 				<th valign="top">?????????????????????????????????????????????????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_wppay_down" name="erphp_wppay_down" value="yes" <?php if($erphp_wppay_down == 'yes') echo 'checked'; ?> />???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">??????????????????????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_free_login" name="erphp_free_login" value="yes" <?php if($erphp_free_login == 'yes') echo 'checked'; ?> />?????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">???????????????????????????</th>
 				<td>
 					<select name="erphp_wppay_type" id="erphp_wppay_type">
 						<option value ="scan">??????????????????????????????????????????</option>
 						<option value ="link" <?php if($erphp_wppay_type == 'link') echo 'selected="selected"';?>>??????????????????????????????????????????</option>
 					</select>
 					<p>????????????????????????????????????????????????????????????????????????????????????Cookie????????????????????????????????????????????????Cookie??????????????????????????????IP?????????Cookie?????????</p>
 				</td>
 			</tr>
 			<tr class="scanment">
 				<th valign="top">????????????????????????</th>
 				<td>
 					<select name="erphp_wppay_payment">
 						<option value ="f2fpay" <?php if($erphp_wppay_payment == 'f2fpay') echo 'selected="selected"';?>>??????????????????</option>
 						<option value ="weixin" <?php if($erphp_wppay_payment == 'weixin') echo 'selected="selected"';?>>????????????????????????</option>
 						<option value ="f2fpay_weixin" <?php if($erphp_wppay_payment == 'f2fpay_weixin') echo 'selected="selected"';?>>??????????????????/????????????????????????</option>
 						<option value ="paypy" <?php if($erphp_wppay_payment == 'paypy') echo 'selected="selected"';?>>Paypy????????????</option>
 						<option value ="f2fpay_paypy" <?php if($erphp_wppay_payment == 'f2fpay_paypy') echo 'selected="selected"';?>>??????????????????/Paypy??????????????????</option>
 						<option value ="payjs" <?php if($erphp_wppay_payment == 'payjs') echo 'selected="selected"';?>>Payjs</option>
 						<option value ="hupiv3" <?php if($erphp_wppay_payment == 'hupiv3') echo 'selected="selected"';?>>?????????V3</option>
 						<option value ="f2fpay_hupiv3" <?php if($erphp_wppay_payment == 'f2fpay_hupiv3') echo 'selected="selected"';?>>??????????????????/?????????V3????????????</option>
 						<option value ="vpay" <?php if($erphp_wppay_payment == 'vpay') echo 'selected="selected"';?>>V??????</option>
 					</select>
 				</td>
 			</tr>
 			<tr>
				<th valign="top">?????????Cookie???????????? *</th>
				<td>
					<input type="number" id="erphp_wppay_cookie" name="erphp_wppay_cookie" value="<?php echo $erphp_wppay_cookie ; ?>" class="regular-text" required="required"/>
				</td>
			</tr>
 			<tr>
 				<th valign="top">??????IP??????</th>
 				<td>
 					<input type="checkbox" id="erphp_wppay_ip" name="erphp_wppay_ip" value="yes" <?php if($erphp_wppay_ip == 'yes') echo 'checked'; ?> />???<b style="color: red">????????????</b>?????????????????????????????????????????????????????????????????????????????????cookie???????????????IP???????????????????????????????????????
 				</td>
 			</tr>
 		</table>
 		<script>
        jQuery(function($){
            if($("#erphp_wppay_type").val() == 'link'){
                $(".scanment").css("display", "none");
            }

            $("#erphp_wppay_type").change(function(){
                if($(this).val() == 'link'){
                    $(".scanment").css("display", "none");
                }else{
                    $(".scanment").css("display", "table-row");
                }
            });
        });
    </script>
 		<h3>????????????</h3>
 		<table class="form-table">
 			<tr>
 				<th valign="top">?????????</th>
 				<td>
 					<input type="checkbox" id="erphp_addon_card" name="erphp_addon_card" value="yes" <?php if($erphp_addon_card == 'yes') echo 'checked'; ?> />???????????????????????????????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">VIP?????????</th>
 				<td>
 					<input type="checkbox" id="erphp_addon_vipcard" name="erphp_addon_vipcard" value="yes" <?php if($erphp_addon_vipcard == 'yes') echo 'checked'; ?> />???????????????VIP????????????????????????VIP???????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">Mycred??????</th>
 				<td>
 					<input type="checkbox" id="erphp_mycred" name="erphp_mycred" value="yes" <?php if($erphp_mycred == 'yes') echo 'checked'; ?> />????????????<a href="https://wordpress.org/plugins/mycred/" target="_blank">mycred??????</a>??? ???????????????
 					<input type="number" step="0.01" id="erphp_to_mycred" name="erphp_to_mycred" value="<?php echo $erphp_to_mycred; ?>" style="width:100px" />?????????100?????? 100?????? = 1<?php echo $erphpdown_payname;?>???
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">???????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_addon_activation" name="erphp_addon_activation" value="yes" <?php if($erphp_addon_activation == 'yes') echo 'checked'; ?> />?????????????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 			<tr>
 				<th valign="top">????????????</th>
 				<td>
 					<input type="checkbox" id="erphp_addon_pancheck" name="erphp_addon_pancheck" value="yes" <?php if($erphp_addon_pancheck == 'yes') echo 'checked'; ?> />???????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????
 				</td>
 			</tr>
 		</table>
 		<p class="submit">
 			<input type="submit" name="Submit" value="????????????" class="button-primary"/>
 			<div >???????????????mobantu.com <a href="http://www.mobantu.com/6658.html" target="_blank">????????????>></a></div>
 		</p>      
 	</form>
 	<script>
  jQuery(".add-more-price").click(function(){
    jQuery(".prices").append('<p>?????? <input type="number" name="epd_game_price[buy][]" value="" class="regular-text" style="width:150px;" step="0.01"/> ??? ???????????? <input type="number" name="epd_game_price[get][]" value="" class="regular-text" style="width:150px;" step="0.01"/> ??? <a href="javascript:;" class="del-price">??????</a></p>');
    jQuery(".del-price").click(function(){
      jQuery(this).parent().remove();
    });
    return false;
  });

  
  jQuery(".del-price").click(function(){
    jQuery(this).parent().remove();
  });
</script>
 </div>
