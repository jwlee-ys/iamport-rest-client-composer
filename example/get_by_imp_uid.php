<?php

use Freshope\Iamport\Iamport;

require_once '../vendor/autoload.php';

// load config values
(new Dotenv\Dotenv(__DIR__))->load();
$imp_key = getenv('IAMPORT_KEY');
$imp_secret = getenv('IAMPORT_SECRET');
$imp_uid = getenv('IMP_UID');

// create Iamport
$iamport = new Iamport($imp_key, $imp_secret);
$result = $iamport->findByImpUID($imp_uid);

if ($result->success) {
	/**
	*	IamportPayment 를 가리킵니다. __get을 통해 API의 Payment Model의 값들을 모두 property처럼 접근할 수 있습니다.
	*	참고 : https://api.iamport.kr/#!/payments/getPaymentByImpUid 의 Response Model
	*/
	$payment_data = $result->data;
	
	echo '## 결제정보 출력 ##';
	echo '가맹점 주문번호 : ' 	. $payment_data->merchant_uid;
	echo '결제상태 : ' 		. $payment_data->status;
	echo '결제금액 : ' 		. $payment_data->amount;
	echo '결제수단 : ' 		. $payment_data->pay_method;
	echo '결제된 카드사명 : ' 	. $payment_data->card_name;
	echo '결제 매출전표 링크 : '	. $payment_data->receipt_url;
	
	/**
	*	IMP.request_pay({
	*		custom_data : {my_key : value}
	*	});
	*	와 같이 custom_data를 결제 건에 대해서 지정하였을 때 정보를 추출할 수 있습니다.(서버에는 json encoded형태로 저장합니다)
	*/
	echo 'Custom Data :'	. $payment_data->getCustomData('my_key');
	
	# 내부적으로 결제완료 처리하시기 위해서는 (1) 결제완료 여부 (2) 금액이 일치하는지 확인을 해주셔야 합니다.
	$amount_should_be_paid = 1004;
	if ( $payment_data->status === 'paid' && $payment_data->amount === $amount_should_be_paid ) {
		//TODO : 결제성공 처리
	}
} else {
	error_log($result->error['code']);
	error_log($result->error['message']);
}
