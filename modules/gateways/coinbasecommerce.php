<?php

/*
<!#CR>
************************************************************************************************************************
*                                                    Copyrigths ©                                                      *
* -------------------------------------------------------------------------------------------------------------------- *
*          Authors Names    > PowerChaos                                                                               *
*          Company Name     > VPS Data                                                                                 *
*          Company Email    > info@vpsdata.be                                                                          *
*          Company Websites > https://vpsdata.be                                                                       *
*                             https://vpsdata.shop                                                                     *
*          Company Socials  > https://facebook.com/vpsdata                                                             *
*                             https://twitter.com/powerchaos                                                           *
*                             https://instagram.com/vpsdata                                                            *
* -------------------------------------------------------------------------------------------------------------------- *
*                                           File and License Informations                                              *
* -------------------------------------------------------------------------------------------------------------------- *
*          File Name        > <!#FN> coinbasecommerce.php </#FN>                                                       
*          File Birth       > <!#FB> 2021/10/16 19:37:07.567 </#FB>                                                    *
*          File Mod         > <!#FT> 2021/10/16 19:37:41.984 </#FT>                                                    *
*          File Version     > <!#FV> 0.0.1 </#FV>                                                                       
*          Documentation    > https://developers.whmcs.com/payment-gateways/getting-started/                           *
</#CR>
*/



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

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

function coinbasecommerce_MetaData()
{
    return array(
        'DisplayName' => 'Coinbase Commerce V2',
        'APIVersion' => '1.1',
    );
}

function coinbasecommerce_config()
{
    return array(
        // the friendly display name for a payment gateway should be
        // defined here for backwards compatibility
        'FriendlyName' => array(
            'Type' => 'System',
            'Value' => 'Coinbase Commerce',
        ),
        /*'logoUri' => array(
            'FriendlyName' => 'Logo URI',
            'Type' => 'text',
            'Size' => '64',
            'Default' => '',
            'Description' => 'Enter a full URI of the logo you want to use',
        ),*/
        'apiKey' => array(
            'FriendlyName' => 'API Key',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Generate an API key from here: https://commerce.coinbase.com/dashboard/settings',
        ),
        'webhookSecret' => array(
            'FriendlyName' => 'Webhook Shared Secret',
            'Type' => 'password',
            'Size' => '25',
            'Default' => '',
            'Description' => 'Get your Webhook shared secret from the Webhook subscriptions here: https://commerce.coinbase.com/dashboard/settings <br>Make sure you add the endpoint for your website into your webook subscriptions.',
        ),
        'restmax' => array(
            'Type' => 'Text',
            'Size' => '25',
            'Default' => '1',
            'Description' => 'set maximum percentage that the amount can surpass to complete the payment in full, please match the value in your coinbase account',
        ),
        'restmin' => array(
            'Type' => 'Text',
            'Size' => '25',
            'Default' => '1',
            'Description' => 'set maximum percentage that the amount can undercut to complete the payment in full, please match the value in your coinbase account',
        ),
    );
}


function coinbasecommerce_link($params)
{
    // Coinbase Commerce Specific Settings
    $ccUrl = "https://api.commerce.coinbase.com/charges";
    $ccPricingType = "fixed_price";
    $ccApiVersion = "2018-03-22";

    /**
     * =============================================================
     * DO NOT EDIT BELOW THIS LINE UNLESS YOU KNOW WHAT YOU'RE DOING
     * =============================================================
     */

    // Gateway Configuration Parameters
    $apiKey = $params['apiKey'];

    // Invoice Parameters
    $invoiceId = $params['invoiceid'];
    $description = $params["description"];
    $amount = $params['amount'];
    $currencyCode = $params['currency'];

    // Client Parameters
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];

    // System Parameters
    $returnUrl = $params['returnurl'];
    $langPayNow = $params['langpaynow'];

    // Compiled Post from Variables
    $postfields = array();
    $postfields['name'] = $description;
    $postfields['description'] = "Invoice - #" . $invoiceId;
    $postfields['local_price'] = array('amount' => $amount, 'currency' => $currencyCode);
    $postfields['pricing_type'] = $ccPricingType;
    $postfields['metadata'] = array('customer_name' => $firstname . " " . $lastname, 'customer_email' => $email, 'invoice_id' => $invoiceId);
    $postfields['redirect_url'] = $returnUrl;
    $postfields['cancel_url'] = $returnUrl;

    // Setup request to send json via POST.
    $payload = json_encode($postfields, JSON_UNESCAPED_SLASHES);

    // Contact Coinbase Commerce and get URL data
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $ccUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
        "Content-Type: application/json",
        "X-CC-Api-Key: $apiKey",
        "X-CC-Version: $ccApiVersion"
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $server_output = curl_exec($ch);
    curl_close($ch);

    // Convert response to PHP array and print button
    $payment_url = json_decode($server_output, true);

    $htmlOutput = '<a class="btn btn-success btn-sm" id="btnPayNow" href="' . $payment_url['data']['hosted_url'] . '" rel="noopener noreferrer" target="_blank"><i class="fab fa-btc"></i>&nbsp;' . $langPayNow . '</a>';

    return $htmlOutput;
}