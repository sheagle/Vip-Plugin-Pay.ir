<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
global $core;

function send($api, $amount, $redirect, $factorNumber=null)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, 'https://pay.ir/payment/send');
	curl_setopt($ch, CURLOPT_POSTFIELDS,"api=$api&amount=$amount&redirect=$redirect&factorNumber=$factorNumber");
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	$res = curl_exec($ch);
	curl_close($ch);
	return $res;
}

$api = 'API';
$amount = $core->temp['gateway']['price']*10;
$invoice = $core->temp['gateway']['invoice'];
$Site_Url = j_url;
$redirect =$core->temp['gateway']['callbackurl'];
$factorNumber = $invoice ;
$result = send($api,$amount,$redirect,$factorNumber);
$result = json_decode($result);
if($result->status)
{
	$go = "https://pay.ir/payment/gateway/$result->transId";
	header("Location: $go");
}
else
{
	echo $result->errorMessage;
}