<?php

namespace App\Http\Controllers\Payment\Paypal;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Carbon\Carbon;

class PayPalController extends Controller
{
    protected $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;

        // Set up API credentials with the config file (use lowercase 'paypal')
        $this->provider->setApiCredentials(config('paypal'));

        // Get access token - important to do this first
        $this->provider->getAccessToken();
    }

    public function createTransaction()
    {
        return view('paypal.payment');
    }

    public function processTransaction(Request $request)
    {
        //return $request->all();

        if (!$request->userId) {
            return redirect()->back()->with('error', "The link you entered is invalid or missing some details. If you believe this is a mistake, please contact support. Thank You.");
        }

        \Session()->put($request->except('_token')); // sets: name, email, phone, packageCode, etc.
        $packagePrice = \Session::get('packagePrice');
        $packageName = \Session::get('packageName');
        $isRecurring = \Session::get('isRecurring', false);
        $recurringDuration = \Session::get('recurringDuration', 30); // Default to 30 days if not specified

        // Handle one-time payment (existing functionality)
        if (!$isRecurring) {
            return $this->processOneTimePayment($packagePrice);
        }

        // Handle subscription payment
        return $this->processSubscription($packagePrice, $recurringDuration);
    }

    protected function processOneTimePayment($packagePrice)
    {
        // Explicitly set the currency to ensure it matches your PayPal account
        $this->provider->setCurrency('USD');

        $response = $this->provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $packagePrice
                    ],
                    // Add reference_id to help with identification
                    "reference_id" => "order_" . uniqid()
                ]
            ],
            "application_context" => [
                "cancel_url" => route('cancelTransaction'),
                "return_url" => route('successTransaction'),
                "brand_name" => config('app.name'),
                "shipping_preference" => "NO_SHIPPING",
                "user_action" => "PAY_NOW",
                "landing_page" => "BILLING"
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {
            // Find approval URL
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }

            return redirect()
                ->route('createTransaction')
                ->with('error', 'No approval link found.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    protected function processSubscription($packagePrice, $recurringDuration)
    {
        // Calculate billing cycles based on duration
        $billingFrequency = $this->getBillingFrequency($recurringDuration);
        $packageCode = \Session::get('packageCode');
        $packageName = \Session::get('packageName');

        // Create product (properly formatted as array)
        $productData = [
            'name' => 'Subscription for ' . $packageName,
            'description' => 'Subscription plan for ' . $packageName,
            'type' => 'SERVICE',
            'category' => 'SOFTWARE'
        ];

        $planResponse = $this->provider->createProduct($productData);

        if (!isset($planResponse['id'])) {
            return redirect()
                ->route('createTransaction')
                ->with('error', $planResponse['message'] ?? 'Failed to create subscription plan.');
        }

        $productId = $planResponse['id'];

        // Create billing plan with proper array format
        $billingPlanData = [
            'product_id' => $productId,
            'name' => 'Pay for ' . $packageName . ' plan =',
            'description' => 'Recurring payment for ' . $packageName,
            'status' => 'ACTIVE',
            'billing_cycles' => [
                [
                    'frequency' => [
                        'interval_unit' => $billingFrequency['interval_unit'],
                        'interval_count' => $billingFrequency['interval_count']
                    ],
                    'tenure_type' => 'REGULAR',
                    'sequence' => 1,
                    'total_cycles' => 0, // Infinite cycles
                    'pricing_scheme' => [
                        'fixed_price' => [
                            'value' => $packagePrice,
                            'currency_code' => 'USD'
                        ]
                    ]
                ]
            ],
            'payment_preferences' => [
                'auto_bill_outstanding' => true,
                'setup_fee' => [
                    'value' => '0',
                    'currency_code' => 'USD'
                ],
                'setup_fee_failure_action' => 'CONTINUE',
                'payment_failure_threshold' => 3
            ]
        ];

        $billingPlanResponse = $this->provider->createPlan($billingPlanData);

        if (!isset($billingPlanResponse['id'])) {
            return redirect()
                ->route('createTransaction')
                ->with('error', $billingPlanResponse['message'] ?? 'Failed to create billing plan.');
        }

        $planId = $billingPlanResponse['id'];

        // Create subscription
        $subscriptionData = [
            'plan_id' => $planId,
            'application_context' => [
                'brand_name' => config('app.name'),
                'locale' => 'en-US',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'SUBSCRIBE_NOW',
                'payment_method' => [
                    'payer_selected' => 'PAYPAL',
                    'payee_preferred' => 'IMMEDIATE_PAYMENT_REQUIRED'
                ],
                'return_url' => route('successSubscription'),
                'cancel_url' => route('cancelTransaction')
            ]
        ];

        $subscriptionResponse = $this->provider->createSubscription($subscriptionData);

        if (isset($subscriptionResponse['id']) && $subscriptionResponse['id'] != null) {
            // Store subscription ID in session for later use
            \Session::put('subscriptionId', $subscriptionResponse['id']);

            // Find approval URL
            foreach ($subscriptionResponse['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return redirect()->away($link['href']);
                }
            }

            return redirect()
                ->route('createTransaction')
                ->with('error', 'No approval link found for subscription.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $subscriptionResponse['message'] ?? 'Failed to create subscription.');
        }
    }


    protected function getBillingFrequency($recurringDuration)
    {
        // Convert days to appropriate billing frequency
        if ($recurringDuration <= 31) {
            // Monthly
            return [
                'interval_unit' => 'MONTH',
                'interval_count' => 1
            ];
        } elseif ($recurringDuration <= 90) {
            // Quarterly
            return [
                'interval_unit' => 'MONTH',
                'interval_count' => 3
            ];
        } elseif ($recurringDuration <= 186) {
            // Semi-annually
            return [
                'interval_unit' => 'MONTH',
                'interval_count' => 6
            ];
        } else {
            // Annually
            return [
                'interval_unit' => 'YEAR',
                'interval_count' => 1
            ];
        }
    }

    /**
     * for One time payment
     * */
    public function successTransaction(Request $request)
    {
        $payment = $this->provider->capturePaymentOrder($request['token']);

        if (isset($payment['status']) && $payment['status'] == 'COMPLETED') {
            // Extract necessary details from the PayPal response
            $capture = $payment['purchase_units'][0]['payments']['captures'][0];

            $packageCode = \Session()->get('packageCode');
            $userId = \Session()->get('userId');
            $name = \Session()->get('name');
            $email = \Session()->get('email');
            $phone = \Session()->get('phone');
            $creditAmount = \Session()->get('creditAmount');

            $data = [
                "userId" => $userId,
                "packageCode" => $packageCode,
                "name" => $name,
                "userEmail" => $email,
                "phone" => $phone,
                "creditAmount" => $creditAmount,
                "transacId" => $payment['id'], // PayPal Transaction ID
                "amount" => $capture['amount']['value'], // Transaction Amount
                "currency" => $capture['amount']['currency_code'], // Currency Code
                "status" => $payment['status'], // Payment Status (COMPLETED, PENDING, etc.)
                "paymentMethod" => "PayPal", // Payment method (PayPal in this case)
                "payerEmail" => $payment['payer']['email_address'], // Payer Email
                "payerId" => $payment['payer']['payer_id'], // Payer Id
                "payerCountry" => $payment['payer']['address']['country_code'], // Payer Country
                "referenceId" => $payment['purchase_units'][0]['reference_id'], // Reference ID
                "createdAt" => $capture['create_time'], // Payment Timestamp
                "isRecurring" => false,
                "recurringDuration" => null,
                "nextBillingDate" => null
            ];

            $response = $this->sendTransaction($data);

            // call api to send mail
            $this->sendPaymentInvoiceMail($data);

            return redirect()
                ->route('createTransaction')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $payment['message'] ?? 'Something went wrong.');
        }
    }

    /**
     * for recurring payment
     * */
    public function successSubscription(Request $request)
    {
        $subscriptionId = \Session::get('subscriptionId');

        if (!$subscriptionId) {
            return redirect()
                ->route('createTransaction')
                ->with('error', 'Subscription ID not found.');
        }

        // Get subscription details
        $subscription = $this->provider->showSubscriptionDetails($subscriptionId);
        //dd($subscription);

        if (!isset($subscription['id'])) {
            return redirect()
                ->route('createTransaction')
                ->with('error', $subscription['message'] ?? 'Failed to retrieve subscription details.');
        }

        $packageCode = \Session()->get('packageCode');
        $userId = \Session()->get('userId');
        $name = \Session()->get('name');
        $email = \Session()->get('email');
        $phone = \Session()->get('phone');
        $creditAmount = \Session()->get('creditAmount');
        $recurringDuration = (int) \Session()->get('recurringDuration', 30);

        // Calculate next billing date
        $startTime = Carbon::parse($subscription['start_time']);
        $nextBillingDate = $startTime->addDays($recurringDuration)->format('Y-m-d H:i:s');

        $data = [
            "userId" => $userId,
            "packageCode" => $packageCode,
            "name" => $name,
            "userEmail" => $email,
            "phone" => $phone,
            "creditAmount" => $creditAmount,
            "transacId" => $subscription['id'], // Subscription ID
            "amount" => $subscription['billing_info']['last_payment']['amount']['value'], // Transaction Amount
            "currency" => $subscription['billing_info']['last_payment']['amount']['currency_code'], // Currency Code
            "status" => $subscription['status'], // Subscription Status
            "paymentMethod" => "PayPal", // Payment method (PayPal in this case)
            "payerEmail" => $subscription['subscriber']['email_address'], // Payer Email
            "payerId" => $subscription['subscriber']['payer_id'], // Payer Id
            "payerCountry" => $subscription['subscriber']['shipping_address']['country_code'] ?? 'Unknown', // Payer Country
            "referenceId" => $subscription['id'], // Reference ID (same as subscription ID)
            "createdAt" => $subscription['create_time'], // Creation Timestamp
            "isRecurring" => true,
            "recurringDuration" => $recurringDuration,
            "nextBillingDate" => $nextBillingDate,
            "subscriptionId" => $subscription['id']
        ];

        $response = $this->sendTransaction($data);

        // save subscription data, will be needed to manage/cancel/suspend subscription
        $subId = $this->saveSubscription($data);

        // call api to send mail
        $this->sendPaymentInvoiceMail($data);


        return redirect()
            ->route('createTransaction')
            ->with('success', 'Subscription activated successfully.');
    }

    public function cancelTransaction()
    {
        return redirect()
            ->route('createTransaction')
            ->with('error', 'You have canceled the transaction.');
    }


    /**
     * Send transaction data to app from where payment request originated. The origin app will process/save payment data.
    */
    public function sendTransaction($data)
    {
        $url = env('NANY_ARTICLE_PAYMENT_API_ENDPOINT_STORE');
        $token = env('NANY_ARTICLE_API_TOKEN');

        // Sending HTTP POST request with Bearer Token
        $response = Http::withToken($token)->post($url, $data);

        // Return the response
        return response()->json($response->json(), $response->status());
    }

    public function manageSubscription($serviceIdentifier, $subscriptionId, Request $request)
    {
        // Get subscription from database
        $subscription = Subscription::where('transac_id', $subscriptionId)->firstOrFail();

        // Get latest details from PayPal
        $paypalSubscription = $this->provider->showSubscriptionDetails($subscriptionId);
        //return $paypalSubscription;

        if (!isset($paypalSubscription['id'])) {
            return redirect()
                ->route('subscriptions')
                ->with('error', 'PayPal could not find your subscription details.');
        }

        // Update subscription status if needed
        if ($subscription->status !== $paypalSubscription['status']) {
            $subscription->status = $paypalSubscription['status'];
            $subscription->is_active = in_array($paypalSubscription['status'], ['ACTIVE', 'APPROVAL_PENDING']);
            $subscription->save();
        }

        //return ($paypalSubscription);


        $subscriptionData = [
            "packageName" => $request->input('packageName'),

            "status" => $paypalSubscription['status'], // ACTIVE or other values
            "status_update_time" => $paypalSubscription['status_update_time'],
            "transac_id" => $paypalSubscription['id'],
            "plan_id" => $paypalSubscription['plan_id'],
            "start_time" => $paypalSubscription['start_time'],
            "quantity" => $paypalSubscription['quantity'],
            "shipping_amount" => $paypalSubscription['shipping_amount']['value'], // Extracting value
            "shipping_currency" => $paypalSubscription['shipping_amount']['currency_code'], // Extracting currency code
            "subscriber_email" => $paypalSubscription['subscriber']['email_address'],
            "subscriber_name" => $paypalSubscription['subscriber']['name']['given_name'] . " " . $paypalSubscription['subscriber']['name']['surname'],
            "billing_info" => [
                "outstanding_balance" => $paypalSubscription['billing_info']['outstanding_balance']['value'],
                "billing_currency" => $paypalSubscription['billing_info']['outstanding_balance']['currency_code'],
                "last_payment_amount" => $paypalSubscription['billing_info']['last_payment']['amount']['value'],
                "last_payment_currency" => $paypalSubscription['billing_info']['last_payment']['amount']['currency_code'],
                "next_billing_time" => $paypalSubscription['billing_info']['next_billing_time'] ?? null,
                "failed_payments_count" => $paypalSubscription['billing_info']['failed_payments_count'],
                "cycle_executions" => $paypalSubscription['billing_info']['cycle_executions']
            ],
            "create_time" => $paypalSubscription['create_time'],
            "update_time" => $paypalSubscription['update_time'],
            "plan_overridden" => $paypalSubscription['plan_overridden'],
            "links" => $paypalSubscription['links'],
            // Adding calculated defaults
            "amount" => $paypalSubscription['billing_info']['last_payment']['amount']['value'], // Using last payment as amount
            "currency" => $paypalSubscription['billing_info']['last_payment']['amount']['currency_code'], // Currency from last payment
            "is_active" => $paypalSubscription['status'] === "ACTIVE", // boolean for active status
            "next_billing_date" => $paypalSubscription['billing_info']['next_billing_time'] ?? null, // Next billing date
        ];
        // dd($subscriptionData);


        // Convert to a Laravel Collection or object if needed
        $subscriptionData = (object) $subscriptionData; // or use collect($paypalSubscription) for a Collection

        return view('paypal.manage-subscription', ['subscription' => $subscriptionData]);
    }

    public function cancelSubscription(Request $request, $subscriptionId)
    {
        $reason = $request->input('reason', 'Canceled by user');

        // Cancel the subscription in PayPal
        $response = $this->provider->cancelSubscription($subscriptionId, $reason);

        // Update subscription status in database
        $subscription = Subscription::where('transac_id', $subscriptionId)->firstOrFail();
        $subscription->status = 'CANCELLED';
        $subscription->is_active = false;
        $subscription->cancel_reason = $reason;
        $subscription->save();

        // Send email notification
        $adminEmail = config('paypal.admin_email', 'contact@nanybot.com');
        // Mail::to($adminEmail)->send(new SubscriptionCancelled($subscription, $reason));

        return redirect()
            ->back()
            ->with('success', 'Subscription canceled successfully.');
    }

    public function suspendSubscription($subscriptionId)
    {
        // Suspend the subscription in PayPal
        $response = $this->provider->suspendSubscription($subscriptionId, 'Suspended by user');

        // Update subscription status in database
        $subscription = Subscription::where('transac_id', $subscriptionId)->firstOrFail();
        $subscription->status = 'SUSPENDED';
        $subscription->is_active = false;
        $subscription->save();

        return redirect()
            ->back()
            ->with('success', 'Subscription suspended successfully.');
    }

    public function reactivateSubscription($subscriptionId)
    {
        // Reactivate the subscription in PayPal
        $response = $this->provider->activateSubscription($subscriptionId, 'Reactivated by user');

        // Update subscription status in database
        $subscription = Subscription::where('transac_id', $subscriptionId)->firstOrFail();
        $subscription->status = 'ACTIVE';
        $subscription->is_active = true;
        $subscription->save();

        //return $subscription;

        return redirect()
            ->back()
            ->with('success', 'Subscription reactivated successfully.');
    }

    // method to list all subscriptions for the current user
    public function listSubscriptions($userId)
    {
        $subscriptions = Subscription::where('user_id', $userId)->orderBy('created_at', 'desc')->get();

        return view('paypal.subscriptions', ['subscriptions' => $subscriptions]);
    }


    /**
     * Save subscription data in this payment processing app instead of origin app (from where payment request originated). Because cancel subscription will be managed by this app, so then we will need subscription info.
    */
    private function saveSubscription($data){
        $subscription = Subscription::create([
            'user_id' => $data['userId'],
            'transac_id' => $data['transacId'],
            'package_code' => $data['packageCode'],
            'payer_email' => $data['payerEmail'],
            'payer_id' => $data['payerId'],
            'payer_country' => $data['payerCountry'],
            'amount' => $data['amount'],
            'currency' => $data['currency'],
            'status' => $data['status'],
            'recurring_duration' => $data['recurringDuration'],
            'next_billing_date' => $data['nextBillingDate'],
            'last_payment_date' => now(), // Assuming last payment date is now, change if needed
            'is_active' => true,
            'cancel_reason' => null,
        ]);

        return $subscription->id;
    }

    private function sendPaymentInvoiceMail($data){
        $invoiceData = [
            'invoiceNumber' => $data['transacId'],
            'appName' => \Session::get('serviceName'),
            'businessAddress' => \Session::get('businessAddress'),
            'contactEmail' => \Session::get('contactEmail'),
            'supportEmail' => \Session::get('supportEmail'),
            'userName' => \Session::get('name'),
            'userPhone' => \Session::get('phone'),
            'userEmail' => \Session::get('email'),
            'items' => [
                [
                    'itemName' => \Session::get('packageName') . ' plan',
                    'quantity' => 1,
                    'unitPrice' => \Session::get('packagePrice'),
                    'total' => \Session::get('packagePrice'),
                ]
            ],
            'taxPercent' =>  \Session::get('taxPercent'),
            'mailSubject' => 'Payment Successful',
        ];

        $apiUrl = config('data.mail_sending_api');
        $token = config('data.mail_sending_api_token');

        // Sending HTTP POST request with Bearer Token
        $response = Http::withToken($token)->post($apiUrl, $invoiceData);

        // Return the response
        return response()->json($response->json(), $response->status());
    }

}