<?php

namespace App\Http\Controllers\Payment\Paypal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
        $response = $this->provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            // You might want to store transaction details in your database here
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
}
