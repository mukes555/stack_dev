<?php

// Include Requests only if not already defined
if (!defined('REQUESTS_SILENCE_PSR0_DEPRECATIONS'))
{
    define('REQUESTS_SILENCE_PSR0_DEPRECATIONS', true);
}

if (class_exists('WpOrg\Requests\Autoload') === false)
{
    require_once __DIR__.'/libs/Requests-2.0.4/src/Autoload.php';
}

try
{
    WpOrg\Requests\Autoload::register();

    if (version_compare(Requests::VERSION, '1.6.0') === -1)
    {
        throw new Exception('Requests class found but did not match');
    }
}
catch (\Exception $e)
{
    throw new Exception('Requests class found but did not match');
}

spl_autoload_register(function ($class)
{
    // project-specific namespace prefix
    $prefix = 'Razorpay\Api';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/src/';

    // does the class use the namespace prefix?
    $len = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0)
    {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name
    $relative_class = substr($class, $len);

    //
    // replace the namespace prefix with the base directory,
    // replace namespace separators with directory separators
    // in the relative class name, append with .php
    //
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file))
    {
        require $file;
    }
});
use Razorpay\Api\Api;
use CodeIgniter\HTTP\URI;

$uri = new URI(current_url());
$keyId = $razorpay_key['keyId'];
$secretKey = $razorpay_key['secretKey'];
$api = new Api($keyId, $secretKey);
// echo'<pre>';
// print_r($api);
// exit;
$order = $api->order->create(array(
    'receipt' => rand(1000, 9999). 'ORD',
    'amount' => $inputs['price'] * 100,
    'payment_capture' => 1,
    'currency' => $inputs['currency'],
));

?>
<meta name="viewport" content="width=device-width" />
<style>
    .razorpay-payment-button{
        position:absolute;
        height:0;
        width:0;
        display:none;
    }
</style>

<form action="<?= base_url('razorpay/success/'.$uri->getSegment(7).'/'.$uri->getSegment(8))?>" method="POST">
    <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key = "<?= $keyId?>"
    data-amount = "<?= $order->amount?>"
    data-currency = "<?= $order->currency?>"
    data-order_id = "<?= $order->id?>"
    data-button-text = "Pay with Razorpay"
    data-name = "Social"
    data-theme-color = "#f0a43c"
    ></script>
    <input type="hidden" custom="Hidden Element" name="hidden">
 
</form>
<center>
    <div class=""><h1>Do not Refresh or Press Back Button</h1></div>
    <h3><a href="<?= base_url('payment/failed')?>">Cancel Payment </a></h3>
</center>
<script>
    document.querySelector('.razorpay-payment-button').click();
</script>
