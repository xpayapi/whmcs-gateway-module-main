<?php

if ($_POST["type"] === "sci_confirm_order") {
    echo sprintf("%s|success", $_POST["order_id"]);
    exit();
}


// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../../init.php';
require_once __DIR__ . '/../../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../../includes/invoicefunctions.php';


require_once __DIR__ . '/../index.php';

$gatewayModuleName = xpayapi_get_module_name();

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Die if module is not active.
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}


$xpayapi = new \xPayapi\xPayapiSCI(
    $gatewayParams["merchant_id"],
    $gatewayParams["merchant_password"]
);

$private_hash = isset($_POST["private_hash"]) ? $_POST["private_hash"] : "";

xpayapi_log([
    $_POST,
]);


$res = $xpayapi->checkTransactionIpn(
    $private_hash
);


xpayapi_log([
    $_POST, $res,
]);

// actions in case of success
$transaction = $res["data"]["invoice"];         // transaction number in the system paykassa: 2431548
$txid = $res["data"]["hash"];                       // A transaction in a cryptocurrency network, an example: 0xb97189db3555015c46f2805a43ed3d700a706b42fb9b00506fbe6d086416b602
$shop_id = $res["data"]["shop_id"];                 // Your merchant's number, example: 138
$id = $res["data"]["order_id"];                     // unique numeric identifier of the payment in your system, example: 150800
$amount = $res["data"]["amount"];            // received amount, example: 1.0000000
$fee = $res["data"]["fee"];                  // Payment processing commission: 0.0000000
$currency = $res["data"]["currency"];               // the currency of payment, for example: DASH
$system = $res["data"]["system"];                   // system, example: Dash
$address_from = $res["data"]["address_from"];       // address of the payer's cryptocurrency wallet, example: 0x5d9fe07813a260857cf60639dac710ebb9531a20
$address = $res["data"]["address"];                 // a cryptocurrency wallet address, for example: Xybb9RNvdMx8vq7z24srfr1FQCAFbFGWLg
$tag = $res["data"]["tag"];                         // Tag for Ripple and Stellar is an integer
$confirmations = $res["data"]["confirmations"];     // Current number of network confirmations
$required_confirmations =
    $res["data"]["required_confirmations"];         // Required number of network confirmations for crediting
$status = $res["data"]["status"];                   // yes - if the payment is credited
$static = $res["data"]["static"];                   // Always yes
$date_update = $res["data"]["date_update"];         // last updated information, example: "2018-07-23 16:03:08"

$explorer_address_link =
    $res["data"]["explorer_address_link"];          // A link to view information about the address
$explorer_transaction_link =
    $res["data"]["pay_link"];      // Link to view transaction information



if ($res['error']) {
    echo $res['message'];
    exit();
} else {
    $invoiceId = checkCbInvoiceID($id, $gatewayParams['name']);
    $invoiceCurrencyCode = xpayapi_get_invoice_currency_code($invoiceId);
    $paymentAmount = xpayapi_convert_amount($amount, $currency, $invoiceCurrencyCode);
    $paymentFee = xpayapi_convert_amount($fee, $currency, $invoiceCurrencyCode);


    checkCbTransID($txid);

    addInvoicePayment(
        $invoiceId,
        $txid,
        $paymentAmount,
        $paymentFee,
        $gatewayModuleName
    );
}

echo $id . '|success';


