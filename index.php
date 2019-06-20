<?php

require_once ("vendor\autoload.php");

// for credit card sale
use GlobalPayments\Api\ServicesConfig;
use GlobalPayments\Api\ServicesContainer;
use GlobalPayments\Api\PaymentMethods\CreditCardData;

// for PayPlan
use GlobalPayments\Api\Entities\Address;
use GlobalPayments\Api\Entities\Customer;

// for HMS
use GlobalPayments\Api\PaymentMethods\GiftCard;

// for ACH
use GlobalPayments\Api\Entities\Enums\AccountType;
use GlobalPayments\Api\Entities\Enums\CheckType;
use GlobalPayments\Api\PaymentMethods\ECheck;

if (!isset($_POST['secretKey'])) {

	echo <<<HEREFORM

<!doctype html>
<html lang='en'>
<head>
    <meta name='author' content='Mark Smedal Jr'>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <title>Secret Key Tester</title>
    
    <!-- Bootstrap CSS -->
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>	  
</head>

<body>
	<div class="container">
		<div class="jumbotron">
			<h1 class="display-4 text-center">Secret Key Tester</h1>
			<p class="lead">Input a certification secret API key and use the controls to test various gateway functions to make sure they're enabled and working.</p>
			<hr class="my-4">
			<p class="text-center">Do <b>not</b> use this utility to test production API keys.</p>
		</div>
	</div>
	
	<div class="container" style="margin-bottom: 80px">
		<form action="index.php" method="post">
			<div class="form-group">
				<label for="secretAPIkey">Secret API Key</label>
				<input type="text" name="secretKey" class="form-control" id="secretAPIkey" placeholder="secret api key goes here" pattern="^([A-Za-z0-9_\-]{53}$)" required>
				<small class="form-text text-muted">Do not use production API keys here</small>
			</div>
			<div class="row text-center">
				<div class="col">
					<button type="submit" class="btn btn-primary">Test</button>
				</div>
			</div>
		</form>
	</div>
	
	<div class='fixed-bottom'>
		<footer class='footer text-center py-3' style="background-color: #e9ecef">
			<div class='container-fluid'>
				<div class='row'>
					<div class='col-md'>&copy; <script>var date = new Date(); document.write(date.getFullYear())</script> <a href="mailto:mark.smedal@e-hps.com">Mark Smedal Jr</a></div>
				</div>
			</div>
		</footer>
	</div>
	
	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
	<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
</body>
</html>

HEREFORM;

} else {

	$secretKey = htmlspecialchars($_POST['secretKey']);

	if (strstr($secretKey, "_prod_")) {
		die("This can't be used to check production API keys.");
	}

	echo <<<HEREFORM

<!doctype html>
<html lang='en'>
<head>
    <meta name='author' content='Mark Smedal Jr'>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>
    <title>Secret Key Tester</title>
    
    <!-- Bootstrap CSS -->
    <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>	  
</head>

<body>
	<div class="container">
		<div class="jumbotron">
			<h1 class="display-4 text-center">Secret Key Tester</h1>
			<p class="lead">Input a certification secret API key and use the controls to test various gateway functions to make sure they're enabled and working.</p>
			<hr class="my-4">
			<p class="text-center">Do <b>not</b> use this utility to test production API keys.</p>
		</div>
	</div>
	
	<div class="container" style="margin-bottom: 80px">
		<form action="index.php" method="post">
			<div class="form-group">
				<label for="secretAPIkey">Secret API Key</label>
				<input type="text" name="secretKey" class="form-control" id="secretAPIkey" value="$secretKey" placeholder="secret api key goes here" pattern="^([A-Za-z0-9_\-]{53}$)" required>
				<small class="form-text text-muted">Do not use production API keys here</small>
			</div>
			<div class="row text-center">
				<div class="col">
					<button type="submit" class="btn btn-primary">Test</button>
				</div>
			</div>
		</form>
	</div>
	
	<br>
	<h2 class="text-center"><u>Results</u>:</h2>
	<br class="border border-btm">
	
	<div class="container">
		<div class="row text-center">

HEREFORM;

	/*
 	* Test Key by running a sale
 	* Test MUT by requesting a MUT
 	*/

	$config = new ServicesConfig();
	$config->secretApiKey = $_POST['secretKey'];
	$config->developerId = "123456";
	$config->versionNumber = "8888";
	$config->serviceUrl = "https://cert.api2.heartlandportico.com";
	ServicesContainer::configure($config);

	$card = new CreditCardData();
	$card->number = "5454545454545454";
	$card->expMonth = "12";
	$card->expYear = "2025";
	$card->cvn = "123";

	try {
		$response = $card->charge(floatval(date('s') . date('s')))
			->withCurrency("USD")
			->withRequestMultiUseToken(true)
			->execute();

		$result = $response->responseCode; // 00 == Success
		$mut	= $response->token; // success will return 24 character string for token
		$txnID	= $response->transactionId;

		if ($result === "00") {
			echo "<div class=\"col\">\n<p class=\"btn btn-success\">Sale</p>\n</div>";
		} else {
			echo "<div class=\"col\">\n<p class=\"btn btn-danger\">Sale</p>\n</div>";
		}

		if (strlen($mut) === 24) {
			echo "<div class=\"col\">\n<p class=\"btn btn-success\">MUT</p>\n</div>";
		} else {
			echo "<div class=\"col\">\n<p class=\"btn btn-danger\">MUT</p>\n</div>";
		}
	} catch (Exception $e) {
		echo "it broke";
	}

	/*
 	* Test PayPlan by attempting to create a PayPlan Customer
 	*/

	try {

		$customer = new Customer();
		$customer->id = date('s') . rand(); // just make a random number
		$customer->firstName = 'John';
		$customer->lastName = 'Doe';
		$customer->status = 'Active';
		$customer->email = 'john.doe@example.com';
		$customer->address = new Address();
		$customer->address->streetAddress1 = '123 Main St.';
		$customer->address->city = 'Dallas';
		$customer->address->province = 'TX';
		$customer->address->postalCode = '75024';
		$customer->address->country = 'USA';
		$customer->workPhone = '5551112222';
		$customer = $customer->create();

		echo "<div class=\"col\">\n<p class=\"btn btn-success\">PayPlan</p>\n</div>";

	} catch (Exception $e) {

		echo "<div class=\"col\">\n<p class=\"btn btn-danger\">PayPlan</p>\n</div>";

	}

	/*
	 * Test HMS by attempting to charge a gift card
	 */

	try {
		$giftCard = new GiftCard();
		$giftCard->number = "5022440000000000098";

		$HMSresponse = $giftCard->charge(10)
			->withCurrency("USD")
			->execute();

		$HMSresult = $HMSresponse->responseCode;

		if ($HMSresponse->responseCode == 00) {
			echo "<div class=\"col\">\n<p class=\"btn btn-success\">HMS</p>\n</div>";
		} else if ($HMSresponse->responseCode == 1) {
			echo "<div class=\"col\">\n<p class=\"btn btn-danger\">HMS</p>\n</div>";
		} else {
			echo "<div class=\"col\">\n<p class=\"btn btn-danger\">HMS Test Broke</p>\n</div>";
		}

	} catch (Exception $e) {

		echo "<div class=\"col\">\n<p class=\"btn btn-danger\">HMS Test Broke</p>\n</div>";

	}

	/*
	 * Test HPS ACH by running a test ACH charge
	 */

	try {
		$check = new ECheck();
		$check->accountNumber = "1357902468";
		$check->routingNumber = "122000030";
		$check->accountType = AccountType::CHECKING;
		$check->checkType = CheckType::PERSONAL;
		$check->checkHolderName = "Jane Smith";
		$check->secCode = \GlobalPayments\Api\Entities\Enums\SecCode::PPD;

		$address = new Address();
		$address->postalCode = "12345";

		$response = $check->charge(floatval(date('s') . date('s')))
			->withCurrency("USD")
			->withAddress($address)
			->execute();

		echo "<div class=\"col\">\n<p class=\"btn btn-success\">ACH</p>\n</div>";

	} catch (Exception $e) {

		echo "<div class=\"col\">\n<p class=\"btn btn-danger\">ACH</p>\n</div>";

	}

	echo <<<HEREFORM

		</div>
		<br>
		<div class="row">
			<div class="col text-center">
				<h3>CC Transaction ID: <i>$txnID</i></h3>
			</div>	
		</div>
	</div>
	
	<footer class='footer text-center py-3' style="background-color: #e9ecef">
		<div class='container-fluid'>
			<div class='row'>
				<div class='col-md'>&copy; <script>var date = new Date(); document.write(date.getFullYear())</script> <a href="mailto:mark.smedal@e-hps.com">Mark Smedal Jr</a></div>
			</div>
		</div>
	</footer>

	<!-- jQuery first, then Popper.js, then Bootstrap JS -->
	<script src='https://code.jquery.com/jquery-3.3.1.slim.min.js' integrity='sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo' crossorigin='anonymous'></script>
	<script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js' integrity='sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1' crossorigin='anonymous'></script>
	<script src='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js' integrity='sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM' crossorigin='anonymous'></script>
</body>
</html>

HEREFORM;
}
