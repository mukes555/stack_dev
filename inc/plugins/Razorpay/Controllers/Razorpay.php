<?php
namespace Plugins\Razorpay\Controllers;

class Razorpay extends \CodeIgniter\Controller
{
    public function __construct(){
        
        $reflect = new \ReflectionClass(get_called_class());
        $this->module = strtolower( $reflect->getShortName() );
        $this->config = include realpath( __DIR__."/../Config.php" );
        include get_module_dir( __DIR__ , 'Libraries/vendor/autoload.php');

        $this->client_id = get_option("razorpay_client_id");
        $this->client_secret = get_option("razorpay_client_secret_key");
        $this->plan = get_payment_plan( uri("segment", 3), uri("segment", 4) );
        if( !get_option("razorpay_one_time_status", 0) || empty($this->plan) || $this->client_id == "" || $this->client_secret == "" ){
            redirect_to( base_url() );
        }
        if ( uri("segment", 2) != "webhook" ) {
            $this->plan = get_payment_plan( uri("segment", 3), uri("segment", 4) );
            if( !get_option("razorpay_recurring_status") || empty($this->plan) || $this->client_id == "" || $this->client_secret == "" ){
                redirect_to( base_url() );
            }
        }
        if(get_option("payment_environment", 0)){
            $this->razorpay->setConfig(
                array(
                    'mode' => 'live',
                )
            );
        }
    }

    public function index($ids = "")
    {
            if(!get_user("id")){
                redirect_to( base_url("login"), true);
            }
            
            try {
            if(get_user_data("is_subscription", 0)){
                $error = __("You are using the monthly payment plan. Cancel it if you want to change the package or change your payment method.");
                redirect_to( base_url( "profile/index/plan?error=".urlencode($error) ) );
            }
            
            $data['inputs'] = array(
                'payment_method' => 'razorpay',
                'name' => $this->plan->name." - ".($this->plan->by==2?__("Annually"):__("Monthly")),
                'currency' => get_option("payment_currency", "USD"),
                'quantity' => 1,
                'sku' => $this->plan->id,
                'price' => $this->plan->amount
            );
            $data['razorpay_key'] = array(
                'keyId' => $this->client_id,
                'secretKey' => $this->client_secret
            ); 
            return view('Plugins\Razorpay\Views\razorpay/Razorpay', $data);
        }catch (Exception $e) {
            $error = [
                "status" => "error",
                "message" => $e->getMessage()
            ];
            redirect_to( base_url("payment/failed?".http_build_query($error) ) );
        }
    }
    public function success($ids= ''){

        $data['response'] = $this->request->getVar();
        $data['razorpay_key'] = array(
            'keyId' => $this->client_id,
            'secretKey' => $this->client_secret
        ); 
        
        return view('Plugins\Razorpay\Views\razorpay\Razorpay_fetch_payment', $data);
    }
    public function complete($ids=''){
        try {
            $data['response'] = json_decode($this->request->getVar('payment'));
            if($data['response']->status == "captured"){
                $data = [
                    'type' => 'razorpay',
                    'plan' => $this->plan->id,
                    'transaction_id' => $data['response']->payment_id,
                    'amount' => $data['response']->amount,
                    'by' =>$this->plan->by,
                ];
                payment_save($data); 
            }else{
                redirect_to( base_url("payment/unsuccess") );
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            exit(0);
        }
    }
    public function recurring($ids = "")
    {
        // try {
            if(!get_user("id")){
                redirect_to( base_url("login"), true);
            }

            if(get_user_data("is_subscription", 0)){
                $error = __("You are using the monthly payment plan. Cancel it if you want to change the package or change your payment method.");
                redirect_to( base_url( "profile/index/plan?error=".urlencode($error) ) );
            }
            $cycles = 12;
            $frequency = 'monthly';
            if($this->plan->by == 2){
                $cycles = 1;
                $frequency = 'yearly';
            }
            $data['inputs'] = array(
                'period' => $frequency,
                'interval' => $cycles,
                'name' => "Package: ".$this->plan->name. " - " . ($this->plan->by == 2?"Annually":"Monthly"), 
                'description' => $this->plan->desc, 
                'amount' => round($this->plan->amount, 2), 
                'currency' => get_option("payment_currency", "USD")
            );
            $data['razorpay_key'] = array(
                'keyId' => $this->client_id,
                'secretKey' => $this->client_secret
            );
            // echo '<pre>';
            // print_r($data);
            // exit;
            
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.razorpay.com/v1/plans',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>'{
        "period": "weekly",
        "interval": 1,
        "item": {
            "name": "Test plan - Weekly",
            "amount": 69900,
            "currency": "INR",
            "description": "Description for the test plan - Weekly"
        },
        "notes": {
            "notes_key_1": "Tea, Earl Grey, Hot",
            "notes_key_2": "Tea, Earl Grey… decaf."
        }
        }',
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic cnpwX3Rlc3RfQmVwMzlWek5pNHE2VU46bTNJM282M0YyWGtQYVB5WUVjOWZmRXpV'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;
        exit;

            return view('Plugins\Razorpay\Views\razorpay/Razorpay_recurring', $data);
            




            // $api = new Api($this->client_id, $this->client_secret);
            // $plan = $api->plan->create(array(
            //     'period' => $frequency, 
            //     'interval' => $cycles, 
            //     'item' => array(
            //         'name' => "Package: ".$this->plan->name. " - " . ($this->plan->by == 2?"Annually":"Monthly"), 
            //         'description' => $this->plan->desc, 
            //         'amount' => round($this->plan->amount, 2), 
            //         'currency' => get_option("payment_currency", "USD")
            //     ),
            //     'notes'=> array('plan'=> 'test')));
            // echo'<pre>';
            // print_r($plan);
            // exit;
            // //$this->plan->amount = round($this->plan->amount, 2);

            

            // $create_plan = new \PayPal\Api\Plan();
            // $create_plan->setName( "Package: ".$this->plan->name. " - " . ($this->plan->by == 2?"Anually":"Monthly") )
            //     ->setDescription($this->plan->desc)
            //     ->setType('FIXED');

            // // Set billing plan definitions
            // $paymentDefinition = new \PayPal\Api\PaymentDefinition();
            // $paymentDefinition->setName('Regular Payments')
            //     ->setType('REGULAR')
            //     ->setFrequency($frequency)
            //     ->setFrequencyInterval('1')
            //     ->setCycles($cycles)
            //     ->setAmount(new \PayPal\Api\Currency(array(
            //         'value' => $this->plan->amount,
            //         'currency' => get_option("payment_currency", "USD")
            //     )
            // ));

            // // Set merchant preferences
            // $merchantPreferences = new \PayPal\Api\MerchantPreferences();
            // $merchantPreferences
            //     ->setReturnUrl( base_url("paypal_recurring/complete/".$ids."/".$this->plan->by) )
            //     ->setCancelUrl( base_url("payment/failed") )
            //     ->setAutoBillAmount('yes')
            //     ->setInitialFailAmountAction('CONTINUE')
            //     ->setMaxFailAttempts('0');
            //     //->setSetupFee(new \PayPal\Api\Currency( ['value' => $this->plan->amount, 'currency' => "USD" ]));

            // $create_plan->setPaymentDefinitions([$paymentDefinition]);
            // $create_plan->setMerchantPreferences($merchantPreferences);

            // $createdPlan = $create_plan->create($this->paypal);

            // $patch = new \PayPal\Api\Patch();
            // $patch->setOp('replace')
            //     ->setPath('/')
            //     ->setValue( json_decode('{"state":"ACTIVE"}') );

            // $patchRequest = new \PayPal\Api\PatchRequest();
            // $patchRequest->addPatch($patch);
            // $createdPlan->update($patchRequest, $this->paypal);
            // $patchedPlan = \PayPal\Api\Plan::get($createdPlan->getId(), $this->paypal);
            
            
            // // Create new agreement
            // $startDate = date('c', time() + 15);
            // $agreement = new \PayPal\Api\Agreement();
            // $agreement->setName( "Plans: ".$this->plan->name. " - " . ($this->plan->by == 2?"Anually":"Monthly") )
            //     ->setDescription( "Plans: ".$this->plan->name. " - " . ($this->plan->by == 2?"Anually":"Monthly") )
            //     ->setStartDate($startDate);

            // // Set plan id
            // $paypal_plan = new \PayPal\Api\Plan();
            // $paypal_plan->setId($patchedPlan->getId());
            // $agreement->setPlan($paypal_plan);

            // // Add payer type
            // $payer = new \PayPal\Api\Payer();
            // $payer->setPaymentMethod('paypal');
            // $agreement->setPayer($payer);

            // $agreement = $agreement->create($this->paypal);
            // $approvalUrl = $agreement->getApprovalLink();
            
            // redirect_to($approvalUrl);

        // }catch (Exception $e) {
        //     $error = [
        //         "status" => "error",
        //         "message" => $e->getMessage()
        //     ];

        //     redirect_to( base_url("payment/failed?".http_build_query($error) ) );
        // }
    }
}