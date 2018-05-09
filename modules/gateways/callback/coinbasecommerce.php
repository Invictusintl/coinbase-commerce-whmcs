<?php
/**
 * Coinbase-Commerce WHMCS Gateway
 *
 * Copyright (c) 2018 Invictus International INC
 *                    Phillip Thurston, <pthurston@goinvictus.com>
 * 
 * This file is part of Coinbase-Commerce WHMCS Gateway
 * 
 * The Coinbase-Commerce WHMCS Gateway is free software: you can 
 * redistribute it and/or modify it under the terms of the GNU 
 * General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or any later version.
 * 
 * The Coinbase-Commerce WHMCS Gateway is distributed in the hope that 
 * it will be useful, but WITHOUT ANY WARRANTY; without even the 
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR 
 * PURPOSE.  See the GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with Ansible.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @see Add GH repo URL
 *
 * @copyright Copyright (c) Invictus International INC 2018
 * @license http://www.gnu.org/licenses/
 */

// Require libraries needed for gateway module functions.
require_once __DIR__ . '/../../../init.php';
require_once __DIR__ . '/../../../includes/gatewayfunctions.php';
require_once __DIR__ . '/../../../includes/invoicefunctions.php';

// Detect module name from filename.
$gatewayModuleName = basename(__FILE__, '.php');

// Fetch gateway configuration parameters.
$gatewayParams = getGatewayVariables($gatewayModuleName);

// Die if module is not active.
if (!$gatewayParams['type']) {
    die("Module Not Activated");
}

// Format payload data
$rawBody = file_get_contents("php://input");
$decodedBody = json_decode($rawBody, true);

// Retrieve data returned in payload
$success = $decodedBody['event']['type'];
$invoiceId = $decodedBody['event']['data']['metadata']['invoice_id'];
$transactionId = $decodedBody['event']['data']['payments']['0']['transaction_id'];
$paymentAmount = $decodedBody['event']['data']['payments']['0']['value']['local']['amount'];
$hash = $_SERVER["HTTP_X_CC_WEBHOOK_SIGNATURE"];

$transactionStatus = ($success == 'charge:confirmed') ? 'Success' : 'Failure';

// Validate that the payload is valid
$secretKey = $gatewayParams['webhookSecret'];
if ($hash != hash_hmac('SHA256', $rawBody , $secretKey)) {
    $transactionStatus = 'Hash Verification Failure';
    $success = false;
}

// Validate that the invoice is valid.
$invoiceId = checkCbInvoiceID($invoiceId, $gatewayParams['name']);

// Validate that the transaction is valid.
checkCbTransID($transactionId);

// Log the raw JSON response from coinbase in the gateway module.
logTransaction($gatewayParams['name'], $rawBody, $transactionStatus);

if ($success == 'charge:confirmed') {

    addInvoicePayment(
        $invoiceId,
        $transactionId,
        $paymentAmount,
        0,
        $gatewayModuleName
    );

}
