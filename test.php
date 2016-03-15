<?php
require_once( dirname( __FILE__ ) . '/mcash.php' );

// Setting API Authentication level and secret/key
mCASH\mCASH::setApiLevel('KEY');
mCASH\mCASH::setApiSecret('-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAuHK0wXbRyPXWwxpCeX9PEimEo2oc+T2VGVNg9c4r4qe84arw
I9JX8X6kPip1n6LxEO0onXZl+Ke4cBQG8iHXmA4CmEIzE7u+yaEUVt4PPiQnmAd+
/5SaHDTt2V8o7yfTdI9TvqVzNnhvJ2sqiXAQHldIplZOXPWh1Yw5qGyfkRNFuYb+
OfEQTHeEAjQKrFVKOi3R8NnYyychmwQFG6teRQdHCesZ2l4KHb1mhlGYTSDtobO4
wQwJTTLWBPQl6XrM24Rm6dcdDC2JQWfCifpn1BUNyUC2dE6Xhrnbl/hoGj/ARoiu
/fLOom4Au759UimUvjiJLzx0I0gwMMVogY330wIDAQABAoIBAQCfmYGWtb2o6jA+
+gXzI5nD2XEQBc20TPkqxN7tUszefs4NZuAL3wKB0FdGA29sBo4ZW1z9g2mQ02/g
azPnaCkpRVYxcXbI6HeZ+GulDtiZqKcqAbJ+bJM9PP9iS9kbt5ZdEXghSwB6bZOg
xDY2XmVUG6qdY6jC/zhdk8yAVB1uZYkDfb6AV26QGQ4CSR9EExj0uu+bhv15wp7T
G61cd7+zyscT3Y5UX8G0kSaaWBXcGuV/vMFGSzoWhBhor/PMHEPgod5TFHLat5LV
RW96YYFe9BvIbppRsV1/97VWed44RrcuRvsl8oslEgIEikKP71J66wM4Jhj/0WZY
r61WVPVBAoGBAMXLoFCcqyXMKPwvGwR/VVEbrzIxzqGb5unbr3IA0EY8R3YYH+PX
uWV6LM17ZJu+UpgqfNEEs7DC+qcugijkqufOyuFFPQ3qPsXe0O5lHFXv17qYodlu
fJ8e9l11JcTmHkQJNETmYizMr3Xo9balm1pSDtmRzHLHtVCTdZJKx0y7AoGBAO65
mCfRSa1J5sf8Twbf5vDeHtPwa0tnzmYziV9VaTkZcPJNfIr/dKMaQusm66986F2L
I8KBcIXkbKmkg88P+KZ+qLu9HHqmcfF//EWZfvHNLG3N2pEbpy2K02CaL5gNcpVo
OXLBYyieA4/U8y3O38z8BQBxmzGGuFnV8ud3wRvJAoGANc8opOWKNfUtrTWPbxDJ
ABC8/7XHFfYYwsQuHaCVCJZ7hmgXvN9CI5YyPBB2lVwYkib039QcoljY4cRoSoGy
8O80CEbSzkdMnn34bvJ42/Qsxymg1ksVgiBlO5WUGdXUpAOxAZF/YdqpXTVsKy0w
b0jlm1DrwsoLzIjhMlQ/leMCgYAjrAYJhSOOSmm63n/slg6LVZWjs9xEk0lrSi4v
2bJ0ftG600tV9eA28ximzNURDwgl2kHIsdDfoQd/vf6YNVnBf1G1ZAfaZ/7EyIwo
1kv6097ctZMAdfJgQstdNwz/mprRqpZTCITJr/r2RirkYHJezhXtQAyhtPDdM7Qt
VF69yQKBgE8FZ6SUG43CF+9jwzDD/XelUrKftEBRPM1aEWa3sas1F/DPjLG3OP68
pqlZQi6ckiqGh6ISVl2ssN2H+qFVlt136eu/bj8Lc1zyoTpBHnrGR+EAnxUBNyfQ
xm7cnsbT0okV0pXC5Q1X1d2uRcRzcknoRDl/ijpbeW8gtVOAaZS5
-----END RSA PRIVATE KEY-----');
// Merchant and user id
mCASH\mCASH::setMerchantId('klapp');
mCASH\mCASH::setUserId('klappuser');
// Setting test environment to true, and setting the test token
mCASH\mCASH::setTestEnvironment(true);
mCASH\mCASH::setTestToken('kuFFDsfv7QSaYC2Yghn1d5tVcIxmoKUmbOOsdaTFfjo');

try {
	$user = mCASH\User::create(array(
		'id' => 'firstapiuser',
		'roles' => array(
			'user','superuser'
		),
		'secret' => 'klappsecret'
	));
	die( var_dump( $user ) );
} catch( Exception $e ){
	die( print_r( $e->getMessage() ) );
}

/* TICKET
try {
	$payment = mCASH\PaymentRequest::create(array(
		'success_return_uri' 	=> 'http://mcash.oleh.dev.klappmedia.no',
		'failure_return_uri' 	=> 'http://mcash.oleh.dev.klappmedia.no',
		'allow_credit' 			=> true,
		'pos_id' 				=> 'mcash_express',
		'pos_tid' 				=> '99291',
		'action' 				=> 'sale',
		'amount' 				=> 100.00,
		'text' 					=> 'tester',
		'currency' 				=> 'NOK'		
	));
	$ticket = $payment->ticket()->create(array(
		'tickets' => array(
			'caption' => 'Please scan this barcode',
			'kind' => 'event',
			'date_expires' => '2016-12-24 17:00:00'
		)
	));
	die( var_dump( $ticket ) );
} catch( Exception $e ){
	die( $e->getMessage() );
}
*/
/* LEDGER
try {
	$ledger = mCASH\Ledger::retrieve('5agb95');
	$report = $ledger->report()->all();
	//die( var_dump( $report->close() ) );
	//die( var_dump( $ledger->report()->close() ) );

	die( var_dump( $report ) );
	$report = mCASH\Report::retrieve(1);
	die(var_dump($report));
} catch( Exception $e ){
	die( $e->getMessage() );
} */

/*
try {
	
	$payment = mCASH\PaymentRequest::create(array(
		'success_return_uri' 	=> 'http://mcash.oleh.dev.klappmedia.no',
		'failure_return_uri' 	=> 'http://mcash.oleh.dev.klappmedia.no',
		'allow_credit' 			=> true,
		'pos_id' 				=> 'mcash_express',
		'pos_tid' 				=> '992',
		'action' 				=> 'sale',
		'amount' 				=> 100.00,
		'text' 					=> 'tester',
		'currency' 				=> 'NOK'
	));	
	if( !$payment ){
		// Unable to perform payment, error handling
	}

	try {
		$outcome = $payment->outcome();
		var_dump( $outcome->status() );
	} catch( mCASH\Error\Api $e ){
		print_r( "Api error: " . $e->getMessage() );
	}
	
	try {
		$payment->capture();
	} catch( mCASH\Error\Api $e ){
		print_r( "Api error: " . $e->getMessage() );
	} catch( mCASH\Error\Request $e ){
		print_r( "Request error: " . $e->getMessage() );
	}

	try {
		var_dump( $payment->release() );
	} catch( mCASH\Error\Api $e ){
		print_r( "Api error: " . $e->getMessage() );
	} catch( mCASH\Error\Request $e ){
		print_r( "Request error: " . $e->getMessage() );
	}
		
	// Check if the uri is set and redirect the user
	if( !empty( $payment->uri ) ) {
		//header('Location: ' . $payment->uri );
	}
	
} catch( mCASH\Error\ApiConnection $e ){
	die( $e->getMessage() );	
} catch( mCASH\Error\Request $e ){
	print_r( "Request error: " . $e->getMessage() );
} catch( mCASH\Error\Api $e ){
	print_r( "Api error: " . $e->getMessage() );
}
*/

?>