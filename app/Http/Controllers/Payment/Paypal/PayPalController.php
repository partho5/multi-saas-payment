<?php

namespace App\Http\Controllers\Payment\Paypal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Srmklive\PayPal\Facades\PayPal;


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
        return view('Paypal.payment');
    }

    public function processTransaction(Request $request)
    {
        // dd($request->all());
        $packagePrice = \Session::get('packagePrice', '');

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
                // dd($response['links']);
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

    public function successTransaction(Request $request)
    {
        $payment = $this->provider->capturePaymentOrder($request['token']);
        $response = [];

        if (isset($payment['status']) && $payment['status'] == 'COMPLETED') {
            // store transaction details in database

            // Extract necessary details from the PayPal response
            $capture = $payment['purchase_units'][0]['payments']['captures'][0];
            $packageCode = \Session::get('packageCode', '');

            $data = [
                "userId" => $payment['payer']['payer_id'], // Use Payer ID as the user identifier
                "packageCode" => $packageCode,
                "transacId" => $payment['id'], // PayPal Transaction ID
                "amount" => $capture['amount']['value'], // Transaction Amount
                "currency" => $capture['amount']['currency_code'], // Currency Code
                "status" => $payment['status'], // Payment Status (COMPLETED, PENDING, etc.)
                "paymentMethod" => "PayPal", // Payment method (PayPal in this case)
                "payerEmail" => $payment['payer']['email_address'], // Payer Email
                "payerCountry" => $payment['payer']['address']['country_code'], // Payer Country
                "referenceId" => $payment['purchase_units'][0]['reference_id'], // Reference ID
                "createdAt" => $capture['create_time'], // Payment Timestamp
            ];

            $response = $this->sendTransaction($data);
            // dd($response);
            return redirect()
                ->route('createTransaction')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    public function cancelTransaction()
    {
        return redirect()
            ->route('createTransaction')
            ->with('error', 'You have canceled the transaction.');
    }

    public function sendTransaction($data)
    {
        $url = env('NANY_PAYMENT_API_ENDPOINT_STORE');
        $token = env('NANY_PAYMENT_API_TOKEN');

        // Sending HTTP POST request with Bearer Token
        $response = Http::withToken($token)->post($url, $data);

        // Return the response
        return response()->json($response->json(), $response->status());
    }

}
