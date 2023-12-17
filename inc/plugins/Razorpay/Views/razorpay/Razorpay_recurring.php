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

// echo '<pre>';
// var_dump(array(
//     'period' => $inputs['period'], 
//     'interval' => $inputs['interval'], 
//     'item' => array(
//         'name' => $inputs['name'], 
//         'description' => $inputs['description'], 
//         'amount' => $inputs['amount'], 
//         'currency' => $inputs['currency']
//         ),
//     'notes'=> ''
//     ));
// exit;
$numericValue = floatval(600);
$result = abs($numericValue);

// $plans = $api->plan->create(array('period' => 'weekly', 'interval' => 1, 'item' => array('name' => 'Test Weekly 1 plan', 'description' => '', 'amount' => 400, 'currency' => 'INR'),'notes'=> array('test'=> 'test','te1'=> 'te1')));

// echo '<pre>';
// print_r($plans);
// exit;


?>