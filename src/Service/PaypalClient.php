<?php
namespace App\Service;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;

class PaypalClient
{
    public static function client(): PayPalHttpClient
    {
        $clientId     = $_ENV['PAYPAL_SANDBOX_CLIENT_ID'];
        $clientSecret = $_ENV['PAYPAL_SANDBOX_CLIENT_SECRET'];
        $environment  = new SandboxEnvironment($clientId, $clientSecret);
        return new PayPalHttpClient($environment);
    }
}
