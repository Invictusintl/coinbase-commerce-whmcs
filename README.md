# WHMCS Coinbase Commerce Gateway Module #

## Summary ##

This is a payment gateway module for for the new Coinbase Commerce. 

On May 1st, 2018 Coinbase disabled all legacy API and merchant tools, but they released an even better tool to replace it, 
Coinbase Commerce. 

Coinbase Commerce is a simple powerful interface to generate checkouts or charges for your customers without having to pay 
them any fees and while keeping full control of your private keys.

As of right now Coinbase Commerce is very new and their API is ready for payments but some features are missing. We go over 
this in the _To Do_ section.

### Note

If someone sends a payment under or over what their invoice total is, 
then the payment will fail. You can still manually add the Payment in WHMCS but the CC API system sends it with the failed 
result and the user is notifed of this as well. Just like this screenshot. Ideally we wouldn't want this to happen and if 
anyone has some suggetsion around this please suggest it.

<img src="https://i.imgur.com/F06xuCx.jpg">

Contributions and requests are very welcome. Simply open an issue with as much detail as possible.

## Installation ##

1. Visit https://commerce.coinbase.com/ and login or sign-up for a free account.

2. **Generate an API key** on the settings page . https://commerce.coinbase.com/dashboard/settings Keep these values super safe.

3. **Get your Webhook _shared secret_** from the _Webhook subscriptions_ section on the same page as above. Keep these values super safe.

4. **Clone or download this project** to your local machine or to your webserver.

5. **Copy the _modules_ directory into your WHMCS root folder.** This will place the files in the needed locations. 
Below is the complete file structure that should be uploaded. Make sure you don't upload any of the files like this 
readme into your WHMCS installation.

```
 modules/gateways/
  |- callback/coinbasecommerce.php
  |  coinbasecommerce.php
```

6. **Activate the Payment Gateway.** To do this visit your Payment Gateways in WHMCS. This is located at
: _**Setup** -> **Payments** -> **Payment Gateways**_. This Module will be called _Coinbase Commerce_.

7. **Customize your gateway settings** with the information we gathered above. You should now be set!

## Minimum Requirements ##

We have no additional requirements beyond what WHMCS already needs.

For the latest WHMCS minimum system requirements, please refer to
https://docs.whmcs.com/System_Requirements

## To Do ##

* Implement a Logo URL as soon as Coinbase gets around to letting us declare it for charge type transactions.
* Implement logic to tell if multiple payments have been made and act accordingly.
* Clean up this super sloppy code, ugh.