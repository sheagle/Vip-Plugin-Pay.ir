<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
global $core;
function verify($api, $transId) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://pay.ir/payment/verify');
	curl_setopt($ch, CURLOPT_POSTFIELDS, "api=$api&transId=$transId");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}
$api = 'API';
$transId = $_POST['transId'];
$result = verify($api,$transId);
$result = json_decode($result);
if($result->status==1)
{
	$core->temp['gateway']['call']['msg']='پرداخت انجام شد !';
		$core->temp['gateway']['call']['trac']=$transId;
		$core->temp['gateway']['call']['erja']='-';
		if(isset($_POST['cardNumber']))
			$core->temp['gateway']['call']['cart']=$_POST['cardNumber'];
		else
			$core->temp['gateway']['call']['cart']='-';
		$core->temp['gateway']['call']['status']=true;
}
else
{
	$core->temp['gateway']['call']['msg']='پرداخت صورت نگرفت !';
		$core->temp['gateway']['call']['trac']='-';
		$core->temp['gateway']['call']['erja']='-';
		$core->temp['gateway']['call']['cart']='-';
		$core->temp['gateway']['call']['status']=false;
}