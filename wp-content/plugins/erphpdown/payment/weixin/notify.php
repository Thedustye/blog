<?php
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);
require_once('../../../../../wp-load.php');
require_once "lib/WxPay.Api.php";
require_once 'lib/WxPay.Notify.php';

//初始化日志
//$logHandler= new CLogFileHandler("./logs/".date('Y-m-d').'.log');
//$log = Log::Init($logHandler, 15);

class PayNotifyCallBack extends WxPayNotify
{
	//查询订单
	public function Queryorder($transaction_id)
	{
		$input = new WxPayOrderQuery();
		$input->SetTransaction_id($transaction_id);
		$result = WxPayApi::orderQuery($input);
		//Log::DEBUG("query:" . json_encode($result));
		if(array_key_exists("return_code", $result)
			&& array_key_exists("result_code", $result)
			&& $result["return_code"] == "SUCCESS"
			&& $result["result_code"] == "SUCCESS")
		{
			global $wpdb;
			$total_fee=$result["total_fee"]*0.01;
			$out_trade_no = $result["out_trade_no"];

			if(strstr($out_trade_no,'MD') || strstr($out_trade_no,'FK')){
				epd_set_wppay_success($out_trade_no,$total_fee,'wxpay');
			}else{
				epd_set_order_success($out_trade_no,$total_fee,'wxpay');
			}

			return true;
		}
		return false;
	}
	
	//重写回调处理函数
	public function NotifyProcess($data, &$msg)
	{
		//Log::DEBUG("call back:" . json_encode($data));
		$notfiyOutput = array();
		
		if(!array_key_exists("transaction_id", $data)){
			$msg = "输入参数不正确";
			return false;
		}
		//查询订单，判断订单真实性
		if(!$this->Queryorder($data["transaction_id"])){
			$msg = "订单查询失败";
			return false;
		}
		return true;
	}
}

//Log::DEBUG("begin notify erphp");
$notify = new PayNotifyCallBack();
$notify->Handle(false);
