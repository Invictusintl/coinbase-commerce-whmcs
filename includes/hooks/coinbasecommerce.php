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
*          File Birth       > <!#FB> 2021/10/17 00:56:11.892 </#FB>                                                    *
*          File Mod         > <!#FT> 2021/10/17 01:04:36.045 </#FT>                                                    *         
*          File Version     > <!#FV> 0.0.1 </#FV>                                                                      
*                                                                                                                      *
</#CR>
*/




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