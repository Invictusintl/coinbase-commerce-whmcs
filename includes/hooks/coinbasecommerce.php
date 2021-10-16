<?php

add_hook('ClientAreaPageViewInvoice', 1, function ($vars) {

    if ($vars['status'] != "Paid" && $_REQUEST['canceled'] == 'yes') {
        $paymentbutton = $vars['paymentbutton'];
        $paymentbutton .= '<br><p class="alert alert-info">Payment has beein canceled, please try again or choose a other payment method</p>';
        return array("paymentbutton" => $paymentbutton);
    }
});

add_hook('ClientAreaPageViewInvoice', 1, function ($vars) {

    if ($vars['status'] != "Paid" && $_REQUEST['canceled'] == 'no') {
        $paymentbutton = $vars['paymentbutton'];
        $paymentbutton .= '<br><p class="alert alert-info">Payment has beein confirmed, please contact support in case invoice does not update in the next few hours</p>';
        return array("paymentbutton" => $paymentbutton);
    }
});