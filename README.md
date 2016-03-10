#mCash PHP SDK

## SETUP

Include the API in your setup by requiring the mcash.php file.

```php
require_once(dirname(__FILE__) . '/mcash.php');
``` 

### Select the level of authentication to be used
OPEN: No authentication needed

SECRET: Authenticate with password

KEY: Authenticate with a private key

```php
mCASH\mCASH::setApiLevel('KEY');
mCASH\mCASH::setApiSecret('RSA KEY');
```

### Provide Merchant and User ID
These are required to perform a API request

```php
mCASH\mCASH::setMerchantId('merchant_id');
mCASH\mCASH::setUserId('user_id');
```

### Perform TEST calls
To perform calls to the test version of the mCash api, set test the sdk to test mode, and provide your test key

```php
mCASH\mCASH::setTestEnvironment(true);
mCASH\mCASH::setTestToken('test_token');
```

## PAYMENT REQUESTS

A payment request goes through several stages. After being registered, the customer can either reject or authorize. An authorization is valid for 3 days, but can be reauthorized before it expires to be valid for 3 new days. Once authorized, it can be captured to be included in the next ledger report and settlement.

### Create a new payment request

To create a new payment request, initialize a new instance of of the PaymentRequest::create

```php
$payment = mCASH\PaymentRequest::create(array(
	'success_return_uri' 	=> 'http://yourdomain.com/success',
	'failure_return_uri' 	=> 'http://yourdomain.com/failure',
	'allow_credit' 			=> true,
	'pos_id' 				=> 'mcash_express',
	'pos_tid' 				=> '999',
	'action' 				=> 'sale',
	'amount' 				=> 100.00,
	'text' 					=> 'Product text',
	'currency' 				=> 'NOK'
));	
```

### Capture payment

Payments can be captured on a instance of mCASH\PaymentRequest. 
```php
$payment = mCASH\PaymentRequest::retrieve('tid');
$payment->capture();
```

### Refund payment

Payments can be refunded on a instance of mCASH\PaymentRequest. 
```php
$payment = mCASH\PaymentRequest::retrieve('tid');
$payment->refund();
```

### Release payment

Payments can be released on a instance of mCASH\PaymentRequest. 
```php
$payment = mCASH\PaymentRequest::retrieve('tid');
$payment->release();
```

### Reauthorize payment

Payments can be reauthorized on a instance of mCASH\PaymentRequest. 
```php
$payment = mCASH\PaymentRequest::retrieve('tid');
$payment->reauthorize();
```

## PAYMENT OUTCOME

The outcome endpoint shows the outcome info for a payment request, reauth or capture.

This endpoints includes specified fee and/or interchange that will be deducted from payout, and also updated additional amount field if the user added gratuity.

If the callback uri registered for the payment request was secure (https), the contents of this form was sent along with the callback. If the callback uri was insecure, a notification pointing to this endpoint was sent instead.

The status field contains a simple string that is one of ok, fail, auth, or pending. 

### Retrieve the outcome of an existing payment request

```php
$payment = mCASH\PaymentRequest::retrieve('tid');
$outcome = $payment->outcome();
```

### Retrieve information about the status code for the payment request

This will return a StatusCode object containing the status code, name and description

```php
$payment = mCASH\PaymentRequest::retrieve('tid');
$status = $payment->outcome()->status();
```

## TICKETS

If the customer should be granted an electronic ticket as a result of a successful payment, the merchant may (at any time) PUT ticket information to this endpoint. There is an ordered list of tickets; the merchant may PUT several times to update the list. The PUT overwrites any existing content, so if adding additional tickets one must remember to also include the tickets previously issued.

### Create one or multiple tickets for a payment request

To create a ticket (or multiple), we need to initiate an instance of PaymentRequest first. Either by creating a new, or retrieving an existing one. Ex:

```php
$payment = mCASH\PaymentRequest::retrieve('tid');
$ticket = $payment->ticket()->create(array(
	'tickets' => array(
		'caption' => 'Please scan this barcode',
		'kind' => 'event',
		'date_expires' => '2016-12-24 17:00:00'
	)
));
```

## STATUS CODES

Some resources, such as the outcome resources (for payment request and permission request), have a status code field in the response body. The status_code resource lists and describes all possible status codes. 

### Retrieve all status codes

returns an array of StatusCode objects

```php
$codes = mCASH\StatusCode::all();
```

### Retrieve information about specific status code

Returns a StatusCode object containing code, name and description

```php
$codes = mCASH\StatusCode::retrieve('code(ex: 5000)');
```

## LEDGER

A Merchant has by default one Ledger, but more can be created - for example one per POS or one per employee. If none are created all payments are associated with the default Ledger.

### Create ledger

To create a new ledger, initialize a new instance of of the Ledger::create

```php
$ledger = mCASH\Ledger::create(array(
	'currency' => 'NOK',
	'description' => 'Short description'
));
```

### Retrieve ledger

To retrieve a specific ledger, call the Ledger::retrieve function and pass the ledger ID to retrieve

```php
$ledger = mCASH\Ledger::retrieve('5agb95');
```

### Update ledger

To update a ledger, you first need to create one, or retrieve one. Ex:

```php
$ledger = mCASH\Ledger::retrieve('5agb95');
$ledger->description = "New description";
$ledger->save();
```

### Delete ledger

To delete a ledger, you first need to create one, or retrieve one. Ex:

```php
$ledger = mCASH\Ledger::retrieve('5agb95');
$ledger->delete();
```

## REPORT

The transactions in a Ledger are grouped into Reports. These Reports are collections of transactions that are to be reconciled as a group. At any one time there is only one open Report for each Ledger, and new transactions that are added to the Ledger are appended to the open Report.

### Retrieve all reports

Since reports are children of ledgers, you first need to create an instance of mCASH\Ledger pointing to an existing ledger. 

Then you can fetch all the reports belonging to that ledger. Ex:

```php
$ledger = mCASH\Ledger::retrieve('5agb95');
$ledger->report()->all();
```

### Retrieve specific report

To retrieve a specific report, use the retrieve function and pass the report ID. Ex:

```php
$ledger = mCASH\Ledger::retrieve('5agb95');
$ledger->report()->retrieve(1);
```

### Close a report

To close a report, use the close function

```php
$ledger = mCASH\Ledger::retrieve('5agb95');
$ledger->report()->retrieve(1)->close();
```

## EXCEPTION HANDLING

The SDK can deliver multiple kinds of Exceptions based on the action that is being performed. 
Best practice is to enclose all your actions in try / catch statements. 

### Error\Api
Api specific error

### Error\ApiConnection
Problems with connection to the mCash API

### Error\Authentication
Problems authenticating with mCash API

### Error\Request
Error during request to the API

### Error\Base
Generic errors